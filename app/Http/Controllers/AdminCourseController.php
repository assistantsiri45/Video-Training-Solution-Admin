<?php

namespace App\Http\Controllers;

use App\AdminCourse;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
   {
    $courses = AdminCourse::all();
    return view('courses.index', compact('courses'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            return view('courses.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'training_link' => 'nullable|url',
        'access_password' => 'nullable|string|max:255',
    ]);

    AdminCourse::create([
        'title' => $request->title,
        'description' => $request->description,
        'training_link' => $request->training_link,
        'access_password' => $request->access_password,
        // 'created_by' => auth()->id(),
    ]);

    return redirect()->route('courses.index')->with('success', 'Course created successfully.');
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
          $course = AdminCourse::findOrFail($id);
        return view('courses.edit', compact('course'));
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
         $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $course = AdminCourse::findOrFail($id);
        $course->title = $request->title;
        $course->description = $request->description;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = AdminCourse::findOrFail($id);
        $course->delete();
       return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
    }
}
