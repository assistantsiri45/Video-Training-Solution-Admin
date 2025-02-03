<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Course::query()->orderBy('order');
            return DataTables::of($query)
                ->addColumn('action', 'pages.courses.action')
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="course-id" value="' . $query->id . '"></div>';
                })
                ->rawColumns(['action', 'order'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'width' => '50%'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '50%']
        ]);

        return view('pages.courses.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request;
        $this->validate($request,[
            'name' => 'required',
        ]);

        $course = new Course();

        $course->name = $request->input('name');
        $course->display = $request->input('display');
        $course->order = Course::query()->count() + 1;
        $course->save();

        return redirect(route('courses.index'))->with('success', 'Course successfully created');;
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
        $course = Course::findOrFail($id);

        return view('pages.courses.edit', compact('course'));
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
            'name' => 'required'
        ]);

        $course = Course::findOrFail($id);

        $course->name = $request->input('name');
        $course->display = $request->input('display');

        $course->save();

        return redirect(route('courses.index'))->with('success', 'Course successfully updated');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);

          // $course->delete();

        // return response()->json(true, 200);

        /***************TE Modified***********/
        if($course->is_enabled==true){                
            $course->is_enabled= false;
    }
    else{
        $course->is_enabled=true;
    }        
    $course->save();
        return response()->json(true, 200);
    /***************TE ends*************/
    }

    public function changeOrder()
    {
        $courseIDs = request()->input('courses');

        if ($courseIDs) {
            $index = 1;

            foreach ($courseIDs as $courseID) {
                $course = Course::find($courseID);

                if ($course) {
                    $course->order = $index;
                    $course->save();
                }

                $index++;
            }
        }
    }
}
