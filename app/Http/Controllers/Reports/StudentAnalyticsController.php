<?php

namespace App\Http\Controllers\Reports;

use App\Models\Student;
use App\Models\StudentLog;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Course;
use App\Models\PackageType;
use App\Models\Language;

class StudentAnalyticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        $courses = Course::where('is_enabled',true)->orderBy('name')->get();
        // $types= PackageType::where('is_enabled',true)->orderBy('name')->get();
        $languages=Language::orderBy('name')->get();
        if (request()->ajax()) {
            $query = StudentLog::query()->with('package','user');
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->wherehas('user', function($query) {
                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                    })
                        ->orWhere(function ($query) {
                            $query->wherehas('package', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                            $query->wherehas('package.course', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                            $query->wherehas('package.level', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                            $query->wherehas('user', function($query) {
                                $query->where('email', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        })
                        ->orWhere(function ($query) {
                           
                                $query->where('user_id', 'like', '%' . request()->input('filter.search') . '%');
                         
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
            // if (!empty(request('filter.log_s_date'))) {
            //     $query->whereDate('created_at','>=', Carbon::parse(request('filter.log_s_date')));
            // }
            // if (!empty(request('filter.log_e_date'))) {
            //     $query->whereDate('created_at','<=', Carbon::parse(request('filter.log_e_date')));
            // }
            if (!empty(request('filter.course'))) {
                $query->wherehas('package.course', function($query) {
                 $query->where('course_id', request('filter.course'));
                });
            }
            if (!empty(request('filter.level'))) {
                $query->wherehas('package.level', function($query) {
                 $query->where('level_id', request('filter.level'));
                });
            }
            if (!empty(request('filter.type'))) {
                $query->wherehas('package.packagetype', function($query) {
                 $query->where('package_type', request('filter.type'));
                });
            }
            if (!empty(request('filter.subject'))) {
                $query->wherehas('package.subject', function($query) {
                 $query->where('subject_id', request('filter.subject'));
                });
            }
            if (!empty(request('filter.chapter'))) {
                $query->wherehas('package.chapter', function($query) {
                 $query->where('chapter_id', request('filter.chapter'));
                });
            }
            if (!empty(request('filter.language'))) {
                $query->wherehas('package.language', function($query) {
                 $query->where('language_id', request('filter.language'));
                });
            }
           

            return DataTables::of($query)
             ->addColumn('user_id', function($query) {
                    return $query->user_id ?? '-';
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
                ->addColumn('log_type', function($query) {
                     if($query->log_type==1)
                     return 'Signup';
                     elseif($query->log_type==2)
                     return 'Login';
                     else if($query->log_type==3)
                     return 'Course View';
                     else if($query->log_type==4)
                     return 'Video Watch';
                     else
                     return 'Logged Out';
                })
                ->addColumn('created_at', function($query) {
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
                ->addColumn('package.name', function($query) {
                    return $query->package->name ?? '-';
                })
                ->addColumn('package.course.name', function($query) {
                    return $query->package->course->name ?? '-';
                })
                ->addColumn('package.level.name', function($query) {
                    return $query->package->level->name ?? '-';
                })
                ->addColumn('package.packagetype.name', function($query) {
                    return $query->package->packagetype->name ?? '-';
                })
                ->addColumn('package.subject.name', function($query) {
                    return $query->package->subject->name ?? '-';
                })
                ->addColumn('package.chapter.name', function($query) {
                    return $query->package->chapter->name ?? '-';
                })
                ->addColumn('package.language.name', function($query) {
                    return $query->package->language->name ?? '-';
                })
                ->addColumn('ip_address', function($query) {
                    return $query->ip_address ?? '-';
                })
                ->orderColumn('created_at', function ($query, $order) {
                    $query->orderBy('created_at', $order);
                })
               
               
             
                // ->rawColumns(['packages'])
                ->make(true);
        }

        $table = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID' ,'orderable' => true],
            ['data' => 'user_id', 'name' => 'user_id', 'title' => 'Student Id' ,'orderable' => false],
            ['data' => 'user.name', 'name' => 'name', 'title' => 'Name', 'orderable' => false],
            ['data' => 'user.email', 'name' => 'email', 'title' => 'Email', 'orderable' => false],
            ['data' => 'user.phone', 'name' => 'phone', 'title' => 'Contact No', 'orderable' => false],
            ['data' => 'log_type', 'name' => 'log_type', 'title' => 'Activity','orderable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date', 'orderable' => true,],
            ['data' => 'login_time', 'name' => 'login_time', 'title' => 'Login Time', 'orderable' => false],
            ['data' => 'logout_time', 'name' => 'logout_time', 'title' => 'Logout Time', 'orderable' => false],
            ['data' => 'package.name', 'name' =>'package','title' => 'Package ', 'orderable' => false],
            ['data' => 'package.course.name', 'name' => 'course', 'title' => 'Course', 'orderable' => false],
            ['data' => 'package.level.name', 'name' => 'level', 'title' => 'Level', 'orderable' => false],
            // ['data' => 'package.packagetype.name', 'name' => 'packagetype', 'title' => 'Type', 'orderable' => false],
            ['data' => 'package.subject.name', 'name' => 'subject', 'title' => 'Subject', 'orderable' => false],
            ['data' => 'package.chapter.name', 'name' => 'chapter', 'title' => 'Chapter', 'orderable' => false],
            ['data' => 'package.language.name', 'name' => 'language', 'title' => 'Language', 'orderable' => false],
            ['data' => 'ip_address', 'name' => 'ip_address', 'title' => 'IP Address', 'orderable' => false],
            
           
        ])->parameters([
            'searching' => false,
            'ordering' => true,
            'processing' =>true,
            'lengthChange' => false,
            'StateSave'=>true,
            'bInfo' => false,
            'pageLength'=> 75,
        ])->orderBy(0, 'desc');

        return view('pages.reports.student_analytics.index', compact('table','courses','languages'));
    }

   

}
