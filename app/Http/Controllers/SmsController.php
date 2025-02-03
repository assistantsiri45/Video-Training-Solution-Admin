<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Sms;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Sms::query()->orderBy('id');

            return DataTables::of($query)
                ->addColumn('action', 'pages.sms.action')
              
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'id'],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title', 'width' => '50%'],
            ['data' => 'template_id', 'name' => 'template_id', 'title' => 'Template Id'],
            ['data' => 'body', 'name' => 'body', 'title' => 'SMS Body', 'width' => '50%'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '50%']
        ]);

        return view('pages.sms.index', compact('html'));
     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.sms.create');
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
            'title' => 'required',
            'template_id' => 'required',
            'body' => 'required',
        ]);

        $sms = new Sms();

        $sms->title = $request->input('title');
        $sms->template_id = $request->input('template_id');
        $sms->body = $request->input('body');

        $sms->save();

        return redirect(route('sms.create'))->with('success', 'SMS successfully created');
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
        $sms = Sms::findOrFail($id);

        return view('pages.sms.edit', compact('sms'));
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
            'title' => 'required'
        ]);

        $sms = Sms::findOrFail($id);

        $sms->title = $request->input('title');
        $sms->template_id = $request->input('template_id');
        $sms->body=$request->input('body');

        $sms->save();

        return redirect(route('sms.index'))->with('success', 'Sms successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sms = Sms::findOrFail($id);

        $sms->delete();

        return response()->json(true, 200);
    }
}
