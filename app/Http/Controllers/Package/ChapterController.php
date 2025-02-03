<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use App\Models\CustomizedPackage;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\PackageStudyMaterial;
use App\Models\PackageVideo;
use App\Models\SubjectPackage;
use App\Models\UserNotification;
use App\PackageFeature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Video;
use App\Models\Package;
use App\Models\PackageType;
use App\Models\LevelType;
use App\Models\Course;
use App\Models\Subject;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function create(Builder $builder)
    {
        //$types = PackageType::where('is_enabled',true)->get();

        if (request()->ajax()) {
            $query = Video::query()->with('professor');
            $query->ofPublished();

            if (request()->has('filter.chapter') && !empty(request('filter.chapter'))) {
                $query->ofChapter(request('filter.chapter'));
            }

            if (request()->has('filter.packages') && !empty(request('filter.packages'))) {
                $query->whereHas('packageVideos', function($query) {
                    $query->whereIn('package_id', request('filter.packages'));
                });
            }

            if (request()->has('filter.professor') && !empty(request('filter.professor'))) {
                $query->where('professor_id', request('filter.professor'));
            }

            return DataTables::of($query)
                ->editColumn('professor', function($query) {
                    if ($query->professor) {
                        if ($query->professor->image_url) {
                            return '<span><img src="' . $query->professor->image . '" class="rounded-circle" width="32" height="32"> ' . $query->professor->name . '</span>';
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
                ->addColumn('select', 'pages.packages.chapter.select')
                ->rawColumns(['professor', 'duration', 'select'])
                ->toJson();
        }

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="select_all" class="custom-control-label"></label>
                    </div>';

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'professor', 'name' => 'professor', 'title' => 'Professor'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'select', 'name' => 'select', 'title' => $checkbox]
        ])->parameters([
            'searching' => false,
            'ordering' => false,
        ]);

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select-all-study-materials" class="custom-control-input select-all-study-materials" name="select_all_study_materials" type="checkbox">
                        <label for="select-all-study-materials" class="custom-control-label"></label>
                    </div>';

        $tableStudyMaterials = app(Builder::class)->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
            ['data' => 'id', 'name' => 'select', 'title' => $checkbox, 'render' => 'renderStudyMaterialsCheckbox(data)']
        ])
            ->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false
            ])
            ->ajax(route('tables.study-materials'))
            ->setTableId('table-study-materials');

        return view('pages.packages.chapter.create', compact('html', 'tableStudyMaterials'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'category' => 'required',
            'name' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'language_id' => 'required',
          //  'package_type' => 'required',
            'price' => 'required|numeric',
            'discounted_price' => 'numeric|nullable',
            'special_price' => 'numeric|nullable',
            'professor_revenue' => 'numeric|required',
            'expiry_name'  => 'required',
            'image' => 'required|image|max:150|dimensions:min_width=400,min_height=200,max_width=400,max_height=200'
        ]);

        $attempt = $request->input('attempt');
        $attempt = '01-' . $attempt;
        $attempt = Carbon::parse($attempt)->startOfMonth();

        $package = new Package();
        $package->type = Package::TYPE_CHAPTER_LEVEL;
        $package->category = $request->input('category');
        $package->name = $request->input('name');
        $package->course_id = $request->input('course_id');
        $package->level_id = $request->input('level_id');
        $package->subject_id = $request->input('subject_id');
        $package->chapter_id = $request->input('chapter_id');
        $package->language_id = $request->input('language_id');
        $package->package_type = $request->input('package_type');
        $package->price = $request->input('price');
        $package->discounted_price = $request->input('discounted_price');
        $package->discounted_price_expire_at = $request->input('discounted_price_expiry_at');
        $package->professor_revenue = $request->input('professor_revenue');
        $package->special_price = $request->input('special_price');
        $package->special_price_expire_at = $request->input('special_price_expiry_at');
        $package->special_price_active_from = $request->input('special_price_active_from');
        $package->attempt = $attempt;
        $package->duration = $request->input('duration');
        $package->expiry_type=$request->input('expiry_name');
        $package->expiry_month=$request->input('expiry_month');
        $package->expire_at = $request->input('expiry_date');
        $package->alt = $request->input('alt');
        $package->study_material_price = $request->input('study_material_price');
        $package->description = $request->input('description');
        $package->is_mini = $request->input('type') == Package::TYPE_MINI;
        $package->is_crash_course = $request->input('type') == Package::TYPE_CRASH;
        $package->pendrive = $request->input('pendrive') == 'on';
        $package->g_drive = $request->input('g-drive') == 'on';
        $package->is_cseet = $request->input('cseet')?? 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/packages', $imageName);
            $package->image = $imageName;
        }

        $package->save();

        $features = $request->features;
        if ($features != null) {
            foreach ($features as $feature){
                if ($feature != null) {
                    $packageFeature = new PackageFeature();
                    $packageFeature->package_id = $package->id;
                    $packageFeature->feature = $feature;
                    $packageFeature->save();
                }
            }
        }

        $sellingAmount = $package->price;

        if ($package->is_prebook && !$package->is_prebook_package_launched) {
            $sellingAmount = $package->booking_amount;
        }

        if (! empty($package->special_price) && \Illuminate\Support\Carbon::today()>= $package->special_price_active_from && $package->special_price_expire_at >= \Illuminate\Support\Carbon::today()) {
            $sellingAmount = $package->special_price;
        }

        if (! empty($package->discounted_price) && $package->discounted_price_expire_at >= Carbon::today()){
            $sellingAmount = $package->discounted_price;
        }

        $package->selling_amount = $sellingAmount;
        $package->save();

        return redirect(url('packages/' . $package->id))->with('success', 'Package successfully created');
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
     * @param Builder $builder
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Builder $builder, $id)
    {
        $courses = Course::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        //$types = PackageType::where('is_enabled',true)->get();
        $checkbox22 = '<div class="custom-control text-center">
       
        <label for="published_select_all" class="custom-control-label"></label>
    </div>';
       
       $package = Package::where('id',$id)->first();
       $types = LevelType::with(['packagetype'=> function($types){
                                $types->where('is_enabled', TRUE);
                            }])
                        ->where('level_id',$package->level_id)
                        ->get();
        $video_id=$package->video_id;
        //echo "hi";exit;
        if( @$video_id!=0){ 
        $video_details= Video::findOrFail($video_id);
        }else{
            $video_details='';
        }
        $packageFeatures = PackageFeature::where('package_id', $package->id)->get();
        if (request()->ajax()) {
            $query = Video::with('course', 'level', 'subject', 'chapter', 'professor', 'user', 'module','package_type')
            ->where('is_published', true)
            ->where('is_archived', false);
            if (request()->filled('filter.search')) {
                $query->where('title', 'like', '%' . request()->input('filter.search') . '%');
                $query->orWhere('description', 'like', '%' . request()->input('filter.search') . '%');
            }
    if (request()->filled('filter.module')) {
                $query->where('module_id', request()->input('filter.module'));
            }

        if (request()->has('filter.chapter') && !empty(request('filter.chapter'))) {
            $query->ofChapter(request('filter.chapter'));
        }

        if (request()->has('filter.professor') && !empty(request('filter.professor'))) {
            $query->ofProfessor(request('filter.professor'));
        }
        if (request()->filled('filter.course')) {
            $query->where('course_id', request()->input('filter.course'));
        }
        if (request()->filled('filter.level')) {
            $query->where('level_id', request()->input('filter.level'));
        }
        if (request()->filled('filter.package_type')) {
            $query->where('package_type_id', request()->input('filter.package_type'));
        }
        if (request()->filled('filter.subject')) {
            $query->where('subject_id', request()->input('filter.subject'));
        }

        return DataTables::of($query)
            ->editColumn('chapter_name', function ($query) {
                if ($query->chapter) {
                    return $query->chapter->name;
                }
                else {
                    return '-';
                }
            })
            ->addColumn('professor_name', function ($query) {
                if ($query->professor) {
                    return $query->professor->name;
                }
                else {
                    return '-';
                }
            })
            ->editColumn('updated_at', function ($query) {
                return Carbon::parse($query->updated_at)->format('d M Y');
            })
            ->editColumn('is_published', function ($query) {
                if ($query->is_published) {
                    return '<i class="fas fa-check ml-3  text-success"></i>';
                }

                return '<i class="fas fa-times ml-3 text-danger"></i>';
            })
            ->editColumn('published_by', function ($query) {
                if ($query->user) {
                    return $query->user->name;
                } else {
                    return '-';
                }

            })
            ->addColumn('module', function ($query) {
                if ($query->module) {
                    return $query->module->name;
                }

                return '-';
            })
          
            ->editColumn('select', static function ($query)use ($video_id) {
                if($video_id==$query->id){
                    return '<input type="radio" checked class="video" id="checkbox-video-{{ $query->id }}" name="videos" value="'.$query->id.'"/>';
                   }else{ 
                    return '<input type="radio" class="video" id="checkbox-video-{{ $query->id }}" name="videos" value="'.$query->id.'"/>';
                   }
            })
            ->rawColumns(['select', 'is_published'])
            ->make(true);

        }

        $html = $builder->columns([
            ['data' => 'select', 'name' => 'id', 'title' => $checkbox22],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Date of Upload'],
            ['data' => 'formatted_duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Package Type','defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'professor_name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'module', 'name' => 'module', 'title' => 'Module'],
            ['data' => 'version_number', 'name' => 'version_number', 'title' => 'Version Number'],
            ['data' => 'media_id', 'name' => 'media_id', 'title' => 'Media ID'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Is Published'],
            ['data' => 'published_by', 'name' => 'user.name', 'title' => 'Published By'],
           
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'stateSave'=> true,
        ]);
        $package = Package::findOrFail($id);
        $packageFeatures = PackageFeature::where('package_id', $package->id)->get();
        return view('pages.packages.chapter.edit', compact('package', 'packageFeatures','html','video_details','video_id','types','courses','subjects'));
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
        $this->validate($request,[
            'category' => 'required',
            'name' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'language_id' => 'required',
          //  'package_type' => 'required',
            'price' => 'required|numeric',
            'freemium_content' => 'numeric|nullable',
            'discounted_price' => 'numeric|nullable',
            'special_price' => 'numeric|nullable',
            'professor_revenue' => 'numeric|required',
            'expiry_name' => 'required',
            'image' => 'nullable|image|max:150|dimensions:min_width=400,min_height=200,max_width=400,max_height=200'
        ]);

        $attempt = $request->input('attempt');
        $attempt = '01-' . $attempt;
        $attempt = Carbon::parse($attempt)->startOfMonth();

        $package = Package::findOrFail($id);

        $package->type = Package::TYPE_CHAPTER_LEVEL;
        $package->category = $request->input('category');
        $package->name = $request->input('name');
        $package->course_id = $request->input('course_id');
        $package->level_id = $request->input('level_id');
        $package->subject_id = $request->input('subject_id');
        $package->chapter_id = $request->input('chapter_id');
        $package->language_id = $request->input('language_id');
        $package->package_type = $request->input('package_type');
        $package->price = $request->input('price');
        $package->discounted_price = $request->input('discounted_price');
        $package->discounted_price_expire_at = $request->input('discounted_price_expiry_at');
        $package->professor_revenue = $request->input('professor_revenue');
        $package->special_price = $request->input('special_price');
        $package->special_price_expire_at = $request->input('special_price_expiry_at');
        $package->special_price_active_from = $request->input('special_price_active_from');
        $package->attempt = $attempt;
        $package->duration = $request->input('duration');
        $package->expiry_type=$request->input('expiry_name');
        $package->is_cseet = $request->input('cseet') ?? 0;
        if($request->input('video_id')){ 
            if( $package->video_id!=$request->input('video_id')){
                $package->video_updated_at=date("Y-m-d H:i:s");
                       }
                      $package->video_id=$request->input('video_id');
           
            }else{
                $package->video_id=0;
            }
        if($request->input('expiry_name')=='1'){
        $package->expiry_month=$request->input('expiry_month');
        $package->expire_at =null;
        }
        elseif($request->input('expiry_name')=='2'){
        $package->expire_at = $request->input('expiry_date');
        $package->expiry_month= null;
        }
        $package->alt = $request->input('alt');
        $package->study_material_price = $request->input('study_material_price');
        $package->description = $request->input('description');
        $package->is_mini = $request->input('type') == Package::TYPE_MINI;
        $package->is_crash_course = $request->input('type') == Package::TYPE_CRASH;
        $package->pendrive = $request->input('pendrive') == 'on';
        $package->g_drive = $request->input('g-drive') == 'on';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/packages', $imageName);
            $package->image = $imageName;
        }
        $package->is_freemium = $request->input('is_freemium');
        $package->freemium_content = $request->input('freemium_content');

        $package->save();

        $packageFeatures = PackageFeature::where('package_id', $package->id)->get();

        foreach ($packageFeatures as $packageFeature){
            $packageFeature->delete();
        }

        $features = $request->features;
        if ($features != null) {
            foreach ($features as $feature) {
                if ($feature != null) {
                    $packageFeature = new PackageFeature();
                    $packageFeature->package_id = $package->id;
                    $packageFeature->feature = $feature;
                    $packageFeature->save();
                }
            }
        }

        $sellingAmount = $package->price;

        if ($package->is_prebook && !$package->is_prebook_package_launched) {
            $sellingAmount = $package->booking_amount;
        }

        if (! empty($package->special_price) && Carbon::today()>= $package->special_price_active_from && $package->special_price_expire_at >= \Illuminate\Support\Carbon::today()) {
            $sellingAmount = $package->special_price;
        }

        if (! empty($package->discounted_price) && $package->discounted_price_expire_at >= Carbon::today()){
            $sellingAmount = $package->discounted_price;
        }

        $package->selling_amount = $sellingAmount;
        $package->save();

        return redirect(url('packages/' . $package->id))->with('success', 'Package successfully updated');
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

    public function addPackageVideos($id)
    {
        $videoIDs = request()->input('video_ids');
        $moduleIDs = request()->input('module_ids');
        $packageVideos = PackageVideo::query()->where('package_id', $id)->get();
        $existingTotalDuration = $packageVideos->sum('video.duration');
        $existingTotalVideos = $packageVideos->count();

        if ($packageVideos) {
            foreach ($packageVideos as $packageVideo) {
                $packageVideo->delete();
            }
        }

        $videoIDs = json_decode($videoIDs);
        $moduleIDs = json_decode($moduleIDs);

        if ($videoIDs) {
            foreach ($videoIDs as $index => $videoID) {
                $packageVideo = new PackageVideo();
                $packageVideo->package_id = $id;
                $packageVideo->video_id = $videoID;
                $packageVideo->module_id = $moduleIDs[$index];
                $packageVideo->save();

            }
        }

        $totalDuration = null;
        $totalVideos = null;

        if ($videoIDs) {
            $totalDuration=Video::whereIn('id', $videoIDs)->where('video_category',1)->sum('duration');
            $bonus_duration=Video::whereIn('id', $videoIDs)->where('video_category',2)->sum('duration');
            // $totalDuration = Video::whereIn('id', $videoIDs)->sum('duration');
            $totalVideos = Video::whereIn('id', $videoIDs)->count();
        }

        $package = Package::findOrFail($id);
        $package->total_duration = $totalDuration;
        $package->total_videos = $totalVideos;
        $package->bonus_duration =$bonus_duration;
        $package->save();

        $newTotalDuration = (intval($totalDuration) - intval($existingTotalDuration));
        $newTotalVideos = (intval($totalVideos) - intval($existingTotalVideos));

        $subjectPackageIDs = SubjectPackage::where('chapter_package_id', $id)->pluck('package_id');
        $customizedPackageIDs = CustomizedPackage::where('selected_package_id', $id)->pluck('package_id');

//        foreach ($subjectPackageIDs as $subjectPackageID) {
//            $package = Package::find($subjectPackageID);
//            $package->total_duration = (intval($package->total_duration ?? 0) + intval($newTotalDuration));
//            $package->total_videos = (intval($package->total_videos ?? 0) + intval($newTotalVideos));
//            $package->save();
//        }
//
//        foreach ($customizedPackageIDs as $customizedPackageID) {
//            $package = Package::find($customizedPackageID);
//            $package->total_duration = (intval($package->total_duration ?? 0) + intval($newTotalDuration));
//            $package->total_videos = (intval($package->total_videos ?? 0) + intval($newTotalVideos));
//            $package->save();
//        }
if($package->is_approved==1){
        return redirect(url('packages/' . $id))->with([
            'success' => 'Package videos successfully updated',
            'notification' => [
                'package_id' => $package->id
            ]
        ]);
    }
    else{
        return redirect(url('packages/' . $id))->with([
            'success' => 'Package videos successfully updated']); 
    }
    }

    public function changeOrder($id)
    {
        /** @var Package $package */
        $package = Package::findOrFail($id);

        /** @var array $packageVideoIDs */
        $packageVideoIDs = PackageVideo::where('package_id', $id)->pluck('video_id');

        return view('pages.packages.chapter.order.index', compact('package', 'packageVideoIDs'));
    }

    public function saveOrder($id)
    {
        $videos = request()->input('video_id');
        $modules = request()->input('module_id');
        $videoOrders = request()->input('video_order');
        $moduleOrders = request()->input('module_order');

        $packageVideos = PackageVideo::where('package_id', $id)->get();

        if ($packageVideos) {
            foreach ($packageVideos as $packageVideo) {
                $packageVideo->delete();
            }
        }

        if ($videos) {
            foreach ($videos as $index => $video) {
                $packageVideo = new PackageVideo();
                $packageVideo->package_id = $id;
                $packageVideo->video_id = $video;
                $packageVideo->video_order = $videoOrders[$index];
                $packageVideo->module_id = $modules[$index];
                $packageVideo->module_order = $moduleOrders[$index];
                $packageVideo->save();
            }
        }

        return redirect(url('packages/' . $id))->with('success', 'Video order successfully updated');
    }
}
