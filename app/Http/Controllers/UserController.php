<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Package;
use App\Models\User;
use App\Models\VideoHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class UserController extends Controller
{
    public function usage(Request $request, Builder $builder)
    {
        $userID = request()->input('user_id');
        $orderItemID = request()->input('order_item_id');
        $orderItem = OrderItem::query()->find($orderItemID);
        $packageID = $orderItem->package_id ?? null;

        if ($request->ajax()) {
            $query = VideoHistory::query();

            $query->where('user_id', $userID)->with('video')
                ->where('order_item_id', $orderItemID)
                ->where('package_id', $packageID);

            return DataTables::of($query)
                ->addColumn('duration', function ($query) {
                    return Package::getFormattedDuration($query->duration);
                })
                ->addColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->toDayDateTimeString();
                })
                ->make(true);
        }

        $table = $builder->columns([
            ['name' => 'created_at', 'data' => 'created_at', 'title' => 'Date'],
            ['name' => 'video.title', 'data' => 'video.title', 'title' => 'Video Title'],
            ['name' => 'duration', 'data' => 'duration', 'title' => 'Duration'],
            ['name' => 'browser_agent', 'data' => 'browser_agent', 'title' => 'Browser Agent'],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false
        ]);

        $watchLimit = Package::find($packageID)->duration ?? null;
        $packageDuration = Package::find($packageID)->total_duration ?? 0;

        $totalDuration = floatval($watchLimit) * $packageDuration;
        $totalDurationWatched = VideoHistory::where('user_id', $userID)->where('package_id', $packageID)->where('order_item_id', $orderItemID)->sum('duration');
        $remainingDuration = round($totalDuration) - round($totalDurationWatched);

        $users = User::query()
            ->where('role', User::ROLE_STUDENT)
            ->get();

        $orderItems = OrderItem::query()
            ->where('user_id', $userID)
            ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
            ->where('item_type', OrderItem::ITEM_TYPE_PACKAGE)
            ->orderBy('updated_at', 'desc')
            ->with('package')
            ->get();

        return view('pages.users.usage', compact('totalDuration', 'remainingDuration', 'users', 'orderItems', 'table'));
    }
}
