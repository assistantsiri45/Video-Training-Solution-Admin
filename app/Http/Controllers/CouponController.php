<?php

namespace App\Http\Controllers;
use App\Mail\StudentCouponMail;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Order;
use App\Models\Student;
use App\Models\PrivateCoupon;
use App\Models\User;
use App\Models\Level;
use App\Models\LevelType;
use App\Models\Subject;
use App\Models\Professor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{

    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Coupon::query();

            return DataTables::of($query)
                ->editColumn('amount', function($query) {
                    if ($query->amount_type == Coupon::FLAT) {
                        return 'FLAT Rs '.$query->amount.' /- OFF';
                    }

                    if ($query->amount_type == Coupon::PERCENTAGE) {
                        return $query->amount.' % OFF';
                    }

                    if ($query->amount_type == Coupon::FIXED_PRICE) {
                        return 'Rs. ' . $query->amount . ' (Fixed Price)';
                    }
                })
                ->editColumn('coupon_type', function($query) {
                    if($query->coupon_type=='2')
                        return 'Private';
                    else return 'Public';
                })
                ->editColumn('validity', function($query) {
                    if( Carbon::now() <= $query->valid_to )
                    return $query->valid_from.' - '.$query->valid_to;
                    else return $query->valid_from.' - '.$query->valid_to.'  <span class="badge badge-danger"> Expired</span>';
                })
                ->editColumn('status', function($query) {
                    if($query->status == 1 )
                        return '<span class="badge badge-info">Draft</span>';
                    elseif($query->status == 2 )
                        return '<span class="badge badge-success">Published</span>';
                    else return '<span class="badge badge-warning">Unpublished</span>';
                })
                ->addColumn('action', 'pages.coupons.action')
                ->rawColumns(['amount','validity','status','action'])
                ->make(true);

        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Coupon'],
            ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
            ['data' => 'coupon_type', 'name' => 'coupon_type', 'title' => 'Type','searchable' => true,],
            ['data' => 'validity', 'name' => 'valid_to', 'title' => 'Validity'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '110px']
        ]);
        return view('pages.coupons.index', compact('html'));
    }


    public function create(Builder $builder)
    {
        $courses = Course::orderBy('name')->get();
        $professors = Professor::where('is_published',1)->orderBy('name')->get();

        if (request()->ajax()) {
        //    $query = Student::with('course','level')->whereHas('level');
        $query = Student::with('course','level')->whereHas('level');

            return DataTables::of($query)

                ->filter(function($query) {
//                    if(request()->has('filter.search')) {
//                        $query->whereHas('orders', function($q){
//                            $q->where('payment_status','=', request('filter.purchase_status'))  ;
//                        } );
//                    }
                    if(request()->filled('filter.course')){
                        $query->where('course_id', request()->input('filter.course'));
                    }

                    if(request()->filled('filter.level')){
                        $query->where('level_id', request()->input('filter.level'));
                    }

                    if (!empty(request('filter.search'))) {
                        $query->where('name','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('email','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('phone','LIKE', '%'.request('filter.search').'%');
                    }
                })
                ->editColumn('course.name', function($query) {
                    if($query->course){
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('level.name', function($query) {
                    if($query->level){
                        return $query->level->name;
                    }
                    return '-';
                })
                ->rawColumns(['payment_status','checkbox'])
                ->make(true);
        }

        $html = $builder->columns([ ['data' => 'id', 'name' => 'id', 'title' => '<input class="select-all" name="select_all" type="checkbox">','render' => ' renderCheckbox(data, type, full, meta)', 'searchable' => false, 'orderable' => false, 'width' => '50px' ],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Signup date'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name','title' => 'Level']
        ]);
        return view('pages.coupons.create', compact('html','courses','professors'));
    }


    public function store(Request $request)
    {
       $this->validate($request,[
            'name' => 'required|alpha_num|max:8|unique:coupons',
            'amount' => 'required|numeric|min:1',
            'amount_type' => 'required|numeric',
//            'coupon_per_user' => 'required|numeric',
            'validity' => 'required',
//            'min_purchase_amount' => 'nullable|numeric',
//            'max_purchase_amount' => 'nullable|numeric',
//            'max_discount_amount' => 'required|numeric',
        ]);
       
        DB::beginTransaction();

        $coupon= new Coupon();
        $coupon->name = $request->input('name');
        $coupon->coupon_type = $request->filled('coupon_type') ? Coupon::PRIVATE :Coupon::PUBLIC;
        $coupon->amount = $request->input('amount');
        $coupon->amount_type = $request->input('amount_type');
        $coupon->coupon_per_user = $request->input('coupon_per_user');
        $coupon->total_coupon_limit = $request->input('total_coupon_limit');
        $dates = explode(' - ' ,$request->validity);
        $coupon->valid_from = date('Y-m-d', strtotime($dates[0]));
        $coupon->valid_to = date('Y-m-d', strtotime($dates[1]));
        $coupon->max_discount_amount = $request->input('max_discount_amount');
        if($request->input('min_purchase_amount')){ 
            $coupon->min_purchase_amount = $request->input('min_purchase_amount');
            }else{
                $coupon->min_purchase_amount =0;
            }
        $coupon->save();

        $emails  = array();
        if($request->filled('coupon_type')){

            $course_id = $request->course_id ?? null  ;
            $level_id = $request->level_id ?? null ;
            $subject_id = $request ->subject_id ?? null ;
            $professor_id = $request->professor_id ?? null ;
           
          


            if(!empty($request->id)){
            foreach($request->id as $student_id){
                $student = Student::select('course_id','level_id')->where('id',$student_id)->first();
                $private_coupon =  new PrivateCoupon();
                $private_coupon->student_id = $student_id;
                $private_coupon->coupon_id = $coupon->id;
                $private_coupon->course_id = $course_id;
                $private_coupon->level_id = $level_id;
                $private_coupon->package_type_id =$request->package_type ?? null;
                $private_coupon->subject_id =$subject_id;
                $private_coupon->professor_id = $professor_id;
                $private_coupon->save();
                $emails[] = Student::find($student_id)->email;
            }
            if(count($emails)>1){
                $to_email = $emails[0];
                unset($emails[0]);
            }else{
                $to_email = $emails[0];
            }

            $parameters = [
                'to_email' => $to_email,
                'recipients_mail' => $emails,
                'coupon_details' => $coupon
            ];

            try{
                Mail::send(new StudentCouponMail($parameters));
            }
            catch (\Exception $exception) {
                info($exception->getMessage(), ['exception' => $exception]);
            }
            }else{
                if(!empty($course_id)){
                    $private_coupon =  new PrivateCoupon();
                    $private_coupon->coupon_id = $coupon->id;
                    $private_coupon->course_id = $course_id;
                    $private_coupon->level_id = $level_id;
                    $private_coupon->package_type_id =$request->package_type ?? null;
                    $private_coupon->subject_id = $subject_id;
                    $private_coupon->professor_id = $professor_id;
                    $private_coupon->student_id = 0;
                    $private_coupon->save();
                }else{

                    if(!empty($professor_id)){
    
                        $private_coupon =  new PrivateCoupon();
                        $private_coupon->coupon_id = $coupon->id;
                        $private_coupon->professor_id = $professor_id;
                        $private_coupon->student_id = 0;
                        $private_coupon->save();
                    }
                }
            }

         }


        DB::commit();
    
//        return redirect()->back()->with('success', 'Coupon successfully created');
        return redirect(route('coupons.index'))->with('success', 'Coupon successfully created');
    }

    public function show(Builder $builder,$id)
    {
        $coupon = Coupon::findOrFail($id);

        $order = Order::with('student','coupon')->where('coupon_id',$id);
        $order_count = $order->count();

        if (request()->ajax()) {
            $query = $order->get();

            return DataTables::of($query)
                ->addColumn('coupon_amount', function($query) {
                    return 'Rs '.$query->coupon_amount. '/-';
                })
                ->addColumn('net_amount', function($query) {
                    return 'Rs '.$query->net_amount. '/-';
                })
                ->addColumn('payment_status', function($query) {
                    if($query->payment_status ==1 )
                        return '<span class="badge badge-success">Success</span>';
                    else if($query->payment_status ==2 ) return '<span class="badge badge-danger">Failed</span>';
                    else return '<span class="badge badge-warning">Returned</span>';
                })
                ->rawColumns(['payment_status'])
                ->make(true);
        }

         $html = $builder->columns([
            ['data' => 'student.name', 'name' => 'student.name', 'title' => 'Name'],
            ['data' => 'id', 'name' => 'id', 'title' => 'Order Id'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'coupon_amount', 'name' => 'coupon_amount', 'title' => 'Coupon Amount'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
        ]);

        return view('pages.coupons.show', compact('coupon','html','order_count'));
    }

    public function edit(Builder $builder, $id)
    {
        $coupon = Coupon::findOrFail($id);
        $private_coupon = PrivateCoupon::with('course','subject','level','package_type')->where('coupon_id',$id)->first();
        
        $courses = Course::orderBy('name')->get();
        $professors = Professor::where('is_published',1)->orderBy('name')->get();
        $types='';
        if($coupon->coupon_type==2){
        $types = LevelType::with(['packagetype'=> function($types){
            $types->where('is_enabled', TRUE);
        }])
        ->where('level_id',$private_coupon->level_id)
        ->get();
        }
        if (request()->ajax()) {
        //    $query = Student::with('course','orders','level','coupons')
        //                     ->withCount(['coupons'])
        //                     ->latest();
        $query = Student::with('course','orders','level','coupons')->withCount(['coupons']);


            return DataTables::of($query)

                ->addColumn('flag', function($student) use($id){
                    if($student->coupons_count) {
                        foreach ($student->coupons as $coupon) {
                            if ($coupon->id == $id) {
                                return true;
                            }
                        }
                    }
                    else{
                            return false;
                        }

                })
                ->filter(function($query) {
                   if(request()->has('filter.course') ) {
                        $query->whereHas('course', function($q){
                            $q->where('course_id','=', request('filter.course'))  ;
                        } );
                    }
                    if(request()->has('filter.level') ) {
                        $query->whereHas('level', function($q){
                            $q->where('level_id','=', request('filter.level'))  ;
                        } );
                    }
                    if(!empty(request('filter.purchase_status')) AND request('filter.purchase_status') != 'all') {
                        dd(request('filter.purchase_status'));
                        $query->whereHas('orders', function($q){
                            $q->where('payment_status','=', request('filter.purchase_status'))  ;
                        } );
                    }
                    
                    if(!empty(request('filter.signup_date_range'))) {
                        $date_range = explode(' - ' ,request('filter.signup_date_range'));
                        $query->whereBetween('created_at',[$date_range]);
                    }

                    if (!empty(request('filter.search'))) {
                        $query->where('name','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('email','LIKE', '%'.request('filter.search').'%');
                    }
                    if (!empty(request('filter.search'))) {
                        $query->Orwhere('phone','LIKE', '%'.request('filter.search').'%');
                    }
                })
                ->editColumn('course.name', function($query) {
                    if($query->course){
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('level.name', function($query) {
                    if($query->level){
                        return $query->level->name;
                    }
                    return '-';
                })
                ->rawColumns(['payment_status','checkbox'])
                ->make(true);
        }

        $html = $builder->columns([['data' => 'id','name' => 'id', 'title' => '<input class="select-all" name="select_all" type="checkbox">','render' => ' renderCheckbox(data, type, full, meta)', 'searchable' => false, 'orderable' => false, 'width' => '50px' ],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
//            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Purchase Status'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Signup date'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name','title' => 'Level']
        ]);

        return view('pages.coupons.edit', compact('coupon','html','private_coupon','courses','professors','types'));
    }

    public function updateStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        $coupon->save();

        return redirect()->back()->with('success', 'Coupon status updated');
    }

    public function update(Request $request, $id)
    {
    
        $this->validate($request,[
            'name' => 'required|alpha_num|max:8|',
            'amount' => 'required|numeric|min:1',
            'amount_type' => 'required',
//            'coupon_per_user' => 'required|numeric',
            'validity' => 'required',
//            'min_purchase_amount' => 'nullable|numeric',
//            'max_purchase_amount' => 'nullable|numeric',
//            'max_discount_amount' => 'required|numeric',
        ]);


        DB::beginTransaction();

        $coupon= Coupon::findOrFail($id);

        if($request->status!=2){
            $coupon->name = $request->input('name');
            $coupon->amount = $request->input('amount');
        }
        $coupon->amount_type = $request->input('amount_type');
        if($coupon->coupon_type==Coupon::PRIVATE&&$request->coupon_type==null){
            $students_with_coupon = PrivateCoupon::where('coupon_id',$id)->get();
            foreach($students_with_coupon as $student_with_coupon){
                $student_with_coupon->delete();
            }
        }
        $coupon->coupon_type = $request->filled('coupon_type') ? Coupon::PRIVATE :Coupon::PUBLIC;
        $coupon->coupon_per_user = $request->input('coupon_per_user');
        $coupon->total_coupon_limit = $request->input('total_coupon_limit');
        $dates = explode(' - ' ,$request->validity);
        $coupon->valid_from = date('Y-m-d', strtotime($dates[0]));
        $coupon->valid_to = date('Y-m-d', strtotime($dates[1]));
        $coupon->max_discount_amount = $request->input('max_discount_amount');
        if($request->input('min_purchase_amount')){ 
            $coupon->min_purchase_amount = $request->input('min_purchase_amount');
            }else{
                $coupon->min_purchase_amount =0;
            }
        $coupon->save();
        if($request->filled('coupon_type')&&$request->filled('selected_students')) {
            foreach ($request->selected_students as $selected_student) {
                $update_students = PrivateCoupon::updateOrCreate(['student_id' => $selected_student, 'coupon_id' => $id]);
                $update_students->save();
            }
            $selected_students[] = $request->selected_students;
            $emails = Student::whereIn('id',$selected_students[0])->pluck('email');

            if($emails){
                if(count($emails)>1){
                    $to_email = $emails[0];
                    unset($emails[0]);
                }else{
                    $to_email = $emails[0];
                }
                $parameters = [
                    'to_email' => $to_email,
                    'recipients_mail' => $emails,
                    'coupon_details' => $coupon
                ];

                try{
                    Mail::send(new StudentCouponMail($parameters));
                }
                catch (\Exception $exception) {
                    info($exception->getMessage(), ['exception' => $exception]);
                }

            }

        }
        if($request->filled('coupon_type')&&$request->filled('removed_students')) {
            foreach ($request->removed_students as $removed_students) {
                // $update_students = PrivateCoupon::updateOrCreate(['student_id' => $selected_student, 'coupon_id' => $id]);
                // $update_students->save();
                $sid=0;
                PrivateCoupon::where('student_id', $removed_students)->where('coupon_id',$id)
       ->update([
           'student_id' => '0'
        ]);
              

                // $pvt=PrivateCoupon::where("student_id",$removed_students)->where('coupon_id',$id);
                // $pvt->delete();
            }
        }

        if($request->filled('coupon_type')) {
            //dd($request->all());
            $course_id = $request->course_id ?? null;
            $level_id = $request->level_id ?? null ;
            $subject_id = $request ->subject_id ?? null ;
            $professor_id = $request->professor_id ?? null ;
            $package_type = $request->package_type ?? null ;

            $update_private = PrivateCoupon::where('coupon_id',$id)->get();
            foreach($update_private as $update_pri){
                $private_coupon = PrivateCoupon::find($update_pri->id);
                $private_coupon->course_id = $course_id;
                $private_coupon->coupon_id = $coupon->id;
                $private_coupon->level_id = $level_id;
                $private_coupon->subject_id = $subject_id;
                $private_coupon->package_type_id = $package_type;
                $private_coupon->student_id = $update_pri->student_id;
                $private_coupon->professor_id = $professor_id;
                $private_coupon->save();
            }
            // if(!empty($update_private)){
            //     $update_private->course_id = $course_id;
            //     $update_private->level_id = $level_id;
            //     $update_private->subject_id = $subject_id;
            //     $update_private->package_type_id = $package_type;
            //     $update_private->professor_id = $professor_id;
            //     $update_private->save();
            // }
            // else{
            //     $private_coupon =  new PrivateCoupon();
            //     $private_coupon->coupon_id = $coupon->id;
            //     $private_coupon->course_id = $course_id;
            //     $private_coupon->level_id = $level_id;
            //     $private_coupon->subject_id = $subject_id;
            //     $private_coupon->package_type_id = $package_type;
            //     $private_coupon->professor_id = $professor_id;
            //     $private_coupon->student_id = 0;
            //     $private_coupon->save();
            // }
        }
        DB::commit();

//        return redirect()->back()->with('success', 'Coupon successfully updated');
        return redirect(route('coupons.index'))->with('success', 'coupon updated successfully');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return response()->json(true, 200);
    }
}
