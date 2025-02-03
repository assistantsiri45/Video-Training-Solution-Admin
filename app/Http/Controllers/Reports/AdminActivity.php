<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;
use App\Models\AdminLog;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\PackageType;
use App\Models\Language;

class AdminActivity extends Controller
{
    public function index(Builder $builder)
    {
        $role=array(User::ROLE_ADMIN=>User::ROLE_ADMIN_TEXT,
                    User::ROLE_COURSE_ADMIN=>User::ROLE_COURSE_ADMIN_TEXT,
                    User::ROLE_BUSINESS_ADMIN=>User::ROLE_BUSINESS_ADMIN_TEXT,
                    User::ROLE_PLATFORM_ADMIN=>User::ROLE_PLATFORM_ADMIN_TEXT,
                    User::ROLE_REPORT_ADMIN=>User::ROLE_REPORT_ADMIN_TEXT,
                    User::ROLE_CONTENT_MANAGER=>User::ROLE_CONTENT_MANAGER_TEXT,
                    User::ROLE_FINANCE_MANAGER=>User::ROLE_FINANCE_MANAGER_TEXT,
                    User::ROLE_BRANCH_MANAGER=>User::ROLE_BRANCH_MANAGER_TEXT,
                    User::ROLE_ASSISTANT=> User::ROLE_ASSISTANT_TEXT,
                    User::ROLE_REPORTING=>User::ROLE_REPORTING_TEXT);
       
       
        if (request()->ajax()) {
            $query = AdminLog::query()->with('user');
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->wherehas('user', function($query) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    })
                       
                        ->orWhere(function ($query) {
                            $query->wherehas('user', function($query) {
                                $query->where('email', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                           
                                $query->where('activity', 'like', '%' . request()->input('filter.search') . '%');
                           
                        });
                });
            }
            if (request()->filled('filter.date')) {
                $dateRange = request()->input('filter.date');
                $explodedDates = explode(' - ', $dateRange);
                $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);

                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            if (request()->filled('filter.role')) {
                $query->where('role_id',  request()->input('filter.role'));

            }
           
           

            return DataTables::of($query)
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->orderColumn('login_time', function ($query, $order) {
                $query->orderBy('login_time', $order);
            })
            ->orderColumn('logout_time', function ($query, $order) {
                $query->orderBy('logout_time', $order);
            })
            ->orderColumn('activity', function ($query, $order) {
                $query->orderBy('activity', $order);
            })
            ->orderColumn('role', function ($query, $order) {
                $query->orderBy('role_id', $order);
            })
            ->orderColumn('ip_address', function ($query, $order) {
                $query->orderBy('ip_address', $order);
            })
           

                ->addColumn('user.name', function($query) {
                    return $query->user->name ?? '-';
                })
               
                ->addColumn('user.email', function($query) {
                    return $query->user->email ?? '-';
                })
                ->addColumn('user.phone', function($query) {
                    return $query->user->phone ?? '-';
                })
                ->addColumn('activity', function($query) {
                    return $query->activity ?? '-';
                })
                ->addColumn('original_value', function($query) {
                    if($query->original_value){
                        return '<a class="updated_value" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';

                    }else{
                        return '-';
                    }
                   // return $query->original_value ?? '-';
                })
                // ->addColumn('modified_value', function($query) {
                //     if($query->modified_value){
                //         $a=$query->modified_value;
                //         return base64_decode($a);

                //     }else{
                //         return '-';
                //     }
                // })
                ->addColumn('created_at', function($query) {
                    return ($query->created_at);
                    if(!empty($query->created_at))
                        return date("Y-m-d",strtotime($query->created_at)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('login_time', function($query) {
                    if(!empty($query->login_time))
                        return date("H:i:s",strtotime($query->login_time)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('logout_time', function($query) {
                    if(!empty($query->logout_time))
                        return date("H:i:s",strtotime($query->logout_time)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('role', function($query) {
                  
                    switch ($query->role_id) {
                        case User::ROLE_ADMIN: return User::ROLE_ADMIN_TEXT;
                        break;
                        case User::ROLE_COURSE_ADMIN: return User::ROLE_COURSE_ADMIN_TEXT;
                        break;
                        case User::ROLE_BUSINESS_ADMIN: return User::ROLE_BUSINESS_ADMIN_TEXT;
                        break;
                        case User::ROLE_PLATFORM_ADMIN: return User::ROLE_PLATFORM_ADMIN_TEXT;
                        break;
                        case User::ROLE_REPORT_ADMIN: return User::ROLE_REPORT_ADMIN_TEXT;
                        break;
                        case User::ROLE_CONTENT_MANAGER: return User::ROLE_CONTENT_MANAGER_TEXT;
                        break;
                        case User::ROLE_FINANCE_MANAGER: return User::ROLE_FINANCE_MANAGER_TEXT;
                        break;
                        case User::ROLE_BRANCH_MANAGER: return User::ROLE_BRANCH_MANAGER_TEXT;
                        break;
                        case User::ROLE_ASSISTANT: return User::ROLE_ASSISTANT_TEXT;
                        break;
                        case User::ROLE_REPORTING: return User::ROLE_REPORTING_TEXT;
                        break;
                        default: return 'Unknown';
                        break;
                    }
                })
                ->addColumn('response', function($query) {
                  
                            return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                       

                })
               
               
             
                 ->rawColumns(['response','original_value'])
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'user.name', 'name' => 'name', 'title' => 'Name', 'orderable' => false],
            ['data' => 'user.email', 'name' => 'email', 'title' => 'Email', 'orderable' => false],
            ['data' => 'role', 'name' => 'role', 'title' => 'Role', 'orderable' => true],
            ['data' => 'user.phone', 'name' => 'phone', 'title' => 'Contact No', 'orderable' => false],
            ['data' => 'activity', 'name' => 'activity', 'title' => 'Activity','orderable' => true],
            ['data' => 'original_value', 'name' => 'original_value', 'title' => 'Edit Log','orderable' => false],
            // ['data' => 'modified_value', 'name' => 'modified_value', 'title' => 'Modified Value','orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action','orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date', 'orderable' => true],
            ['data' => 'login_time', 'name' => 'login_time', 'title' => 'Login Time', 'orderable' => true],
            ['data' => 'logout_time', 'name' => 'logout_time', 'title' => 'Logout Time', 'orderable' => true],
          
            ['data' => 'ip_address', 'name' => 'ip_address', 'title' => 'IP Address', 'orderable' => true],
             ['data' => 'response', 'name' => 'response', 'title' => '', 'orderable' => false]
            
           
        ])
        ->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 75,
        ])->orderBy(0,'desc');

        return view('pages.reports.admin_activity.index', compact('table','role'));
    }
    public function indexaction(Builder $builder)
    {
        $role=array(User::ROLE_ADMIN=>User::ROLE_ADMIN_TEXT,
                    User::ROLE_COURSE_ADMIN=>User::ROLE_COURSE_ADMIN_TEXT,
                    User::ROLE_BUSINESS_ADMIN=>User::ROLE_BUSINESS_ADMIN_TEXT,
                    User::ROLE_PLATFORM_ADMIN=>User::ROLE_PLATFORM_ADMIN_TEXT,
                    User::ROLE_REPORT_ADMIN=>User::ROLE_REPORT_ADMIN_TEXT,
                    User::ROLE_CONTENT_MANAGER=>User::ROLE_CONTENT_MANAGER_TEXT,
                    User::ROLE_FINANCE_MANAGER=>User::ROLE_FINANCE_MANAGER_TEXT,
                    User::ROLE_BRANCH_MANAGER=>User::ROLE_BRANCH_MANAGER_TEXT,
                    User::ROLE_ASSISTANT=> User::ROLE_ASSISTANT_TEXT,
                    User::ROLE_REPORTING=>User::ROLE_REPORTING_TEXT);
       
       
        if (request()->ajax()) {
            $query = AdminLog::query()->with('user');
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->wherehas('user', function($query) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    })
                       
                        ->orWhere(function ($query) {
                            $query->wherehas('user', function($query) {
                                $query->where('email', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                           
                                $query->where('activity', 'like', '%' . request()->input('filter.search') . '%');
                           
                        });
                });
            }
            if (request()->filled('filter.date')) {
                $dateRange = request()->input('filter.date');
                $explodedDates = explode(' - ', $dateRange);
                $fromDate = Carbon::createFromFormat('d/m/Y', $explodedDates[0]);
                $toDate = Carbon::createFromFormat('d/m/Y', $explodedDates[1]);

                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            if (request()->filled('filter.role')) {
                $query->where('role_id',  request()->input('filter.role'));

            }
            $query->where('action','update');
           
           

            return DataTables::of($query)
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->orderColumn('login_time', function ($query, $order) {
                $query->orderBy('login_time', $order);
            })
            ->orderColumn('logout_time', function ($query, $order) {
                $query->orderBy('logout_time', $order);
            })
            ->orderColumn('activity', function ($query, $order) {
                $query->orderBy('activity', $order);
            })
            ->orderColumn('role', function ($query, $order) {
                $query->orderBy('role_id', $order);
            })
            ->orderColumn('ip_address', function ($query, $order) {
                $query->orderBy('ip_address', $order);
            })
           

                ->addColumn('user.name', function($query) {
                    return $query->user->name ?? '-';
                })
               
                ->addColumn('user.email', function($query) {
                    return $query->user->email ?? '-';
                })
                ->addColumn('user.phone', function($query) {
                    return $query->user->phone ?? '-';
                })
                ->addColumn('activity', function($query) {
                    return $query->activity ?? '-';
                })
                ->addColumn('original_value', function($query) {
                    if($query->original_value){
                        return '<a class="updated_value" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';

                    }else{
                        return '-';
                    }
                   // return $query->original_value ?? '-';
                })
                // ->addColumn('modified_value', function($query) {
                //     if($query->modified_value){
                //         $a=$query->modified_value;
                //         return base64_decode($a);

                //     }else{
                //         return '-';
                //     }
                // })
                ->addColumn('created_at', function($query) {
                    return ($query->created_at);
                    if(!empty($query->created_at))
                        return date("Y-m-d",strtotime($query->created_at)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('login_time', function($query) {
                    if(!empty($query->login_time))
                        return date("H:i:s",strtotime($query->login_time)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('logout_time', function($query) {
                    if(!empty($query->logout_time))
                        return date("H:i:s",strtotime($query->logout_time)) ?? '-';
                    else
                        return '-';
                })
                ->addColumn('role', function($query) {
                  
                    switch ($query->role_id) {
                        case User::ROLE_ADMIN: return User::ROLE_ADMIN_TEXT;
                        break;
                        case User::ROLE_COURSE_ADMIN: return User::ROLE_COURSE_ADMIN_TEXT;
                        break;
                        case User::ROLE_BUSINESS_ADMIN: return User::ROLE_BUSINESS_ADMIN_TEXT;
                        break;
                        case User::ROLE_PLATFORM_ADMIN: return User::ROLE_PLATFORM_ADMIN_TEXT;
                        break;
                        case User::ROLE_REPORT_ADMIN: return User::ROLE_REPORT_ADMIN_TEXT;
                        break;
                        case User::ROLE_CONTENT_MANAGER: return User::ROLE_CONTENT_MANAGER_TEXT;
                        break;
                        case User::ROLE_FINANCE_MANAGER: return User::ROLE_FINANCE_MANAGER_TEXT;
                        break;
                        case User::ROLE_BRANCH_MANAGER: return User::ROLE_BRANCH_MANAGER_TEXT;
                        break;
                        case User::ROLE_ASSISTANT: return User::ROLE_ASSISTANT_TEXT;
                        break;
                        case User::ROLE_REPORTING: return User::ROLE_REPORTING_TEXT;
                        break;
                        default: return 'Unknown';
                        break;
                    }
                })
                ->addColumn('response', function($query) {
                  
                            return '<a class="a-response" data-id=' . $query->id . '><i class="fas fa-eye"></i></a>';
                       

                })
               
               
             
                 ->rawColumns(['response','original_value'])
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'user.name', 'name' => 'name', 'title' => 'Name', 'orderable' => false],
            ['data' => 'user.email', 'name' => 'email', 'title' => 'Email', 'orderable' => false],
            ['data' => 'role', 'name' => 'role', 'title' => 'Role', 'orderable' => true],
            ['data' => 'user.phone', 'name' => 'phone', 'title' => 'Contact No', 'orderable' => false],
            ['data' => 'activity', 'name' => 'activity', 'title' => 'Activity','orderable' => true],
            ['data' => 'original_value', 'name' => 'original_value', 'title' => 'Edit Log','orderable' => false],
            // ['data' => 'modified_value', 'name' => 'modified_value', 'title' => 'Modified Value','orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => 'Action','orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date', 'orderable' => true],
            ['data' => 'login_time', 'name' => 'login_time', 'title' => 'Login Time', 'orderable' => true],
            ['data' => 'logout_time', 'name' => 'logout_time', 'title' => 'Logout Time', 'orderable' => true],
          
            ['data' => 'ip_address', 'name' => 'ip_address', 'title' => 'IP Address', 'orderable' => true],
             ['data' => 'response', 'name' => 'response', 'title' => '', 'orderable' => false]
            
           
        ])
        ->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 75,
        ])->orderBy(0,'desc');

        return view('pages.reports.admin_activity.index', compact('table','role'));
    }
    public function getresponse(){
        $adminlog = AdminLog::findOrFail(request()->input('id'));
       $log= unserialize(base64_decode($adminlog->server_var));

        return json_encode($log, JSON_PRETTY_PRINT);
    }
    public function getEditLog(){
       
        $adminlog = AdminLog::findOrFail(request()->input('id'));
        $log= unserialize(base64_decode($adminlog->original_value));
        $log1=json_encode($log, JSON_PRETTY_PRINT);
        $mod= unserialize(base64_decode($adminlog->modified_value));
        $mod1=json_encode($mod, JSON_PRETTY_PRINT);
        $data='<table border="1"><tr><td></td><th>Original value</th><th>Modified value</th></tr>';
        
        foreach($log as $key=>$row){
            if(@$key!='body'){
            if(@$log[$key]==@$mod[$key]){
                $data.='<tr><td>'.$key.'</td><td>'.@$log[$key].'</td><td>'.@$mod[$key].'</td></tr>';

            }else{ 
            $data.='<tr bgcolor="green"><td>'.$key.'</td><td>'.@$log[$key].'</td><td>'.@$mod[$key].'</td></tr>';
            }
        }
        }
        
       // $data.='<th>Original value</th><th>Modified value</th></tr><tr>';
      //  $data.=
       // $data.='<td>'.$log1.'</td><td>'.$mod1.'</td></tr></table>';
        echo $data;
 
        // return json_encode($log, JSON_PRETTY_PRINT);
    }

}
