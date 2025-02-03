<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Package;
use App\Models\PackageExtension;
use App\Models\User;
use App\Models\VideoHistory;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        DB::enableQueryLog();
//
//        return DB::table("order_items")
//            ->join("packages", function($join){
//                $join->on("packages.id", "=", "order_items.package_id");
//            })
//            ->select("order_items.order_id", "order_items.price", "packages.name")
//            ->get();
//
//
//        dd(DB::getQueryLog());






//        $posts = OrderItem::query()->with('package');
//
//        $posts = $posts->toSql();
//
//        dd($posts);
//
//
//        $bindings = $posts->getBindings();
//
//        return $bindings;

// $posts = $posts->paginate(4);
//        $orderItems = OrderItem::with('package')->get();
//
////        return $orderItems->toSql();
//
//        dd($orderItems->toSql(), $orderItems->getBindings());
////
////        info();
////
//        return $orderItems;
    }

    public function updateProgressPercentage()
    {
        return '0';

        $orderItems = OrderItem::all();

        foreach ($orderItems as $orderItem){

            $totalDurationWatched = VideoHistory::where('user_id', $orderItem->user_id)
                ->where('package_id', $orderItem->package_id)
                ->where('order_item_id', $orderItem->id)->sum('duration');

            $package = Package::find($orderItem->package_id);
            if($package){
                $packageTotalDuration = $package->total_duration * $package->duration;

                $percentage = ($totalDurationWatched * 100) / $packageTotalDuration;

                $item = OrderItem::find($orderItem->id);
                $item->progress_percentage = $percentage;
                $item->save();
            }
        }

        return '1';
    }


    public function updateExpireAt(Request $request)
    {
        return '0';

        $orderItems = OrderItem::whereNull('expire_at')->cursor();

        foreach ($orderItems as $orderItem){
            $orderItem->expire_at = Carbon::parse($orderItem->created_at)->addMonths(Package::VALIDITY_IN_MONTHS);
            $orderItem->save();
        }

        return '1';
    }

    public function updateExtention()
    {
        return '0';

        $packageExtensions = PackageExtension::get();
        foreach ($packageExtensions as $packageExtension){
            $orderItem = OrderItem::find($packageExtension->order_item_id);
            $orderItem->extended_date = $packageExtension->extended_date;
            $orderItem->extended_hours = $packageExtension->extended_hours;
            $orderItem->save();
        }

        return '1';
    }
}
