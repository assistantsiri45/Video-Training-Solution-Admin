<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\EdugulpVideos;
use App\Models\Package;
use App\Models\PackageType;
use App\Models\LevelType;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Migration;
use App\Services\BotrAPI;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Builder;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $courses = Course::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="select_all" class="custom-control-label"></label>
                    </div>';

        $unpublishedVideos = app(Builder::class)->columns([
            ['data' => 'select', 'name' => 'id', 'title' => $checkbox],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'formatted_duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'professor_name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'module', 'name' => 'module', 'title' => 'Module'],
            ['data' => 'version_number', 'name' => 'version_number', 'title' => 'Version Number'],
            ['data' => 'applicable_for', 'name' => 'applicable_for', 'title' => 'Applicable For'],
            ['data' => 'media_id', 'name' => 'media_id', 'title' => 'Media ID'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Is Published'],
            ['data' => 'published_by', 'name' => 'user.name', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'stateSave'=> true,
        ])->ajax(url('fetch-unpublished-videos'))->setTableId('tbl-unpublished-videos');

        $tableStudioUploadVideos = app(Builder::class)->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'chapter', 'name' => 'chapter', 'title' => 'Chapter'],
            ['data' => 'professor', 'name' => 'professor', 'title' => 'Professor'],
            ['data' => 'is_merged', 'name' => 'is_merged', 'title' => 'Status'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => false,
            'stateSave'=> true,
        ])->ajax(url('studio-upload-videos'))->setTableId('tbl-studio');

        $checkbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="published_select_all" class="custom-control-input select_all" name="select_all" type="checkbox">
                        <label for="published_select_all" class="custom-control-label"></label>
                    </div>';

        $publishedVideos = app(Builder::class)->columns([
            ['data' => 'select', 'name' => 'id', 'title' => $checkbox],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload'],
            ['data' => 'formatted_duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'professor_name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'module', 'name' => 'module', 'title' => 'Module'],
            ['data' => 'version_number', 'name' => 'version_number', 'title' => 'Version Number'],
            ['data' => 'applicable_for', 'name' => 'applicable_for', 'title' => 'Applicable For'],
            ['data' => 'media_id', 'name' => 'media_id', 'title' => 'Media ID'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Is Published'],
            ['data' => 'published_by', 'name' => 'user.name', 'title' => 'Published By'],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'stateSave'=> true,
        ])->ajax(url('fetch-published-videos'))->setTableId('tbl-published-videos');

        $archivedVideos = app(Builder::class)->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'duration', 'name' => 'duration', 'title' => 'Duration'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor', 'defaultContent' => ''],
            ['data' => 'module.name', 'name' => 'module.name', 'title' => 'Module', 'defaultContent' => ''],
            ['data' => 'version_number', 'name' => 'version_number', 'title' => 'Version Number'],
            ['data' => 'applicable_for', 'name' => 'applicable_for', 'title' => 'Applicable For'],
            ['data' => 'media_id', 'name' => 'media_id', 'title' => 'Media ID'],
            ['data' => 'is_published', 'name' => 'is_published', 'title' => 'Is Published'],
            ['data' => 'publisher.name', 'name' => 'publisher.name', 'title' => 'Published By', 'defaultContent' => ''],
            ['data' => 'action', 'name' => 'action', 'title' => '']
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'stateSave'=> true,
        ])->ajax(route('tables.videos', ['is_archived' => true]))->setTableId('table-archived-videos');

        return view('pages.videos.index', compact('tableStudioUploadVideos','publishedVideos','unpublishedVideos', 'archivedVideos',
        'courses','subjects'));
    }


    public function studioVideos(Builder $builder){

        $query = EdugulpVideos::query();

        return DataTables::of($query)
            ->editColumn('is_merged', function($query) {
                if ($query->is_merged == 0 ) {
                    return 'NOT MERGED';
                }
                else {
                    return 'MERGED';
                }

            })
            ->addColumn('action', 'pages.videos.studio_upload.action')
            ->rawColumns(['action'])
            ->make(true);

    }

    public function fetchPublishedVideos(Builder $builder){
        $query = Video::with('course', 'level','package_type', 'subject', 'chapter', 'professor', 'user', 'module')
            ->where('is_published', true)
            ->where('is_archived', false);

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
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->format('d M Y');
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
            ->addColumn('action', function ($video) {

                return view('pages.videos.action', compact('video'));
            })
            ->addColumn('select', 'pages.videos.select')
            ->rawColumns(['select', 'action', 'is_published'])
            ->make(true);


    }

    public function fetchUnPublishedVideos(Builder $builder){

        $query = Video::with('course', 'level','package_type', 'subject', 'chapter', 'professor', 'user', 'module')
            ->where('is_published', false)
            ->where('is_archived', false)
            ->where('is_studio_video', false);


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

            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->format('d M Y');
            })
            ->addColumn('module', function ($query) {
                if ($query->module) {
                    return $query->module->name;
                }

                return '-';
            })
            ->addColumn('action', function ($video) {

                return view('pages.videos.action', compact('video'));
            })
            ->addColumn('select', 'pages.videos.select')
            ->rawColumns(['select', 'action', 'is_published'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = PackageType::where('is_enabled',true)->get();
        return view('pages.videos.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $this->validate($request, [
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'professor_id' => 'required',
//            'thumbnail' => 'required',
            'videos.*.title' => 'exclude_unless:videos.*.checked,on|required|regex:/^([^<>]*)$/',
            'videos.*.description' => 'exclude_unless:videos.*.checked,on|regex:/^([^<>]*)$/',
            'videos.*.tags' => 'exclude_unless:videos.*.checked,on|regex:/^([^<>]*)$/',
//            'videos.*.session' => 'exclude_unless:videos.*.checked,on|required',
//            'videos.*.date' => 'exclude_unless:videos.*.checked,on|required|date',
//            'videos.*.order' => 'exclude_unless:videos.*.checked,on|required|numeric',
            'videos.*.start_time' => 'exclude_unless:videos.*.checked,on|exclude_unless:videos.*.has_demo,on|required',
            'videos.*.end_time' => 'exclude_unless:videos.*.checked,on|exclude_unless:videos.*.has_demo,on|required',
        ], [ ], [
            'videos.*.checked' => 'checked',
            'videos.*.title' => 'title',
//            'videos.*.session' => 'session',
//            'videos.*.date' => 'date',
//            'videos.*.order' => 'order',
            'videos.*.start_time' => 'start time',
            'videos.*.end_time' => 'end time',
        ]);

        if ($request->filled('has_media_id')) {
            $mediaIDs = $request->input('media_id');
            $mediaTitles = $request->input('media_title');
            $mediaDescriptions = $request->input('media_description');
            $mediaTags = $request->input('media_tags');
            $mediaLanguages = $request->input('media_language');
            $mediaModules = $request->input('media_module');

            foreach ($mediaIDs as $index => $mediaID) {
                $data = [
                    'course_id' => $request->input('course_id'),
                    'level_id' => $request->input('level_id'),
                    'package_type_id' =>$request->input('package_type'),
                    'subject_id' => $request->input('subject_id'),
                    'chapter_id' => $request->input('chapter_id'),
                    'version_number' => $request->input('version_number'),
                    'applicable_for' => $request->input('applicable_for'),
                    'professor_id' => $request->input('professor_id'),
                    'video_category'=>$request->input('video_cat'),
                    'media_id' => $mediaID,
                    'title' => $mediaTitles[$index],
                    'description' => $mediaDescriptions[$index],
                    'tags' => $mediaTags[$index],
                    'language_id' => $mediaLanguages[$index],
                    'module_id' => $mediaModules[$index],
                    'has_demo' => false
                ];

                Video::create($data);
            }
        } else {
            $inputs = $request->all();

            $videos = $request->get('videos');
            $count = collect($videos)->filter(function ($video) {
                return isset($video['checked']) ? $video['checked'] : false;
            })->count();

            if ($count == 0) {
                return back()->withInput()->withError('There are no video selected to upload');
            }

            $jwplatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

            try {
                DB::beginTransaction();

                foreach ($videos as $video) {
                    $save = isset($video['checked']) ? $video['checked'] : false;

                    if (!$save) continue;

                    $params = collect($video)->only(['title', 'description', 'tags']);

                    $params = $params->map(function ($item) {
                        return html_entity_decode($item);
                    });

                    $params = $params->toArray();

                    if (!empty($video['url'])) {
                        $params['download_url'] = config('filesystems.disks.videos.url').$video['url'];
                        $params['download_url'] = str_replace(' ', '%20', $params['download_url']);
                    }

                    $params['date'] = strtotime(Carbon::now()->format('d-m-Y'));

                    info(json_encode($params));

                    $response = $jwplatform->call("/videos/create", $params);
                    if ($response['status'] != 'ok') {
                        return redirect()->back()->withError("Video upload error");
                    }

                    $has_demo = isset($video['is_demo']) ? true : false;

//                if ($request->hasFile('thumbnail')) {
//                    $file = $request->file('thumbnail');
//                    $fileName = Carbon::now()->timestamp.'.'.$file->getClientOriginalExtension();
//                    $file->storeAs('video_thumbnails', $fileName, 'public');
//                }

                    $startTime = $video['start_time'];
                    $endTime = $video['end_time'];

                    $startTimeCollenCount = substr_count($startTime, ':');
                    $endTimeCollenCount = substr_count($endTime, ':');

                    if ($startTimeCollenCount == 1) {
                        $startTime = $startTime . ':00';
                    }

                    if ($endTimeCollenCount == 1) {
                        $endTime = $endTime . ':00';
                    }

                    $data = [
                        'course_id' => $request->input('course_id'),
                        'level_id' => $request->input('level_id'),
                        'package_type_id' =>$request->input('package_type'),
                        'subject_id' => $request->input('subject_id'),
                        'chapter_id' => $request->input('chapter_id'),
                        'professor_id' => $request->input('professor_id'),
                        'version_number' => $request->input('version_number'),
                        'applicable_for' => $request->input('applicable_for'),
                        'video_category'=>$request->input('video_cat'),
                        'media_id' => $response['video']['key'],
                        'title' => $video['title'],
//                    'thumbnail' => $fileName,
                        'url' => $params['download_url'],
//                    'order' => $video['order'],
                        'description' => $video['description'],
                        'tags' => $video['tags'],
                        'language_id' => $video['language'] ?? null,
                        'module_id' => $video['module'] ?? null,
                        'has_demo' => $has_demo,
                        'start_time' => $has_demo ? $startTime : null,
                        'end_time' => $has_demo ? $endTime : null,
//                    'session' => $video['session']
                    ];

                    if ($has_demo) {
                        $params['trim_in_point'] = $startTime;
                        $params['trim_out_point'] = $endTime;

                        $response = $jwplatform->call('/videos/create', $params);

                        if ($response['status'] != 'ok') {
                            return redirect()->back()->withError("Video upload error");
                        }

                        $data['demo_media_id'] = $response['video']['key'];
                    }

                    Video::create($data);
                }

                DB::commit();
            } catch (\Exception $e) {
                info($e->getMessage());
                return redirect()->back()->withInput()->withError($e->getMessage());
            }
        }

        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully');
    }

    public function show(Video $video)
    {
        $signedUrl = $video->getSignedUrl();

        if($signedUrl == 'aws-s3') {
            $is_old_video = $video->is_old_video;
            $mediaID = $video->media_id;
            $res_src = array();
            $multi_res_src = json_decode($video->multi_res_src,true);
            if(!empty($is_old_video) && empty($multi_res_src)){
                $res_src = $this->get_resolution_from_migrated_asset($video, $mediaID, true);
            } else if(!empty($multi_res_src)) {
                $res_src = $multi_res_src;
            }
            $res_src = !empty($res_src) ? json_encode($res_src) : '';
            $signedUrl = $video->url;
            $pathinfo = pathinfo($signedUrl);
            $extension = !empty($pathinfo['extension']) ? $pathinfo['extension'] : 'mp4';
            $videoId = $video->id;
            $videoDuration = $video->duration;
            return view('pages.videos.s3show', compact('signedUrl','videoId','videoDuration','extension', 'res_src'));
        }

        return view('pages.videos.show', compact('signedUrl'));
    }

    private function get_resolution_from_migrated_asset($video, $mediaID, $force_update = false){
        $all_assets = array();
        $migrated_asset = Migration::where('media_id', $mediaID)->first();
        if(!empty($migrated_asset)){
            $s3_video_path = $migrated_asset->s3_video_path;
            $all_video_resolutions = explode(",",$migrated_asset->all_video_resolutions);
            $highest_video_resolution = $migrated_asset->highest_video_resolution;
            if(!empty($all_video_resolutions)){
                $s3_path = pathinfo($s3_video_path);
                $s3_folder_path = $s3_path['dirname'];
                $s3_file_name = $s3_path['basename'];
                foreach($all_video_resolutions as $resolution){
                    if($resolution != $highest_video_resolution){
                        $s3_url = $s3_folder_path."/$resolution/$s3_file_name";
                    } else {
                        $s3_url = $migrated_asset->s3_video_path;
                    }
                    $temp_array = array(
                        'src' => env('AWS_CDN') . $s3_url,
                        'type' => "video/mp4",
                        'label' => $resolution,
                        'res' => $resolution
                    );
                    array_push($all_assets,$temp_array);
                }
                if($force_update){
                    $video->multi_res_src = json_encode($all_assets);
                    $video->save();
                }
            }
        }
        return $all_assets;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $types = LevelType::with(['packagetype'=> function($types){
            $types->where('is_enabled', TRUE);
        }])
        ->where('level_id',$video->level_id)
        ->get();
        return view('pages.videos.edit', compact('video','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'professor_id' => 'required',
            'title' => 'required|regex:/^([^<>]*)$/',
            'description' => 'required|regex:/^([^<>]*)$/',
            'tags' => 'required|regex:/^([^<>]*)$/',
            'duration' => 'required',
        ]);

        $data = [
            'course_id' => $request->input('course_id'),
            'level_id' => $request->input('level_id'),
            'package_type_id' => $request->input('package_type'),
            'subject_id' => $request->input('subject_id'),
            'chapter_id' => $request->input('chapter_id'),
            'professor_id' => $request->input('professor_id'),
            'version_number' => $request->input('version_number'),
            'applicable_for' => $request->input('applicable_for'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'tags' => $request->input('tags'),
            'language_id' => $request->input('language'),
            'module_id' => $request->input('module'),
            'has_demo' => $request->input('has_demo') == 'on',
            'duration' => $request->input('duration'),
        ];
        if ($request->filled('video')) {
            $video = Video::findOrFail($request->input('video'));

            $data['url'] = $video->url;
            $data['media_id'] = $video->media_id;
            $data['duration'] = $video->duration;
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        Video::where('id', $id)->update($data);

        return redirect()->route('videos.index')->with('success', 'Video updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }


    /**
     * @param integer $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish($id)
    {
        $video = Video::findOrFail($id);

        $jwPlatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

        $fullVideo = null;
        $demoVideo = null;

        if ($video->media_id) {
            if (strpos(strtolower($video->url), 'cloudfront') === false) {
                $fullVideo = $jwPlatform->call('/videos/show', ['video_key' => $video->media_id]);
            }else{
                $fullVideo['video']['status'] = 'success';
                $fullVideo['status'] = 'success';
            }
        }

        if ($video->demo_media_id) {
            $demoVideo = $jwPlatform->call('/videos/show', ['video_key' => $video->demo_media_id]);
        }

        if ($fullVideo['status'] == 'error') {
            return response()->json(['message' => $fullVideo['message'], 'status' => 503], 200);
        }

        if ($fullVideo['video']['status'] == 'processing') {
            return response()->json(['message' => 'Video is not published by JWPlayer. Please try again.', 'status' => 503], 200);
        }

        if ($video->demo_media_id && $demoVideo['video']['status'] == 'processing') {
            return response()->json(['message' => 'Video is not published by JWPlayer. Please try again.', 'status' => 503], 200);
        }
        if (strpos(strtolower($video->url), 'cloudfront') === false) {
            $video->duration = round($fullVideo['video']['duration']);
        }
        $video->is_published = true;
        $video->published_user_id = Auth::id();
        $video->save();

        return response()->json(['message' => 'Video published.', 'status' => 200], 200);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function multiplePublish(Request $request)
    {
        $this->validate($request, [
            'videos' => 'required',
        ]);

        $videos = $request->input('videos');

        $published_videos = [];

        foreach($videos as $id) {
            $video = Video::findOrFail($id);

            $jwPlatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

            $fullVideo = null;
            $demoVideo = null;

            if ($video->media_id) {
                $fullVideo = $jwPlatform->call('/videos/show', ['video_key' => $video->media_id]);
            }

            if ($video->demo_media_id) {
                $demoVideo = $jwPlatform->call('/videos/show', ['video_key' => $video->demo_media_id]);
            }

            if ($fullVideo['video']['status'] == 'processing') {
//                return response()->json(['messags' => 'Video is not published by JWPlayer. Please try again.', 'status' => 503], 200);
                continue;
            }

            if ($video->demo_media_id && $demoVideo['video']['status'] == 'processing') {
//                return response()->json(['message' => 'Video is not published by JWPlayer. Please try again.', 'status' => 503], 200);
                continue;
            }

            $video->duration = round($fullVideo['video']['duration']);
            $video->is_published = true;
            $video->published_user_id = Auth::id();
            $video->save();

            $published_videos[] = $video;
        }

        $published_videos_count = count($published_videos);
        $total_videos_count = count($videos);


        return response()->json(['message' => "$published_videos_count/$total_videos_count video published.", 'status' => 200], 200);
    }

    public function unPublish($id)
    {
        $package = Video::findOrFail($id);
        $package->is_published = 0;
        $package->published_user_id = null;
        $package->save();

        return response()->json(['message' => 'Video Un-Published'], 200);
    }

    public function multipleUnPublish(Request $request)
    {
        $this->validate($request, [
            'videos' => 'required',
        ]);

        $videos = $request->input('videos');

        $un_published_videos = [];

        foreach($videos as $id) {
            $video = Video::findOrFail($id);
            $video->is_published = 0;
            $video->published_user_id = null;
            $video->save();

            $un_published_videos[] = $video;
        }

        $published_videos_count = count($un_published_videos);
        $total_videos_count = count($videos);


        return response()->json(['message' => "$published_videos_count/$total_videos_count video unpublished.", 'status' => 200], 200);
    }

    public function syncVideos(){

        $exitCode = Artisan::call('sync:video');
        return redirect()->back()->with('success', 'Videos synced successfully');
    }

    public function mergeVideos($id){

        $video = EdugulpVideos::find($id);
        return view('pages.videos.studio_upload.edit')->with('video',$video);
    }

    public function mergeEdugulpVideos(Request $request){

        DB::beginTransaction();
        $video_details = EdugulpVideos::find($request->id);
        $video_details->is_merged = 1;
        $video_details->update();

        $video = new Video();
        $video->course_id = $request->input('course_id');
        $video->level_id = $request->input('level_id');
        $video->subject_id = $request->input('subject_id');
        $video->chapter_id = $request->input('chapter_id');
        $video->professor_id = $request->input('professor_id');
        $video->language_id = $request->input('language_id');
        $video->media_id = $video_details->media_id;
        if($request->input('video_title')){
            $video->title = $request->input('video_title');
        }
        else{
            $video->title = $video_details->title;
        }
        $video->url = $video_details->url;
        if($request->input('video_description')) {
            $video->description = $request->input('video_description');
        }
        else{
            $video->description = $video_details->description;
        }
        if($request->input('video_tags')){
            $video->tags = $request->input('video_tags');
        }
        else{
            $video->tags = $video_details->tags;
        }
        if($video_details->has_demo!=null){
            $video->has_demo = $video_details->has_demo;
        }
        else{
            $video->has_demo = 0;
        }
        $video->save();
        DB::commit();
        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully');

    }

    public function change()
    {
        $videoID = request()->input('video_id');
        $mediaID = request()->input('media_id');

        $video = Video::query()->find($videoID);
        $video->media_id = $mediaID;
        $video->created_at= date('Y-m-d H:i:s');
        $video->save();

        return response()->json(['success' => 'Media ID successfully changed'], 200);
    }

    public function tableVideos()
    {
        if (request()->ajax()) {
            $query = Video::query()
                ->with('course', 'level','package_type', 'subject', 'chapter', 'module', 'professor', 'publisher');

            return DataTables::of($query)
                ->filter(function (\Illuminate\Database\Eloquent\Builder $query) {
                    if (request()->filled('is_archived')) {
                        $query->where('is_archived', true);
                    }

                    if (request()->filled('filter.chapter')) {
                        $query->ofChapter(request()->input('filter.chapter'));
                    }

                    if (request()->filled('filter.professor')) {
                        $query->ofProfessor(request()->input('filter.professor'));
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
                })
                ->editColumn('duration', function ($query) {
                    return Video::formatDuration($query->duration);
                })
                ->editColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('version_number', function ($query) {
                    return $query->version_number;
                })
                ->editColumn('is_published', function ($query) {
                    if (! $query->is_published) {
                        return '<i class="fas fa-times ml-3 text-danger"></i>';
                    }

                    return '<i class="fas fa-check ml-3  text-success"></i>';
                })
                ->addColumn('action', function ($query) {
                    return '<a href="' . route('videos.archive.remove', $query->id) . '" class="a-remove-from-archive"><i class="fas fa-box-open"></i></a>';
                })
                ->rawColumns(['is_published', 'action'])
                ->make(true);
        }
    }

    public function addToArchive(int $id)
    {
        $video = Video::query()
            ->findOrFail($id);

        $video->is_archived = true;
        $video->save();

        return back()->with('success', 'Video successfully archived');
    }

    public function removeFromArchive(int $id)
    {
        $video = Video::query()
            ->findOrFail($id);

        $video->is_archived = false;
        $video->save();

        return back()->with('success', 'Video successfully removed from archives');
    }

    public function archeieveSelectedVideos(Request $request)
    {
        $selectedVideoIds = $request->input('selectedVideoIds');

        foreach ($selectedVideoIds as $selectedVideoId){
            $video = Video::find($selectedVideoId);
            $video->is_archived = true;
            $video->save();
        }

        return response()->json( 'Video successfully archived', 200);
    }
    public function getPlayer($id)
    {
        $video = Video::findOrFail($id);

        $mediaID = null;

        if ($video->has_demo) {
            $mediaID = $video->demo_media_id;
        }

        if ($video->is_purchased) {
            $mediaID = $video->media_id;
        }

        $secret = 'McDMAuOcJtkr7k6U172rSnjI';
        $path = 'manifests/' . $video->media_id . '.m3u8';
        $expires = round((time() + 3600) / 300) * 300;
        $signature = md5($path . ':' . $expires . ':' . $secret);
        $url = 'https://cdn.jwplayer.com/' . $path . '?exp=' . $expires . '&sig=' . $signature;
        info($url);

        $response = $url;

        return response()->json($response);
    }

    public function transcodeWebhook(Request $request){
        $response = array('status'=>false,'msg'=>'');
        $headers = $request->header();
        $token = $request->header('Auth-Token');
        if($token == env('TRANSCODE_WEBHOOK_HEADER')){
            $mediaID = $request->input('media_id');
            $duration = $request->input('duration');
            $videoUrl = $request->input('video_url');
            if(!empty($mediaID) && !empty($duration) && !empty($videoUrl)){
                $videos = Video::where('s3_media_id', $mediaID)->get();
                if($videos){
                    foreach($videos as $video){
                        $video->duration = $duration;
                        $video->s3_video_url = $video->url;
                        $video->url = $videoUrl;
                        $video->save();
                    }
                }
            }
            $response = array('status'=>true,'msg'=>'success');
        } else {
            $response = array('status'=>false,'msg'=>'Invalid Request');
        }
        return response()->json($response);
    }
}
