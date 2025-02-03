<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Models\ThirdPartyAgent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;

class OrderController extends Controller
{
    public function index(Builder $builder)
    {

        if (request()->ajax()) {
            $query = Order::query()->with('student','orderItem.package')->where('third_party_id',Auth::id())->latest();


            return \Yajra\DataTables\DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.search')) {
                        info(request()->input('filter.search'));
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
                                ->orWhereHas('orderItem.package', function ($query) {
                                    $query->where(function ($query) {
                                        $query
                                            ->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                });
                        });
                    }

                })
                ->addColumn('action', function ($query) {
                    return view('pages.third_party_order.orders.action', ['query' => $query]);
                })
                ->editColumn('package',function ($query)
                {
                    return $query->orderItem->package->name ?? '';
                })
                ->editColumn('created_at',function ($query) {
                    return \Carbon\Carbon::parse($query->created_at)->toFormattedDateString();
                })
                ->addColumn('last_assigned_at', function ($query) {
                    if ($query->student) {
                        if ($query->student->orderItems) {
                            if ($query->orderItems()->latest()->first()) {
                                return \Carbon\Carbon::parse($query->orderItems()->latest()->first()->created_at)->toFormattedDateString();
                            }
                        }
                    }

                    return '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'student.email', 'name' => 'student.email', 'title' => 'Email'],
            ['data' => 'student.phone', 'name' => 'student.phone', 'title' => 'Phone'],
            ['data' => 'package', 'name' => 'package', 'title' => 'Package'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At'],
            ['data' => 'last_assigned_at', 'name' => 'last_assigned_at', 'title' => 'Last Assigned At'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Price'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
        ]);

        return view('pages.third_party_order.orders.index',compact('html'));
    }
}
