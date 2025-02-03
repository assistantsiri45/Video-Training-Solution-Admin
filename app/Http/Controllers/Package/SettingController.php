<?php


namespace App\Http\Controllers\Package;
use App\Http\Controllers\Controller;
use App\PackageSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class SettingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Builder $builder)
    {
        $professor_revenue = PackageSetting::first();

        return view('pages.packages.settings.create', compact('professor_revenue'));
    }

    public function store(Request $request){

        $this->validate($request,[
            'professor_revenue' => 'required|between:0,99.99',
        ]);

        $professor_revenue = PackageSetting::first();

        if(!$professor_revenue){
            $revenue = new PackageSetting();
            $revenue->professor_revenue =  $request->input('professor_revenue');
            $revenue->save();
        }
        else{
            $professor_revenue->professor_revenue = $request->input('professor_revenue');
            $professor_revenue->update();
        }

        return redirect()->back()->with('success', 'Settings successfully updated');
    }
}
