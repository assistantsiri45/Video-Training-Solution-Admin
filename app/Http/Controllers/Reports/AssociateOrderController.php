<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Builder;

class AssociateOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Order::query()->with('associate.user','student','orderItem.package')->whereNotNull('associate_id')->latest();

//            if (request()->filled('filter.search')) {
//            $query->where(function ($query) {
//                $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
//                    ->orWhereHas('student', function ($query) {
//                        $query->where(function ($query) {
//                            $query
//                                ->where('email', 'like', '%' . request()->input('filter.search') . '%')
//                                ->orWhere('phone', 'like', '%' . request()->input('filter.search') . '%');
//                        });
//                    });
//            });
//        }


            return \Yajra\DataTables\DataTables::of($query)
                ->filter(function($query) {

                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query->where('id',request()->input('filter.search'))
                                ->orwhere('name', 'like', '%' . request()->input('filter.search') . '%')
                                ->orWhereHas('student', function ($query) {
                                    $query->where(function ($query) {
                                        $query
                                            ->where('email', 'like', '%' . request()->input('filter.search') . '%')
                                            ->orWhere('phone', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                })
                                ->orWhereHas('associate.user', function ($query) {
                                    $query->where(function ($query) {
                                        $query
                                            ->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                });
                        });
                    }

                })
//                ->editColumn('package',function ($query)
//                {
//                    return $query->orderItem->package->name ?? '';
//                })
                ->editColumn('associate_id',function ($query)
                {
                    if ($query->associate_id) {
                        return $query->associate->user->name;
                    }
                })
                ->editColumn('created_at',function ($query)
                {
                    $date = Carbon::parse($query->created_at)->format('d M Y');
                    return $date;
                })
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => '#Order'],
            ['data' => 'associate_id', 'name' => 'associate_id', 'title' => 'Name'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Student'],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email'],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
//            ['data' => 'package', 'name' => 'package', 'title' => 'Package'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date'],
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.reports.associates.index',compact('html'));
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
