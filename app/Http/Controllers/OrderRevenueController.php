<?php

namespace App\Http\Controllers;

use App\Exports\OrderRevenueExport;
use App\Mail\PurchaseMail;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;

class OrderRevenueController extends Controller
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
            $query = Order::PaymentStatus()->select('orders.*')->with('student', 'associate.user','third_party.user');

            return DataTables::of($query)
                ->filter(function($query) {

                    if (!empty(request('filter.search'))) {

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

                })

                ->addColumn('student.name', function($query) {
                                
                    if(!empty($query->student->name)){
                        return $query->student->name;
                    }else{
                        return '-';
                    }
                })

                ->addColumn('packagename', function($query) {
                    $query2=  OrderItem::with('package')->where('order_id',$query->id)->get();
                        $val3='';
                        $i=1;
                        foreach($query2 as $val2){
                            if(!empty($val2->package->name)){
                                $val3.=$i.') ';
                                $val3.=$val2->package->name .' <br> ';                               
                            }else{
                                $val3.='- <br> ';
                            }
                            ++$i;
                        }
                    return $val3;

                })
                ->addColumn('response', function($query) {
                    if ($query->payment_status != 1) {
                        return '<a class="no-response" data-id=' . $query->id . '><i class="fa fa-check"></i></a>';
                    }

                    return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                })
                ->addColumn('created_at', function($query) {
                    return $query->created_at->toDayDateTimeString();
                })
                ->addColumn('net_amount', function($query) {
                    return $query->net_amount;
                })
                ->editColumn('updated_by', function($query) {
                    if ($query->updated_by) {
                        return $query->updatedBy->name;
                    }

                    return '-';
                })
                ->editColumn('updated_method', function($query) {
                    if ($query->updated_method) {
                        if ($query->updated_method == Order::UPDATE_METHOD_CCAVENUE) {
                            return 'CC AVENUE';
                        }

                        if ($query->updated_method == Order::UPDATE_METHOD_MANUAL) {
                            return 'MANUAL';
                        }

                        if ($query->updated_method == Order::UPDATE_METHOD_CRON) {
                            return 'CRON';
                        }
                    }

                    return '-';
                })
                ->editColumn('updated_ip_address', function($query) {
                    if ($query->updated_ip_address) {
                        return $query->updated_ip_address;
                    }

                    return '-';
                })
                ->editColumn('transaction_id', function($query) {
                    if ($query->transaction_id) {
                        return $query->transaction_id;
                    }

                    return '-';
                })
                ->editColumn('is_refunded', function($query) {
                    if ($query->is_refunded==1) {
                        return '✓';
                    }
                })
                ->editColumn('associate_id', function($query) {
                    if ($query->associate_id) {
                        return $query->associate->user->name.' <span class="badge badge-primary">Agent</span>';
                    }
                    if (!$query->third_party)
                    {
                        return '-';
                    }
                    else{
                        return $query->third_party->user->name.' <span class="badge badge-primary">Third Party</span>';
                    }
                })
                ->addColumn('action', 'pages.order_revenue.action')
                ->rawColumns(['response', 'status','action','associate_id','packagename'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'orders.id', 'title' => 'ID'],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Student', 'defaultContent' => ''],
            ['data' => 'packagename', 'name' => 'packagename', 'title' => 'Package', 'defaultContent' => ''],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID'],
            ['data' => 'transaction_response_status', 'name' => 'transaction_response_status', 'title' => 'Payment Status'],
            ['data' => 'is_refunded', 'name' => 'is_refunded', 'title' => 'Refunded?'],
            ['data' => 'associate_id', 'name' => 'associate_id', 'title' => 'Associate Name', 'defaultContent' => ''],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, ],
            ['data' => 'commission', 'name' => 'commission', 'title' => 'Commission']
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'lengthChange' => false,
            'pageLength'=>8,
            'bInfo' => true
        ])->orderBy(0, 'desc');

        return view('pages.order_revenue.index', compact('html'));
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

        $order_items = OrderItem::with('order','package')
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
                    if($order_items->delivery_mode == OrderItem::PEN_DRIVE){
                        return '<span class="badge badge-default">'.OrderItem::PENDRIVE_TEXT.'</span>';
                    }
                    else{
                        return  '<span class="badge badge-default">'.OrderItem::ONLINE_TEXT.'</span>';
                    }
                })
                ->rawColumns(['price','package.name','booking_amount','delivery_mode','balance_amount'])
                ->make(true);

        }

        $tableOrderItems= $builder->columns([
            ['data' => 'package.name', 'name' => 'package.name', 'title' => 'Package'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'booking_amount', 'name' => 'booking_amount', 'title' => 'Booking Amount'],
            ['data' => 'balance_amount', 'name' => 'balance_amount', 'title' => 'Balance Amount'],
            ['data' => 'delivery_mode', 'name' => 'delivery_mode', 'title' => 'Delivery Mode'],
        ])->parameters([
            'searching' => false,
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
                'ordering' => false,
                'lengthChange' => false,
                'bInfo' => false
            ])
            ->ajax(route('tables.payments',$id))
            ->setTableId('tbl-payments')
            ->orderBy(0, 'desc');

        return view('pages.orders.show', compact('orders','tableOrderItems','tablePayments'));
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

        return Excel::download(new OrderRevenueExport($search), 'ORDERS_' . time() . '.csv');
    }
}
