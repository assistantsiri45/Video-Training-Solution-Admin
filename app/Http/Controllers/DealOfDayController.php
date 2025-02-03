<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\DodPackageEditLog;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DealOfDayController extends Controller
{
    public function index( Builder $builder){
        if (request()->ajax()) {
            $query = Package::where('special_price','>','0')->where('special_price_expire_at','>=',Carbon::today())->latest();
             if (request()->filled('filter.search')) {
                $query->where(function($query) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    });

                }
                if (request()->filled('filter.date')) {
                    $dateRange = request()->input('filter.date');
                    $explodedDates = explode(' - ', $dateRange);
                    $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                    $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                    $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                    $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';
        
                    $query->where('special_price_active_from','>=',$from)->where('special_price_expire_at','>=',$to);
                    }
            return DataTables::of($query)
            ->editColumn('special_price_active_from', function($query) {
                if ($query->special_price_active_from) {
                    return date('d-m-Y',strtotime($query->special_price_active_from));
                }
            })
            ->editColumn('special_price_expire_at', function($query) {
                if ($query->special_price_expire_at) {
                    return date('d-m-Y',strtotime($query->special_price_expire_at));
                }
            })
            ->addColumn('action', 'pages.deal_of_day.action')
            ->rawColumns(['action'])
            ->make(true);

        }
        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'name', 'name' => 'name', 'title' => 'Package Name', 'orderable' => false],
            ['data' => 'special_price', 'name' => 'special_price', 'title' => 'Special Price', 'orderable' => false],
            ['data' => 'special_price_active_from', 'name' => 'special_price_active_from', 'title' => 'Special Price Active From', 'orderable' => true,],
            ['data' => 'special_price_expire_at', 'name' => 'special_price_expire_at', 'title' => 'Special Price Expiry At', 'orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']           
           
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 25,
        ]);

        return view('pages.deal_of_day.index', compact('table'));
   }
   public function getPackagedata($id){
        $package=Package::findorfail($id);
        if(!empty($package)){
            if($package->special_price_active_from){
                $active_from=date('Y-m-d',strtotime($package->special_price_active_from));
            }else{
                $active_from=date('Y-m-d');
            }

            $packageArr = array(
                'package_id' => $package->id,
                'package_name' => $package->name,
                'special_price' => $package->special_price,
                'special_price_active_from' => $active_from,
                'special_price_expire_at' => date('Y-m-d',strtotime($package->special_price_expire_at)));
            }
            else{
                $packageArr = array(
                    'package_id' => '',
                    'package_name' => '',
                    'special_price' => '',
                    'special_price_active_from' =>'',
                    'special_price_expire_at' => '');
            }
            return response()->json($packageArr);


   }
   public function update(Request $request){
            $package=Package::findorfail($request->pkgId);
            $package->special_price=$request->special_price;
            $package->special_price_active_from=$request->special_price_active_from;
            $package->special_price_expire_at=$request->special_price_expire_at;
            $package->save();

            if($package){
                $log=new DodPackageEditLog();
                $log->package_id=$request->pkgId;
                $log->user_id=Auth::user()->id;
                $log->ip=request()->ip();
                $log->server_var = base64_encode(serialize($_SERVER));
                $log->save();

            }
            return redirect()->back()->with('success', 'Package updated');      

   }
}
