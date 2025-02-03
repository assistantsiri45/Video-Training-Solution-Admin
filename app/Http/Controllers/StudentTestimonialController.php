<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use App\Models\Student;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class StudentTestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        if (request()->ajax()) {
            $query = Testimonial::query();
            $query->orderBy('id', 'desc');
            

            return DataTables::of($query)
                
                ->editColumn('status', function($query) {
                    if($query->publish == Testimonial::UNPUBLISHED )
                        return '<span class="badge badge-info">Unpublished</span>';
                    else return '<span class="badge badge-success">Published</span>';
                })
                ->editColumn('first_name', function($query) {
                    if($query->first_name )
                        return $query->first_name;
                   
                })
                ->editColumn('last_name', function($query) {
                    if($query->last_name )
                        return $query->last_name;
                   
                })
                ->editColumn('email', function($query) {
                    if($query->email )
                        return $query->email;
                   
                })
                ->editColumn('phone', function($query) {
                    if($query->phone )
                        return $query->phone;
                   
                })
                ->editColumn('testimonial', function($query) {
                    if($query->testimonial )
                        return $query->testimonial;
                   
                })
                ->editColumn('action', 'pages.testimonials.students.action')
                ->rawColumns(['publish','status','action'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Last Name'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone No.'],
            ['data' => 'email', 'name' => 'email', 'title' => 'E-mail'],
            ['data' => 'testimonial', 'name' => 'testimonial', 'title' => 'Testimonial'],
            // ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status','searchable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '110px']
        ])->parameters([
            'stateSave'=> true,
            'searching' => true,
        ]);
        return view('pages.testimonials.students.index', compact('html'));
    }

    public function publishStudentTestimony(Request $request){

        $update_status = Testimonial::findOrFail($request->id);
        $update_status->publish = $request->status;
        $update_status->save();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        'student_id' => 'required',
        'professor_id' => 'required|',
        'testimony' => 'required|regex:/^[A-Za-z0-9. -]+$/',
        ]);

        $store = new Testimonial();
        $store->student_id = $request->student_id;
        $store->professor_id = $request->professor_id;
        $store->testimonial = $request->testimony;
        $store->save();

        return redirect()->back()->with('success', 'Student Testimony successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('pages.testimonials.students.edit', compact('testimonial'));
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
        // $this->validate($request,[
        //     'fname' => 'required',
        //     'lname' => 'required',
        //     'email' => 'required',
        //     'phone' => 'required',
        //     'testimonial' => 'required',

        //     //'testimony' => 'required|regex:/^[A-Za-z0-9. -]+$/',
        // ]);
        
       $update_status = Testimonial::find($id);
       $update_status->publish = $request->filled('is_published') ? Testimonial::PUBLISHED : Testimonial::UNPUBLISHED ;
       if($request->testimonial){
            $update_status->testimonial = $request->testimonial;
       }
       $update_status->save();

       return redirect()->route('student-testimonials.index')->with('success', 'Testimony successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $student_testimony = Testimonial::findOrFail($id);
       $student_testimony->delete();

       return response()->json(true, 200);
    }
}
