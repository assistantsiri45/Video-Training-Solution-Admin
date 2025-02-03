<?php

namespace App\Http\Controllers;

use App\Exports\PackageProfessorRevenueExport;
use App\Imports\PackageProfessorRevenueImport;
use App\Models\Agent;
use App\Models\Associate;
use App\Models\Course;
use App\Models\CustomizedPackage;
use App\Models\OrderItem;
use App\Models\PackageStudyMaterial;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\SpinWheelCampaign;
use App\Models\StudyMaterialV1;
use App\Models\Subject;
use App\Models\SubjectPackage;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\Package;
use App\Models\PackageType;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function index(Builder $builder)
    {

        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user')->latest();

            if (request()->has('filter.status') && !empty(request('filter.status'))) {
                if (request('filter.status') == 'published') {
                    $query->where('is_approved', 1);
                }

                if (request('filter.status') == 'unpublished') {
                    $query->where('is_approved', 0);
                }
            }

            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->addColumn('approved_by', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    else {
                        return '-';
                    }
                })
                ->addColumn('action', 'pages.packages.action')
                ->rawColumns(['category', 'is_approved', 'action'])
                ->make(true);
        }
        $checkbox = '<div class="custom-control custom-checkbox text-center">
        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
        <label for="select_all" class="custom-control-label"></label>
    </div>';
        $html = $builder->columns([
            ['data' => 'select', 'name' => 'id', 'title' => $checkbox],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Builder $builder,$id)
    {
        $package = Package::findOrFail($id);

        $package_file = url('storage/packages/'.$package->image);

        $packageIDs = [];

        if (request()->ajax()) {
            $query = PackageVideo::query()->with('video','module')->where('package_id',$id);

            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                            $query->whereHas('video',function ($query){
                                $query->where('title','like','%'.request()->input('filter.search')."%");
                            });
                });
            }

            return DataTables::of($query)
                ->editColumn('duration',function ($query){
                    if($query->video->duration) {
                        return $query->video->formatted_duration;
                    }
                    else{
                        return '-';
                    }

                })
                ->addColumn('date_of_upload', function ($query) {
                    if($query->video->created_at){
                        return Carbon::parse($query->video->created_at)->format('d M Y');
                    }
                    else{
                        return '-';
                    }
                })
                ->addColumn('action', function($query) use ($package) {
                    return '<a href="' . url('packages/videos') . '?package_id=' . $package->id . '"><i class="fas fa-video"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $videodatatable = $builder->columns([
            ['data' => 'video.title', 'name' => 'video.title', 'title' => 'Title'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'video.description', 'name' => 'video.description', 'title' => 'Description'],
            ['data' => 'module.name', 'name' => 'module.name', 'title' => 'Module'],
            ['data' => 'video.media_id', 'name' => 'module.media_id', 'title' => 'Media Id'],
            ['data' => 'video.version_number', 'name' => 'module.version_number', 'title' => 'Version Number'],
            ['data' => 'video.applicable_for', 'name' => 'video.applicable_for', 'title' => 'Applicable For'],
            ['data' => 'date_of_upload', 'name' => 'date_of_upload', 'title' => 'Date of Upload'],
            ['data' => 'action', 'name' => 'action', 'title' => ''],
            

        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => true,
            'bInfo' => true,
            'pageLength' => 15,
        ]);



            $html = app(Builder::class)->columns([
                ['data' => 'study_material.title', 'name' => 'study_material.title', 'title' => 'Title', 'defaultContent' => ''],
                ['data' => 'study_material.course.name', 'name' => 'study_material.course.name', 'title' => 'Course', 'defaultContent' => ''],
                ['data' => 'study_material.level.name', 'name' => 'study_material.level.name', 'title' => 'Level', 'defaultContent' => ''],
                ['data' => 'study_material.subject.name', 'name' => 'study_material.subject.name', 'title' => 'Subject', 'defaultContent' => ''],
                ['data' => 'study_material.chapter.name', 'name' => 'study_material.chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
                ['data' => 'study_material.language.name', 'name' => 'study_material.language.name', 'title' => 'Language', 'defaultContent' => ''],
                ['data' => 'study_material.professor.name', 'name' => 'study_material.professor.name', 'title' => 'Professor', 'defaultContent' => ''],
                ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload', 'defaultContent' => ''],
                ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
                
//            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => true,
                'pageLength'=> 15,
                'bInfo' => true,
            ])
            ->ajax(route('packages.package-study-materials.index', ['package' => $id]))
            ->addAction(['title' => '', 'class' => 'text-right p-3', 'width' => 70]);


        $subjectPackages = app(Builder::class)->columns([
            ['data' => 'chapter_package.name','name' =>'id','title' => 'Package', 'defaultContent' => ''],
            ['data' => 'duration','name' =>'duration','title' => 'Duration', 'defaultContent' => ''],
            ['data' => 'chapter_package.description','name' =>'chapter_package.description','title' => 'Description', 'defaultContent' => ''],
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => true,
                'pageLength'=> 15,
                'bInfo' => true
            ])
            ->ajax(route('packages.subjects.index',$id))
            ->addAction(['title' => '', 'class' => 'text-right p-3', 'width' => 70])
            ->setTableId('subject-level-packages');


        $customizedPackages = app(Builder::class)->columns([
            ['data' => 'selected_package.name','name' =>'id','title' => 'Package', 'defaultContent' => ''],
            ['data' => 'duration','name' =>'duration','title' => 'Duration', 'defaultContent' => ''],
            ['data' => 'selected_package.description','name' =>'selected_package.description','title' => 'Description', 'defaultContent' => ''],
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => true,
                'pageLength'=> 15,
                'bInfo' => true
            ])
            ->ajax(route('packages.customizes.index',$id))
            ->addAction(['title' => '', 'class' => 'text-right p-3', 'width' => 70])
            ->setTableId('customized-packages');


        $study_material_count = PackageStudyMaterial::where('package_id',$id)->count();

            if($package->type == Package::TYPE_CHAPTER_LEVEL)
            {
                $packageIDs[] = $package->id;

                $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id');

                $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                $package_video_count = PackageVideo::query()->with('video','module')->where('package_id',$id)->count();

                return view('pages.packages.chapter.show',compact('package','html','videodatatable','professorNames','package_file','package_video_count','study_material_count'));

            }
            elseif($package->type == Package::TYPE_SUBJECT_LEVEL)
            {
                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');

//                foreach ($chapterPackageIDs as $chapterPackageID) {
//                    $packageIDs[] = $chapterPackageID;
//                }

                $professorIDs = PackageVideo::with('video')->whereIn('package_id', $chapterPackageIDs)->get()->pluck('video.professor_id');

                $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                $subject_package_count = SubjectPackage::where('package_id', $id)->count();

                return view('pages.packages.subject.show',compact('package','html','subjectPackages','professorNames','package_file','subject_package_count','study_material_count'));
            }
            else
                {
                $selectedPackageIDs = CustomizedPackage::where('package_id', $package->id)->get()->pluck('selected_package_id');
                $packageIDs = [];

                foreach ($selectedPackageIDs as $selectedPackageID) {

                    $package = Package::findOrFail($selectedPackageID);

                    if($package->type == Package::TYPE_CHAPTER_LEVEL){

                        $packageIDs[] = $package->id;
                    }
                    if($package->type == Package::TYPE_SUBJECT_LEVEL){

                        $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');
                        foreach ($chapterPackageIDs as $chapterPackageID) {
                            $packageIDs[] = $chapterPackageID;
                        }

                    }

                }

                $customized_package_count = CustomizedPackage::where('package_id', $id)->count();

                $professorIDs = PackageVideo::with('video')->whereIn('package_id', $packageIDs)->get()->pluck('video.professor_id');

                $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                $package = Package::findOrFail($id);


                return view('pages.packages.customize.show',compact('package','html','customizedPackages','professorNames','package_file','customized_package_count','study_material_count'));
            }




    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Builder $builder, $id)
    {
        /** @var Package $package */
        $package = Package::where('id', $id)
            ->firstOrFail();

        if (request()->ajax()) {
            $query = Video::query()->with(['professor', 'packageVideo']);

            if (request()->has('filter.professor')) {
                $query->where('professor_id', request('filter.professor'));
            }

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->editColumn('professor', function($query) {
                    if ($query->professor) {
                        if ($query->professor->image_url) {
                            return '<span><img src="' . $query->professor->image_url . '" class="rounded-circle" width="32" height="32"> ' . $query->professor->name . '</span>';
                        }

                        return '<span><img src="https://cdn.iconscout.com/icon/free/png-32/avatar-380-456332.png" class="rounded-circle" width="32" height="32"> ' . $query->professor->name . '</span>';
                    }

                    return '';
                })
                ->editColumn('duration', function($query) {
                    if ($query->formatted_duration) {
                        return '<span><i class="far fa-clock"></i> ' . $query->formatted_duration . '</span>';
                    }

                    return '';
                })
                ->editColumn('select', function($query) {
                    if ($query->packageVideo) {
                        return '<div class="custom-control custom-checkbox text-center">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-video-' . $query->id . '" name="videos[]" value="' . $query->id . '" checked>
                                    <label for="checkbox-video-' . $query->id . '" class="custom-control-label"></label>
                                </div>';
                    }

                    return '<div class="custom-control custom-checkbox text-center">
                                    <input class="custom-control-input" type="checkbox" id="checkbox-video-' . $query->id . '" name="videos[]" value="' . $query->id . '">
                                    <label for="checkbox-video-' . $query->id . '" class="custom-control-label"></label>
                                </div>';


                })
                ->rawColumns(['professor', 'duration', 'select'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'professor', 'name' => 'professor', 'title' => 'Professor'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'select', 'name' => 'select', 'title' => '']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
            'lengthMenu' => [ 50, 100, 150,-1 ],
        ]);

        return view('pages.packages.edit', compact('html', 'package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'category' => 'required',
            'name' => 'alpha_spaces',
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'language_id' => 'required',
            'price' => 'numeric|required',
            'discounted_price' => 'numeric|nullable',
            'special_price' => 'numeric|nullable',
            'professor_revenue' => 'numeric',
            'description' => 'regex:/^([^<>]*)$/',
            'expire_at' => 'regex:/^[0-9: -]+$/',
            'file' => 'mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        $package = Package::findOrFail($id);
//        $package->type = Package::TYPE_CHAPTER_LEVEL;
        $package->category = $request->input('category');
        $package->name = $request->input('name');
        $package->course_id = $request->input('course_id');
        $package->level_id = $request->input('level_id');
        $package->subject_id = $request->input('subject_id');
        $package->chapter_id = $request->input('chapter_id');
        $package->language_id = $request->input('language_id');
        $package->price = $request->input('price');
        $package->discounted_price = $request->input('discounted_price');
        $package->discounted_price_expire_at = $request->input('discounted_price_expiry_at');
        $package->special_price = $request->input('special_price');
        $package->special_price_expire_at = $request->input('special_price_expiry_at');
        $package->professor_revenue = $request->input('professor_revenue');
        $package->description = $request->input('description');
        $package->is_mini = $request->filled('is_mini') ?? false;
        $package->is_crash_course = $request->filled('is_crash_course') ?? false;
        $package->pendrive = $request->filled('pendrive') ?? false;
        $package->save();

        if ($request->image) {
            $data = $request->image;
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            $image_name= time().'.png';
            Storage::disk('public')->put("packages/$image_name", $data);
            $package->image = $image_name;
            $package->save();
        }

        $sellingAmount = $package->price;

        if ($package->is_prebook && !$package->is_prebook_package_launched) {
            $sellingAmount = $package->booking_amount;
        }

        if (! empty($package->special_price) && $package->special_price_expire_at >= Carbon::today()) {
            $sellingAmount = $package->special_price;
        }

        if (! empty($package->discounted_price) && $package->discounted_price_expire_at >= Carbon::today()){
            $sellingAmount = $package->discounted_price;
        }

        $package->selling_amount = $sellingAmount;
        $package->save();

        return redirect()->back()->with('success', 'Package successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Package  $package
     * @return mixed
     */
    public function destroy(Package $package)
    {
        $package->delete();

        return response()->json([
            'message' => 'Package deleted',
            'data' => $package
        ], 200);
    }

    /**
     * Publish the specified package
     *
     * @param integer $id
     * @return mixed
     */
    public function publish($id)
    {
        $package = Package::findOrFail($id);
        $package->is_approved = 1;
        $package->approved_user_id = Auth::id();
        $package->save();

        return response()->json(['message' => 'Package published'], 200);
    }

    public function unPublish($id)
    {
        $package = Package::findOrFail($id);
        $package->is_approved = 0;
        $package->approved_user_id = null;
        $package->save();

        return response()->json(['message' => 'Package Un-Published'], 200);
    }
    public function professorRevenue(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Package::query()->with('course', 'level', 'subject','language', 'chapter', 'user')
                                ->where('is_approved',1);
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }
            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->addColumn('professor_revenue', function($query)
                {
                    return '<div><input class="form-control" id="package-value-'.$query->id.'" type="text" name="package" value="' . $query->professor_revenue . '"></div>';

                })
//                ->addColumn('action', function($query) {
//
//                    return '<a href="'.url('packages/professor/revenues/update/'.$query->id).'">Update</a>';
//                })
                ->addColumn('action', 'pages.packages.professor_revenues.action')
                ->addColumn('total_professors', function ($package){
                    $totalProfessors = count($package->professors);

                    $professors = [];
                    foreach ($package->professors as $data)
                    {
                        $professors[] = $data->name;
                    }
                    $professors = collect($professors)->all();

                    $professorName = implode(', ', $professors);

                    return '<a href="#" class="total-professors-count" data-name="'.$professorName.'">'.$totalProfessors.'</a>';
                })
                ->rawColumns(['professor_revenue','action', 'total_professors'])
                ->make(true);
        }

            $html = $builder->columns([
                ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
                ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
                ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
                ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
                ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
                ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
                ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
                ['data' => 'professor_revenue','name' => 'professor_revenue', 'title' => 'Professor Revenue'],
                ['data' => 'total_professors','name' => 'total_professors', 'title' => 'Number of Prof.'],
                ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => false,
                'bInfo' => true,
            ]);



        return view('pages.packages.professor_revenues.index', compact('html'));
    }
    public function professorRevenueUpdate(Request $request)
    {
        $package = Package::findOrFail($request->id);
        $package->professor_revenue = $request->professor_revenue;
        $package->save();
    }
    public function draftedPackages(Builder $builder)
    {
        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user','package_type')->where('is_approved',0)->where('is_archived',0)->latest();

            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }
            if (request()->filled('filter.course')) {
                $query->where('course_id', request()->input('filter.course'));
            }
            if (request()->filled('filter.level')) {
                $query->where('level_id', request()->input('filter.level'));
            }
            if (request()->filled('filter.package_type')) {
                $query->where('package_type', request()->input('filter.package_type'));
            }
            if (request()->filled('filter.subject')) {
                $query->where('subject_id', request()->input('filter.subject'));
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->addColumn('action', 'pages.packages.drafted_packages.action')
                ->rawColumns(['category', 'is_approved', 'action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language','defaultContent' => ''],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.drafted_packages.index', compact('html','courses','subjects'));

    }

    public function publishedPackages(Builder $builder)
    {
        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user','package_type')->where('is_approved',1)->latest();


            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                if (request()->filled('filter.type') === 'is_freemium') {
                    $query->where('is_freemium', 1);
                }else{
                    $query->where('type', request()->input('filter.type'));
                }
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }
            if (request()->filled('filter.course')) {
                $query->where('course_id', request()->input('filter.course'));
            }
            if (request()->filled('filter.level')) {
                $query->where('level_id', request()->input('filter.level'));
            }
            if (request()->filled('filter.package_type')) {
                $query->where('package_type', request()->input('filter.package_type'));
            }
            if (request()->filled('filter.subject')) {
                $query->where('subject_id', request()->input('filter.subject'));
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->addColumn('approved_by', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    else {
                        return '-';
                    }
                })
                ->editColumn('price',function ($query){

                    $test = [];
                        foreach ($query->strike_prices as $data)
                        {
                            $test[] = $data;
                        }
                        $result = collect($test)->all();
                        $price = implode(', ', $result);

                        if($price != null) {
                            if ($query->is_prebook==1) {  
                                $c=count($result);
                                if($c>=1){
                                    $dip_amount=last($result);
                                    $price_x=array_pop($result);
                                    $price = implode(', ', $result);
                                    return '<del>'.$price.'</del><br>'. $dip_amount.','.$query->booking_amount."(PreBook)";
                                }
                                return '<del>'.$price.'</del>,'.$query->booking_amount."(PreBook)";
                            
                            }else{
                                return '<del>'.$price.'</del>,'.$query->selling_prices;
                                }
                            
                        }
                        else{
                            if ($query->is_prebook==1) { 
                                return $query->selling_prices.','.$query->booking_amount.'(PreBook)';
                            }
                            else
                            return $query->selling_prices;
                        }

                })
                ->addColumn('action', 'pages.packages.published_packages.action')
                ->rawColumns(['category', 'is_approved', 'action','price'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language','defaultContent' => ''],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.published_packages.index', compact('html','subjects','courses'));
    }

    public function allPackages(Builder $builder)
    {

        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user','package_type')->latest();
            $query->where('is_archived',0);
           //$query->orWhere('is_archived',NULL);
            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }
            if (request()->filled('filter.course')) {
                $query->where('course_id', request()->input('filter.course'));
            }
            if (request()->filled('filter.level')) {
                $query->where('level_id', request()->input('filter.level'));
            }
            if (request()->filled('filter.package_type')) {
                $query->where('package_type', request()->input('filter.package_type'));
            }
            if (request()->filled('filter.subject')) {
                $query->where('subject_id', request()->input('filter.subject'));
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->addColumn('is_approved', function($query) {
                    if ($query->is_approved) {
                        return '<i class="fas fa-check"></i>';
                    }
                    else {
                        return '<i class="fas fa-times"></i>';
                    }
                })
                ->addColumn('approved_by', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    else {
                        return '-';
                    }
                })
                ->editColumn('price',function ($query){

                    $test = [];
                    foreach ($query->strike_prices as $data)
                    {
                        $test[] = $data;
                    }
                    $result = collect($test)->all();
                    $price = implode(', ', $result);

                    if($price != null) {
                        if ($query->is_prebook==1) {  
                            $c=count($result);
                            if($c>=1){
                                $dip_amount=last($result);
                                $price_x=array_pop($result);
                                $price = implode(', ', $result);
                                return '<del>'.$price.'</del><br>'. $dip_amount.','.$query->booking_amount."(PreBook)";
                            }
                            return '<del>'.$price.'</del>,'.$query->booking_amount."(PreBook)";
                        
                        }else{
                            return '<del>'.$price.'</del>,'.$query->selling_prices;
                            }
                        
                    }
                    else{
                        if ($query->is_prebook==1) { 
                            return $query->selling_prices.','.$query->booking_amount.'(PreBook)';
                        }
                        else
                        return $query->selling_prices;
                    }


                })
                ->addColumn('action', function ($package) {
                    return view('pages.packages.all_packages.action', compact('package'));
                })
                ->editColumn('select', static function ($row) {
                    return '<input type="checkbox" name="packages[]"  value="'.$row->id.'"/>';
                })
                ->rawColumns(['category', 'is_approved', 'action','price','select'])

                             ->make(true);
        }
        $checkbox = '<div class="custom-control custom-checkbox text-center">
        <input id="published_select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
        <label for="published_select_all" class="custom-control-label"></label>
    </div>';
        $html = $builder->columns([
            ['data' => 'select', 'name' => 'id', 'title' => $checkbox],
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language','defaultContent' => ''],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'is_approved', 'name' => 'is_approved', 'title' => 'Is Approved'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.all_packages.index', compact('html','courses','subjects'));
    }
    public function archivedPackages(Builder $builder) {
        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();

        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user','package_type')->where('is_archived',1)->latest();


            if (request()->filled('filter.search')) {
                $query->where(function($query) {
                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                        ->orWhere(function ($query) {
                            $query->wherehas('subject', function($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                });
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }
            if (request()->filled('filter.course')) {
                $query->where('course_id', request()->input('filter.course'));
            }
            if (request()->filled('filter.level')) {
                $query->where('level_id', request()->input('filter.level'));
            }
             if (request()->filled('filter.package_type')) {
                $query->where('package_type', request()->input('filter.package_type'));
            }
            if (request()->filled('filter.subject')) {
                $query->where('subject_id', request()->input('filter.subject'));
            }

            return DataTables::of($query)
                ->editColumn('type', function($query) {
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
                ->setRowClass(function ($query) { return ($query->is_prebook && !Carbon::parse($query->prebook_launch_date)->startOfDay()->isPast()) ? 'bg-custom-table-row' : 'bg-default'; })
                ->editColumn('category', function($query) {
                    if ($query->is_mini) {
                        return 'Mini Package';
                    }

                    if ($query->is_crash_course) {
                        return 'Crash Course';
                    }

                    return 'Full Package';
                })
                ->editColumn('is_mini', function($query) {
                    if ($query->is_mini) {
                        return '<i class="fas fa-check ml-3  text-success"></i>';
                    }

                    return '<i class="fas fa-times ml-3 text-danger"></i>';
                })
                ->editColumn('course.name', function($query) {
                    if ($query->course) {
                        return $query->course->name;
                    }
                    return '-';
                })
                ->editColumn('subject.name', function($query) {
                    if ($query->subject) {
                        return $query->subject->name;
                    }
                    return '-';
                })
                ->addColumn('approved_by', function($query) {
                    if ($query->user) {
                        return $query->user->name;
                    }
                    else {
                        return '-';
                    }
                })
                ->editColumn('price',function ($query){

                    $test = [];
                    foreach ($query->strike_prices as $data)
                    {
                        $test[] = $data;
                    }
                    $result = collect($test)->all();
                    $price = implode(', ', $result);

                    if($price != null) {
                        if ($query->is_prebook==1) {  
                            $c=count($result);
                            if($c>=1){
                                $dip_amount=last($result);
                                $price_x=array_pop($result);
                                $price = implode(', ', $result);
                                return '<del>'.$price.'</del><br>'. $dip_amount.','.$query->booking_amount."(PreBook)";
                            }
                            return '<del>'.$price.'</del>,'.$query->booking_amount."(PreBook)";
                        
                        }else{
                            return '<del>'.$price.'</del>,'.$query->selling_prices;
                            }
                        
                    }
                    else{
                        if ($query->is_prebook==1) { 
                            return $query->selling_prices.','.$query->booking_amount.'(PreBook)';
                        }
                        else
                        return $query->selling_prices;
                    }

                })
                ->addColumn('action', function($package) {

                    return view('pages.packages.archived_packages.action', compact('package'));
                })
                ->rawColumns(['category', 'is_approved', 'action','price'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => '#'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language','defaultContent' => ''],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'width'=>'200px']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);

        return view('pages.packages.archived_packages.index', compact('html','subjects','courses'));
    }

    public function createVideosPackages(Request $request, Builder $builder, $id)
    {
        $package = Package::where('id',$id)->first();
        $videoIDs = PackageVideo::where('package_id', $id)->pluck('video_id');
        $moduleIDs = PackageVideo::where('package_id', $id)->pluck('module_id');
        $types = PackageType::where('is_enabled',true)->get();

        if (request()->ajax()) {
            $query = Video::query()
                ->with('course', 'level', 'subject', 'chapter', 'module', 'language', 'professor','package_type')
                ->where('is_archived', 0);
            //    ->where('chapter_id',$package->chapter_id);
            if(!request()->filled('filter.search')&& !request()->filled('filter.course') && !request()->filled('filter.level') && !request()->filled('filter.subject') && !request()->filled('filter.chapter')&&
            !request()->filled('filter.module') && !request()->filled('filter.language') && !request()->filled('filter.professor') && !request()->filled('filter.type') && !request()->filled('filter.version_no') && !request()->filled('filter.media_id')){
                $query->where('chapter_id',$package->chapter_id);
            }
            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query->where('title','like','%'. request()->input('filter.search') .'%')
                        ->orWhere(function ($query) {
                            $query->where(function ($query) {
                                $query->whereHas('course', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('level', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('subject', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('chapter', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('module', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                });
                            });
                        });
                });
            }

            if (request()->filled('filter.course')) {
                $query->where(function ($query) {
                    $query->where('course_id', request()->input('filter.course'));
                });
            }

            if (request()->filled('filter.level')) {
                $query->where(function ($query) {
                    $query->where('level_id', request()->input('filter.level'));
                });
            }

            if (request()->filled('filter.subject')) {
                $query->where(function ($query) {
                    $query->where('subject_id', request()->input('filter.subject'));
                });
            }

            if (request()->filled('filter.chapter')) {
                $query->where(function ($query) {
                    $query->where('chapter_id', request()->input('filter.chapter'));
                });
            }

            if (request()->filled('filter.module')) {
                $query->where(function ($query) {
                    $query->where('module_id', request()->input('filter.module'));
                });
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            if (request()->filled('filter.professor')) {
                $query->where('professor_id', request()->input('filter.professor'));
            }

            if (request()->filled('filter.type')) {
                $query->where('package_type_id', request()->input('filter.type'));
            }

            if (request()->filled('filter.version_no')) {
                $query->where('version_number', request()->input('filter.version_no'));
            }
            if (request()->filled('filter.media_id')) {
                $query->where('media_id', request()->input('filter.media_id'));
            }

            return DataTables::of($query)
                    ->editColumn('duration',function ($query)
                    {
                        if($query->duration){
                            return $query->formatted_duration;
                        }
                    })
                    ->editColumn('created_at', function ($query) {
                        return Carbon::parse($query->created_at)->format('d M Y');
                    })
                    ->rawColumns(['id'])
                    ->make(true);
        }

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="select_all" class="custom-control-label"></label>
                    </div>';

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'module.name', 'name' => 'module.name', 'title' => 'Module', 'defaultContent' => ''],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language', 'defaultContent' => ''],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor', 'defaultContent' => ''],
            ['data' => 'version_number', 'name' => 'version_number', 'title' => 'Version Number'],
            ['data' => 'applicable_for', 'name' => 'applicable_for', 'title' => 'Applicable For'],
            ['data' => 'media_id', 'name' => 'media_id', 'title' => 'Media ID'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload'],
            ['data' => ['id' => 'id'], 'name' => 'select', 'title' => $checkbox, 'render' => 'renderVideosCheckbox(data)']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => true,
            'bInfo' => true,
            'pageLength' => 15
        ])
        ->setTableId('tbl-videos');

        return view('pages.packages.chapter.videos.index', compact('html','package', 'videoIDs', 'moduleIDs','types'));
    }

    public function createChapterPackages(Request $request, Builder $builder, $id)
    {
        $package = Package::where('id',$id)->first();
        $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');
        $types = PackageType::where('is_enabled',true)->get();

        if (request()->ajax()) {
            $query = Package::query()->with('course', 'level', 'subject', 'chapter', 'language','package_type')->where('type',Package::TYPE_CHAPTER_LEVEL);
            //    ->where('subject_id', $package->subject_id);
            if(!request()->filled('filter.search')&& !request()->filled('filter.course') && !request()->filled('filter.level') && !request()->filled('filter.subject') && !request()->filled('filter.chapter')
            && !request()->filled('filter.language') && !request()->filled('filter.professor') && !request()->filled('filter.type') ){
                $query->where('subject_id', $package->subject_id);
            }
            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query->where('name','like','%'. request()->input('filter.search') .'%')
                        ->orWhere(function ($query) {
                            $query->where(function ($query) {
                                $query->whereHas('course', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('level', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('subject', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('chapter', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                });
                            });
                        });
                });
            }

            if (request()->filled('filter.course')) {
                $query->where(function ($query) {
                    $query->where('course_id', request()->input('filter.course'));
                });
            }

            if (request()->filled('filter.level')) {
                $query->where(function ($query) {
                    $query->where('level_id', request()->input('filter.level'));
                });
            }

            if (request()->filled('filter.subject')) {
                $query->where(function ($query) {
                    $query->where('subject_id', request()->input('filter.subject'));
                });
            }

            if (request()->filled('filter.chapter')) {
                $query->where(function ($query) {
                    $query->where('chapter_id', request()->input('filter.chapter'));
                });
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            if (request()->filled('filter.professor')) {
                $query->whereHas('chapterVideos', function ($query) {
                    $query->where('professor_id', request()->input('filter.professor'));
                });
            }
            if (request()->filled('filter.type')) {
                $query->where('package_type', request()->input('filter.type'));
            }

            return DataTables::of($query)
                ->editColumn('duration',function ($query) {
                    $query->total_duration;
                    
                    // if (!@$query->total_duration || ! @$query->bonus_duration ) {
                    //     return null;
                    // }
                  //  
            if(@$query->total_duration || @$query->bonus_duration ) {
                    $durationInSeconds = @$query->total_duration + @$query->bonus_duration ;
                    $h = floor($durationInSeconds / 3600);
                    $resetSeconds = $durationInSeconds - $h * 3600;
                    $m = floor($resetSeconds / 60);
                    $resetSeconds = $resetSeconds - $m * 60;
                    $s = round($resetSeconds, 3);
                    $h = str_pad($h, 2, '0', STR_PAD_LEFT);
                    $m = str_pad($m, 2, '0', STR_PAD_LEFT);
                    $s = str_pad($s, 2, '0', STR_PAD_LEFT);
            
                    
                    $duration[] = $h;
                    
            
                    $duration[] = $m;
            
                    $duration[] = $s;
            
                    return implode(':', $duration);
                    } else {
                        return '-';
                    }
                })
                ->addColumn('professors', function ($query) {
                    $packageID = $query->id;
                    $professorIDs = PackageVideo::with('video')
                        ->where('package_id', $packageID)
                        ->get()
                        ->pluck('video.professor_id')
                        ->unique()
                        ->values();

                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                    return implode(', ', $professorNames);
                })
                ->make(true);
        }

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="select_all" class="custom-control-label"></label>
                    </div>';

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Package'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Total Duration'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => '-'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language', 'defaultContent' => ''],
            ['data' => 'professors', 'name' => 'professors', 'title' => 'Professors'],
            ['data' => 'id', 'name' => 'id', 'title' => $checkbox   , 'render' => 'renderCheckbox(data)']
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => true,
                'bInfo' => true,
                'pageLength' => 15
            ])
            ->setTableId('tbl-packages');

        return view('pages.packages.subject.packages.index', compact('html','package', 'chapterPackageIDs','types'));
    }

    public function createAllPackages(Request $request, Builder $builder, $id)
    {
        $package = Package::where('id',$id)->first();
        $selectedPackageIDs = CustomizedPackage::query()->where('package_id', $id)->pluck('selected_package_id');
        $types = PackageType::where('is_enabled',true)->get();

        if (request()->ajax()) {
            $query = Package::query()->with('course', 'level', 'subject', 'chapter', 'language','package_type')->whereIn('type',[Package::TYPE_SUBJECT_LEVEL,Package::TYPE_CHAPTER_LEVEL])
                ->where('course_id', $package->course_id)
                ->where('level_id', $package->level_id);

            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query->where('name','like','%'. request()->input('filter.search') .'%')
                        ->orWhere(function ($query) {
                            $query->where(function ($query) {
                                $query->whereHas('course', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('level', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('subject', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('chapter', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                });
                            });
                        });
                });
            }

            if (request()->filled('filter.course')) {
                $query->where(function ($query) {
                    $query->where('course_id', request()->input('filter.course'));
                });
            }

            if (request()->filled('filter.level')) {
                $query->where(function ($query) {
                    $query->where('level_id', request()->input('filter.level'));
                });
            }

            if (request()->filled('filter.subject')) {
                $query->where(function ($query) {
                    $query->where('subject_id', request()->input('filter.subject'));
                });
            }

            if (request()->filled('filter.chapter')) {
                $query->where(function ($query) {
                    $query->where('chapter_id', request()->input('filter.chapter'));
                });
            }

            if (request()->filled('filter.type')) {
                info (request()->input('filter.type'));

                $query->where(function ($query) {
                    $query->where('type', request()->input('filter.type'));
                });
            }


            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            if (request()->filled('filter.professor')) {
                $query->wherehas('chapter.video.professor', function($query) {
                    $query->where('id', request()->input('filter.professor'));
                });
            }
            if (request()->filled('filter.package_type')) {
                $query->where('package_type', request()->input('filter.package_type'));
            }

            return DataTables::of($query)
                ->editColumn('type', function ($query) {
                    if ($query->type == 1) {
                        return 'Chapter Level';
                    }

                    if ($query->type == 2) {
                        return 'Subject Level';
                    }
                })
                ->editColumn('duration',function ($query) {
                    if($query->total_duration_formatted) {
                        return $query->total_duration_formatted;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('professors', function ($query) {
                    $packageID = $query->id;
                    $package = Package::find($packageID);
                    $packageIDs = [];

                    if ($package->type == 1) {
                        $packageIDs[] = $package->id;
                    }

                    if ($package->type == 2) {
                        $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->pluck('chapter_package_id');

                        foreach ($chapterPackageIDs as $chapterPackageID) {
                            $packageIDs[] = $chapterPackageID;
                        }
                    }

                    $professorIDs = PackageVideo::with('video')
                        ->whereIn('package_id', $packageIDs)
                        ->get()
                        ->pluck('video.professor_id')
                        ->unique()
                        ->values();

                    $professorNames = Professor::whereIn('id', $professorIDs)->pluck('name')->toArray();

                    return implode(', ', $professorNames);
                })
                ->make(true);
        }

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="select_all" class="custom-control-label"></label>
                    </div>';

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Package'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Total Duration'],
            ['data' => 'price', 'name' => 'price', 'title' => 'Price'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language', 'defaultContent' => ''],
            ['data' => 'professors', 'name' => 'professors', 'title' => 'Professors'],
            ['data' => 'id', 'name' => 'id', 'title' => $checkbox   , 'render' => 'renderCheckbox(data)']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => true,
            'bInfo' => true,
            'pageLength' => 15
        ])
            ->setTableId('table-customize');

        return view('pages.packages.customize.packages.index', compact('html','package', 'selectedPackageIDs','types'));
    }

    public function markAsPublished($id)
    {
        /** @var Package $package */
        $package = Package::findOrFail($id);
        $package->markAsApproved();

        if ($package->is_approved) {
            return back()->with('success', 'Package successfully published');
        } else {
            return back()->with('success', 'Package successfully un-published');
        }
    }

    public function togglePrebook($id)
    {
        /** @var Package $package */
        $package = Package::findOrFail($id);
        $package->togglePrebook();

        if ($package->is_prebook) {
            return back()->with('success', 'Prebook enabled');
        } else {
            return back()->with('success', 'Prebook disabled');
        }
    }

    public function addOrEditStudyMaterial(Request $request, Builder $builder, $id)
    {
        $package = Package::findOrFail($id);
        $studyMaterialsIDs = PackageStudyMaterial::where('package_id', $package->id)->get()->pluck('study_material_id');
        $package_types = PackageType::where('is_enabled',true)->get();

        if (request()->ajax()) {
            $query = StudyMaterialV1::query()->with('course','level','subject','chapter','language','professor','package_type');

            if ($package->type == Package::TYPE_CUSTOMIZED) {
                $selectedPackageIDs = CustomizedPackage::query()
                    ->where('package_id', $package->id)
                    ->pluck('selected_package_id');

                $subjectIDs = Package::query()
                    ->whereIn('id', $selectedPackageIDs)
                    ->pluck('subject_id');

                $query->whereIn('subject_id', $subjectIDs);
            } else {
                $query->where('subject_id', $package->subject_id);
            }

            $dataTable = \Yajra\DataTables\Facades\DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where('title','like','%'. request('filter.search') . '%');
                    }

                    if (request()->filled('filter.type')) {
                        $query->where('type', request('filter.type'));
                    }

                    if (request()->filled('filter.language')) {
                        $query->where('language_id', request('filter.language'));
                    }

                    if (request()->filled('filter.professor')) {
                        $query->where('professor_id', request('filter.professor'));
                    }

                    if (request()->filled('filter.package_type')) {
                        $query->where('package_type_id', request('filter.package_type'));
                    }
                })
                ->editColumn('type', function($query) {
                    if ($query->type == StudyMaterialV1::STUDY_MATERIALS) {
                        return 'STUDY MATERIAL';
                    } else if ($query->type == StudyMaterialV1::STUDY_PLAN) {
                        return 'STUDY PLAN';
                    } else if ($query->type == StudyMaterialV1::TEST_PAPER) {
                        return 'TEST PAPER';
                    } else {
                        return '';
                    }
                })
                ->editColumn('file_name', function($query) {
                    if ($query->file_name) {
                        return '<a target="_blank" href="'.$query->file.'">'. substr($query->file_name,10).'</a>';
                    } else {
                        return '';
                    }
                })
                ->editColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->rawColumns(['type','file_name']);

            return $dataTable->make(true);
        }

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select-all-study-materials" class="custom-control-input select-all-study-materials" name="select_all_study_materials" type="checkbox">
                        <label for="select-all-study-materials" class="custom-control-label"></label>
                    </div>';

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title', 'defaultContent' => ''],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'study_material.level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language', 'defaultContent' => ''],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor', 'defaultContent' => ''],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload', 'defaultContent' => ''],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
            ['data' => 'id', 'name' => 'select', 'title' => $checkbox, 'render' => 'renderStudyMaterialsCheckbox(data)']
            ])->parameters([
                'searching' => false,
                'ordering' => false,
                'lengthChange' => true,
                'pageLength'=> 15,
                'bInfo' => true
            ])
            ->setTableId('table-study-materials');

        return view('pages.packages.study_materials.show',compact('html','package', 'studyMaterialsIDs','package_types'));

    }

    public function addStudyMaterials($id)
    {
        $studyMaterialIDs = request()->input('study_material_ids');

        $packageStudyMaterials = PackageStudyMaterial::query()->where('package_id', $id)->get();

        if ($packageStudyMaterials) {
            foreach ($packageStudyMaterials as $packageStudyMaterial) {
                $packageStudyMaterial->delete();
            }
        }

        $studyMaterialIDs = json_decode($studyMaterialIDs);

        if ($studyMaterialIDs) {
            foreach ($studyMaterialIDs as $studyMaterialID) {
                $packageStudyMaterial = new PackageStudyMaterial();
                $packageStudyMaterial->package_id = $id;
                $packageStudyMaterial->study_material_id = $studyMaterialID;
                $packageStudyMaterial->save();
            }
        }

        return redirect(url('packages/' . $id))->with('success', 'Study Materials successfully updated');
    }

    public function videos()
    {
        $package = Package::query()->findOrFail(request()->input('package_id'));

        $packageIDs = [];

        if ($package->type == Package::TYPE_CHAPTER_LEVEL) {
            $packageIDs[] = $package->id;
        }

        if ($package->type == Package::TYPE_SUBJECT_LEVEL) {
            $packageIDs = SubjectPackage::query()->where('package_id', $package->id)->pluck('chapter_package_id');
        }

        $videos = [];

        $packages = Package::query()
            ->whereIn('id', $packageIDs)
            ->get();

        foreach ($packages as $package) {
            $packageVideos = Video::query()->whereHas('packages', function ($query) use ($package) {
                $query->where('package_id', $package->id);
            })->get();

            $videos[$package->name] = $packageVideos;
        }

        return view('pages.packages.videos.index', ['packageVideos' => $videos]);
    }

    public function export()
    {
        $search = request()->input('export_search') ?? '';

        return Excel::download(new PackageProfessorRevenueExport($search), 'ORDERS_' . time() . '.csv');
    }

    public function import()
    {
        $filePath = null;

        if (request()->hasFile('file')) {
            $file = request()->file('file');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public/imports', $fileName);
        }

//        $importLog = ImportLog::create();
//
        if ($filePath) {
            Excel::import(new PackageProfessorRevenueImport, $filePath);
            Storage::delete($filePath);
        }
//
//        $importLog = ImportLog::find($importLog->id);
//
        return back()->with('success', 'Successfully imported');
    }

    public function updateReviews()
    {
        return '0';

        $packages = Package::all();
        foreach ($packages as $package){
            $orderItems = OrderItem::where('package_id', $package->id)->where('rating', '!=', null)->get();
            if(count($orderItems) >0){
                $orderItemTotalRating = $orderItems->sum('rating');
                $packageRating = $orderItemTotalRating / count($orderItems);

                $package->rating = $packageRating;
                $package->number_of_reviews = $packageRating;
                $package->save();

                $reviewCount = OrderItem::where('package_id', $package->id)->where('review', '!=', null)->count();
                $package->number_of_reviews = $reviewCount;
                $package->save();
            }
        }

        return '1';
    }

    public function updateSellingAmount()
    {
        $packages = Package::all();

        foreach ($packages as $package){

            $sellingAmount = $package->price;

            if ($package->is_prebook && !$package->is_prebook_package_launched) {
                $sellingAmount = $package->booking_amount;
            }

            if (! empty($package->special_price) && $package->special_price_expire_at >= Carbon::today()) {
                $sellingAmount = $package->special_price;
            }

            if (! empty($package->discounted_price) && $package->discounted_price_expire_at >= Carbon::today()){
                $sellingAmount = $package->discounted_price;
            }

            $package->selling_amount = $sellingAmount;
            $package->save();
        }
    }

    public function addToArchive($id)
    {
        $package = Package::query()
            ->findOrFail($id);

        $package->is_archived = true;
        $package->save();

        return back()->with('success', 'Package successfully archived');
    }

    public function removeFromArchive($id)
    {
        $package = Package::query()
            ->findOrFail($id);

        $package->is_archived = false;
        $package->save();

        return back()->with('success', 'Package successfully removed from archives');
    }
    public function archeieveSelectedpackage(Request $request)
    {
       
      $selectedVideoIds = $request->input('selectedVideoIds');
      
        foreach ($selectedVideoIds as $selectedVideoId){
            $video = Package::find($selectedVideoId);
            $video->is_archived = true;
            $video->save();
        }

        return response()->json( 'Video successfully archived', 200);
    }
    public function getvideodetails($id){
        $video = Video::where('id',$id)->first();
$a=route('videos.show', $video->id) ;
        return '<a class="popup-iframe dropdown-item" href="'.$a.'">
        <i class="fas fa-play"></i>'.$video->title.'
    </a><span><a href="javascript: void(0)"    id="unlink_video"><i class="fas fa-trash ml-3"></i></a></span>';
      // echo '<p>'.$video->title.'<span>&nbsp;&nbsp;<i class="fas fa-play"></i></span>&nbsp;&nbsp;</p>';
       
    }
    public function UnlinkVideo(Request $request){

          $id=$request->id;     
       
         $package_id=$request->package_id;
         $data['video_id']=0;
         $free_resource = Package::where('id',$package_id)->update($data);
         return response()->json(true, 200);
     }
}
