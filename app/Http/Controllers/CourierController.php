<?php

namespace App\Http\Controllers;

use App\Models\Courier;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            // $couriers=Courier::select('id','name')->where('status',1)->get();
            // return $couriers;
            $query = Courier::query();

            return DataTables::of($query)
                ->addColumn('action', 'pages.couriers.action')
                ->addColumn('showstatus', function($query) {
                    if ($query->status == 1) {
                        return '<span class="badge bg-primary">Enable</span>';
                    }else{
                        return '<span class="badge bg-secondary">Disable</span>';
                    }

                   
                })
                ->rawColumns(['showstatus','action'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'width' => '20%'],
            ['data' => 'url', 'name' => 'url', 'title' => 'URL', 'width' => '50%'],
            ['data' => 'showstatus', 'name' => 'showstatus', 'title' => 'Status', 'width' => '20%'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '50%']
        ]);

        return view('pages.couriers.index', compact('html'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.couriers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required',
            'url' => 'required'
        ]);

        $courier = new Courier();

        $courier->name = $request->input('name');

        $courier->url = $request->input('url');

        $courier->save();

        return redirect(route('couriers.index'))->with('success', 'Courier successfully created');;
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
        $courier = Courier::findOrFail($id);

        return view('pages.couriers.edit', compact('courier'));
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
        $this->validate($request,[
            'name' => 'required',
            'url' => 'required',
            'status'=>'required'
        ]);

        $courier = Courier::findOrFail($id);

        $courier->name = $request->input('name');

        $courier->url = $request->input('url');

        $courier->status = $request->input('status');


        $courier->save();

        return redirect(route('couriers.index'))->with('success', 'Courier successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $courier = Courier::findOrFail($id);

        $courier->delete();

        return response()->json(true, 200);
    }
    public function getCouriers( Builder $builder){

        if (request()->ajax()) {
            // $couriers=Courier::select('id','name')->where('status',1)->get();
            // return $couriers;
            $query = Courier::query();

            return DataTables::of($query)
                ->addColumn('action', 'pages.couriers.action')
                ->addColumn('showstatus', function($query) {
                    if ($query->status == 1) {
                        return '<span class="badge bg-primary">Enable</span>';
                    }else{
                        return '<span class="badge bg-secondary">Disable</span>';
                    }

                   
                })
                ->rawColumns(['showstatus','action'])
                ->make(true);
        }

    }
}
