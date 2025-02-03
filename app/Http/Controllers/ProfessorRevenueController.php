<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\ProfessorRevenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ProfessorRevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax())
        {
            $query = ProfessorRevenue::query()->with('package','professor')->orderBy('invoice_date', 'desc');
            return DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query
                                ->where('package_total',request('filter.search'));
                        });
                    }
                    if (request()->filled('filter.date')) {
                        $query->where(function ($query) {
                            $query
                                ->whereDate('invoice_date',request('filter.date'));
                        });
                    }
                    if (request()->filled('filter.package')) {
                        $query->where(function ($query) {
                            $query
                                ->WhereHas('package',function ($query){
                                    $query->where('id', request('filter.package'));
                                });
                        });
                    }
                    if (request()->filled('filter.professor')) {
                        $query->where(function ($query) {
                            $query
                                ->WhereHas('professor',function ($query){
                                    $query->where('id', request('filter.professor'));
                                });
                        });
                    }
                })
                ->editColumn('invoice_id', function ($professorRevenue){
                    if(!$professorRevenue->invoice_id){
                        return '-';
                    }
                    return $professorRevenue->invoice_id;
                })
                ->editColumn('invoice_date',function ($query)
                {
                    if($query->invoice_date)
                    {
                        $invoice_date=Carbon::parse($query->invoice_date)->format('d-m-yy');
                        return $invoice_date;
                    }
                })
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor', 'width' => '30%'],
            ['data' => 'package.name', 'name' => 'package.name', 'title' => 'Package', 'width' => '30%'],
            ['data' => 'invoice_id', 'name' => 'invoice_id', 'title' => '#Invoice', 'width' => '30%'],
            ['data' => 'package_total', 'name' => 'package_total', 'title' => 'Package Amount', 'width' => '30%'],
            ['data' => 'package_revenue_percentage', 'name' => 'package_revenue_percentage', 'title' => 'Package Revenue(%)', 'width' => '30%'],
            ['data' => 'professor_contribution_percentage', 'name' => 'professor_contribution_percentage', 'title' => 'Professor Contribution(%)', 'width' => '30%'],
            ['data' => 'revenue_amount', 'name' => 'revenue_amount', 'title' => 'Revenue Amount', 'width' => '30%'],
            ['data' => 'invoice_date', 'name' => 'invoice_date', 'title' => 'Date', 'width' => '30%'],
//            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.reports.professor_revenue.index', compact('html'));
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
