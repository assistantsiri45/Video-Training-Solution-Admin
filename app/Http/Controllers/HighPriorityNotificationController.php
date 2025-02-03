<?php

namespace App\Http\Controllers;

use App\Models\HighPriorityNotification;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class HighPriorityNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = HighPriorityNotification::query();

            return DataTables::of($query)
                ->addColumn('action', 'pages.high_priority_notifications.action')
                ->editColumn('status', function($query) {
                    if ($query->status) {
                        return '<span class="badge badge-success">Active</span>';
                    }
                    if (!$query->third_party)
                    {
                        return '-';
                    }
                })->rawColumns(['action','status'])

                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'start_at', 'name' => 'start_at', 'title' => 'Start At', 'width' => '20%'],
            ['data' => 'end_at', 'name' => 'start_at', 'title' => 'End At', 'width' => '20%'],
            ['data' => 'content', 'name' => 'content', 'title' => 'Content', 'width' => '40%'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status', 'width' => '10%'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.high_priority_notifications.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.high_priority_notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_at' => 'required',
            'end_at' => 'required',
            'content' => 'required',
        ])->validate();

        if($request->input('status') == 'on'){
            HighPriorityNotification::query()->update(['status' => 0]);
        }

        $notification = new HighPriorityNotification();
        $notification->start_at = $request->input('start_at');
        $notification->end_at = $request->input('end_at');
        $notification->content = $request->input('content');
        $notification->status = $request->input('status') == 'on';
        $notification->save();

        return redirect(route('high-priority-notifications.index'))->with('success', 'Notification successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HighPriorityNotification  $highPriorityNotification
     * @return \Illuminate\Http\Response
     */
    public function show(HighPriorityNotification $highPriorityNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HighPriorityNotification  $highPriorityNotification
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $notification = HighPriorityNotification::findOrFail($id);

        return view('pages.high_priority_notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HighPriorityNotification  $highPriorityNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_at' => 'required',
            'end_at' => 'required',
            'content' => 'required',
        ])->validate();

        if($request->input('status') == 'on'){
            HighPriorityNotification::query()->update(['status' => 0]);
        }

        $notification = HighPriorityNotification::findOrFail($id);
        $notification->start_at = $request->input('start_at');
        $notification->end_at = $request->input('end_at');
        $notification->content = $request->input('content');
        $notification->status = $request->input('status') == 'on';
        $notification->save();

        return redirect(route('high-priority-notifications.index'))->with('success', 'Notification successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HighPriorityNotification  $highPriorityNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = HighPriorityNotification::findOrFail($id);
        $notification->delete();
        return response()->json([
            'message' => 'Successfully deleted',
            'status' =>200
        ], 200);
    }
}
