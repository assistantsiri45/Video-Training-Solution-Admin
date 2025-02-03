<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class FeedbackListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = OrderItem::query()->orderBy('reviewed_at','desc');

            return DataTables::of($query)
                ->filter(function ($query) {
//                    $query->where('is_approved', true);
                    $query->latest();

                    if (request()->filled('filter.search')) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    }
                })
                ->editColumn('package_id', function ($query) {
                    if(!$query->package) {
                        return '';
                    }
                    return $query->package->name;
                })
                ->editColumn('user_id', function ($query) {
                    if(!$query->user) {
                        return '';
                    }
                    return $query->user->name;

                })
                ->addColumn('accepted_at', function ($query) {
                    if(!$query->accepted_at) {
                        return '';
                    }
                    $reviewedAt =  Carbon::parse($query->reviewed_at);
                    $accpetedAt = Carbon::parse($query->accepted_at);
                    $timeDifference = $accpetedAt->diffForHumans($reviewedAt, null, $absolute = false);
                    return str_replace(['after', 'before'], [' ', ' '], $timeDifference);


                })
//                ->addColumn('rejected_at', function ($query) {
//                    if(!$query->rejected_at) {
//                        return '';
//                    }
//                    $reviewedAt =  Carbon::parse($query->reviewed_at);
//                    $rejectedAt = Carbon::parse($query->rejected_at);
//                    $timeDifference = $rejectedAt->diffForHumans($reviewedAt, null, $absolute = false);
//                    return str_replace(['after', 'before'], [' ', ' '], $timeDifference);
//
//
//                })
                ->editColumn('review_status', function ($query) {
                    if($query->review_status == OrderItem::REVIEW_STATUS_PENDING) {

                        return '<span class="badge badge-primary">Pending</span>';

                    }elseif ($query->review_status == OrderItem::REVIEW_STATUS_ACCEPTED)
                    {
                        return '<span class="badge badge-success">Accepted</span>';
                    }
                    return '<span class="badge badge-danger">Rejected</span>';
                })

                ->addColumn('action', 'pages.feedback_lists.action')
                ->rawColumns(['action','review_status','user_id','package_id'])
                ->make(true);
        }

        $table = $builder->columns([

            ['data' => 'package_id', 'name' => 'package_id', 'title' => 'Package'],
            ['data' => 'user_id', 'name' => 'user_id', 'title' => 'User'],
            ['data' => 'review_title', 'name' => 'review_title', 'title' => 'Review Title'],
            ['data' => 'review', 'name' => 'review', 'title' => 'Review'],
            ['data' => 'rating', 'name' => 'rating', 'title' => 'Rating'],
            ['data' => 'accepted_at', 'name' => 'accepted_at', 'title' => 'Accepted After'],
//            ['data' => 'rejected_at', 'name' => 'rejected_at', 'title' => 'Rejected After'],
            ['data' => 'review_status', 'name' => 'review_status', 'title' => 'Review Status'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'stateSave'=>true,
            'searching' => false,
            'ordering' => false
        ]);

        return view('pages.feedback_lists.index', compact('table'));
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

    public function StatusAccepted(Request $request, $id)
    {
        $orderItem = OrderItem::findOrFail($id);

        $orderItem->review_status = OrderItem::REVIEW_STATUS_ACCEPTED;
        $orderItem->accepted_at = Carbon::now();
        $orderItem->save();
        return response()->json($orderItem, 200);
    }
    public function StatusRejected(Request $request, $id)
    {

        $orderItem = OrderItem::findOrFail($id);

        $orderItem->review_status = OrderItem::REVIEW_STATUS_REJECTED;
        $orderItem->rejected_at = Carbon::now();
        $orderItem->save();
        return response()->json($orderItem, 200);
    }
}
