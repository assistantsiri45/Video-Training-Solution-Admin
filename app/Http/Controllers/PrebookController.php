<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrebookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

      $prebook = Package::find($request->id);
      $prebook->is_prebook = request()->input('is_prebook') == 'on';
      $prebook->prebook_launch_date =  Carbon::createFromFormat('m/d/Y', $request->input('launch_date'));
      $prebook->booking_amount = $request->input('booking_amount');
      $prebook->prebook_price = $request->input('prebook_price');
      $prebook->is_prebook_content_ready = $request->input('is_content_ready') == 'on';
      $prebook->prebook_content = $request->input('prebook_content');
      $prebook->prebook_lectures = $request->input('prebook_lectures');
      $prebook->prebook_total_duration = $request->input('prebook_total_duration');
      $prebook->update();

      return redirect("packages/$prebook->id")->with('success', 'Prebooking updated');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::find($id);

        return view('pages.packages.prebook.create',compact('id','package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
