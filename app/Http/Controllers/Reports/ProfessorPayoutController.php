<?php

namespace App\Http\Controllers\Reports;

use App\Models\ProfessorPayout;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ProfessorPayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = ProfessorPayout::with('professor','order','package');

            return DataTables::of($query)
                ->filter(function($query) {
                    if (!empty(request('filter.date'))) {
                        $query->whereDate('created_at', Carbon::parse(request('filter.date')));
                    }
                    if (!empty(request('filter.professor_id'))) {
                        $query->where('professor_id', request('filter.professor_id'));
                    }
                    if (!empty(request('filter.package_id'))) {
                        $query->where('package_id', '=', request('filter.package_id'));
                    }
                    if (!empty(request('filter.order_id'))) {
                       $query->where('order_id', '=', request('filter.order_id'));
                    }
                    if (!empty(request('filter.amount'))) {
                        $query->whereBetween( 'amount', [request('filter.amount')-1,request('filter.amount')+1]);
                    }
                })
//                ->editColumn('amount', function($query) {
//                    if($query->amount){
//                        return  'Rs ' .round($query->amount) .'/-';
//                    }
//                })
                ->editColumn('created_at', function($query) {
                    if($query->created_at){
                        return Carbon::createFromFormat('Y-m-d H:i:s', $query->created_at)->format('d-m-Y');
                    }
                })
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'professor.name', 'name' => 'professor_name', 'title' => 'Professor'],
            ['data' => 'package.name', 'name' => 'package_name', 'title' => 'Package'],
            ['data' => 'order_id', 'name' => 'order_id', 'title' => 'Order ID'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
            ['data' => 'percentage', 'name' => 'percentage', 'title' => 'Percentage (%)'],
            ['data' => 'created_at', 'name' => 'date', 'title' => 'Date'],
//            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.reports.professor_payouts.index', compact('html'));
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
}
