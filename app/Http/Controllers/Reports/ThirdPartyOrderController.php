<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

class ThirdPartyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = Order::query()->with('third_party.user','student','orderItem.package')->whereNotNull('third_party_id')->latest();
        if (request()->ajax()) {



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
                                ->orWhereHas('third_party.user', function ($query) {
                                    $query->where(function ($query) {
                                        $query
                                            ->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                });
                        });
                    }
                    if (request()->filled('filter.name')) {
                        $query->where(function ($query) {
                            $query->where('name', 'like', '%' . request()->input('filter.name') . '%')
                                ->orWhereHas('third_party.user', function ($query) {
                                    $query->where(function ($query) {
                                        $query
                                            ->where('name', 'like', '%' . request()->input('filter.name') . '%');
                                    });
                                });

                        });
                    }
                    if (request()->filled('filter.date')) {

                        $query->where(function ($query) {
                           

                            $query->whereDate('created_at','=', Carbon::parse(request()->input('filter.date')));
                        });
                    }
                    
                    
                })
                ->addColumn('third_party.user.name', function ($query) {
                    if (@$query->third_party->user->name) {
                        return $query->third_party->user->name;
                    }
    
                    return '-';
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
            ['data' => 'third_party.user.name', 'name' => 'third_party.user.name', 'title' => 'Name'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Student'],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email'],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone'],
//            ['data' => 'package', 'name' => 'package', 'title' => 'Package'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date'],
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.reports.third_party_orders.index',compact('html'));
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
