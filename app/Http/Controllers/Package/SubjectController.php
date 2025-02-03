<?php

namespace App\Http\Controllers\Package;

use App\Http\Controllers\Controller;
use App\Models\CustomizedPackage;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\PackageStudyMaterial;
use App\Models\PackageVideo;
use App\Models\Course;
use App\Models\Subject;
use App\Models\StudyMaterialV1;
use App\Models\UserNotification;
use App\PackageFeature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Package;
use App\Models\Video;
use App\Models\SubjectPackage;
use App\Models\PackageType;
use App\Models\LevelType;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $package_id = null)
    {
        $query = SubjectPackage::query()->with('package','chapterPackage')->where('package_id',$package_id);

        if (request()->ajax()) {
            $dataTable = DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query->whereHas('chapterPackage', function ($query) {
                                $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                            });
                        });
                    }
                })
                ->editColumn('duration',function ($query){
                    if(@$query->chapterPackage->total_duration || @$query->chapterPackage->bonus_duration ) {
                        $durationInSeconds = @$query->chapterPackage->total_duration + @$query->chapterPackage->bonus_duration ;
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
                ->addColumn('action', function($query) {
                    return '<a href="' . url('packages/videos') . '?package_id=' . $query->chapter_package_id . '"><i class="fas fa-video"></i></a>';
                })
                ->rawColumns(['action']);

            return $dataTable->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create()
    {
        $types = PackageType::where('is_enabled',true)->get();
        return view('pages.packages.subject.create',compact('types'));
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
            'language_id' => 'required',
            'price' => 'required|numeric',
            'discounted_price' => 'numeric|nullable',
            'special_price' => 'numeric|nullable',
            'professor_revenue' => 'numeric|required',
            'expiry_name' => 'required',
           // 'package_type' => 'required',
            'image' => 'required|image|max:150|dimensions:min_width=400,min_height=200,max_width=400,max_height=200'
        ]);

        $attempt = $request->input('attempt');
        $attempt = '01-' . $attempt;
        $attempt = Carbon::parse($attempt)->startOfMonth();

        $package = new Package();
        $package->type = Package::TYPE_SUBJECT_LEVEL;
        $package->category = $request->input('category');
        $package->name = $request->input('name');
        $package->course_id = $request->input('course_id');
        $package->level_id = $request->input('level_id');
        $package->subject_id = $request->input('subject_id');
        $package->language_id = $request->input('language_id');
        $package->package_type = $request->input('package_type');
        $package->price = $request->input('price');
        $package->discounted_price = $request->input('discounted_price');
        $package->discounted_price_expire_at = $request->input('discounted_price_expiry_at');
        $package->professor_revenue = $request->input('professor_revenue');
        $package->special_price = $request->input('special_price');
        $package->special_price_active_from = $request->input('special_price_active_from');
        $package->special_price_expire_at = $request->input('special_price_expiry_at');
        $package->attempt = $attempt;
        $package->duration = $request->input('duration');
        $package->expiry_type=$request->input('expiry_name');
        $package->expiry_month=$request->input('expiry_month') ?? null;
        $package->expire_at = $request->input('expiry_date' ?? null);
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

        if (! empty($package->special_price) && Carbon::today()>= $package->special_price_active_from && $package->special_price_expire_at >= \Illuminate\Support\Carbon::today()) {
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
        /** @var Package $package */
        $package = Package::findOrFail($id)->load('course', 'level', 'subject', 'chapter', 'language');

            return response()->json($package);


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
        if (request()->filled('filter.subject')) {
            $query->where('subject_id', request()->input('filter.subject'));
        }
        if (request()->filled('filter.package_type')) {
            $query->where('package_type_id', request()->input('filter.package_type'));
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
        return view('pages.packages.subject.edit', compact('package', 'packageFeatures','html','video_details','video_id','types','courses','subjects'));
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
            'language_id' => 'required',
           // 'package_type' => 'required',
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

        $package->type = Package::TYPE_SUBJECT_LEVEL;
        $package->category = $request->input('category');
        $package->name = $request->input('name');
        $package->course_id = $request->input('course_id');
        $package->level_id = $request->input('level_id');
        $package->subject_id = $request->input('subject_id');
        $package->language_id = $request->input('language_id');
        $package->package_type = $request->input('package_type');
        $package->price = $request->input('price');
        $package->discounted_price = $request->input('discounted_price');
        $package->discounted_price_expire_at = $request->input('discounted_price_expiry_at');
        $package->professor_revenue = $request->input('professor_revenue');
        $package->special_price = $request->input('special_price');
        $package->special_price_active_from = $request->input('special_price_active_from');
        $package->special_price_expire_at = $request->input('special_price_expiry_at');
        $package->attempt = $attempt;
        $package->duration = $request->input('duration');
        $package->expiry_type=$request->input('expiry_name');
        $package->is_cseet = $request->input('cseet')?? 0;
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
        if($request->input('video_id')){ 
            if( $package->video_id!=$request->input('video_id')){
                $package->video_updated_at=date("Y-m-d H:i:s");
                       }
            $package->video_id=$request->input('video_id');
            }else{
                $package->video_id=0;
            }
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

    public function addChapterPackages($id)
    {
        $chapterPackageIDs = request()->input('chapter_package_ids');
        $subjectPackages = SubjectPackage::query()->where('package_id', $id)->get();
        $packageVideos = PackageVideo::query()->whereIn('package_id', $subjectPackages->pluck('chapter_package_id'))->get();
        $existingTotalDuration = $packageVideos->sum('video.duration');
        $existingTotalVideos = $packageVideos->count();

        if ($subjectPackages) {
            foreach ($subjectPackages as $subjectPackage) {
                $subjectPackage->delete();
            }
        }

        $chapterPackageIDs = json_decode($chapterPackageIDs);

        if ($chapterPackageIDs) {
            foreach ($chapterPackageIDs as $chapterPackageID) {
                $subjectPackage = new SubjectPackage();
                $subjectPackage->package_id = $id;
                $subjectPackage->chapter_package_id = $chapterPackageID;
                $subjectPackage->save();
            }
        }

        $totalDuration = null;
        $totalVideos = null;

        if ($chapterPackageIDs) {
            $totalDuration = PackageVideo::whereIn('package_id', $chapterPackageIDs)->with(['video' => function($query){
                $query->where('video_category',1);}])->get()->sum('video.duration');
            $bonus_duration= PackageVideo::whereIn('package_id', $chapterPackageIDs)->with(['video' => function($query){
                $query->where('video_category',2);}])->get()->sum('video.duration');
            $totalVideos = PackageVideo::whereIn('package_id', $chapterPackageIDs)->count();
        }

        $package = Package::findOrFail($id);
        $package->total_duration = $totalDuration;
        $package->bonus_duration =$bonus_duration;
        $package->total_videos = $totalVideos;
        $package->save();

        $newTotalDuration = (intval($totalDuration) - intval($existingTotalDuration));
        $newTotalVideos = (intval($totalVideos) - intval($existingTotalVideos));

        $customizedPackageIDs = CustomizedPackage::where('selected_package_id', $id)->pluck('package_id');

        foreach ($customizedPackageIDs as $customizedPackageID) {
            $package = Package::find($customizedPackageID);
            $package->total_duration = (intval($package->total_duration) + intval($newTotalDuration));
            $package->total_videos = (intval($package->total_videos) + intval($newTotalVideos));
            $package->save();
        }
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
        $chapterPackageIDs = SubjectPackage::where('package_id', $id)->pluck('chapter_package_id');

        return view('pages.packages.subject.order.index', compact('package', 'chapterPackageIDs'));
    }

    public function saveOrder($id)
    {
        $subjectPackages = SubjectPackage::where('package_id', $id)->get();

        if ($subjectPackages) {
            foreach ($subjectPackages as $subjectPackage) {
                $subjectPackage->delete();
            }
        }

        $chapterPackageIDs = request()->input('package_id');
        $chapterPackageOrder = request()->input('package_order');

        if ($chapterPackageIDs) {
            foreach ($chapterPackageIDs as $index => $chapterPackageID) {
                $subjectPackage = new SubjectPackage();
                $subjectPackage->package_id = $id;
                $subjectPackage->chapter_package_id = $chapterPackageID;
                $subjectPackage->chapter_package_order = $chapterPackageOrder[$index];
                $subjectPackage->save();
            }
        }

        return redirect(url('packages/' . $id))->with('success', 'Package order successfully updated');
    }
    
}
