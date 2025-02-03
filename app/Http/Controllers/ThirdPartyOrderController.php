<?php

namespace App\Http\Controllers;

use App\Mail\OrderCancelled;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Associate;
use App\Models\CustomizedPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageStudyMaterial;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\Student;
use App\Models\SubjectPackage;
use App\Models\User;
use App\Notifications\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;


class ThirdPartyOrderController extends Controller
{
    public function index()
    {
        return view('pages.third_party_order.index');
    }
    public function getStudent(Request $request)
    {
       $student = Student::query()->where('email',$request->name)->orWhere('phone',$request->name)->get();

       return response()->json($student);

    }
    public function show(Builder $builder,$id)
    {
        if (request()->ajax()) {

            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user')
                ->where('is_approved', 1)
                ->where('is_archived', 0)
                ->latest();


            if (request()->has('filter.status') && !empty(request('filter.status'))) {
                if (request('filter.status') == 'published') {
                    $query->where('is_approved', 1);
                }

                if (request('filter.status') == 'unpublished') {
                    $query->where('is_approved', 0);
                }
            }

            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            return \Yajra\DataTables\DataTables::of($query)
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
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
                ->addColumn('approved_by', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    else {
                        return '-';
                    }
                })
                ->editColumn('name', function($query) use ($id){
                    if ($query->name) {
                        return '<div><input type="hidden" id="student-id-'.$query->id.'" value="'.$id.'"></div>'.$query->name;
                    }
                    return '-';
                })
                ->editColumn('price',function ($query){

                    $test = [];
                    foreach ($query->strike_prices as $data)
                    {
                        $test[] = $data;
                    }
                    $result = collect($test)->all();
                    $price = implode(', ', $result);

                    if($price != null) {
                        return '<del>'.$price.'</del>,'.$query->selling_price;
                    }
                    else{
                        return $query->selling_price;
                    }


                })
                ->addColumn('professors', function ($query) {
                    $packageID = $query->id;
                    $package = Package::find($packageID);
                    $packageIDs = [];

                    if ($package->type == 1) {
                        $packageIDs[] = $package->id;
                    }

                    if ($package->type == 2) {
                        $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->pluck('chapter_package_id');

                        foreach ($chapterPackageIDs as $chapterPackageID) {
                            $packageIDs[] = $chapterPackageID;
                        }
                    }

                    if ($package->type == 3) {
                        $selectedPackageIDs = CustomizedPackage::where('package_id', $package->id)->pluck('selected_package_id');

                        foreach ($selectedPackageIDs as $selectedPackageID) {
                            $package = Package::find($selectedPackageID);

                            if ($package->type == 1) {
                                $packageIDs[] = $package->id;
                            }

                            if ($package->type == 2) {
                                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->pluck('chapter_package_id');

                                foreach ($chapterPackageIDs as $chapterPackageID) {
                                    $packageIDs[] = $chapterPackageID;
                                }
                            }
                        }
                    }

                    $professorIDs = PackageVideo::with('video')
                        ->whereIn('package_id', $packageIDs)
                        ->get()
                        ->pluck('video.professor_id')
                        ->unique()
                        ->values();

                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                    return implode(', ', $professorNames);
                })
                ->addColumn('action','pages.third_party_order.action')
                ->rawColumns(['category', 'is_approved', 'action','name','price'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'professors', 'name' => 'professors', 'title' => 'Professors'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        $student = Student::with('course','level','country','state')->where('id',$id)->first();

        return view('pages.third_party_order.show', compact('html','student'));
    }
    public function store(Request $request)
    {
        
        DB::beginTransaction();
       $student = Student::where('id',$request->student_id)->first();
       $address = Address::where('user_id',$student->user_id)->first();
        $order = new Order();
        $order->user_id = $student->user_id;
        $order->third_party_id = Auth::id();
        $order->cgst = 0;
        $order->cgst_amount = 0;
        $order->sgst = 0;
        $order->sgst_amount = 0;
        $order->igst = 0;
        $order->igst_amount = 0;
        $order->transaction_id = rand(0, 1000000000000);
        $order->unique_key = rand(0, 100000000000);
        $order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
        $order->payment_mode = 1;
        $package = Package::find($request->package_id);
        $order->net_amount = 0;
        $order->address_id = $address->id;
        $address = Address::find($address->id);
        $order->name = $address->name ?? null;
        $order->country_code = $address->country_code ?? null;
        $order->phone = $address->phone ?? null;
        $order->alternate_phone = $address->alternate_phone ?? null;
        $order->city = $address->city ?? null;
        $order->state = $address->state ?? null;
        $order->pin = $address->pin ?? null;
        $order->address = $address->address ?? null;
        $order->status = 1;
        $order->updated_method = 2;
        $order->updated_ip_address = request()->ip();
        $order->save();


        $orderItem = new OrderItem();
        $orderItem->order_id = $order->id;
        $orderItem->package_id = $package->id;
        $orderItem->user_id = $order->user_id;
        $orderItem->price = $package->study_material_price ?? null;
        $orderItem->price_type = 1;
        $orderItem->is_prebook = false;
        $orderItem->delivery_mode = 1;
        $orderItem->payment_status = 2;
        $orderItem->is_completed = 0;
        $orderItem->item_type = OrderItem::ITEM_TYPE_PACKAGE;
        $orderItem->item_id = $package->id;
        if ($package->expiry_type == '1') {
            $orderItem->expire_at = Carbon::now()->addMonths($package->expiry_month);
        } else if ($package->expiry_type == '2') {
            $orderItem->expire_at = $package->expire_at;
        } else {
            $orderItem->expire_at = Carbon::now()->addMonths(Package::VALIDITY_IN_MONTHS);
        }
        $orderItem->save();


        if($request->is_study_material)
        {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->package_id = $package->id;
            $orderItem->user_id = $order->user_id;
            $orderItem->price = $package->study_material_price ?? null;
            $orderItem->price_type = 1;
            $orderItem->is_prebook = false;
            $orderItem->delivery_mode = 1;
            $orderItem->payment_status = 2;
            $orderItem->is_completed = 0;
            $orderItem->item_type = OrderItem::ITEM_TYPE_STUDY_MATERIAL;
            $orderItem->item_id = $package->id;
            $orderItem->order_status = OrderItem::STATUS_ORDER_PLACED;
            if ($package->expiry_type == '1') {
                $orderItem->expire_at = Carbon::now()->addMonths($package->expiry_month);
            } else if ($package->expiry_type == '2') {
                $orderItem->expire_at = $package->expire_at;
            } else {
                $orderItem->expire_at = Carbon::now()->addMonths(Package::VALIDITY_IN_MONTHS);
            }

            $orderItem->save();

            $user = User::with('address')->find($order->user_id);

            if ($user) {
                try {
                    Mail::send(new OrderPlaced([
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'order_id' => $orderItem->order_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'address' => optional($user->address)->address ?? '',
                        'area' => optional($user->address)->area ?? '',
                        'landmark' => optional($user->address)->landmark ?? '',
                        'city' => optional($user->address)->city ?? '',
                        'state' => optional($user->address)->state ?? '',
                        'pin' => optional($user->address)->pin ?? '',
                    ]));
                } catch (\Exception $exception) {
                    info ($exception->getMessage());
                }
            }
        }

        try {
            $user = User::find($order->user_id);

            $attributes['name'] = $user->name;
            $attributes['email'] = $user->email;
            $attributes['package_name'] = $package->name;

            $notification = new OrderCreated($attributes);
            Notification::route('sms', $user->phone)->notify($notification);
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }


        DB::commit();

        return response()->json(true, 200);


    }

    public function cancel($id, Request $request)
    {
        $orderItems = OrderItem::where('order_id', $id)->get();

        foreach ($orderItems as $orderItem) {
            $orderItem->is_canceled = 1;

            if ($orderItem->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
                $orderItem->order_status = OrderItem::STATUS_ORDER_CANCELED;

                $package = Package::find($orderItem->package_id);
                $user = User::find($orderItem->user_id);

                try {
                    Mail::send(new OrderCancelled([
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'order_id' => $orderItem->order_id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'address' => optional($user->address)->address ?? '',
                        'area' => optional($user->address)->area ?? '',
                        'landmark' => optional($user->address)->landmark ?? '',
                        'city' => optional($user->address)->city ?? '',
                        'state' => optional($user->address)->state ?? '',
                        'pin' => optional($user->address)->pin ?? '',
                    ]));
                } catch (\Exception $exception) {
                    info ($exception->getMessage());
                }
            }

            $orderItem->save();
        }

        return response()->json('1','200');

    }

}
