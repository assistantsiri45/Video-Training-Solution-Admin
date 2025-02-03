<?php

namespace App\Http\Controllers;

use App\Exports\PackageReportExport;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Course;
use App\Models\StudyMaterialV1;
use App\Models\ProfessorRevenue;
use App\Models\PackageVideo;
use App\Models\SubjectPackage;
use App\Models\Professor;

class PackageReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Package::query();
//            $query->wherehas('orderItems');
            $query->with('course', 'level','package_type');
//            $query->latest();

            if (request()->filled('published')) {
                $isPublished = request()->input('published') == 'true';
                $query->where('is_approved', $isPublished);
            }

            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.sales')) {
                        $query->has('orderItems', request()->input('filter.sales'));
                    }

                    if (request()->filled('filter.amount')) {
                        $query->where('price', '=', request('filter.amount'));
                    }
                    if (!empty(request('filter.package'))) {
                        $query->where('name', 'like','%'. request('filter.package').'%');
                    }
                    if (!empty(request('filter.course'))) {
                        $query->where('course_id', '=', request('filter.course'));
                    }
                    if (!empty(request('filter.level'))) {
                        $query->where('level_id', '=', request('filter.level'));
                    }
                    if (request()->filled('filter.package_type')) {
                        $query->where('package_type', request()->input('filter.package_type'));
                    }
                    if (!empty(request('filter.rating'))) {
                        $query->wherehas('chapter.video.professor', function($query) {
                            $query->where('rating', request('filter.rating'));
                        });
                    }

                    if (!empty(request('filter.professor'))) {
                        $query->wherehas('packageVideos.video.professor', function($query) {
                            $query->where('id', request('filter.professor'));
                        });
                    }
                })
                ->addColumn('type', function($query) {
                    if ($query->type == Package::TYPE_CHAPTER_LEVEL) {
                        return Package::TYPE_CHAPTER_LEVEL_VALUE;
                    }

                    if ($query->type == Package::TYPE_SUBJECT_LEVEL) {
                        return Package::TYPE_SUBJECT_LEVEL_VALUE;
                    }

                    if ($query->type == Package::TYPE_CUSTOMIZED) {
                        return Package::TYPE_CUSTOMIZED_VALUE;
                    }
                })
                ->addColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })

                ->addColumn('sales', function($query) {
                    return $query->orderItems->count() ?? '0';
                })
                ->editColumn('expire_at', function ($query) {
                    if ($query->expire_at) {
                        return $query->expire_at->toFormattedDateString();
                    }
                })
                ->editColumn('duration', function ($query) {
                    if ($query->duration) {
                        return $query->duration . ' times';
                    }
                })
                ->addColumn('action', 'pages.reports.packages.action')
                 ->addColumn('chapter.video.professor.name',function($query){
                     // if($query->chapter){
                    //     if($query->chapter->video){
                    //          return $query->chapter->video->professor->name;
                    //     }
                    // }                        
                    //  return '-';
                    if ($query->type == Package::TYPE_SUBJECT_LEVEL) {
                        $chapterPackageIDs = SubjectPackage::where('package_id', $query->id)->get()->pluck('chapter_package_id');

                        foreach ($chapterPackageIDs as $chapterPackageID) {
                            $packageIDs[] = $chapterPackageID;
                        }
                    } else {
                        $packageIDs[] = $query->id;
                    }
                    $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id');
                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();
                    if(count($professorNames)>0)
                    return implode(', ', $professorNames);
                    else
                    return '-';               
                 })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'orderable' => true],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type','orderable' => true],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category','orderable' => false],
            ['data' => 'course.name', 'name' => 'course', 'title' => 'Course','orderable' => false],
            ['data' => 'level.name', 'name' => 'level', 'title' => 'Level','orderable' => false],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'chapter.video.professor.name', 'name' => 'professor', 'title' => 'Professors', 'orderable' => false],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price','orderable' => true],
            ['data' => 'sales', 'name' => 'sales', 'title' => 'No. of Sales','orderable' => true],
            ['data' => 'expire_at', 'name' => 'expire_at', 'title' => 'Expiry Date','orderable' => false],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration','orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '','orderable' => false]
        ])->parameters([
            'searching' => false,
            'lengthChange' => false,
            'bInfo' => false
        ]);

        $totalPackageCount = Package::count();
        $activePackageCount = Package::where('is_approved', true)->count();
        $courses = Course::where('is_enabled',true)->orderBy('name')->get();
        
        return view('pages.reports.packages.index', compact('html', 'totalPackageCount', 'activePackageCount','courses'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function show($id, Builder $builder)
    {
        if (request()->ajax()) {
            $query = Order::query();
            $query->whereHas('orderItems', function($query) use($id) {
                $query->where('package_id', $id);
            });

            return DataTables::of($query)
                ->addColumn('student', function($query) {
                    return $query->student->user->name ?? null;
                })
                ->addColumn('associate', function($query) {
                    return $query->associate->user->name ?? null;
                })
                ->addColumn('payment_status', function($query) {
                    if ($query->status == Order::PAYMENT_STATUS_SUCCESS) {
                        return '<span class="badge bg-success">Success</span>';
                    }

                    if ($query->status == Order::PAYMENT_STATUS_FAILED) {
                        return '<span class="badge bg-danger">Failed</span>';
                    }

                    if ($query->status == Order::PAYMENT_STATUS_RETURN) {
                        return '<span class="badge bg-yellow">Return</span>';
                    }
                })
                ->addColumn('status', function($query) {
                    if ($query->status == Order::STATUS_RECEIVED) {
                        return '<span class="badge bg-secondary">Received</span>';
                    }

                    if ($query->status == Order::STATUS_PROCESSED) {
                        return '<span class="badge bg-primary">Processed</span>';
                    }

                    if ($query->status == Order::STATUS_SHIPPED) {
                        return '<span class="badge bg-info">Shipped</span>';
                    }

                    if ($query->status == Order::STATUS_DELIVERED) {
                        return '<span class="badge bg-success">Delivered</span>';
                    }

                    if ($query->status == Order::STATUS_PENDING) {
                        return '<span class="badge bg-warning">Pending</span>';
                    }
                })
                ->addColumn('created_at', function($query) {
                    return $query->created_at->toDayDateTimeString();
                })
                ->rawColumns(['payment_status', 'status'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'student', 'name' => 'student', 'title' => 'Student'],
            ['data' => 'associate', 'name' => 'associate', 'title' => 'Associate'],
            ['data' => 'net_amount', 'name' => 'net_amount', 'title' => 'Net Amount'],
            ['data' => 'coupon_amount', 'name' => 'coupon_amount', 'title' => 'Coupon Amount'],
            ['data' => 'reward_amount', 'name' => 'reward_amount', 'title' => 'Reward Amount'],
            ['data' => 'transaction_id', 'name' => 'transaction_id', 'title' => 'Transaction ID'],
            ['data' => 'payment_status', 'name' => 'payment_status', 'title' => 'Payment Status'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Order Status'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created At']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
        ]);

        $package = Package::findOrFail($id);
        $packageSales = Order::whereHas('orderItems', function($query) use($id) {
            $query->where('package_id', $id);
        })->count();
        $packageSalesAmount = Order::whereHas('orderItems', function($query) use($id) {
            $query->where('package_id', $id);
        })->sum('net_amount');

        return view('pages.reports.packages.show', compact('html', 'package', 'packageSales', 'packageSalesAmount'));
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
        $package = Package::findOrFail($id);
        $package->expire_at = $request->input('expire_at');
        $package->duration = $request->input('duration');
        $package->save();

        return redirect()->back()->with('success', 'Package successfully updated');
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

    public function export()
    {
        $sales = request()->input('export_sales') ?? '';
        $amount = request()->input('export_amount') ?? '';
        $rating = request()->input('export_rating') ?? '';
        $professor = request()->input('export_professor') ?? '';

        return Excel::download(new PackageReportExport($sales, $amount, $rating, $professor), 'PACKAGES_' . time() . '.csv');
    }
}
