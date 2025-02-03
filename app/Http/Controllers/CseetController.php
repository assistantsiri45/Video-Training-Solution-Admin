<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\CseetStudentDoc;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Payment;

class CseetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = CseetStudentDoc::query()->with('user','package','order');
            $query->whereHas('order', function($query) {
                $query->where('payment_status',3);
                $query->orWhere('payment_status',1);
            });
            return DataTables::of($query)
            ->addColumn('package.name', function ($query) {
                if($query->package->name){
                    return $query->package->name;
                }
                else{
                    return '-';
                }
            })
            ->addColumn('payment_status', function ($query) {
                if($query->order->payment_status){
                  if($query->order->payment_status==1){
                    return 'Success';

                  }else if($query->order->payment_status==3){
                    return 'Refund';

                  }
                
                else{
                    return '-';
                }
                }
            })
            ->addColumn('filename', function ($query) {
                if($query->filename){
                    $file= $query->filename;
                    $path=env('WEB_URL').'/enroll_proofs/'.$file;
                    return '<a href="'.$path.'" target="_blank" >'.  $file.'</a>';
                }
                else{
                    return '-';
                }
            })
            ->addColumn('action', function ($query) {
                if ($query->is_verified==0)  {
                    return view('pages.cseet.action', compact('query'));
                }else if($query->is_verified==1){
                    return '<span class="badge badge-success">Verified</span>';
                }else if($query->is_verified==2){
                    return '<span class="badge badge-danger">Rejected</span>';
                }
                    else{
                        return '<span class="badge badge-primary">Pending</span>';;
                }
                
            })
            
               // ->addColumn('action', 'pages.cseet.action')
                ->rawColumns(['action','filename'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID','defaultContent' => ''],
            ['data' => 'user.id', 'name' => 'user.id', 'title' => 'User Id','defaultContent' => ''],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Student', 'defaultContent' => ''],
            ['data' => 'user.email', 'name' => 'user.email', 'title' => 'Email', 'defaultContent' => ''],
            ['data' => 'user.phone', 'name' => 'user.phone', 'title' => 'Phone', 'defaultContent' => ''],
            ['data' => 'package.name', 'name' => 'package.name', 'title' => 'Package Name', 'defaultContent' => '', 'searchable' => true],
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'order Id', 'defaultContent' => ''],
            ['data' => 'order.net_amount', 'name' => 'order.net_amount', 'title' => 'Net Amount', 'defaultContent' => ''],
            ['data' => 'filename', 'name' => 'filename', 'title' => 'Document Uploaded', 'defaultContent' => ''],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]
        ])  ->parameters([
            'searching' => true,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 10,
        ])->orderBy(0,'desc');

        return view('pages.cseet.index', compact('html'));
        
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
        //
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

    public function StatusAccepted(Request $request, $id)
    {
        $cseetStudentDoc = CseetStudentDoc::findOrFail($id);
        $order_id=$cseetStudentDoc->order_id;

        OrderItem::where('order_id', $order_id)
       ->update([
           'payment_status' => 2
        ]);

        Payment::where('order_id', $order_id)
        ->update([
            'payment_status' => 1
         ]);
        

        $cseetStudentDoc->is_verified = 1;
      
        $cseetStudentDoc->save();
        return redirect('cseet-students')->with('success', 'Certificate verified successfully');;
    }
    public function StatusRejected(Request $request, $id)
    {

        $cseetStudentDoc = CseetStudentDoc::findOrFail($id);

        $cseetStudentDoc->is_verified = 2;
      
        $cseetStudentDoc->save();

        $order_id=$cseetStudentDoc->order_id;
        $order=Order::findOrFail($order_id);
        $order->payment_status=3;
        $order->save();

        return redirect('cseet-students')->with('error', 'Certificate rejected');;
    }
}
