<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class HomePageCountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        $file_data = file_get_contents(public_path('home_page_count.txt'));

        return view('pages.home_page_count.index', compact('file_data'));
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
        $inputs = $request->input();
        // unset($inputs['_token']);
        // $home_page_counts = HomePageCount::findOrFail(1);
        // $home_page_counts->courses_purchased = $request->input('courses_purchased');
        // $home_page_counts->students_enrolled = $request->input('students_enrolled');
        // $home_page_counts->uploaded_videos = $request->input('uploaded_videos');
        // $home_page_counts->listed_courses = $request->input('listed_courses');
        // $home_page_counts->save();
        $contents = $request->input('courses_purchased').'|'.$request->input('students_enrolled').'|'.$request->input('uploaded_videos').'|'.$request->input('listed_courses');
        file_put_contents(public_path('home_page_count.txt'), $contents);
        return back()->with('success', 'Counts saved');
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
