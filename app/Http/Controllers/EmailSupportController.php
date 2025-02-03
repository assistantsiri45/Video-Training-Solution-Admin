<?php

namespace App\Http\Controllers;


use App\Models\EmailSupport;
use App\Models\User;
use App\Models\Student;
use App\Models\PackageExtension;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RemarkMail;
use App\Models\OrderItem;
use App\Mail\ExtensionMail;

class EmailSupportController extends Controller
{
    public function index(Builder $builder)
    {
       
        $pending = app(Builder::class)->columns([
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Last Name'],

            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone No.'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'query', 'name' => 'query', 'title' => 'Query'],
            ['data' => 'remark', 'name' => 'remark', 'title' => 'Remarks'],
            ['data' => 'extension', 'name' => 'extension', 'title' => 'Extension','searchable' => false, 'orderable' => false, 'width' => '80px'],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '80px'],
            
            
           

        ])->ajax(url('fetch-pending'))->setTableId('tbl-pending');

        $completed = app(Builder::class)->columns([
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'First Name'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Last Name'],
           
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone No.'],
            ['data' => 'email', 'name' => 'email', 'title' => 'E-mail'],
            ['data' => 'query', 'name' => 'query', 'title' => 'Query'],
            ['data' => 'extended_date','name' => 'extended_date', 'title' => 'Extended Date'],
            ['data' => 'remark', 'name' => 'remark', 'title' => 'Remarks'],
           

        ])->ajax(url('fetch-completed'))->setTableId('tbl-completed');

        return view('pages.email_support.index', compact('pending','completed'));
    }

   
   

    public function InProgress(Builder $builder){

        if (request()->ajax()) {
            $query = EmailSupport::query()->where('status',1)->orderBy('id','DESC');
    
            return DataTables::of($query)
                ->orderColumn('id', 'id $1')
                ->editColumn('first_name', function($query) {
                    if ($query->first_name) {
                        return $query->first_name;
                    }
                    
                })
                ->addColumn('action',' pages.email_support.action')
               
                ->editColumn('last_name', function($query) {
                    if ($query->last_name) {
                        return $query->last_name;
                    }
                })
                ->editColumn('phone', function($query) {
                    if ($query->phone) {
                        return $query->phone;
                    }
                })
                ->editColumn('email', function($query) {
                    return $query->email;
                })
                ->editColumn('query', function($query) {
                    return $query->query;
                })
                ->editColumn('remark', function($query) {
                    return $query->remark;
                })
               
               
                ->addColumn('extension', 'pages.email_support.select')
                ->rawColumns(['action', 'extension'])
                //->addColumn('action', 'pages.email_support.action')
                
                ->make(true);
            }

    }

    public function completed(Builder $builder){
        if (request()->ajax()) {
            $query = EmailSupport::query()->where('status',2)->orderBy('id','DESC');
    
            return DataTables::of($query)
                ->orderColumn('id', 'id $1')
                ->editColumn('first_name', function($query) {
                    if ($query->first_name) {
                        return $query->first_name;
                    }
                    
                })
                ->editColumn('last_name', function($query) {
                    if ($query->last_name) {
                        return $query->last_name;
                    }
                })
                ->editColumn('phone', function($query) {
                    if ($query->phone) {
                        return $query->phone;
                    }
                })
                ->editColumn('extended_date', function($query) {
                    if ($query->extended_date) {
                        return $query->extended_date;
                    }
                })
                ->editColumn('email', function($query) {
                    return $query->email;
                })
                ->editColumn('query', function($query) {
                    return $query->query;
                })
                ->editColumn('remark', function($query) {
                    return $query->remark;
                })
                
              
                
                
                ->make(true);
            }
    
    }

    public function update_status(Request $request){
        $id = $request->qid;
        $aprv_status = $request->approve_status;
        if($aprv_status == 1){
            $a_status = 'Approved';
        }else{
            $a_status = 'Rejected';
        }
        
        $emailsupport = EmailSupport::findOrFail($id);

        $parameters = [
            'to' => $emailsupport->email,
            'query' => $emailsupport->query,
            'remark' => $request->remark,
            'first_name'=> $emailsupport->first_name,
            'last_name'=> $emailsupport->last_name,
            'status' => $a_status
        ];

        $emailsupport->remark = $request->remark;
        $emailsupport->approved_status = $aprv_status;
        $emailsupport->status = 2;
        $emailsupport->save();

        

        Mail::send(new RemarkMail($parameters));

        return redirect()->back()->with('success', 'Remark updated');
    }


    public function getdata($id){
       
        $emailsupport = EmailSupport::findOrFail($id);
        $student_email = $emailsupport->email;
       
        $student_details = Student::select('students.id','students.user_id','courses.name','order_items.total_watched_duration','order_items.expire_at')
                            ->join('courses','students.course_id','=','courses.id')
                            ->join('order_items','students.user_id','=','order_items.user_id')->where('students.email',$student_email)->first();

        $orderItems = OrderItem::select('order_items.package_id','order_items.expire_at','order_items.total_watched_duration','packages.name')
                        ->join('packages','order_items.package_id','packages.id')
                        ->where('item_type', OrderItem::ITEM_TYPE_PACKAGE)
                        ->where('user_id', @$student_details->user_id)
                        ->where('is_canceled', false)
                        ->whereIn('payment_status', [OrderItem::PAYMENT_STATUS_FULLY_PAID, OrderItem::PAYMENT_STATUS_PARTIALLY_PAID])
                        ->whereHas('order', function($query) {
                        $query->where('is_refunded', false);
                            })
                        ->get();
        $option[] ='<option value="0">Select Course</option>';
        foreach($orderItems as $orderitem){
            $option[] = '<option value="'.$orderitem->package_id.'">'.$orderitem->name.'</option>';
        }
        $options= $option;
       

        if(!empty($student_details)){

            $studentArr = array(
                'student_id' => $student_details->id,
                'student_name' => $emailsupport->first_name.' '.$emailsupport->last_name,
                'course' => $student_details->name,
                'watched_hrs' => $student_details->total_watched_duration,
                'expire_at' => $student_details->expire_at,
                'user_id' => $student_details->user_id,
                'order_items'=> $options
            );
        }
        else{
            $studentArr = array(
                'student_id' => '',
                'student_name' => $emailsupport->first_name.' '.$emailsupport->last_name,
                'course' => '',
                'watched_hrs' => '',
                'expire_at' => '',
                'user_id' => '',
                'order_items' => ''
            );
        }
        return response()->json($studentArr);
    }

    public function updateExtension(Request $request){
        //dd($request);
        $order_item = OrderItem::select('*')->where('user_id',$request->u_id)->where('package_id',$request->course)->first();


        if(!empty($order_item)){
            $order_item->expire_at = $request->extension;
            $order_item->save();

            $package_extension = new PackageExtension();
            $package_extension->extended_date = $request->extension;
            $package_extension->order_item_id = $order_item->id;
            $package_extension->save();

            $emailsupport = EmailSupport::findOrFail($request->s_id);

            $parameters = [
                'to' => $emailsupport->email,
                'query' => $emailsupport->query,
                'first_name'=> $emailsupport->first_name,
                'last_name'=> $emailsupport->last_name,
                'extension' => $request->extension
            ];

       
            $emailsupport->status = 2;
            $emailsupport->extended_date = $request->extension;
            $emailsupport->save();

            Mail::send(new ExtensionMail($parameters));
            return redirect()->back()->with('success', 'Extension updated');
        }else{
            return redirect()->back()->with('error', 'Extension not updated.');
        }
    }

    public function getPackvalidity(Request $request){
        $user_id = $request->user_id;
        $package_id = $request->package_id ;

        $orderitem  = OrderItem::select('total_watched_duration','expire_at')
                    ->where('user_id',$user_id)->where('package_id',$package_id)->first();

        $orderitem_arr = array(
                        'validity' => $orderitem->expire_at,
                        'total_watched' => $orderitem->total_watched_duration
        );

        return response()->json($orderitem_arr);

    }
    
}
