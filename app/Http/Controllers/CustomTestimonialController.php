<?php

namespace App\Http\Controllers;

use App\Models\CustomTestimonial;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class CustomTestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = CustomTestimonial::get();

            return DataTables::of($query)
                ->addColumn('status', function($query) {
                    if($query->publish == CustomTestimonial::UNPUBLISHED ){
                        return '<span class="badge badge-info">Unpublished</span>';
                    }
                    else return '<span class="badge badge-success">Published</span>';
                })
                ->addColumn('action', 'pages.testimonials.custom.action')
                ->rawColumns(['status','action'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '110px']
        ]);
        return view('pages.testimonials.custom.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.testimonials.custom.create');
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
            'name' => 'required|alpha_spaces',
            'testimonial' => 'required|regex:/^([^<>]*)$/',
            'image_file' => 'required|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

       $custom_testimony = new CustomTestimonial();
       $custom_testimony->name=$request->name;
       $custom_testimony->testimonial=$request->testimonial;
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = Carbon::now()->timestamp.'.'.$file->getClientOriginalExtension();
            $file->storeAs('custom_testimonials', $fileName, 'public');
            $custom_testimony->image = $fileName;
            $custom_testimony->save();
        }

        return redirect()->back()->with('success', 'Testimony successfully created');
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
        $testimonial = CustomTestimonial::find($id);
        return view('pages.testimonials.custom.edit', compact('testimonial'));
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
            'name' => 'required|alpha_spaces',
            'testimonial' => 'required|regex:/^([^<>]*)$/',
            'image_file' => 'mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        $custom_testimony = CustomTestimonial::findOrFail($id);
        $custom_testimony->name=$request->name;
        $custom_testimony->testimonial=$request->testimonial;
        $custom_testimony->publish=$request->filled('status') ? CustomTestimonial::PUBLISHED : CustomTestimonial::UNPUBLISHED ;
        if (! $request->filled('image')) {
            $custom_testimony->image = null;
        }
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = Carbon::now()->timestamp.'.'.$file->getClientOriginalExtension();
            $file->storeAs('custom_testimonials', $fileName, 'public');
            $custom_testimony->image = $fileName;
        }
        $custom_testimony->update();
        return redirect(route('custom-testimonials.index'))->with('success', 'Testimony successfully updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student_testimony = CustomTestimonial::findOrFail($id);
        $student_testimony->delete();

        return response()->json(true, 200);
    }
}
