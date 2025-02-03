<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\TechSupport;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\TechSupportRemarkMail;
use App\Models\TechSupportAttachment;
use App\Mail\TechSupportAdminRemarkMail;
use App\Models\EmailLog;

class TechSupportController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = TechSupport::with('user');
            if(request()->filled('filter.search')){
                $query->whereHas('user', function($query) {
                    $query->where('name','LIKE', '%'.request('filter.search').'%');
                });
                
            }
            if (request()->filled('filter.search')) {
                $query->Orwhere('user_id','=',request('filter.search'));                               
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
           
            return DataTables::of($query)

            ->addColumn('attachments', function($query) {
                $attachments = TechSupportAttachment::where('query_id', $query->id)->get()->pluck('attachment');

                foreach ($attachments as $attachment) {
                    return '<span><img src="'.env('WEB_URL').'/screenshots/'. $attachment . '" class="rounded-square" width="100" height="60" onclick="imagezoom()"></span><br>';
                }
                // if ($query->image) {
                //     return '<span><img src="'.env('WEB_URL').'/screenshots/'. $query->image . '" class="rounded-square" width="100" height="60" onclick="imagezoom()"></span>';
                // }
                return '';
            })

            ->addColumn('remarks', function($query) {
                return $query->remarks;
            })

            ->addColumn('created_at', function($query) {
                return optional($query->created_at)->format('d/m/y H:i:s');
            })

            ->addColumn('updated_at', function($query) {
                return optional($query->updated_at)->format('d/m/y H:i:s');
            })
            
            ->addColumn('status',function($query){
                if($query->status == 1){
                    return "Closed";
                }else{
                    return "Pending";
                }
            })
            
            ->addColumn('img', function ($query) {
                $attachments = TechSupportAttachment::select('id','attachment')->where('query_id', $query->id)->get();
                return view('pages.techsupport.image', compact('query','attachments'));

            })
            ->addColumn('action', function ($query){
                return view('pages.techsupport.action',compact('query'));
            })
            ->rawColumns(['image','action','img','attachments'])
            ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'user_id', 'name' => 'user_id', 'title' => 'User ID' ,'orderable' => true],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'User Name','defaultContent' => ''],
            //['data' => 'image', 'name' => 'image', 'title' => 'Image', 'orderable' => false],
            ['data' => 'description', 'name' => 'description', 'title' => 'Issue Reported', 'orderable' => false,'defaultContent' => ''],
            ['data' => 'pageorcourse', 'name' => 'pageorcourse', 'title' => 'Page or Course', 'orderable' => false,'defaultContent' => ''],
            ['data' => 'img', 'name' => 'img', 'title' => 'Attachments', 'orderable' => false],
            ['data' => 'remarks','name'=>'remarks','title'=>'Remarks','defaultContent' => '','orderable' => false],
            ['data' => 'status','name'=>'status' ,'title'=>'Status','defaultContent' => '','orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Issue Reported at', 'orderable' => true,'defaultContent' => ''],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Updated at', 'orderable' => true],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '80px']
        ])
        ->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 10,
        ])->orderBy(0,'desc');

        return view('pages.techsupport.index', compact('table'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function update_remark(Request $request){
        $id = $request->qid;
        $techsupport = TechSupport::findOrFail($id);
        $techsupport->remarks = $request->remark;
        $techsupport->actions_by = Auth::id();
        $techsupport->status = true;
        $techsupport->updated_at = Carbon::now()->toDateTimeString();
        $techsupport->save();

        $user = User::findOrFail($techsupport->user_id);
        $admin_mail = Setting::where('key', 'email_bcc')->first();
        $admins = User::select('name','role')->where('id',$techsupport->actions_by);
        $adminemails = Setting::where('key', 'admin_email')->first();
        try{
            $parameters = [
                'to' => $user->email,
                'remark' => $techsupport->remarks,
                'name'=> $user->name,
                'email_bcc' => $admin_mail->value,
                'admin_mail' => $adminemails->value,
                'query' => $techsupport->description,
            ];
            Mail::send(new TechSupportRemarkMail($parameters));
           
            $email_log = new EmailLog();
            $email_log->email_to = $user->email;
            $email_log->email_from = env('MAIL_FROM_ADDRESS');
            $email_log->content = "JKSHAH ONLINE - Tech Support";
            $email_log->save();

            Mail::send(new TechSupportAdminRemarkMail($parameters));
            
            $email_log = new EmailLog();
            $email_log->email_to = $adminemails->value;
            $email_log->email_from = env('MAIL_FROM_ADDRESS');
            $email_log->content = "JKSHAH ONLINE - Tech Support";
            $email_log->save();


        }catch(\Exception $exception){
            info($exception->getMessage());
        }
        
        return redirect()->back()->with('success', 'Remark updated');
    }
    
}
