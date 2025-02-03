<?php

namespace App\Http\Controllers;

use App\Jobs\SendEmailJob;
use App\Jobs\SendSms;
use App\Models\Level;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Package;
use App\Models\Student;
use App\Models\Course;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\Sms;
use App\Models\ErrorLog;
use App\Notifications\SendCustomnotificationMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;


class CustomNotificationController extends Controller
{
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Notification::query()
                ->orderBy('created_at', 'desc');

            return DataTables::of($query)
                ->editColumn('title', function ($query) {
                    if ($query->title) {
                        return Str::limit($query->title, 100);
                    }
                    return '-';
                })
                ->editColumn('count', function ($query) {
                    if ($query->count) {
                       return $query->count;
                    }
                    return '-';
                })
                ->editColumn('package_id', function ($query) {
                    if ($query->package_id) {
                        return $query->package->name;
                    }
                    return '-';
                })
                ->editColumn('level_id', function ($query) {
                    if ($query->level_id) {
                        return $query->level->name;
                    }
                    return '-';
                })
                ->editColumn('type', function ($query) {
                    if ($query->type) {
                        return Notification::$types[$query->type];
                    }
                    return '-';
                })
                ->editColumn('notification_body', function ($query) {
                    if ($query->notification_body) {
                        // $body = json_decode($query->body, true);
                        // if($body){
                        //     $blocks = $body['blocks'];
                        //     $notification['body'] = collect($blocks)->map(function ($block) {
                        //         switch ($block['type']) {
                        //             case 'header':
                        //                 $level = $block['data']['level'] ?? 1;
                        //                 return '<h'.$level.'>'.$block['data']['text'].'</h'.$level.'>';
                        //             case 'paragraph':
                        //                 return '<p>'.$block['data']['text'].'</p>';
                        //             case 'image':
                        //                 $classes = [
                        //                     'border' => $block['data']['withBorder'],
                        //                     'bg-light' => $block['data']['withBackground'],
                        //                     'justify-content-center' => $block['data']['withBackground'],
                        //                     'p-2' => $block['data']['withBackground'],
                        //                 ];

                        //                 $caption = $block['data']['caption'];

                        //                 $html = '';

                        //                 if ($caption) {
                        //                     $html .= "<small class='d-block text-center text-muted mb-5'>$caption</small>";
                        //                 }

                        //                 return $html;
                        //         }
                        //     })->join('');

                        //     $notificationBody = $notification['body'];

                        //     return view('pages.notifications.custom-notifications.notification_body', compact('notificationBody'));
                        return $query->notification_body;
                         }
                       
                    return '-';
                })
//                ->editColumn('order', function($query) {
//                    return '<div class="order">' . $query->order . '<input type="hidden" class="banner-id" value="' . $query->id . '"></div>';
//                })
//                ->addColumn('action', 'pages.banners.action')
//                ->rawColumns(['action','image','title_url', 'order'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title', 'width' => 15],
            ['data' => 'notification_body', 'name' => 'notification_body', 'title' => 'Body', 'width' => 25],
            ['data' => 'count', 'name' => 'count', 'title' => 'Number of Students', 'width' => 10],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type', 'width' => 10],
            ['data' => 'package_id', 'name' => 'package_id', 'title' => 'Package', 'width' => 10],
            ['data' => 'level_id', 'name' => 'level_id', 'title' => 'Level', 'width' => 10],
//            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Created By'],
//            ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'searchable' => false, 'orderable' => false, 'width' => '80px']
        ]);

        return view('pages.notifications.custom-notifications.index', compact('html'));
    }

    public function create()
    {
        $packages = Package::all();
        $levels = Level::all();
        $sms = Sms::all();
        $student = User::where('role', 5)->get();
        $course = Course::all();

        return view('pages.notifications.custom-notifications.create', compact('packages', 'levels', 'sms', 'student','course'));
    }
    public function store(Request $request)
    {
        $request->validate([
            // 'title' => 'required',
        ]);

        $title = $request->title;

        $notificationBody = $request->notification_description;
        $createdBy = Auth::id();

        DB::beginTransaction();



        if ($request->filled('all_student')) {
            $notification = new Notification();
            $notification->title = $title;
            $notification->notification_body = $notificationBody;
            $notification->type = Notification::ALL_USER_TYPE;
            $notification->mail_notification_body = $notificationBody;
            $notification->template_id = $request->template_id; //added by TE
            $notification->save();
            $students = Student::get();
            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = count($students);
            $updateNotification->save();

            foreach ($students as $student) {
                $userNotification = new UserNotification();

                $userNotification->user_id = $student->user_id;
                $userNotification->notification_id = $notification->id;

                $notification_body = "<br>" .   $notificationBody .
                    "<p>Thank you,</p>" .
                    "<p>Team J. K. Shah</p>";
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                }

                if (isset($request->sms_notification)) {
                    $userNotification->sms = 3;
                }
                $userNotification->save();
                if ($request->filled('email_notification')) {
                    $this->sendNotificationEmail($request->all(), $student, $notification_body);
                }
                if ($request->filled('sms_notification')) {

                    $data1['student_name']  = $name = $student->name;
                    $data1['student_id']    = $id = $student->id;
                    $pname                  = '';
                    $data1['phone']         =  $student->phone;
                    $expiry                 = '';
                    $data1['template_id']   = $request->template_id;
                    $template = $sms->body;
                    $a = str_replace("#~NAME#~", $name, $template);

                    $v = str_replace("#~PACKAGE_NAME#~", @$pname, $a);
                    $msg = str_replace("#~VALIDITY#~", @$expiry, $v);
                    $data1['content']           = $msg;
                    $data1['module_type']   = 1;
                    $data1['notification_id'] = $notification->id;
                    // echo "<pre>";
                    // print_r($data1);exit;
                    User_notification_sms::insert($data1);
                }
            }
        } elseif ($request->filled('is_package')) {

            for ($i = 0; $i < count($request->package); $i++) {

                $package_id = $request->package[$i];

                $notification = new Notification();
                $notification->title = $title;
                $notification->notification_body = $notificationBody;
                $notification->type = Notification::PACKAGE_TYPE;
                $notification->package_id = $package_id;
                $notification->mail_notification_body = $notificationBody;
                $notification->template_id = $request->template_id; //added by TE

                $notification->save();
                $orderItems = OrderItem::where('package_id', $package_id)
                    ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                    ->get();
                $package = Package::select("name as pname")->where('id', $package_id)->first();

                $updateNotification = Notification::findOrFail($notification->id);
                $updateNotification->count = count($orderItems);
                $updateNotification->save();

                foreach ($orderItems as $orderItem) {
                    $student = User::where('id', $orderItem->user_id)->first();
                    $notification_body = "<br>" .   $notificationBody .
                        "<p>Thank you,</p>" .
                        "<p>Team J. K. Shah</p>";
                    $userNotification = new UserNotification();
                    $userNotification->user_id = $orderItem->user_id;
                    $userNotification->notification_id = $notification->id;
                    //added by TE
                    if (isset($request->email_notification)) {
                        $userNotification->email = 1;
                    }

                    if (isset($request->sms_notification)) {
                        $userNotification->sms = 3;
                    }
                    $userNotification->save();

                    if ($request->filled('email_notification')) {
                         $this->sendNotificationEmail($request->all(), $student,  $notification_body);
                    }
                    if ($request->filled('sms_notification')) {



                        $data1['student_name']  = $name = $student->name;
                        $data1['student_id']    = $id = $student->id;
                        $pname                  = $package->pname;;
                        $data1['phone']         =  $student->phone;
                        $expiry                 = $orderItem->expire_at;
                        $data1['template_id']   = $request->template_id;
                        $template = $sms->body;
                        $a = str_replace("#NAME#", $name, $template);

                        $v = str_replace("#PACKAGE_NAME#", @$pname, $a);
                        $msg = str_replace("#VALIDITY#", @$expiry, $v);
                        $data1['content']           = $msg;
                        $data1['module_type']   = 1;
                        $data1['notification_id'] = $notification->id;
                        // echo "<pre>";
                        // print_r($data1);exit;
                        User_notification_sms::insert($data1);
                    }
                }
            }
        } elseif ($request->filled('is_level')) {


            for ($i = 0; $i < count($request->level); $i++) {
                $level_id = $request->level[$i];
                $notification = new Notification();
                $notification->title = $title;
                $notification->notification_body = $notificationBody;
                $notification->type = Notification::LEVEL_TYPE;
                $notification->level_id = $level_id;
                $notification->mail_notification_body = $notificationBody;
                $notification->template_id = $request->template_id; //added by TE
                $notification->save();
                $students = Student::where('level_id', $level_id)->get();

                $updateNotification = Notification::findOrFail($notification->id);
                $updateNotification->count = count($students);
                $updateNotification->save();

                foreach ($students as $student) {
                    $userNotification = new UserNotification();
                    $userNotification->user_id = $student->user_id;
                    $userNotification->notification_id = $notification->id;
                    //added by TE

                    $notification_body = "<br>" .   $notificationBody .
                        "<p>Thank you,</p>" .
                        "<p>Team J. K. Shah</p>";
                    if (isset($request->email_notification)) {
                        $userNotification->email = 1;
                    }

                    if (isset($request->sms_notification)) {
                        $userNotification->sms = 3;
                    }
                    $userNotification->save();

                    if ($request->filled('email_notification')) {

                        $this->sendNotificationEmail($request->all(), $student, $notification['body']);
                    }
                    if ($request->filled('sms_notification')) {

                        $data1['student_name']  = $name = $student->name;
                        $data1['student_id']    = $id = $student->id;
                        $pname                  = '';
                        $data1['phone']         =  $student->phone;
                        $expiry                 = '';
                        $data1['template_id']   = $request->template_id;
                        $template = $sms->body;
                        $a = str_replace("#~NAME#~", $name, $template);

                        $v = str_replace("#~PACKAGE_NAME#~", @$pname, $a);
                        $msg = str_replace("#~VALIDITY#~", @$expiry, $v);
                        $data1['content']           = $msg;
                        $data1['module_type']   = 1;
                        $data1['notification_id'] = $notification->id;
                        // echo "<pre>";
                        // print_r($data1);exit;
                        User_notification_sms::insert($data1);
                    }
                }
            }
        }elseif ($request->filled('is_course')) {
            for ($i = 0; $i < count($request->course); $i++) {
            $course_id = $request->course[$i];
            $notification = new Notification();
            $notification->title = $title;
            $notification->notification_body = $notificationBody;
            $notification->type = Notification::COURSE_TYPE;
            $notification->course_id = $course_id;
            $notification->mail_notification_body = $notificationBody;
            $notification->template_id = $request->template_id; //added by TE
            $notification->save();
            $students = Student::where('course_id', $course_id)->get();

            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = count($students);
            $updateNotification->save();
            foreach ($students as $student) {
                $userNotification = new UserNotification();
                $userNotification->user_id = $student->user_id;
                $userNotification->notification_id = $notification->id;
                //added by TE

                $notification_body = "<br>" .   $notificationBody .
                    "<p>Thank you,</p>" .
                    "<p>Team J. K. Shah</p>";
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                }

                if (isset($request->sms_notification)) {
                    $userNotification->sms = 3;
                }
                $userNotification->save();

                if ($request->filled('email_notification')) {

                    $this->sendNotificationEmail($request->all(), $student, $notification['body']);
                }
                if ($request->filled('sms_notification')) {

                    $data1['student_name']  = $name = $student->name;
                    $data1['student_id']    = $id = $student->id;
                    $pname                  = '';
                    $data1['phone']         =  $student->phone;
                    $expiry                 = '';
                    $data1['template_id']   = $request->template_id;
                    $template = $sms->body;
                    $a = str_replace("#~NAME#~", $name, $template);

                    $v = str_replace("#~PACKAGE_NAME#~", @$pname, $a);
                    $msg = str_replace("#~VALIDITY#~", @$expiry, $v);
                    $data1['content']           = $msg;
                    $data1['module_type']   = 1;
                    $data1['notification_id'] = $notification->id;
                    // echo "<pre>";
                    // print_r($data1);exit;
                    User_notification_sms::insert($data1);
                }
            }

            }
          
        } else {
            $notification = new Notification();
            $notification->title = $title;
            $notification->notification_body = $notificationBody;
            $notification->type = Notification::SINGLE_USER_TYPE;
            $notification->mail_notification_body = $notificationBody;
            $notification->template_id = $request->template_id; //added by TE
            $notification->save();
            for ($i = 0; $i < count($request->email); $i++) {
                $student_email = $request->email[$i];
                $student = User::where('id', $student_email)->first();



                //added by TE

                $notification_body = "<br>" .   $notificationBody .
                    "<p>Thank you,</p>" .
                    "<p>Team J. K. Shah</p>";
                if (!$student) {
                    return redirect('custom-notifications/create')->with('error', 'No user with this email');
                }

                $updateNotification = Notification::findOrFail($notification->id);
                $updateNotification->count = count($request->email);
                $updateNotification->save();

                $userNotification = new UserNotification();
                $userNotification->user_id = $student->id;
                $userNotification->notification_id = $notification->id;
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                }

                if (isset($request->sms_notification)) {
                    $userNotification->sms = 1;
                    //  $data1['template'] = $sms->body;


                }

                $userNotification->save();

                if ($request->filled('email_notification')) {
                    $this->sendNotificationEmail($request->all(), $student,  $notification_body);
                }
                if ($request->filled('sms_notification')) {

                    $data1['student_name']  = $name = $student->name;
                    $data1['student_id']    = $id = $student->id;
                    $pname                  = '';
                    $data1['phone']         =  $student->phone;
                    $expiry                 = '';
                    $data1['template_id']   = $request->template_id;
                    $template = $sms->body;
                    $a = str_replace("#~NAME#~", $name, $template);

                    $v = str_replace("#~PACKAGE_NAME#~", @$pname, $a);
                    $msg = str_replace("#~VALIDITY#~", @$expiry, $v);
                    $data1['content']           = $msg;
                    $data1['module_type']   = 1;
                    $data1['notification_id'] = $notification->id;
                    // echo "<pre>";
                    // print_r($data1);exit;
                    User_notification_sms::insert($data1);
                }
            }
        }


        DB::commit();
        return redirect('custom-notifications/create')->with('success', 'Notification added successfully');
    }

    public function storebk(Request $request)
    {

        $request->validate([
           // 'title' => 'required',
        ]);

        $title = $request->title;
      
        $notificationBody = $request->notification_description;
        $createdBy = Auth::id();

        DB::beginTransaction();

        $notification = new Notification();
        $notification->title = $title;
        $notification->notification_body = $notificationBody;
      
        if ($request->filled('all_student')) {
            $notification->type = Notification::ALL_USER_TYPE;
        } elseif ($request->filled('is_package')) {
            $notification->type = Notification::PACKAGE_TYPE;
            $notification->package_id = $request->package;
        } elseif ($request->filled('is_level')) {
            $notification->type = Notification::LEVEL_TYPE;
            $notification->level_id = $request->level;
        } else {
            $notification->type = Notification::SINGLE_USER_TYPE;
        }
        $notification->mail_notification_body = $notificationBody;
        $notification->template_id = $request->template_id; //added by TE
        $notification->save();

       
//         $blocks = $body['blocks'];

//         $notification['body'] = collect($blocks)->map(function ($block) {
//             switch ($block['type']) {
//                 case 'header':
//                     $level = $block['data']['level'] ?? 1;
//                     return '<h'.$level.'>'.$block['data']['text'].'</h'.$level.'>';
//                 case 'paragraph':
//                     return '<p>'.$block['data']['text'].'</p>';
//                 case 'image':
//                     $classes = [
//                         'border' => $block['data']['withBorder'],
//                         'bg-light' => $block['data']['withBackground'],
//                         'justify-content-center' => $block['data']['withBackground'],
//                         'p-2' => $block['data']['withBackground'],
//                     ];

//                     $classes = collect($classes)->filter()->keys()->join(' ');

//                     $img_Classes = [
//                         'w-100' => $block['data']['stretched'],
//                     ];

//                     $img_Classes = collect($img_Classes)->filter()->keys()->join(' ');

//                     $caption = $block['data']['caption'];

//                     $html = '';

// //                    if ($block['data']['file']) {
// //                        if ($caption) {
// //                            $html =  '<div class="d-flex '.$classes.'"><img class="img-fluid '.$img_Classes.'" src="'.$block['data']['file']['url'].'"  alt="'.$caption.'" /></div>';
// //                        } else {
// //                            $html =  '<div class="d-flex '.$classes.'"><img class="img-fluid mb-5'.$img_Classes.'" src="'.$block['data']['file']['url'].'"  alt="'.$caption.'" /></div>';
// //                        }
// //                    }

//                     if ($caption) {
//                         $html .= "<small class='d-block text-center text-muted mb-5'>$caption</small>";
//                     }

//                     return $html;
//             }
//         })->join('');


        if ($request->filled('all_student')) {

            $students = Student::get();
            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = count($students);
            $updateNotification->save();

            foreach ($students as $student) {
                $userNotification = new UserNotification();

                $userNotification->user_id = $student->user_id;
                $userNotification->notification_id = $notification->id;
                
                $notification_body = "<br>" .   $notificationBody .
                     "<p>Thank you,</p>".
                     "<p>Team J. K. Shah</p>";
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                    }
                  
                    if (isset($request->sms_notification)) {
                        $userNotification->sms = 3;
                    }  
                     $userNotification->save();
                if ($request->filled('email_notification')) {
                    $this->sendNotificationEmail($request->all(), $student, $notification_body);
                }
                if ($request->filled('sms_notification')) {

                    $data1['student_name']  = $name = $student->name;
                $data1['student_id']    = $id = $student->id;
                $pname                  = '';
                $data1['phone']         =  $student->phone;
                $expiry                 = '';
                $data1['template_id']   = $request->template_id;
                $template = $sms->body;
                $a = str_replace("#~NAME#~", $name, $template);

                $v = str_replace("#~PACKAGE_NAME#~", @$pname, $a);
                $msg = str_replace("#~VALIDITY#~", @$expiry, $v);
                $data1['content']           = $msg;
                $data1['module_type']   =1;
                $data1['notification_id']=$notification->id;
                // echo "<pre>";
                // print_r($data1);exit;
                User_notification_sms::insert($data1);
                }
            }
        }

        if ($request->filled('is_package')) {

            $orderItems = OrderItem::where('package_id', $request->package)
                ->where('payment_status', OrderItem::PAYMENT_STATUS_FULLY_PAID)
                ->get();
                $package = Package::select("name as pname")->where('id', $request->package)->first();
// echo "<pre>";
// print_r($orderItems);exit;
            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = count($orderItems);
            $updateNotification->save();

            foreach ($orderItems as $orderItem) {
                $student = User::where('id', $orderItem->user_id)->first();
                $notification_body = "<br>" .   $notificationBody .
                "<p>Thank you,</p>".
                "<p>Team J. K. Shah</p>"; //added by TE
                $userNotification = new UserNotification();
                $userNotification->user_id = $orderItem->user_id;
                $userNotification->notification_id = $notification->id;
                //added by TE
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                }
              
                if (isset($request->sms_notification)) {
                    $userNotification->sms = 3;
                }  
                $userNotification->save();

                if ($request->filled('email_notification')) {
                    $this->sendNotificationEmail($request->all(), $student,  $notification_body);
                }
                if ($request->filled('sms_notification')) {
               
                $data1['name']=$student->name;
                $data1['phone']=$student->phone;
                $data1['expiry']=$orderItem->expire_at;
                $data1['package']=$package->pname;
                $data1['template_id'] =$request->template_id;
            
                  $this->sendNotificationSms($data1);
                }
            }
        }

        if ($request->filled('is_level')) {

            $students = Student::where('level_id', $request->level)->get();

            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = count($students);
            $updateNotification->save();

            foreach ($students as $student) {
                $userNotification = new UserNotification();
                $userNotification->user_id = $student->user_id;
                $userNotification->notification_id = $notification->id;
                //added by TE

                $notification_body = "<br>" .   $notificationBody .
            "<p>Thank you,</p>" .
                "<p>Team J. K. Shah</p>";
                if (isset($request->email_notification)) {
                    $userNotification->email = 1;
                }
              
                if (isset($request->sms_notification)) {
                    $userNotification->sms = 3;
                }  
                $userNotification->save();

                if ($request->filled('email_notification')) {
                 
                  $this->sendNotificationEmail($request->all(), $student, $notification['body']);
                }
                if($request->filled('sms_notification')) {
                  
                  $this->sendNotificationSms($request->all(), $student,  $notification_body);
                }
            }
        }

        if ($request->filled('is_student')) {
          
            $student = User::where('id', $request->email)->first();



          //added by TE
           
          $notification_body = "<br>" .   $notificationBody .
          "<p>Thank you,</p>" .
          "<p>Team J. K. Shah</p>";
            if (!$student) {
                return redirect('custom-notifications/create')->with('error', 'No user with this email');
            }

            $updateNotification = Notification::findOrFail($notification->id);
            $updateNotification->count = 1;
            $updateNotification->save();

            $userNotification = new UserNotification();
            $userNotification->user_id = $student->id;
            $userNotification->notification_id = $notification->id;
            if (isset($request->email_notification)) {
                 $userNotification->email = 1;
            }
          
            if (isset($request->sms_notification)) {
                $userNotification->sms = 1;
            }  
           
            $userNotification->save();

            if ($request->filled('email_notification')) {
                $this->sendNotificationEmail($request->all(), $student,  $notification_body);
            }
            if ($request->filled('sms_notification')) {
               
              $this->sendNotificationSms($data1);
            }
        }

        DB::commit();


        return redirect('custom-notifications/create')->with('success', 'Notification added successfully');
    }

    public function sendNotificationEmail($request, $student, $notificationBody)
    {

      
        info($notificationBody);

        // $attributes['logo'] = env('WEB_URL') . '/assets/images/logo.png';
        $attributes['logo']=env('APP_ENV')=='production'?env('WEB_URL') . '/assets/images/logo.png':public_path('logo.png');
        $attributes['web'] = env('WEB_URL');
        $attributes['title'] = $request['title'];
        $attributes['body'] = $notificationBody;
        $attributes['name'] = $student->name;
        $attributes['email'] = $student->email;
        try {
    dispatch(new SendEmailJob($attributes, $student));
} catch (\Exception $exception) {
    $data1['response']  = $exception->getMessage();
    ErrorLog::insert($data1);
}

    }
//Added by TE
    public function sendNotificationSms($data)
    {
        dispatch(new SendSms($data));
        // $response = Http::get('https://k3digitalmedia.co.in/websms/api/http/index.php',[
        //          'username' => 'K3JKSHAH',
        //          'apikey' => '67311-C0DBD',
        //          'apirequest' => 'Template',
        //          'sender' => 'JKSHAH',
        //          'mobile' => $data['phone'],
        //          'TemplateID' => '1107165217718653763',
        //          'Values' =>  $data['name'], @$data['pname'], @$data['expiry'], 
        //          'route' => 'ServiceImplicit'
        //      ]);
    //    dispatch(new SendSms($data));
        // $response = Http::get('https://k3digitalmedia.co.in/websms/api/http/index.php',[
        //     'username' => 'K3JKSHAH',
        //     'apikey' => '67311-C0DBD',
        //     'apirequest' => 'Template',
        //     'sender' => 'JKSHAH',
        //     'mobile' => '9961557760',
        //     'TemplateID' => '1107165217718653763',
        //     'Values' => "hello",
        //     'route' => 'ServiceImplicit'
        // ]);
      
        //     $response = Http::get('https://k3digitalmedia.co.in/websms/api/http/index.php',[
        //      'username' => 'K3JKSHAH',
        //      'apikey' => '67311-C0DBD',
        //      'apirequest' => 'Template',
        //      'sender' => 'JKSHAH',
        //      'mobile' => $data['phone'],
        //      'TemplateID' => '1107165217718653763',
        //      'Values' =>  $data['name'], @$data['pname'], @$data['expiry'], 
        //      'route' => 'ServiceImplicit'
        //  ]);
        

       // dispatch(new SendEmailJob($attributes, $student));

    }

    public function templatebody($id){
       $data=Sms::select('body')->where('template_id',$id)->first();
       echo json_encode($data);

    }

}
