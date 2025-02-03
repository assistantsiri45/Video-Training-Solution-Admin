<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HolidayOffer;
use App\Models\Course;
use App\Models\Level;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;


class HolidaySchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = HolidayOffer::query()->orderBy('id');
            return DataTables::of($query)
            ->editColumn('discount_type', function($query) {
                if($query->discount_type=='1')
                    return 'FLAT';
                else return 'PERCENTAGE';
            })
            ->editColumn('cashback_type', function($query) {
                if($query->cashback_type=='1')
                    return 'FLAT';
                else return 'PERCENTAGE';
            })
            ->editColumn('discount_amount', function($query) {
                if($query->discount_type==1)
                    return $query->discount_amount;
                else  return $query->discount_amount.'%';
            })
            ->editColumn('cashback_amount', function($query) {
                if($query->cashback_type==2)
                return $query->cashback_amount.'%';
                else  return $query->cashback_amount;
            })
                ->addColumn('action', 'pages.holidayscheme.action')
               
                ->rawColumns(['action','discount_amount'])
                ->make(true);
        }

        $html = $builder->columns([
          
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
           
            ['data' => 'discount_type', 'name' => 'discount_type', 'title' => 'Discount Type'],
            ['data' => 'discount_amount', 'name' => 'discount_amount', 'title' => 'Discount'],
            ['data' => 'cashback_type', 'name' => 'cashback_type', 'title' => 'Cashback Type'],
            ['data' => 'cashback_amount', 'name' => 'cashback_amount', 'title' => 'Cashback','width'=>'20%'],
            ['data' => 'max_cashback', 'name' => 'max_cashback', 'title' => 'Maximum Cashback Amount'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]
        ]);

        return view('pages.holidayscheme.index', compact('html'));
        //return view('pages.holidayscheme.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::where('is_enabled',true)->get();
        return view('pages.holidayscheme.create',compact('courses'));
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
        $this->validate($request,[
            'name' => 'required|unique:coupons',
           // 'dis_amount' => 'required|numeric|min:1',
           // 'dis_amount_type' => 'required|numeric',
//            'coupon_per_user' => 'required|numeric',
            'validity' => 'required',
//            'min_purchase_amount' => 'nullable|numeric',
//            'max_purchase_amount' => 'nullable|numeric',
//            'max_discount_amount' => 'required|numeric',
        ]);
        //dd($inputs);
        DB::beginTransaction();
        $coupon= new HolidayOffer();
        $coupon->name = $request->input('name');
        $coupon->discount_type = $request->input('dis_amount_type');
        $coupon->discount_amount = $request->input('dis_amount');
        $coupon->cashback_type = $request->input('cashback_amount_type');
        $coupon->cashback_amount = $request->input('cb_amount');
        $coupon->max_cashback = $request->input('max_cashback');
        $coupon->min_cart_amount=$request->input('min_cart_value') ?? 0;
        
        $coupon->courses =  $request->input('course_id')??null; 
        if($request->input('level_id')){
            $applicable_level=implode(',',$request->input('level_id'));
            $coupon->level_id=$applicable_level;
        } 
        if($request->input('package_type')){
            $applicable_type=implode(',',$request->input('package_type'));
            $coupon->package_type=$applicable_type;
        } 
        $dates = explode(' - ' ,$request->validity);
        $coupon->from_date = date('Y-m-d H:i:s', strtotime($dates[0]));
        $coupon->to_date = date('Y-m-d H:i:s', strtotime($dates[1]));
        $coupon->save();

        DB::commit();

        return redirect('holiday-scheme')->with('success', 'Holiday Scheme successfully created');
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
        $holidayscheme = HolidayOffer::findOrFail($id);
        $courses = Course::where('is_enabled',true)->get();
        if($holidayscheme->courses){
                                       
        $sel_course = explode(',' ,$holidayscheme->courses);
        
        } else{
        $sel_course = [];
        }

        if($holidayscheme->level_id){
                                       
            $sel_levels = explode(',' ,$holidayscheme->level_id);
            
            }
            else{
                $sel_levels= [];
                }
                if($holidayscheme->package_type){
                                       
                    $sel_type = explode(',' ,$holidayscheme->package_type);
                    
                    }
                    else{
                        $sel_type= [];
                        }
           
        $level=Level::where('course_id',$holidayscheme->courses)->where('is_enabled',true)->get();
        
          
        return view('pages.holidayscheme.edit', compact('holidayscheme','courses','sel_course','level','sel_levels','sel_type'));
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
        $inputs = $request->input();
        $this->validate($request,[
            'name' => 'required|unique:coupons',
           // 'dis_amount' => 'required|numeric|min:1',
           // 'dis_amount_type' => 'required|numeric',
//            'coupon_per_user' => 'required|numeric',
            'validity' => 'required',
//            'min_purchase_amount' => 'nullable|numeric',
//            'max_purchase_amount' => 'nullable|numeric',
//            'max_discount_amount' => 'required|numeric',
        ]);
       
        DB::beginTransaction();
        $coupon= HolidayOffer::findOrFail($id);
      //  $coupon= new HolidayOffer();
        $coupon->name = $request->input('name');
        $coupon->discount_type = $request->input('dis_amount_type');
        $coupon->discount_amount = $request->input('dis_amount');
        $coupon->cashback_type = $request->input('cashback_amount_type');
        $coupon->cashback_amount = $request->input('cb_amount');
        $coupon->max_cashback = $request->input('max_cashback');
        $coupon->min_cart_amount=$request->input('min_cart_value')??0;
        // $applicable_course='';
        // if($request->input('course')){            
        //     $applicable_course=implode(',',$request->input('course'));
        // }
        $coupon->courses =  $request->input('course')??null; 
        if($request->input('level_id')){
            $applicable_level=implode(',',$request->input('level_id'));
            $coupon->level_id=$applicable_level;
        } 
        if($request->input('package_type')){
            $applicable_type=implode(',',$request->input('package_type'));
            $coupon->package_type=$applicable_type;
        } 
        $dates = explode(' - ' ,$request->validity);
        $coupon->from_date = date('Y-m-d H:i:s', strtotime($dates[0]));
        $coupon->to_date = date('Y-m-d H:i:s', strtotime($dates[1]));
        
        $coupon->save();

        DB::commit();
        return redirect('holiday-scheme')->with('success', 'Holiday scheme successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $holidayscheme = HolidayOffer::findOrFail($id);

     $holidayscheme->delete();

         return response()->json(true, 200);
    
    }
    public function publishOffer(Request $request){
        $holidayscheme = HolidayOffer::findOrFail($request->id);
        if($holidayscheme->is_published==true){                
            $holidayscheme->is_published= false;
    }
    else{
        $holidayscheme->is_published=true;
    }        
    $holidayscheme->save();
        return response()->json(true, 200); 
    }


    public function usage_report(Builder $builder){

        if (request()->ajax()) {
            $query = Order::with('student','orderItems.package','holidayoffer')->where('holiday_offer_id','!=',null)->where('holiday_offer_amount','>',0)
            ->orWhere('holiday_cashback_point','>',0);
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->wherehas('student', function($query) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    })
                        ->orWhere(function ($query) {
                            $query->wherehas('orderItems.package', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                            $query->wherehas('holidayoffer', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });

                });
            }
            if (request()->filled('filter.date')) {
                $dateRange = request()->input('filter.date');
                $explodedDates = explode(' - ', $dateRange);
                $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);
                $from= date("Y-m-d",strtotime($fromDate)).''.' 00:00:00';
                $to= date("Y-m-d",strtotime($toDate)).''.' 23:59:59';    
                $query->whereBetween('created_at', [$from, $to]);
                }
            if (request()->filled('filter.amount')) {
                 $query->where('holiday_offer_amount','=', request('filter.amount'));               
            }
            return DataTables::of($query)
            ->addColumn('packagename', function($query) {
                $query2=  OrderItem::with('package')->where('order_id',$query->id)->get();
                    $val3='';
                    $i=1;
                    foreach($query2 as $val2){
                       
                        if(!empty($val2->package->name)){
                            $val3.=$i.') ';
                            $val3.=$val2->package->name .' <br> ';
                        }else{
                            $val3.='- <br>';
                        }
                            ++$i;
                        }
                        return $val3;
    
            })
            ->addColumn('holiday_cashback_point', function($query) {
                if($query->holiday_cashback_point){
                    return $query->holiday_cashback_point;
                }else{
                    return '-';
                }
                
            })  
            ->addColumn('holiday_offer_amount', function($query) {
                if($query->holiday_offer_amount){
                    return $query->holiday_offer_amount;
                }else{
                    return '-';
                }
                
            })  
            
            ->addColumn('created_at', function($query) {
                return $query->created_at->toDayDateTimeString();
            })   
            ->rawColumns(['packagename'])
            ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'orders.id', 'title' => 'Order ID'],
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Name','defaultContent'=>''],
            ['data' => 'packagename', 'name' => 'packagename', 'title' => 'Package','defaultContent'=>''],
            ['data' => 'holiday_offer_id', 'name' => 'orders.holiday_offer_id', 'title' => 'Holiday Scheme ID','defaultContent'=>''],
            ['data' => 'holidayoffer.name', 'name' => 'holidayoffer.name', 'title' => 'Holiday Scheme Name','defaultContent'=>''],
            ['data' => 'holiday_offer_amount', 'name' => 'holiday_offer_amount', 'title' => 'Holiday Scheme Amount','defaultContent'=>''],
            ['data' => 'holiday_cashback_point', 'name' => 'holiday_cashback_point', 'title' => 'Holiday Cash Back','defaultContent'=>''],
            ['data' => 'created_at', 'name' => 'orders.created_at', 'title' => 'Purchase Date'],

        ])->parameters([
            'searching' => false,
            'stateSave'=>true,
            'ordering' => true,
        ]);

        return view('pages.holidayscheme.usage_report', compact('html'));
    }
}
