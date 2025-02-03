<?php

namespace App\Http\Controllers;

use App\Models\JMoney;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class JMoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {

            $query = JMoney::select('j_money.*')->with('students')->where('points','>',0)->whereHas('students');
            return DataTables::of($query)
                ->orderColumn('name', function ($query, $order) {
                    $query->orderBy('id', $order);
                })
                ->orderColumn('activity', function ($query, $order) {
                    $query->orderBy('activity', $order);
                })
                ->orderColumn('order_id', function ($query, $order) {
                    $query->orderBy('order_id', $order);
                })
                ->orderColumn('points', function ($query, $order) {
                    $query->orderBy('points', $order);
                })
                ->orderColumn('expire_at', function ($query, $order) {
                    $query->orderBy('expire_at', $order);
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('j_money.created_at', $order);
                })
                ->filter(function($query) {
                    if (request()->filled('filter.name')) {
                       $query->WhereHas('students', function ($query) {
                         $query->where('name', 'like', '%' . request()->input('filter.name') . '%');
                      });
                    }
                    if (request()->filled('filter.activity')) {
                       $query->where('activity', request()->input('filter.activity'));
                    }
                    
                     if (!empty(request('filter.transaction_type'))) {
                           $query->where('transaction_type',request('filter.transaction_type'));
                    }
                    
                    if (request()->filled('filter.date')) {
                             $query->whereDate('expire_at', Carbon::parse(request('filter.date')));
                    }

                    if (request()->filled('filter.transaction_date')) {
                        $dateRange = request()->input('filter.transaction_date');
                        $explodedDates = explode(' - ', $dateRange);
                        $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                        $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                        $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                        $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';

                        $query->whereBetween('j_money.created_at', [$from, $to]);
                    }
                })
                ->editColumn('students.name', function($query) {
                    if($query->students){
                        return $query->students->name;
                    }
                    else{
                        return '';
                    }
                })
                ->editColumn('activity', function($query) {
                    if($query->activity == JMoney::SIGN_UP)
                        return '<span class="badge badge-info">Signup</span>';
                    elseif($query->activity==JMoney::FIRST_PURCHASE)
                        return '<span class="badge badge-info">First Purchase</span>';
                    elseif($query->activity==JMoney::PROMOTIONAL_ACTIVITY)
                        return '<span class="badge badge-info">Promotional Activity</span>';
                    elseif($query->activity==JMoney::REFERRAL_ACTIVITY)
                        return '<span class="badge badge-info">Referral Activity</span>';
                    elseif($query->activity==JMoney::REFUND)
                        return '<span class="badge badge-info">Refund</span>';
                    elseif($query->activity==JMoney::CASHBACK)
                       return '<span class="badge badge-info">Cashback</span>';
                    elseif($query->activity==JMoney::PURCHASE)
                        return '<span class="badge badge-info">Purchase</span>';
                })
                ->editColumn('expire_after', function($query) {
                    return $query->expire_after.' days';
                })
                ->editColumn('expire_at', function($query) {
                    if($query->expire_at!=null){
                    if($query->expire_at<=Carbon::now())
                        return $query->expire_at.' <span class="badge badge-danger">Expired</span>';
                    else return $query->expire_at;
                }
                })
               ->editColumn('order_id',  function($query) {
                   if($query->order_id!=null)
                         return $query->order_id;
                     else
                         return '';
                })
               ->editColumn('transaction_type',  function($query) {
                  if($query->transaction_type==1)
                       return 'Credit';
                  else if($query->transaction_type==2)
                       return 'Debit';
                  else
                     return '';
                }) 

                ->addColumn('created_at', function($query) {
                    return $query->created_at;
                })

                ->addColumn('action', 'pages.j_money.action')
                ->rawColumns(['activity','expire_after','expire_at','action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'students.name', 'name' => 'students.name', 'title' => 'Name'],
            ['data' => 'activity', 'name' => 'activity', 'title' => 'Activity','orderable' => true],
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order ID','orderable' => true],
            ['data' => 'points', 'name' => 'points', 'title' => 'Points','orderable' => true,],            
            ['data' => 'transaction_type', 'name' => 'transaction_type', 'title' => 'Transaction Type'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Transaction Date','orderable' => true],
            ['data' => 'expire_at', 'name' => 'expire_at', 'title' => 'Expires At',],
//            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '30px']
        ]);
        return view('pages.j_money.index',compact('html'));
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
        $j_money = JMoney::findOrFail($id);
        $j_money->delete();

        return response()->json([
            'message' => 'Successfully deleted',
            'data' => $j_money
        ], 200);
    }
}
