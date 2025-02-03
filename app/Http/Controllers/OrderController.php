<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use App\Mail\PurchaseMail;
use App\Models\OrderItem;
use App\Models\PackageStudyMaterial;
use App\Models\Package;
use App\Models\SubjectPackage;
use App\Models\CustomizedPackage;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Models\HolidayOffer;
use App\Models\Address;
use App\Models\StudyMaterialV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use PDF;
use App\Models\Setting;



class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        if (request()->ajax()) {
            // $query = Order::select('orders.*')->with('student', 'associate.user','third_party.user');
            $query = Order::PaymentStatus()->with('student', 'associate.user','third_party.user','orderItems','payment');

            return DataTables::of($query)
                ->filter(function($query) {

                    if (!empty(request('filter.search'))) {
                        //$query->whereHas('student.name','LIKE', '%'.request('filter.search').'%');

                        $query->whereHas('student', function($query) {
                            $query->where('name','LIKE', '%'.request('filter.search').'%')
                                ->orWhere('email','LIKE', '%'. request('filter.search').'%')
                                ->orWhere('phone','LIKE', '%'. request('filter.search').'%');
                        });
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('transaction_id',  '=', request('filter.search'));
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('net_amount','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('payment_status','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('orders.id','=', request('filter.search'));
                    }
//                    if (!empty(request('filter.date'))) {
//                        $query->whereDate('created_at', Carbon::parse(request('filter.date')));
//                    }
//
                   if (!empty(request('filter.course'))) {
                       $query->whereHas('orderItems.package', function($query) {
                           $query->where('course_id', request('filter.course'));
                       });
                   }
                   if (!empty(request('filter.level'))) {
                    $query->whereHas('orderItems.package', function($query) {
                        $query->where('level_id', request('filter.level'));
                    });
                    }
                    if (!empty(request('filter.type'))) {
                        $query->whereHas('orderItems.package', function($query) {
                            $query->where('package_type', request('filter.type'));
                        });
                    }
                    if (!empty(request('filter.subject'))) {
                        $query->whereHas('orderItems.package', function($query) {
                            $query->where('subject_id', request('filter.subject'));
                        });
                        }
                        if (!empty(request('filter.chapter'))) {
                            $query->whereHas('orderItems.package', function($query) {
                                $query->where('chapter_id', request('filter.chapter'));
                            });
                            }
                            if (!empty(request('filter.language'))) {
                                $query->whereHas('orderItems.package', function($query) {
                                    $query->where('language_id', request('filter.language'));
                                });
                                }
                            
                    if (request()->filled('filter.date')) {
                        $dateRange = request()->input('filter.date');
                        $explodedDates = explode(' - ', $dateRange);
                        $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                        $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                        $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                        $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';
                        $query->whereBetween('created_at', [$from, $to]);
                    }

                })
               
                ->addColumn('order_id', function($query) {

                    return '<a class="a-row-details text-primary" data-id=' . $query->id . ' ><i class="fas fa-eye ml-3"></i></a>';
                })
                ->addColumn('invoice', function($query) {
                    $link = url('invoice_generate/'.$query->id);
                    return '<a class="a-row-details text-primary" href="'.$link.'" target="_blank"><i class="fas fa-file-pdf" ml-3 style="color:#df1a1a"></i></a>';
                })

                ->addColumn('student.name', function($query) {
                                
                    if(!empty($query->student->name)){
                        return $query->student->name;
                    }else{
                        return '-';
                    }
                })

                ->addColumn('student.email', function($query) {
                                
                    if(!empty($query->student->email)){
                        return $query->student->email;
                    }else{
                        return '-';
                    }
                })

                ->addColumn('student.phone', function($query) {
                                
                    if(!empty($query->student->phone)){
                        return $query->student->phone;
                    }else{
                        return '-';
                    }
                })

     
                ->addColumn('created_at', function($query) {
                    if(!empty($query->created_at)){
                        $datetime = explode(" ",$query->created_at);
                        $date = date("d-m-Y", strtotime($datetime[0]));
                        $date_time = $date.', '.$datetime[1];
                        return $date_time;
                    }else{
                        return '-';
                    }
                    
                })
                ->addColumn('net_amount', function($query) {
                    return $query->net_amount;
                })
                
                ->addColumn('payment_status', function($query) {
                    if(!empty($query->transaction_response_status)){
                        return $query->transaction_response_status;
                    }else{
                        return '-';
                    }

                })

                ->addColumn('response', function($query) {
                    
                    return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                      
                })


                ->addColumn('payment_mode', function($query) {
                    if(!empty($query->payment_mode)){
                        if($query->payment_mode == 1){
                            return 'ONLINE';
                        }
                        if($query->payment_mode == 2){
                            return 'CASH ON DELIVERY';
                        }
                        if($query->payment_mode == 3){
                            return 'PREPAID';
                        }
                    }else{
                        return '-';
                    }

                })

                ->addColumn('payment_method', function($query) {
                    $mode='';
                    if ($query->updated_method==1)
                    {
                        $mode= "CCAVENUE";
                    }
                    elseif ($query->updated_method==2)
                    {
                        $mode="MANUAL";
                    }
                    elseif ($query->updated_method==3)
                    {
                    $mode="CRON";
                    }
                    elseif ($query->updated_method==4)
                    {
                        $mode="EASEBUZZ";
                    }else{
                        $mode= '-';
                    }
                    return $mode;

                })

                ->addColumn('transaction_id',function($query){
                    if(!empty($query->transaction_id)){
                        return $query->transaction_id;
                    }else{
                        return '-';
                    }
                })

                ->addColumn('action', 'pages.orders.action')
                ->rawColumns(['response', 'status','action','associate_id','packagename','course','level','subject','chapter',
                'language','validity','content_delivery','study_material','packageprice','id','professors','mode_of_lecture','study_material_fees','is_pendrive','order_id','invoice'])
                ->make(true);
        }

        $html = $builder->columns([
          
            ['data' => 'id', 'name' => 'id', 'title' => 'Order ID','defaultContent' => ''],
            ['data' => 'student.id', 'name' => 'student.id', 'title' => 'Student ID', 'defaultContent' => ''],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Name', 'defaultContent' => ''],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone Number','defaultContent' => ''],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email Address', 'defaultContent' => ''],
            ['data' => 'net_amount','name' => 'net_amount', 'title' => 'Amount'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'payment_mode', 'name' => 'payment_mode', 'title' => 'Payment Type'],
            ['data' => 'payment_method', 'name' => 'payment_method', 'title' => 'Payment Mode'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction Id'],
            ['data' => 'response', 'name' => 'response', 'title' => 'Response'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date & Time'],
            // ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, ],
            ['data' => 'order_id', 'name' => 'order_id', 'title' => '', 'class'=>'details-control','id'=>'id','orderable' => false,],
            ['data' => 'invoice', 'name' => 'invoice', 'title' => '', 'class'=>'details-control','id'=>'id','orderable' => false,],
        ])->parameters([
            'searching' => false,
            'stateSave'=>true,
            'ordering' => true,
            'lengthChange' => false,
            'pageLength'=>15,
            'bInfo' => true
        ])->orderBy(0, 'desc');

        return view('pages.orders.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Builder $builder,$id)
    {
        $orders = Order::find($id);

        $order_items = OrderItem::with('order','package','package.course','package.level','package.subject','package.chapter','package.language')
                                 ->where('order_id',$id)
                                 ->get();

        if (request()->ajax()) {
            return DataTables::of($order_items)

                ->editColumn('price', function($order_items) {
                    switch ($order_items->price_type) {
                        case $order_items->price_type == OrderItem::PRICE: return  '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::PRICE_TEXT.'</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::DISCOUNTED_PRICE: return '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::DISCOUNTED_PRICE_TEXT.'</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::SPECIAL_PRICE: return '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::SPECIAL_PRICE_TEXT.'</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE: return '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::PENDRIVE_TEXT.'</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE_DISCOUNTED_PRICE: return '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::PEN_DRIVE_DISCOUNTED_PRICE_TEXT.'</span></sup>';
                            break;
                        case $order_items->price_type == OrderItem::PEN_DRIVE_SPECIAL_PRICE: return '₹ '.$order_items->price.'  <sup><span class="badge badge-info">' .OrderItem::PEN_DRIVE_SPECIAL_PRICE_TEXT.'</span></sup>';
                            break;
                        default: return 'Unknown';
                            break;
                    }
                })
                ->editColumn('package.name', function($order_items) {
                    if($order_items->is_prebook){
                        return $order_items->package->name.'  <sup><span class="badge badge-info">PREBOOK</span></sup>';
                    }



                    return $order_items->package->name;
                })
                ->editColumn('package.id', function($order_items) {
                  
                    

                    return '+';
                })
                ->editColumn('package.type', function($order_items) {
                  
                    if($order_items->package->type){
                        if ( $order_items->package->type ==1) {

                            return Package::TYPE_CHAPTER_LEVEL_VALUE;
                        }else  if ( $order_items->package->type ==2) {

                            return Package::TYPE_SUBJECT_LEVEL_VALUE;
                        }else{
   
                            return Package::TYPE_CUSTOMIZED_VALUE;
                        }

                    }else{
                        return '';
                    }
              
                    
              
            })

            ->editColumn('package.category', function($order_items) {
                  
                if($order_items->package->category){
                    if ( $order_items->package->category ==1) {

                        return "Video Only";
                    
                    }else{

                        return '';
                    }

                }else{
                    return '';
                }
          
                
          
        })
        ->editColumn('package.course', function($order_items) {
                  
            return $order_items->package->course->name;
        
              
        
      })
      ->editColumn('package.level', function($order_items) {
                        
          return $order_items->package->level->name;
      
            
      
      })
      ->editColumn('package.subject', function($order_items) {
                        
          return @$order_items->package->subject->name;
      
            
      
      })
      ->editColumn('package.chapter', function($order_items) {
                        
          return @$order_items->package->chapter->name;
      
            
      
      })
      ->editColumn('package.expire_at', function($order_items) {
                        
          return date('d-m-Y',strtotime(@$order_items->expire_at));
      
            
      
      })
      ->editColumn('package.language', function($order_items) {
                        
          return @$order_items->package->language->name;
      
            
      
      })
      ->editColumn('expire_at', function($order_items) {
                        
        return @$order_items->expire_at ?? "-";  
    
    })

    ->editColumn('study_material', function($query) {
        if ($query->item_type==2) {
            return '✓';
        }
    })


        ->editColumn('package.ptype', function($order_items) {
                  
            if($order_items->package->category){
                if ( $order_items->package->is_mini ==1) {

                    return " Mini-Package";
                
                }
                if ( $order_items->package->is_crash_course ==1) {

                    return "Crash Course";
                
                }
                if ( $order_items->package->pendrive ==1) {

                    return "Pen Drive";
                
                }

            }else{
                return '';
            }
      
            
      
                })
                ->editColumn('booking_amount', function($order_items) {
                    if($order_items->booking_amount){
                        if($order_items->is_booking_amount_paid){
                            return '₹ '.$order_items->booking_amount .'  <sup><span class="badge badge-success">PAID</span></sup>';
                        }
                        else{
                            return '₹ '.$order_items->booking_amount .'  <sup><span class="badge badge-danger">NOT PAID</span></sup>';
                        }

                    }
                    else return '0.00';
                })  
                
                ->editColumn('discount_amount', function($order_items) {
                    if($order_items->discount_amount){
                        return '₹ '.$order_items->discount_amount ;
                       }
                    else return '-';
                })

                ->editColumn('package_discount_amount', function($order_items) {
                    if($order_items->package_discount_amount){
                        return '₹ '.$order_items->package_discount_amount ;
                       }
                    else return '-';
                })

                ->editColumn('balance_amount', function($order_items) {
                    if($order_items->balance_amount){
                        if($order_items->is_balance_amount_paid){
                            return '₹ '.$order_items->balance_amount .'  <sup><span class="badge badge-success">PAID</span></sup>';
                        }
                        else{
                            return '₹ '.$order_items->balance_amount .'  <sup><span class="badge badge-danger">NOT PAID</span></sup>';
                        }

                    }
                    else return '0.00';
                })
                ->editColumn('delivery_mode', function($order_items) {
                    if($order_items->delivery_mode == OrderItem::PENDRIVE){
                        return '<span class="badge badge-default">'.OrderItem::PENDRIVE_TEXT.'</span>';
                    }
                    elseif($order_items->delivery_mode == OrderItem::G_DRIVE){
                        return '<span class="badge badge-default">'.OrderItem::G_DRIVE_TEXT.'</span>';
                    }
                    else{
                        return  '<span class="badge badge-default">'.OrderItem::ONLINE_TEXT.'</span>';
                    }
                })
                ->rawColumns(['price','package.name','booking_amount','delivery_mode','balance_amount','type','category','course','level','subject','chapter','expire_at','language','id'])
                ->make(true);

        }

        $tableOrderItems= $builder->columns([
            ['data' => 'package.id', 'name' => 'package.id', 'title' => '', 'class'=>'details-control','id'=>'package.id' , 'class'=>'details-control'],
            ['data' => 'package.name', 'name' => 'package.name', 'title' => 'Package'],
            ['data' => 'package.type', 'name' => 'package.type', 'title' => 'Package Type'],
           
            ['data' => 'package.category', 'name' => 'package.category', 'title' => 'Category'],
            ['data' => 'package.ptype', 'name' => 'package.ptype', 'title' => 'Type'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'discount_amount', 'name' => 'discount_amount', 'title' => 'Discount Amount'],
            ['data' => 'package_discount_amount', 'name' => 'package_discount_amount', 'title' => 'Package Discount Amount'],
            ['data' => 'booking_amount', 'name' => 'booking_amount', 'title' => 'Booking Amount'],
            ['data' => 'balance_amount', 'name' => 'balance_amount', 'title' => 'Balance Amount'],
            ['data' => 'study_material', 'name' => 'study_material', 'title' => 'Study Material'],
            ['data' => 'delivery_mode', 'name' => 'delivery_mode', 'title' => 'Delivery Mode'],
            ['data' => 'expire_at', 'name' => 'expire_at', 'title' => 'Validity'],
        ])->parameters([
            'searching' => false,
            'stateSave'=>true,
            'ordering' => true
        ])->setTableId('tbl-orderItems')
          ->orderBy(0, 'desc');

        $tablePayments = app(Builder::class)->columns([
            ['data' => 'receipt_no', 'name' => 'receipt_no', 'title' => 'Receipt No'],
            ['data' => 'tax', 'name' => 'tax', 'title' => 'Tax'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'response', 'name' => 'response', 'title' => 'Response'],
            ['data' => 'discounts', 'name' => 'discounts', 'title' => 'Discounts'],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Updated By'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At']
        ])
            ->parameters([
                'searching' => false,
                'stateSave'=>true,
                'ordering' => false,
                'lengthChange' => false,
                'bInfo' => false
            ])
            ->ajax(route('tables.payments',$id))
            ->setTableId('tbl-payments')
            ->orderBy(0, 'desc');

            return view('pages.orders.show', compact('orders','tableOrderItems','tablePayments','id'));
    }

    public function getPaymentDetails($id)
    {
        $payments = Payment::with('user')->where('order_id',$id)->get();
        if (request()->ajax()) {
            return DataTables::of($payments)
                ->editColumn('receipt_no', function($payments) {
                    return '#'.str_pad($payments->receipt_no, 6, "0", STR_PAD_LEFT);
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
                               ' J-Koins : ₹ '.$payments->reward_amount;
                    }
                     elseif($payments->coupon_amount ){
                         return ' COUPON : ₹ '.$payments->coupon_amount.' ('.$payments->coupon_code.') <br>';
                     }
                     elseif($payments->reward_amount ){
                         return ' J-Koins : ₹ '.$payments->reward_amount;
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
                        elseif($payments->payment_updated_method ==  Order::UPDATE_METHOD_EASEBUZZ ){
                            return ' <span class="badge badge-success">Success</span><br> Easebuzz';
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

                    return '<a class="no-response" data-id=' . $payments->order_id . '><i class="fa fa-check"></i></a>';

                })
                ->editColumn('user.name', function($payments) {
                    if ($payments->user && $payments->updated_ip_address) {
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

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return response()->json(true, 200);
    }

    public function getBarDataByYear() {
        $startOfYear = Carbon::create(request('year'))->startOfYear();
        $endOfYear = Carbon::create(request('year'))->endOfYear();

        if (request()->has('package_id')) {
            $packageID = request('package_id');
            $orderData = Order::whereHas('orderItems', function($query) use($packageID) {
                $query->where('package_id', $packageID);
            })
                ->whereDate('created_at', '>=', $startOfYear)
                ->whereDate('created_at', '<=', $endOfYear)
                ->select(DB::raw('count(*) as count') , DB::raw('MONTH(created_at) as month'))
                ->groupBy('month')
                ->get();
        } else {
            $orderData = Order::whereDate('created_at', '>=', $startOfYear)
                ->whereDate('created_at', '<=', $endOfYear)
                ->select(DB::raw('count(*) as count') , DB::raw('MONTH(created_at) as month'))
                ->groupBy('month')
                ->get();
        }


        $countBarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $countBarData[$month] = 0;
        }

        foreach($orderData as $data) {
            $countBarData[$data->month] = $data->count;
        }

        $amountBarData = [];

        for ($month = 1; $month <= 12; $month++) {
            $amountBarData[$month] = 0;
        }

        foreach($orderData as $data) {
            $amountBarData[$data->month] = $data->sum('net_amount');
        }

        $xAxisData = [1 => 'JAN', 2 => 'FEB', 3 => 'MAR', 4 => 'APR', 5 => 'MAY', 6 => 'JUN', 7 => 'JUL', 8 => 'AUG', 9 => 'SEP', 10 => 'OCT', 11 => 'NOV', 12 => 'DEC'];

        return response()->json(['count' => ['data' => $countBarData, 'xAxis' => $xAxisData], 'amount' => ['data' => $amountBarData, 'xAxis' => $xAxisData]]);
    }

    public function getBarDataByMonth() {
        $MonthAndYear = request('month');
        $MonthAndYear = explode('-', $MonthAndYear);
        $month = $MonthAndYear[0];
        $year = $MonthAndYear[1];

        $startOfMonth = Carbon::create($year, $month)->startOfMonth();
        $endOfMonth = Carbon::create($year, $month)->endOfMonth();
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;

        $xAxisData = [];

        for ($xAxis = 1; $xAxis <= $daysInMonth; $xAxis++) {
            $xAxisData[$xAxis] = $xAxis;
        }

        if (request()->has('package_id')) {
            $packageID = request('package_id');

            $orderData = Order::whereHas('orderItems', function($query) use($packageID) {
                $query->where('package_id', $packageID);
            })
                ->whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->select(DB::raw('count(*) as count') , DB::raw('DAY(created_at) as day'))
                ->groupBy('day')
                ->get();
        } else {
            $orderData = Order::whereDate('created_at', '>=', $startOfMonth)
                ->whereDate('created_at', '<=', $endOfMonth)
                ->select(DB::raw('count(*) as count') , DB::raw('DAY(created_at) as day'))
                ->groupBy('day')
                ->get();
        }

        $countBarData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $countBarData[$day] = 0;
        }

        foreach($orderData as $data) {
            $countBarData[$data->day] = $data->count;
        }

        $amountBarData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $amountBarData[$day] = 0;
        }

        foreach($orderData as $data) {
            $amountBarData[$data->day] = $data->sum('net_amount');
        }

        return response()->json(['count' => ['data' => $countBarData, 'xAxis' => $xAxisData], 'amount' => ['data' => $amountBarData, 'xAxis' => $xAxisData]]);
    }

    public function getOrderResponse()
    {
        $order = Order::findOrFail(request()->input('id'));

        return json_encode(json_decode($order->transaction_response ?? null), JSON_PRETTY_PRINT);
    }

    public function getPaymentResponse()
    {
        $payment = Payment::findOrFail(request()->input('id'));

        return json_encode(json_decode($payment->transaction_response ?? 'null'), JSON_PRETTY_PRINT);
    }

    public function fetchOrderItems()
    {
        $order = Order::findOrFail(request()->input('id'));

        $order_items = OrderItem::with('package')->where('order_id',request()->input('id'))->get();

        return $order_items;
    }

    public function fetchOrderDetails(){

        $order = Order::findOrFail(request()->input('id'));

        $order_items = OrderItem::with('package')->where('order_id',request()->input('id'))->get();
        $i=1;
        $packageIDs=[];
        $data['course'] = $data['level']=$data['subject']=$data['chapter']=$data['language']=$data['professors']=$data['mode_of_lecture']=$data['package']=$data['package_validity']='';
        $data['study_material']=$data['study_material_price']=$data['is_pendrive']=$data['pendrive_price']=$data['reward_amount']=$data['coupon_amount']=$data['expiry_date']=$data['package_duration']='';
        $data['holiday_offer_amount']=$data['net_amount']=$data['address']=$data['transaction_id']=$data['invoice_no']= $data['created_at']=$data['order_id']=$data['response']=$data['pkg_type']='';
        $data['gross_amount']=0;
        foreach($order_items as $val2){
            
            if(!empty($val2->package->name)){
                $data['package'].=$i.') ';
                $data['package'].=$val2->package->name .' <br> ';                               
            }else{
                $data['package'].='- <br> ';
            }

            if(!empty($val2->package->course->name)){
                $data['course'].=$i.') ';
                $data['course'].=$val2->package->course->name .' <br> ';                               
            }else{
                $data['course'].='- <br> ';
            }

            if(!empty($val2->package->level->name)){
                $data['level'].=$i.') ';
                $data['level'].=$val2->package->level->name .' <br> ';                               
            }else{
                $data['level'].='- <br> ';
            }

            if(!empty($val2->package->packagetype->name)){
                $data['pkg_type'].=$i.') ';
                $data['pkg_type'].=$val2->package->packagetype->name.' <br> ';                               
            }else{
                $data['pkg_type'].='- <br> ';
            }

            if(!empty($val2->package->subject->name)){
                $data['subject'].=$i.') ';
                $data['subject'].=$val2->package->subject->name .' <br> ';                               
            }else{
                $data['subject'].='- <br> ';
            }

            if(!empty($val2->package->chapter->name)){
                $data['chapter'].=$i.') ';
                $data['chapter'].=$val2->package->chapter->name .' <br> ';                               
            }else{
                $data['chapter'].='- <br> ';
            }

            if(!empty($val2->package->language->name)){
                $data['language'].=$i.') ';
                $data['language'].=$val2->package->language->name .' <br> ';                               
            }else{
                $data['language'].='- <br> ';
            }

            if ($val2->item_type==2) {
                $data['study_material'].=$i.') '.'Yes <br>';
                $data['study_material_price'].=$i.') '.$val2->price.'<br>';
            }
            else{
                $data['study_material'].=$i.') '.'No <br> ';
                $data['study_material_price'].=$i.') '.'-<br> ';
            }

            if(!empty($val2->package)){
                $data['mode_of_lecture'].=$i.') ';
                if($val2->package->pendrive==true){
                    $data['mode_of_lecture'].=OrderItem::PENDRIVE_TEXT.'<br>';
                }
                elseif($val2->package->g_drive == true){
                    $data['mode_of_lecture'].=OrderItem::G_DRIVE_TEXT.'<br>';
                }
                else{
                    $data['mode_of_lecture'].=OrderItem::ONLINE_TEXT.'<br>';
                }
               
            }else{
                $data['mode_of_lecture'].='- <br> ';
            }

            if(!empty($val2->package->expiry_type)){
                $data['package_validity'].=$i.') ';
                if($val2->package->expiry_type == 1){
                    $data['package_validity'].=$val2->package->expiry_month .' '.'Months<br> ';
                }elseif($val2->package->expiry_type == 2){
                    $data['package_validity'].=$val2->package->expire_at .'<br> ';
                }
            }else{
                $data['package_validity'].=$i.') ';
                if(!empty($val2->package->expire_at)){
                    $data['package_validity'].=$val2->package->expire_at .' <br> ';                               
                }else{
                    $data['package_validity'].='9 Months<br> ';
                }
            }

            if(!empty($val2->expire_at)){
                $data['expiry_date'].=$i.')'.$val2->expire_at.'<br>';
            }else{
                $data['expiry_date'].='- <br>';
            }

            if(!empty($val2->package_duration)){
                $data['package_duration'].=$i.')'.$val2->package_duration.'<br>';
            }else{
                $data['package_duration'].='-<br>';
            }

            $data['gross_amount']= $data['gross_amount'] +$val2->price;

            ++$i;
        }
        if(!empty($order_items)){
            foreach($order_items as $val2){
                if(!empty($val2->package->type)){
                   // $data['professors'].=$i.')';
                    if($val2->package->type == 1){
                        $packageIDs[] = $val2->package->id;
                    }
                    
                    if($val2->package->type == 2){
                        $packageIDss = SubjectPackage::where('package_id', $val2->package->id)->get()->pluck('chapter_package_id');
                        foreach($packageIDss as $packages){
                            $package = Package::find($packages);
    
                            $packageIDs[]=$package->id;
                        }
                    }
    
                    if($val2->package->type == 3){
                        $selectedPackageIDs = CustomizedPackage::where('package_id', $val2->package->id)->get()->pluck('selected_package_id');
                        foreach ($selectedPackageIDs as $selectedPackageID) {
    
                            $package = Package::find($selectedPackageID);
            
                            if ($package->type == 1) {
                                $packageIDs[] = $package->id;
                            }
            
                            if ($package->type == 2) {
                                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');
            
                                foreach ($chapterPackageIDs as $chapterPackageID) {
                                    $package = Package::find($chapterPackageID);
                                    $packageIDs[] = $package->id;
                                }
                            }
                        }
                    } 
                }           
            }
            $professorIDs = PackageVideo::whereIn('package_id', $packageIDs)->with('video')->get()->pluck('video.professor_id')->unique();
        
            $professors = Professor::whereIn('id', $professorIDs)->get();

            foreach($professors as $professor){
                if($professors->last()==$professor){
                    $data['professors'].= $professor->name;
                }
                else{
                 $data['professors'].= $professor->name.',';
                }
            }
        }else{
            $data['professors'].='- <br>';
        }
        if(!empty($order->pendrive_price)){
            $data['is_pendrive'] .= 'Yes';
            $data['pendrive_price'] = $order->pendrive_price;
        }else{
            $data['is_pendrive'] .= 'No';
            $data['pendrive_price'] = '-';
        }

        if($order->reward_amount){
            $data['reward_amount']=$order->reward_amount;
        }else{
            $data['reward_amount']='-';
        }

        if($order->coupon_amount){
            $data['coupon_amount']=$order->coupon_amount;
        }else{
            $data['coupon_amount']='-';
        }

        if($order->holiday_offer_amount){
            $data['holiday_offer_amount']=$order->holiday_offer_amount;
        }else{
            $data['holiday_offer_amount']= '-';
        }

        if(!empty($order->net_amount)){
            $data['net_amount']=$order->net_amount;
        }else{
            $data['net_amount']='-';
        }

        if(!empty($order->address)){
            $data['address']=$order->address;
        }else{
            $data['address']='-';
        }

        if ($order->transaction_id) {
            $data['transaction_id']= $order->transaction_id;
        }else{
            $data['transaction_id']='';
        }

        if(!empty($order->payment->receipt_no)){
            $data['invoice_no']=$order->payment->receipt_no;
        }else{
            $data['invoice_no']='-';
        }

        if(!empty($order->created_at)){
            $datetime = explode(" ",$order->created_at);
            $date = date("d-m-Y", strtotime($datetime[0]));
            $date_time = $date.', '.$datetime[1];
            $data['created_at']=$date_time;
        }else{
            $data['created_at']='-';
        }

        if(!empty($order->id)){
            $data['order_id']=$order->id;
        }else{
            $data['order_id']='';
        }


        return $data;
    }

    public function assignPackages()
    {
        DB::beginTransaction();

        //UPDATE ORDERS TABLE
        $update_order = Order::findOrFail(request()->input('id'));
        $update_order->payment_status = Order::PAYMENT_STATUS_SUCCESS;
        $update_order->transaction_response_status = "Success";
        $update_order->transaction_response = "Success";
        $update_order->updated_by = auth()->id();
        $update_order->updated_method = Order::UPDATE_METHOD_MANUAL;
        $update_order->updated_ip_address = request()->ip();
        $update_order->update();

        //UPDATE PAYMENTS TABLE
        $update_payment = Payment::where('order_id',request()->input('id'))->first();
        if($update_payment){
            $update_payment->receipt_no = Payment::getReceiptNo();
            $update_payment->payment_status = Order::PAYMENT_STATUS_SUCCESS;
            $update_payment->unique_key = rand(0,100000000000);
            $update_payment->transaction_response_status = "Success";
            $update_payment->transaction_response = "Success";
            $update_payment->payment_updated_by = auth()->id();
            $update_payment->payment_updated_method = Order::UPDATE_METHOD_MANUAL;
            $update_payment->updated_ip_address = request()->ip();
            $update_payment->update();
        }

        $order_items = OrderItem::where('order_id',$update_order->id)->pluck('package_id');
        $packages = Package::with('subject')->whereIn('id',$order_items)->get();

        // TO SEND PURCHASE DETAILS MAIL TO STUDENT
        $order_details = Student::where('user_id','=',$update_order->user_id)->first();
        $order_details['order_id'] = $update_order->id;
        $order_details['net_amount'] = $update_order->net_amount;
        $order_details['packages'] = $packages;
        if($update_order['cgst']){
            $order_details['cgst'] = $update_order['cgst'];
            $order_details['cgst_amount'] = $update_order['cgst_amount'];
        }
        if($update_order['igst']){
            $order_details['igst'] = $update_order['igst'];
            $order_details['igst_amount'] = $update_order['igst_amount'];
        }
        if($update_order['sgst']){
            $order_details['sgst'] = $update_order['sgst'];
            $order_details['sgst_amount'] = $update_order['sgst_amount'];
        }

        DB::commit();

        try{
            Mail::send(new PurchaseMail($order_details));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

        return 1;
    }

    public function export()
    {
        $search = request()->input('export_search') ?? '';
        $date = request()->input('export_date') ?? '';
        $course = request()->input('export_course') ?? '';
        $level = request()->input('export_level') ?? '';
        $type = request()->input('export_type') ?? '';
        $subject = request()->input('export_subject') ?? '';
        $chapter = request()->input('export_chapter') ?? '';
        $language = request()->input('export_language') ?? '';
        
        return Excel::download(new OrderExport($search,$date,$course,$level,$type,$subject,$chapter,$language), 'ORDERS_' . time() . '.xlsx');
    }
    /* To download invoice from order report*/
    public function invoiceGen($order_id){
        $student_orders = Payment::with(['orderItems.package' => function($query) {
            $query->withTrashed();
        }])
             ->where('order_id', $order_id)
             ->first();
            
    
        $student_orders['net_amount_without_tax'] = $student_orders->net_amount - (( mb_strtoupper($student_orders->order->state) == 'MAHARASHTRA') ?  ($student_orders['cgst_amount'] +$student_orders['sgst_amount'] ) : $student_orders['igst_amount']);
        $gst = Setting::where('key','=','gstn')->first();
        $data['gstn'] = $gst->value;
       
        $data['pendrive_price'] = Setting::where('key','=','pendrive_price')->first();
           
        $data['order_details'] =  $student_orders;
  //  dd($student_orders);
  $data['holiday_offer_name']='';
  if($student_orders['order']['holiday_offer_id']){
    $offer_id=$student_orders['order']['holiday_offer_id'];
    $data['holiday_offer_name']=HolidayOffer::where('id',$offer_id)->first()->name;
}else{
    $data['holiday_offer_name']='';
}
    
        $data['order_item']= $student_orders['orderItems'];
    
        $pdf = PDF::loadView('pdf.invoice_latest',$data)->setPaper('A4', 'portrait');
        return $pdf->download('Invoice-' . $order_id . '.pdf');
    }
    
}
