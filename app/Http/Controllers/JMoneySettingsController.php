<?php

namespace App\Http\Controllers;

use App\Models\JMoneySetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;

class JMoneySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $settings = JMoneySetting::first();
        return view('pages.j_money_settings.create',compact('settings'));
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
            'sign_up_point' => 'required|numeric',
            'sign_up_point_expiry' => 'required|integer',
            'first_purchase_point' => 'required|numeric',
            'first_purchase_point_expiry' => 'required|integer',
            'promotional_activity_point' => 'required|numeric',
            'promotional_activity_point_expiry' => 'required|integer',
            'referral_activity_point' => 'required|numeric',
            'referral_activity_point_expiry' => 'required|integer',
            'refund_expiry' => 'required|integer',
        ]);

        if(!JMoneySetting::first()){
            $store = new JMoneySetting();
            $store->sign_up_point = $request->input('sign_up_point');
            $store->sign_up_point_expiry = $request->input('sign_up_point_expiry');
            $store->first_purchase_point = $request->input('first_purchase_point');
            $store->first_purchase_point_expiry = $request->input('first_purchase_point_expiry');
            $store->promotional_activity_point = $request->input('promotional_activity_point');
            $store->promotional_activity_point_expiry = $request->input('promotional_activity_point_expiry');
            $store->referral_activity_point = $request->input('referral_activity_point');
            $store->referral_activity_point_expiry = $request->input('referral_activity_point_expiry');
            $store->refund_expiry = $request->input('refund_expiry');
            $store->max_jkoin= $request->input('max_jkoin');
            $store->save();
        }
        else{
            $update = JMoneySetting::findOrFail(1);
            $update->sign_up_point = $request->input('sign_up_point');
            $update->sign_up_point_expiry = $request->input('sign_up_point_expiry');
            $update->first_purchase_point = $request->input('first_purchase_point');
            $update->first_purchase_point_expiry = $request->input('first_purchase_point_expiry');
            $update->promotional_activity_point = $request->input('promotional_activity_point');
            $update->promotional_activity_point_expiry = $request->input('promotional_activity_point_expiry');
            $update->referral_activity_point = $request->input('referral_activity_point');
            $update->referral_activity_point_expiry = $request->input('referral_activity_point_expiry');
            $update->refund_expiry = $request->input('refund_expiry');
            $update->max_jkoin= $request->input('max_jkoin');
            $update->update();
        }

        return redirect()->back()->with('success', 'Settings successfully updated');


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
