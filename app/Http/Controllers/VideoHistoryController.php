<?php

namespace App\Http\Controllers;

use App\Models\AdminVideoHistory;
use App\Models\AdminVideoHistoryLog;
use App\Models\OrderItem;
use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $videoHistory = AdminVideoHistory::where('video_id', $request->input('video_id'))
            ->where('user_id', Auth::id())
            ->first();


        if (!$videoHistory) {
            $videoHistory = new AdminVideoHistory();
        }

        info($request->total_duration);
        $videoHistory->video_id = $request->input('video_id');
        $videoHistory->user_id = Auth::id();
        $videoHistory->duration = $videoHistory->duration + $request->input('duration');
        $videoHistory->total_duration = $request->input('total_duration');
        $videoHistory->position = $request->input('position');
        $videoHistory->browser_agent = $request->input('browser_agent');
        $videoHistory->save();

        $videoHistoryLog = new AdminVideoHistoryLog();
        $videoHistoryLog->video_id = $request->input('video_id');
        $videoHistoryLog->user_id = Auth::id();
        $videoHistoryLog->package_id = $request->input('package_id');
        $videoHistoryLog->order_item_id = $request->input('order_item_id');
        $videoHistoryLog->duration = $request->input('duration');
        $videoHistoryLog->total_duration = $request->input('total_duration');
        $videoHistoryLog->position = $request->input('position');
        $videoHistoryLog->browser_agent = $request->input('browser_agent');
        $videoHistoryLog->save();

//        $lastWatchedVideo = LastWatchedVideo::where('user_id', Auth::id())->first();
//        if(!$lastWatchedVideo){
//            $lastWatchedVideo = new LastWatchedVideo();
//        }
//        $lastWatchedVideo->video_id = $request->input('video_id');
//        $lastWatchedVideo->user_id = Auth::id();
//        $lastWatchedVideo->package_id = $request->input('package_id');
//        $lastWatchedVideo->order_item_id = $request->input('order_item_id');
//        $lastWatchedVideo->duration = $videoHistoryLog->duration;
//        $lastWatchedVideo->position = $request->input('position');
//        $lastWatchedVideo->save();

//        $totalDurationWatched = AdminVideoHistory::where('user_id', Auth::id())
//            ->where('package_id', $request->input('package_id'))
//            ->where('order_item_id', $request->input('order_item_id'))
//            ->sum('duration');
//
//        $package = Package::find($request->input('package_id'));
//        $packageTotalDuration = $package->total_duration * $package->duration;
//
//
//        $percentage = ($totalDurationWatched * 100) / $packageTotalDuration;
//
//        $orderItem = OrderItem::find($request->input('order_item_id'));
//        $orderItem->progress_percentage = $percentage;
//        $orderItem->total_watched_duration = $totalDurationWatched;
//        $orderItem->save();

        return $this->jsonResponse('Video History updated', $videoHistory);
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
