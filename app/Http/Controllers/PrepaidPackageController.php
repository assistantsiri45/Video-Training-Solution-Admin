<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseMail;
use App\Mail\SignUpMail;
use App\Models\Address;
use App\Models\Country;
use App\Models\Course;
use App\Models\JMoney;
use App\Models\JMoneySetting;
use App\Models\Level;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Payment;
use App\Models\PaymentOrderItem;
use App\Models\Professor;
use App\Models\Referral;
use App\Models\Setting;
use App\Models\State;
use App\Models\Student;
use App\Models\SubjectPackage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class PrepaidPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Student::query();
            $query->latest();

            return DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                                ->orWhere('email', 'like', '%' . request()->input('filter.search') . '%')
                                ->orWhere('phone', 'like', '%' . request()->input('filter.search') . '%');
                        })->orWhere(function ($query) {
                            $query->whereHas('course', function ($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })->orWhere(function ($query) {
                            $query->whereHas('level', function ($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                    }

                    if (request()->filled('filter.date')) {
                        $query->where(function ($query) {
                            $query->whereDate('created_at', Carbon::parse(request()->input('filter.date')));
                        });
                    }

                    if (request()->filled('filter.professor')) {
                        $query->where(function ($query) {
                            $query->wherehas('orderItems.package.chapter.video.professor', function($query) {
                                $query->where('id', request('filter.professor'));
                            });
                        });
                    }
                })
                ->addColumn('course', function ($query) {
                    return $query->course->name ?? null;
                })
                ->addColumn('level', function ($query) {
                    return $query->level->name ?? null;
                })
                ->addColumn('associate', function ($query) {
                    return $query->associate->user->name ?? null;
                })
                ->addColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->toFormattedDateString();
                })
                ->addColumn('last_assigned_at', function ($query) {
                    if ($query->orderItems) {
                        if ($query->orderItems()->latest()->first()) {
                            return Carbon::parse($query->orderItems()->latest()->first()->created_at)->toFormattedDateString();
                        }
                    }
                })
                ->addColumn('action', 'pages.prepaid-packages.action')
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', ],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'course', 'name' => 'course', 'title' => 'Course'],
            ['data' => 'level', 'name' => 'level', 'title' => 'Level'],
            ['data' => 'associate', 'name' => 'associate', 'title' => 'Associate'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'last_assigned_at', 'name' => 'last_assigned_at', 'title' => 'Last Assigned At'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        $countries = Country::get();
        $states = State::get();
        return view('pages.prepaid-packages.index', compact('html','countries','states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $package = Package::find($request->input('package_id'));

        $cgst = Setting::select('value')->where('key', 'cgst')->first()->value;
        $sgst = Setting::select('value')->where('key', 'sgst')->first()->value;
        $igst = Setting::select('value')->where('key', 'igst')->first()->value;

        DB::beginTransaction();

        //INSERT INTO ORDERS TABLE
        $order = new Order();
        $order->user_id = $request->input('user_id');
        $order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
        $order->payment_mode = Order::PAYMENT_MODE_PREPAID;
        $order->transaction_response_status = "Success";

        //calculate package price based on discount and special price
        if($package->is_prebook){
            $net_total = $package->prebook_price;
        }
        else{
            if ($package->special_price) {
                $net_total = $package->special_price;
            } else if ($package->discounted_price) {
                $net_total = $package->discounted_price;
            } else {
                $net_total = $package->price;
            }
        }
        $order->net_amount = $net_total;

        //calculate net total and tax based on student's state
        $address = Address::where('user_id', $request->input('user_id'))->first();
        if( strtoupper($address->state) == 'MAHARASHTRA'){
            $amountExceptCGST_SGST = ($net_total * 100) / (100 + $cgst + $sgst);
            $cgst_amount = round(($amountExceptCGST_SGST * $cgst)/100,2);
            $sgst_amount = round(($amountExceptCGST_SGST * $sgst)/100,2);
            $order->cgst = $cgst;
            $order->cgst_amount = $cgst_amount;
            $order->sgst = $sgst;
            $order->sgst_amount = $sgst_amount;
        }else{
            $amountExceptIGST = ($net_total * 100) / (100 + $igst);
            $igst_amount = round(($amountExceptIGST * $igst)/100,2);
            $order->net_amount = $net_total;
            $order->igst = $igst;
            $order->igst_amount = $igst_amount;
        }

        //save student address details
        $order->address_id = $address->id;
        $order->name = $address->name ?? null;
        $order->phone = $address->phone ?? null;
        $order->alternate_phone = $address->alternate_phone ?? null;
        $order->city = $address->city ?? null;
        $order->state = $address->state ?? null;
        $order->pin = $address->pin ?? null;
        $order->address = $address->address ?? null;
        $order->status = Order::STATUS_RECEIVED;
        $order->updated_by = Auth::id();
        $order->updated_ip_address = request()->ip();
        $order->updated_method = Order::UPDATE_METHOD_MANUAL;
        $order->save();

        //INSERT INTO PAYMENTS TABLE
        $payment = new Payment;
        $payment->user_id = $order->user_id;
        $payment->receipt_no = Payment::getReceiptNo();
        $payment->order_id = $order->id;
        $payment->cgst = $order->cgst;
        $payment->cgst_amount = $order->cgst_amount;
        $payment->sgst = $order->sgst;
        $payment->sgst_amount = $order->sgst_amount;
        $payment->igst = $order->igst;
        $payment->igst_amount = $order->igst_amount;
        $payment->payment_status = Order::PAYMENT_STATUS_SUCCESS;
        $payment->net_amount = $order->net_amount;
        $payment->payment_updated_by = Auth::id();
        $payment->updated_ip_address = request()->ip();
        $payment->payment_updated_method = Order::UPDATE_METHOD_MANUAL;
        $payment->save();

        //INSERT INTO ORDER ITEMS TABLE
        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->package_id = $package->id ?? null;
        $orderItem->user_id = $order->user_id;
        $orderItem->price = $net_total;
        $orderItem->package_duration = $package->duration;
        
        $orderItem->price_type = 1;
        $orderItem->delivery_mode = 1;
        $orderItem->payment_status = OrderItem::PAYMENT_STATUS_FULLY_PAID;
        $orderItem->item_type = OrderItem::ITEM_TYPE_PACKAGE;
        $orderItem->item_id = $package->id;
        if ($package->is_prebook) {
            $orderItem->is_prebook = 1;
        }
        //$orderItem->expire_at = Carbon::now()->addMonths(Package::VALIDITY_IN_MONTHS);
        if ($package->expiry_type == '1') {
            $orderItem->expire_at = Carbon::now()->addMonths($package->expiry_month);
        } else if ($package->expiry_type == '2') {
            $orderItem->expire_at = $package->expire_at;
        } else {
            $orderItem->expire_at = Carbon::now()->addMonths(Package::VALIDITY_IN_MONTHS);
        }
        $orderItem->save();

        $paymentOrderItem = new PaymentOrderItem();
        $paymentOrderItem->payment_id = $payment->id;
        $paymentOrderItem->order_item_id = $orderItem->id;
        $paymentOrderItem->save();

        //INSERT INTO JMONEY TABLE
        $orderExist = Order::where('user_id', $order->user_id)->first();
        if (! $orderExist) {
            $jMoney = new JMoney();
            $jMoney->user_id = $order->user_id;
            $jMoney->activity = JMoney::FIRST_PURCHASE;
            $jMoney->points = JMoneySetting::first()->first_purchase_point;
            $jMoney->expire_after = JMoneySetting::first()->first_purchase_point_expiry;
            $jMoney->expire_at = Carbon::now()->addDays($jMoney->expire_after);
            $jMoney->save();
        }

        //TO SEND PURCHASE MAIL
        $packages = Package::with('subject')->where('id',$package->id)->get();
        $order_details = Student::where('user_id','=',$order->user_id)->first();
        $order_details['order_id'] = $order->id;
        $order_details['net_amount'] = $order->net_amount;
        $order_details['packages'] = $packages;
        if($order->cgst){
            $order_details['cgst'] = $order->cgst;
            $order_details['cgst_amount'] = $order->cgst_amount;
        }
        if($order->sgst){
            $order_details['sgst'] = $order->sgst;
            $order_details['sgst_amount'] = $order->sgst_amount;
        }
        if($order->igst){
            $order_details['igst'] = $order->igst;
            $order_details['igst_amount'] = $order->igst_amount;
        }

        DB::commit();

        try{
            Mail::send(new PurchaseMail($order_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

        return response()->json('Package successfully assigned');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::findOrFail($id);

        $tableOrderItems= app(Builder::class)->columns([
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order Id'],
            ['data' => 'package.name', 'name' => 'package.name', 'title' => 'Package'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'booking_amount', 'name' => 'booking_amount', 'title' => 'Booking Amount'],
            ['data' => 'balance_amount', 'name' => 'balance_amount', 'title' => 'Balance Amount'],
            ['data' => 'delivery_mode', 'name' => 'delivery_mode', 'title' => 'Delivery Mode'],
        ])->parameters([
            'searching' => false,
            'ordering' => true
        ])->ajax(route('tables.student-order-items',$student->user_id))
          ->setTableId('tbl-studentOrderItems');

        $tablePayments = app(Builder::class)->columns([
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order Id'],
            ['data' => 'receipt_no', 'name' => 'receipt_no', 'title' => 'Receipt No'],
            ['data' => 'tax', 'name' => 'tax', 'title' => 'Tax'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'response', 'name' => 'response', 'title' => 'Response'],
            ['data' => 'discounts', 'name' => 'discounts', 'title' => 'Discounts'],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Updated By'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
        ])->ajax(route('tables.student-transactions',$student->user_id))
          ->setTableId('tbl-studentPayments')
            ->orderBy(0, 'desc');

        $tablePackages = app(Builder::class)->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'professor', 'name' => 'professor', 'title' => 'Professor'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
        ])->ajax(route('tables.packages'))
            ->setTableId('table-packages')
            ->orderBy(0, 'desc');

        return view('pages.prepaid-packages.show', compact('student', 'tableOrderItems','tablePayments','tablePackages'));
    }

    public function checkPackageAssigned(Request  $request){

        $package_id = $request->package_id;
        $order_exists =  Order::whereHas('orderItems', function($q)use($package_id){
                                        $q->where('package_id','=', $package_id);
                                    } )
                                ->where('user_id',$request->user_id)
                                ->where('payment_status','=',1)
                                ->first();
        if(!$order_exists){
            return 0;
        }
        return 1;
    }

    public function getTablePackages()
    {
        if (request()->ajax()) {
            $query = Package::where('is_archived', 0)->where('is_approved', true)->with('course', 'level', 'subject', 'chapter', 'language')->latest();

            if (request()->filled('filter.search')) {
                $query->where('name',  request()->input('filter.search'))
//                    ->orWhere('professors', function ($professors){
//                        info($professors->get());
//                    })
                    ->orWhereHas('subject',function ($subject){
                        $subject->where('name', 'like', "%" . request('filter.search') . "%");
                    })
                    ->orWhereHas('chapter',function ($chapter){
                        $chapter->where('name',  'like', "%" . request('filter.search') . "%");
                    })
                    ->orWhereHas('course',function ($course){
                        $course->where('name', 'like', "%" . request('filter.search') . "%");
                    })
                    ->orWhereHas('level',function ($level){
                        $level->where('name', 'like', "%" . request('filter.search') . "%");
                    })
                    ->orWhereHas('language',function ($language){
                        $language->where('name', 'like', "%" . request('filter.search') . "%");
                    });
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
                    if ($query->type == Package::TYPE_CHAPTER_LEVEL) {
                        return Package::TYPE_CHAPTER_LEVEL_VALUE;
                    }

                    if ($query->type == Package::TYPE_SUBJECT_LEVEL) {
                        return Package::TYPE_SUBJECT_LEVEL_VALUE;
                    }

                    if ($query->type == Package::TYPE_CUSTOMIZED) {
                        return Package::TYPE_CUSTOMIZED_VALUE;
                    }
                })
                ->setRowClass(function ($query) {
                    if ($query->is_prebook && $query->prebook_launch_date->greaterThan(Carbon::today())) {
                        return 'bg-custom-table-row';
                    } else {
                        return 'bg-default';
                    }
                })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->editColumn('price', function($query) {

                    if (! empty($query->special_price) && $query->special_price_expire_at >= Carbon::today()) return $query->special_price;
                    if (! empty($query->discounted_price) && $query->discounted_price_expire_at >= Carbon::today()) return $query->discounted_price;
                    return $query->price;
                })
                ->editColumn('professor', function($query) {
                    $packageIDs = [];

                    if ($query->type == Package::TYPE_SUBJECT_LEVEL) {
                        $chapterPackageIDs = SubjectPackage::where('package_id', $query->id)->get()->pluck('chapter_package_id');

                        foreach ($chapterPackageIDs as $chapterPackageID) {
                            $packageIDs[] = $chapterPackageID;
                        }
                    } else {
                        $packageIDs[] = $query->id;
                    }

                    $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id');
                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name');

                    $professors = '';

                    foreach ($professorNames as $professorName) {
                        $professors .= $professorName . ', ';
                    }

                    return rtrim($professors, ', ');
                })
                ->addColumn('action', 'pages.prepaid-packages.packages.action')
                ->rawColumns(['category', 'action'])
                ->make(true);
        }
    }

    public function createStudent(Request  $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'mobile_code' => 'required',
            'mobile' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city' => 'required',
            'pin' => 'required',
        ]);

        DB::beginTransaction();

        $user = new User();
        $user->name = $request->name;
        $user->country_code = $request->mobile_code;
        $user->phone = $request->mobile;
        $user->email = $request->email;
        $password = Str::random(8);
        $user->password = Hash::make($password);
        $user->role = User::ROLE_STUDENT;
        $user->save();

        $student = new Student();
        $student->user_id = $user->id;
        $student->name = $request->name;
        $student->email = $request->email;
        $student->country_code = $request->mobile_code;
        $student->phone = $request->mobile;
        $student->country_id = $request->country_id;
        $student->state_id = $request->state_id;
        $student->city = $request->city;
        $student->pin = $request->pin;
        $student->course_id = $request->course_id;
        $student->level_id = $request->level_id;
        $student->save();

        $address = new Address();
        $address->user_id = $user->id;
        $address->name = $request->name;
        $address->country_code = $request->mobile_code;
        $address->phone = $request->mobile;
        $address->city = $request->city;
        $address->state = $request->state_id_text;
        $address->pin = $request->pin;
        $address->address = $request->address;
        $address->save();

        $jMoney = new JMoney();
        $jMoney->user_id = $user->id;
        $jMoney->activity = JMoney::SIGN_UP;
        $jMoney->points = JMoneySetting::first()->sign_up_point;
        $jMoney->expire_after = JMoneySetting::first()->sign_up_point_expiry;
        $jMoney->expire_at = Carbon::now()->addDays($jMoney->expire_after);
        $jMoney->save();

        DB::commit();

        $user_details = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $password
        ];

        try{
            Mail::send(new SignUpMail($user_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

        return redirect()->back()->with('success', 'Student created successfully');
    }

    public function tableStudentOrderItems($id){

        $orders = Order::where('user_id',$id)->pluck('id');
        $order_items = OrderItem::with('order','package')
                                ->whereIn('order_id',$orders)
                                ->get();

        if (request()->ajax()) {
            return DataTables::of($order_items)
                ->editColumn('price', function ($order_items) {
                    switch ($order_items->price_type) {
                        case $order_items->price_type == OrderItem::PRICE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::PRICE_TEXT . '</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::DISCOUNTED_PRICE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::DISCOUNTED_PRICE_TEXT . '</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::SPECIAL_PRICE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::SPECIAL_PRICE_TEXT . '</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::PENDRIVE_TEXT . '</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE_DISCOUNTED_PRICE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::PEN_DRIVE_DISCOUNTED_PRICE_TEXT . '</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE_SPECIAL_PRICE:
                            return '₹ ' . $order_items->price . '  <sup><span class="badge badge-info">' . OrderItem::PEN_DRIVE_SPECIAL_PRICE_TEXT . '</span></sup>';
                            break;
                        default:
                            return 'Unknown';
                            break;
                    }
                })
                ->editColumn('package.name', function ($order_items) {
                    if ($order_items->is_prebook) {
                        return $order_items->package->name . '  <sup><span class="badge badge-info">PREBOOK</span></sup>';
                    }

                    return $order_items->package->name;
                })
                ->editColumn('booking_amount', function ($order_items) {
                    if ($order_items->booking_amount) {
                        if ($order_items->is_booking_amount_paid) {
                            return '₹ ' . $order_items->booking_amount . '  <sup><span class="badge badge-success">PAID</span></sup>';
                        } else {
                            return '₹ ' . $order_items->booking_amount . '  <sup><span class="badge badge-danger">NOT PAID</span></sup>';
                        }

                    } else return '0.00';
                })
                ->editColumn('balance_amount', function ($order_items) {
                    if ($order_items->balance_amount) {
                        if ($order_items->is_balance_amount_paid) {
                            return '₹ ' . $order_items->balance_amount . '  <sup><span class="badge badge-success">PAID</span></sup>';
                        } else {
                            return '₹ ' . $order_items->balance_amount . '  <sup><span class="badge badge-danger">NOT PAID</span></sup>';
                        }

                    } else return '0.00';
                })
                ->editColumn('delivery_mode', function ($order_items) {
                    if ($order_items->delivery_mode == OrderItem::PEN_DRIVE) {
                        return '<span class="badge badge-default">' . OrderItem::PENDRIVE_TEXT . '</span>';
                    } else {
                        return '<span class="badge badge-default">' . OrderItem::ONLINE_TEXT . '</span>';
                    }
                })
                ->rawColumns(['price', 'package.name', 'booking_amount', 'delivery_mode', 'balance_amount'])
                ->make(true);
        }
    }

    public function tableStudentTransactions($id){

        $payments = Payment::with('user','order')
                            ->whereHas('order',function ($orders)use($id){
                                $orders->where('user_id',$id) ;
                            })->get();

        if (request()->ajax()) {
            return DataTables::of($payments)
                ->editColumn('receipt_no', function($payments) {
                    if($payments->receipt_no){
                        return '#'.str_pad($payments->receipt_no, 6, "0", STR_PAD_LEFT);
                    }
                  return '-';
                })
                ->addColumn('tax', function($payments) {
                    if($payments->cgst && $payments->sgst && !$payments->igst){
                        return ' CGST : ₹ '.round($payments->cgst_amount,2).' ('.$payments->cgst.'%) <br>'.' SGST : ₹ '.round($payments->sgst_amount,2).' ('.$payments->sgst.'%)';
                    }
                    elseif($payments->igst && !($payments->cgst && $payments->sgst)){
                        return 'IGST : ₹ '.round($payments->igst_amount,2).' ('.$payments->igst.'%) <br>' ;
                    }
                    else return '-';
                })
                ->addColumn('discounts', function($payments) {
                    if($payments->coupon_amount && $payments->reward_amount) {
                        return ' COUPON : ₹ '.$payments->coupon_amount.' ('.$payments->coupon_code.') <br>'.
                            ' REWARDS : ₹ '.$payments->reward_amount;
                    }
                    elseif($payments->coupon_amount ){
                        return ' COUPON : ₹ '.$payments->coupon_amount.' ('.$payments->coupon_code.') <br>';
                    }
                    elseif($payments->reward_amount ){
                        return ' REWARDS : ₹ '.$payments->reward_amount;
                    }

                    else return '-';
                })
                ->editColumn('payment_status', function($payments) {
                    if($payments->payment_status == Order::PAYMENT_STATUS_SUCCESS){
                        if($payments->payment_updated_method ==  Order::UPDATE_METHOD_CRON ){
                            return '<span class="badge badge-success">Success</span><br> Cron';
                        }
                        elseif($payments->payment_updated_method ==  Order::UPDATE_METHOD_MANUAL ){
                            return ' <span class="badge badge-success">Success</span><br> Manual';
                        }
                        else{
                            return ' <span class="badge badge-success">Success</span> <br>Ccavenue';
                        }

                    }
                    else  return '<span class="badge badge-danger">Failed</span>';
                })
                ->addColumn('response', function($payments) {
                    if ($payments->payment_status == 1) {
                        if($payments->payment_updated_method!=2){
                            return '<a class="a-response" data-id=' . $payments->id . '><i class="fas fa-eye"></i></a>';
                        }
                        return "Success";
                    }

                    return 'Failed';
//                    return '<a class="no-response" data-id=' . $payments->order_id . '><i class="fa fa-check"></i></a>';

                })
                ->editColumn('user.name', function($payments) {
                    if ($payments->user && $payments->updated_ip_address ) {
                        return $payments->user->name.' ('.$payments->updated_ip_address.')';
                    }

                    return '-';
                })
                ->addColumn('created_at', function($payments) {
                    return $payments->created_at->toDayDateTimeString();
                })
                ->rawColumns(['tax','discounts','payment_status','response','user.name'])
                ->make(true);
        }

    }
    public function edit(Request $request, $id)
    {
        $student = Student::with('course','level','country','state')->findOrFail($id);

        return response()->json($student);
    }
    public function update(Request $request, $id)
    {
//        $validated = $request->validate([
//            'name' => 'required',
//            'email' => 'required|email:rfc,dns|unique:users',
//            'mobile_code' => 'required',
//            'mobile' => 'required',
//            'course_id' => 'required',
//            'level_id' => 'required',
//            'country_id' => 'required',
//            'state_id' => 'required',
//            'city' => 'required',
//            'pin' => 'required',
//        ]);

        DB::beginTransaction();

        $state_name = State::where('id',$request->state_id)->first();

        $student = Student::findOrFail($id);
        $student->name = $request->name;
        $student->email = $request->email;
        $student->country_code = $request->mobile_code;
        $student->phone = $request->mobile;
        $student->country_id = $request->country_id;
        $student->state_id = $request->state_id;
        $student->city = $request->city;
        $student->pin = $request->pin;
        $student->address = $request->address;
        $student->course_id = $request->course_id;
        $student->level_id = $request->level_id;
        $student->update();

        $address = Address::where('user_id',$student->user_id)->first();
        $address_update = Address::findOrFail($address->id);
        $address_update->name = $request->name;
        $address_update->country_code = $request->mobile_code;
        $address_update->phone = $request->mobile;
        $address_update->city = $request->city;
        $address_update->state = $state_name->name;
        $address_update->pin = $request->pin;
        $address_update->address = $request->address;
        $address_update->update();


        $user = User::where('id',$student->user_id)->first();
        $user_update = User::findOrFail($user->id);
        $user_update->name = $request->name;
        $user_update->country_code = $request->mobile_code;
        $user_update->phone = $request->mobile;
        $user_update->email = $request->email;
        $user_update->update();

        DB::commit();

        return redirect()->back()->with('success', 'Student Updated Successfully');

    }


}
