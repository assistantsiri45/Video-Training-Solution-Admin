<?php

namespace App\Http\Controllers;


use App\Models\Courierorder;
use App\Mail\StudentCourier;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\StudyMaterialOrderLog;
use App\Notifications\StudentCourier as NotificationsStudentCourier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {

            $query = OrderItem::query()->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                ->where(function($query) {
                    $query->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
                          ->orWhere('delivery_mode', '=', 2);
                });


//            $query = $query->where('delivery_mode', 2);

//            $query = $orderItems->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
//                  ->orWhere('delivery_mode', 2);

            if (request()->filled('filter.search')) {
                $query->where('id', request()->input('filter.search'))
                    ->orWhere('order_id', request()->input('filter.search'))
                    ->orWhere(function ($query) {
                        $query->whereHas('order', function ($query) {
                            $search = '%' . request()->input('filter.search') . '%';
                            $query->where('name', 'like', $search);
                        })
                            ->orWhere(function($query) {
                                $query->whereHas('package', function($query) {
                                    $search = '%' . request()->input('filter.search') . '%';
                                    $query->where('name', 'like', $search);
                                });
                            });
                    });
            }

            if (request()->filled('filter.order_status')) {
                $query->where('order_status', request()->input('filter.order_status'));
            }
            if (request()->filled('filter.order_type')) {
                if(request()->input('filter.order_type') == 'study_material'){
                    $query->where('item_type', 2);
                }
                if(request()->input('filter.order_type') == 'pendrive'){
                    $query->where('delivery_mode', 2);
                }
            }

            $query->with('order.userAddress');

            return DataTables::of($query)
                ->addColumn('receipt_no', function ($query) {
                    return $query->order->payment->receipt_no ?? '-';
                })
                ->addColumn('name', function ($query) {
                    return '<a class="a-view-address" href="#modal-address" data-toggle="modal" data-order="' . htmlspecialchars(json_encode(optional($query->order)->userAddress), ENT_QUOTES, 'UTF-8') . '">' . optional($query->order)->name . '</a>';
                })
                ->addColumn('transaction_id', function ($query) {
                    return $query->order->transaction_id ?? '-';
                })
                ->addColumn('price', function ($query) {
                    return ('₹' . $query->price) ?? '-';
                })
                ->addColumn('package', function ($query) {
                    return $query->package->name ?? '-';
                })
                ->addColumn('payment_status', function ($query) {
                    if ($query->payment_status == OrderItem::PAYMENT_STATUS_FAILED) {
                        return '<span class="badge badge-danger">Failed</span>';
                    }

                    if ($query->payment_status == OrderItem::PAYMENT_STATUS_FULLY_PAID) {
                        return '<span class="badge badge-success">Success</span>';
                    }

                    return '-';
                })
                ->addColumn('created_at', function ($query) {
                    return $query->created_at->format('d-m-Y H:i:s') ?? '-';
                })
                ->addColumn('updated_at', function ($query) {
                    return $query->updated_at->format('d-m-Y H:i:s') ?? '-';
                })
                ->addColumn('order_status', function ($query) {
                    if ($query->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
                        $status = $query->order_status ?? null;

                        if ($status) {
                            switch ($status) {
                                case $status == OrderItem::STATUS_ORDER_PLACED:
                                    return '<span class="badge badge-secondary">' . OrderItem::STATUS_ORDER_PLACED_TEXT . '</span>';
                                case $status == OrderItem::STATUS_ORDER_ACCEPTED:
                                    return '<span class="badge badge-warning">' . OrderItem::STATUS_ORDER_ACCEPTED_TEXT . '</span>';
                                case $status == OrderItem::STATUS_ORDER_SHIPPED:
                                    return '<span class="badge badge-primary">' . OrderItem::STATUS_ORDER_SHIPPED_TEXT . '</span>';
                                case $status == OrderItem::STATUS_ORDER_DELIVERED:
                                    return '<span class="badge badge-success">' . OrderItem::STATUS_ORDER_DELIVERED_TEXT . '</span>';
                                case $status == OrderItem::STATUS_ORDER_CANCELED:
                                    return '<span class="badge badge-danger">' . OrderItem::STATUS_ORDER_CANCELED_TEXT . '</span>';
                                default:
                                    return '-';
                            }
                        }
                    }

                    return '-';
                })
                ->addColumn('item_type', function ($query) {
                    if ($query->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL && $query->delivery_mode == 2) {
                        return '<span class="badge badge-success">Study Material</span> <span class="badge badge-success">Pendrive</span>';
                    }
                    if ($query->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL && $query->delivery_mode != 2) {
                        return '<span class="badge badge-success">Study Material</span>';
                    }
                    if ($query->item_type != OrderItem::ITEM_TYPE_STUDY_MATERIAL && $query->delivery_mode == 2) {
                        return '<span class="badge badge-success">Pendrive</span>';
                    }

                    return '-';
                })
                ->addColumn('action', function ($query) {
                    if ($query->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
                        $id = $query->id ?? null;
                        $status = $query->order_status ?? null;
                        $studymaterial= StudyMaterialOrderLog::with('user','orderItem')->where('order_item_id',$id)->orderBy('status')->get();

                        $showorderhistory= '<a class="a-change-status" href="#modal-change-status" data-toggle="modal" data-id="' . $id . '" data-status="' . $status .'"><i class="fas fa-box-open"></i></a>';
                    return    $showorderhistory.='<a class="a-show-orderhistory" href="#modal-show-orderhistory" data-toggle="modal" data-order="' . htmlspecialchars(json_encode($studymaterial), ENT_QUOTES, 'UTF-8') . '">' . '<i class="fas fa-eye"></i>' . '</a>';
                        // <a class="a-show-orderhistory" href="#modal-show-orderhistory" data-toggle="modal" data-id="' . $id . '" data-status="' . $status .'"><i class="fas fa-box-open"></i></a>';
                    }

                    return '-';
                })
                ->rawColumns(['name', 'payment_status', 'order_status', 'action', 'item_type'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'orderable' => true],
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order ID', 'orderable' => false],
            ['data' => 'receipt_no', 'name' => 'receipt_no', 'title' => 'Receipt No.', 'orderable' => false],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID', 'orderable' => false],
            ['data' => 'item_type', 'name' => 'item_type', 'title' => 'Type', 'orderable' => false],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'orderable' => false],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price', 'orderable' => false],
            ['data' => 'package', 'name' => 'package', 'title' => 'Package', 'orderable' => false],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status', 'orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At', 'orderable' => false],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated At', 'orderable' => false],
            ['data' => 'order_status', 'name' => 'order_status', 'title' => 'Order Status', 'orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false]
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'lengthChange' => false,
            'pageLength'=> 8,
            'processing'=> true
        ])->orderBy(0, 'desc');

        $orderReceivedCount = OrderItem::where('order_status',
            OrderItem::STATUS_ORDER_PLACED)->whereDate('created_at', '>', Carbon::now()->subDays(7))
            ->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
            ->latest()
            ->count();
        $orderAcceptedCount = OrderItem::where('order_status',
            OrderItem::STATUS_ORDER_ACCEPTED)->whereDate('updated_at', '>', Carbon::now()->subDays(7))
            ->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
            ->latest()
            ->count();
        $orderShippedCount = OrderItem::where('order_status',
            OrderItem::STATUS_ORDER_SHIPPED)->whereDate('updated_at', '>', Carbon::now()->subDays(7))
            ->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
            ->latest()
            ->count();
        $orderDeliveredCount = OrderItem::where('order_status',
            OrderItem::STATUS_ORDER_DELIVERED)->whereDate('updated_at', '>', Carbon::now()->subDays(7))
            ->where('item_type', '!=', OrderItem::ITEM_TYPE_PACKAGE)
            ->latest()
            ->count();
        return view('pages.purchases.index', compact('html','orderReceivedCount','orderAcceptedCount','orderShippedCount','orderDeliveredCount'));
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->input('status')==3){
            $courier_id=$request->input('courier_id');
            $dispatch_detail=$request->input('dispatch_detail');
            $this->sendEmailSmsToStudent($id,$courier_id, $dispatch_detail);
        }


        $orderItem = OrderItem::findOrFail($id);
        $orderItem->order_status = $request->input('status');
        $orderItem->save();

        if ($orderItem->item_type == OrderItem::ITEM_TYPE_STUDY_MATERIAL) {
            $studyMaterialOrderLog = new StudyMaterialOrderLog();
            $studyMaterialOrderLog->order_item_id = $orderItem->id;
            $studyMaterialOrderLog->user_id = auth()->id();
            $studyMaterialOrderLog->status = $orderItem->order_status;
            $studyMaterialOrderLog->save();
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendEmailSmsToStudent($order_item_id,$courier_id,$dispatch_detail){

       $courierordersave= new Courierorder;
       $courierordersave->courier_id=$courier_id;
       $courierordersave->dispatch_detail=$dispatch_detail;
       $courierordersave->order_item_id=$order_item_id;
       $courierordersave->save();

        $courierorder = Courierorder::select('courierorders.*')
        ->with('courier','orderItem.package','orderItem.order.student')
        ->where('order_item_id',$order_item_id)
        ->first();


        try{
            Mail::send(new StudentCourier($courierorder));
        }
        catch (\Exception $exception) {
            info($exception->getMessage(), ['exception' => $exception]);
        }

     //  $notification = new NotificationsStudentCourier($courierorder);
    //   Notification::route('sms', $courierorder->orderItem->order->student->phone)->notify($notification);
       $values= "Student,Your {$courierorder->orderItem->package->name} despatched using {$courierorder->courier->name} tracking no is {$courierorder->dispatch_detail}. Track using {$courierorder->courier->url} JKSHAH Education";
       $arrContextOptions=array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

    $data = 'username=' . 'K3JKSHAH' . '&apikey=' . '67311-C0DBD'. '&apirequest=' .'Template' . '&sender=' . 'JKSHAH' . '&mobile=' . $courierorder->orderItem->order->student->phone . '&TemplateID=' . 1107161821502842076 . '&Values=' . $values . '&route=' . 'ServiceImplicit' ;


    $url      = 'https://k3digitalmedia.co.in/websms/api/http/index.php?'.$data;
    $url      = preg_replace("/ /", "%20", $url);
    $response = file_get_contents($url, false, stream_context_create($arrContextOptions));

    }
    // public function test(){
    //    $study= StudyMaterialOrderLog::with('user','orderItem')->where('order_item_id',9201)->get();

    // //  echo  $study->user->name;
    // //  echo $study->orderItem->created_at;
    // foreach($study as $val){
    //        echo  $val->user->name;
    //       echo   $val->orderItem->created_at;
    //        echo  $val->status;
    //       echo   $val->updated_at;
    //      echo    $val->created_at;
    //     // print_r($val);
    //     // exit;
    // }
    //    //dd($study);
    // }

//     public function test(){
//         $arrContextOptions=array(
//             "ssl"=>array(
//                 "verify_peer"=>false,
//                 "verify_peer_name"=>false,
//             ),
//         );
//         $data = [
//             'username' => 'K3JKSHAH',
//             'apikey' => '67311-C0DBD',
//             'apirequest' => 'Template',
//             'sender' => 'JKSHAH',
//             'mobile' => 9096954548,
//             'TemplateID' => 1107161821502842076,
//             'Values' => 'student,test',
//             'route' => 'ServiceImplicit'
//         ];
//         $data = 'username=' . 'K3JKSHAH' . '&apikey=' . '67311-C0DBD'. '&apirequest=' .'Template' . '&sender=' . 'JKSHAH' . '&mobile=' . 9096954548 . '&TemplateID=' . 1107161821502842076 . '&Values=' . 'student,test' . '&route=' . 'ServiceImplicit' ;


//         $url      = 'https://k3digitalmedia.co.in/websms/api/http/index.php?'.$data;
//         $url      = preg_replace("/ /", "%20", $url);
//         $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
//         print_r($response);
//         exit;


//         $courierorder = Courierorder::select('courierorders.*')
//         ->with('courier','orderItem.package','orderItem.order.student')
//         ->where('order_item_id',9192)
//         ->first();
//         try {
//           //  $notification = new NotificationsStudentCourier($courierorder);
//           //  print_r($notification);
// $will=new Courierorder();
// $test =$will->send($courierorder);
//           //  $test=Notification::route('sms',9096954548)->notify($notification);
//             echo 'jes';
//             print_r($test);
//         } catch (\Throwable $th) {
//             echo 'will';
//             throw $th;
//         }
//     }
}
