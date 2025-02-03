<?php

namespace App\Http\Controllers;

use App\Exports\CallRequestExport;
use App\Models\CallRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Log;

class CallRequestController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return Response
     * @throws Exception
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
             $query = CallRequest::query();

            return DataTables::of($query)
                ->filter(function($query) {


                    if (!empty(request('filter.search'))) {
                        $query->Where('phone','LIKE', '%'.request('filter.search').'%');
                    }

                    if (!empty(request('filter.date'))) {
                        $query->whereDate('created_at', Carbon::parse(request('filter.date')));
                    }
                    if (!empty(request('filter.status'))) {
                        $query->where('status',request('filter.status'));
                    }
//
//                    if (!empty(request('filter.course'))) {
//                        $query->whereHas('orderItems.package', function($query) {
//                            $query->where('course_id', request('filter.course'));
//                        });
//                    }
//
//                    if (!empty(request('filter.package'))) {
//                        $query->whereHas('orderItems', function($query) {
//                            $query->where('package_id', request('filter.package'));
//                        });
//                    }
//
//                    if (!empty(request('filter.location'))) {
//                        $query->whereHas('student', function($query) {
//                            $query->where('city', request('filter.location'));
//                        });
//                    }
//
//                    if (!empty(request('filter.order_id'))) {
//                        $query->where('id', request('filter.order_id'));
//                    }
//
//                    if (!empty(request('filter.amount'))) {
//                        $query->where('net_amount', '>=', request('filter.amount'));
//                    }
//
//                    if (!empty(request('filter.repeat'))) {
//                        $userIDs = Order::select('user_id')->groupBy('user_id')->havingRaw('COUNT(*) = ' . request('filter.repeat'))->pluck('user_id');
//
//                        $query->whereIn('user_id', $userIDs);
//                    }
                })
                ->addColumn('status', function($query) {
                    if ($query->status==1) {
                        return '<span class="badge badge-info">new</span>';
                    }

                    return '<span class="badge badge-success">updated</span>';
                })
                ->editColumn('created_at', function($query) {
                    if ($query->created_at) {
                        return $query->created_at;
                    }
                })
                ->editColumn('updated_at', function($query) {
                    if ($query->updated_at) {
                        return $query->updated_at;
                    }
                })
                ->addColumn('action', 'pages.call_requests.action')
                ->rawColumns(['action','status'])
                ->make(true);

        }

        $html = $builder->columns([
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone','orderable' => true],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'created_at','name' => 'created_at', 'title' => 'Created At','orderable' => true],
            ['data' => 'updated_at','name' => 'updated_at', 'title' => 'Updated At','orderable' => true],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '110px']
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'lengthChange' => false,
            'bInfo' => false
        ])->orderBy(2,'desc');

        return view('pages.call_requests.index', compact('html'));
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
     * @param  \App\Models\CallRequest  $callRequest
     * @return \Illuminate\Http\Response
     */
    public function show(CallRequest $callRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallRequest  $callRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(CallRequest $callRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallRequest  $callRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'description' => 'required|regex:/^([^<>]*)$/',
        ]);

        $callRequest  = CallRequest::findOrFail($id);
        $callRequest->content = $request->description;
        $callRequest->status = 2;
        $callRequest->update();
        return redirect()->back()->with('success', 'Call request successfully updated');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallRequest  $callRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallRequest $callRequest)
    {
        $callRequest->delete();

        return response()->json([
            'message' => 'Call request deleted',
            'data' => $callRequest
        ], 200);
    }

    public function export()
    {
        $status = request()->input('export_status') ?? '';
        $search = request()->input('export_search') ?? '';
        $createdAt = request()->input('export_created_at') ?? '';

        if ($createdAt) {
            $createdAt = Carbon::create($createdAt);
        } else {
            $createdAt = '';
        }



        return Excel::download(new CallRequestExport($status, $search, $createdAt), 'CALL_REQUESTS_' . time() . '.csv');
    }
}
