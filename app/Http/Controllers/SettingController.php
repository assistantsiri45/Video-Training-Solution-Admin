<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Setting[] $settings */
        $settings = Setting::where('key' ,'!=','maharashtra_cgst')->get();

        return view('pages.settings.index', compact('settings'));
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
        unset($inputs['_token']);

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                if($key=='crone_to'||$key == 'special_bcc'||$key =='admin_email'||$key =='email_bcc'){
                    $emailsin=explode(',',$value);
                    $cleaned_email=[];
                    $cleanem='';
                    for($i=0;$i<count($emailsin);$i++){
                        $cleaned_email[$i]=trim($emailsin[$i]);
                    }
                    $cleanem =implode(',',$cleaned_email);
                    $setting->value = $cleanem;
                }
                else{
                $setting->value = $value;
                }
                $setting->save();
            }
        }

        return back()->with('success', 'Settings saved');
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
