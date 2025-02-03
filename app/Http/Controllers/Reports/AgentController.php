<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Associate;
use App\Models\Order;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Agent;

class AgentController extends Controller
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
            $query = Associate::query();

            return DataTables::of($query)
                ->filter(function($query) {
                    if (!empty(request('filter.purchase_date'))) {
                        $query->whereHas('orders', function($query) {
                            $query->whereDate('created_at', Carbon::parse(request('filter.purchase_date')));
                        });
                    }

                    if (!empty(request('filter.purchase_count'))) {
                        $query->withCount('orders')->has('orders', request('filter.purchase_count'));
                    }

                    if (!empty(request('filter.purchase_amount'))) {
                        $query->whereHas('orders', function($query) {
                            $query->where('net_amount', '>=', request('filter.purchase_amount'));
                        });
                    }
                })
                ->addColumn('action', 'reports.pages.agents.action')
                ->addColumn('name', function($query) {
                    return $query->user->name ?? null;
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);;

        return view('reports.pages.agents.index', compact('html'));
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
        $associate = Associate::findOrFail($id);
        $associate->sales_count = Order::where('associate_id', $id)->count();
        $associate->commission = Order::where('associate_id', $id)->sum('commission');

        $tableStudents = app(Builder::class)->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'course', 'name' => 'course', 'title' => 'Course'],
            ['data' => 'level', 'name' => 'level', 'title' => 'Level']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ])->ajax(route('reports.table-agent-students') . '?associate_id=' . $id)->setTableId('table-students');

        $tableOrders = app(Builder::class)->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'coupon_amount', 'name' => 'coupon_amount', 'title' => 'Coupon Amount'],
            ['data' => 'reward_amount', 'name' => 'reward_amount', 'title' => 'Reward Amount'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Order Status']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ])->ajax(route('reports.table-agent-orders') . '?associate_id=' . $id)->setTableId('table-orders');

        return view('reports.pages.agents.show', compact('associate', 'tableStudents', 'tableOrders'));
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

    public function getTableStudents()
    {
        if (request()->ajax()) {
            $query = Student::query();
            $query->where('associate_id', request('associate_id'));

            return DataTables::of($query)
                ->addColumn('course', function($query) {
                    return $query->course->name ?? null;
                })
                ->addColumn('level', function($query) {
                    return $query->level->name ?? null;
                })
                ->make(true);
        }
    }

    public function getTableOrders()
    {
        if (request()->ajax()) {
            $query = Order::query();
            $query->where('associate_id', request('associate_id'));

            return DataTables::of($query)
                ->filter(function($query) {
                    if (request('filter.new_students') == 'true') {
                        $query->whereHas('student', function($query) {
                            $query->whereYear('created_at', Carbon::now()->year)
                                ->whereMonth('created_at', Carbon::now()->month);
                        });
                    }
                })
                ->addColumn('payment_status', function($query) {
                    if ($query->status == Order::PAYMENT_STATUS_SUCCESS) {
                        return '<span class="badge bg-success">Success</span>';
                    }

                    if ($query->status == Order::PAYMENT_STATUS_FAILED) {
                        return '<span class="badge bg-danger">Failed</span>';
                    }

                    if ($query->status == Order::PAYMENT_STATUS_RETURN) {
                        return '<span class="badge bg-yellow">Return</span>';
                    }
                })
                ->addColumn('status', function($query) {
                    if ($query->status == Order::STATUS_RECEIVED) {
                        return '<span class="badge bg-secondary">Received</span>';
                    }

                    if ($query->status == Order::STATUS_PROCESSED) {
                        return '<span class="badge bg-primary">Processed</span>';
                    }

                    if ($query->status == Order::STATUS_SHIPPED) {
                        return '<span class="badge bg-info">Shipped</span>';
                    }

                    if ($query->status == Order::STATUS_DELIVERED) {
                        return '<span class="badge bg-success">Delivered</span>';
                    }

                    if ($query->status == Order::STATUS_PENDING) {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                })
                ->rawColumns(['payment_status', 'status'])
                ->make(true);
        }
    }
}
