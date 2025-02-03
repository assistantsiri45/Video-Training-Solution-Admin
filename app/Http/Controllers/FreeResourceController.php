<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\FreeResource;
use App\Models\Professor;
use App\Models\Package;
use App\Models\User;
use App\Models\FreeResourcePackage;
use App\Models\Video;
use App\Services\BotrAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Html\Builder;
use App\Models\LevelType;

class FreeResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = FreeResource::query();

            $query->orderBy('order');

            return DataTables::of($query)
                ->addColumn('action', 'pages.free_resources.action')
                ->editColumn('type', function($query) {
                    if($query->type == FreeResource::YOUTUBE_ID){
                        return '<span class="badge badge-danger">'.FreeResource::YOUTUBE_ID_TEXT.'</span>' ;
                    }
                    elseif($query->type == FreeResource::IMAGE){
                        return '<span class="badge badge-secondary">'.FreeResource::IMAGE_TEXT.'</span>' ;
                    }
                    elseif($query->type == FreeResource::NOTES){
                        return '<span class="badge badge-success">'.FreeResource::NOTES_TEXT.'</span>' ;
                    }
                    elseif($query->type == FreeResource::AUDIO_FILES){
                        return '<span class="badge badge-info">'.FreeResource::AUDIO_FILES_TEXT.'</span>' ;
                    }
                    elseif($query->type == FreeResource::JW_VIDEO){
                        return '<span class="badge badge-warning">'.FreeResource::JW_VIDEO_TEXT.'</span>' ;
                    }
                })
                ->editColumn('file', function($query) {
                    if($query->type == FreeResource::YOUTUBE_ID){
                        return '<img  width="100" height="60" src="https://img.youtube.com/vi/'.$query->youtube_id.'/mqdefault.jpg">';
                    }
                    elseif($query->type == FreeResource::IMAGE){
                        if ($query->file) {
                            return '<span><img src="'. $query->file . '" class="rounded-square" width="100" height="60"></span>';
                        }
                        return '';
                    }
                    elseif($query->type == FreeResource::NOTES){
                        return '<span class="text-center" style="font-size: 48px; color: #0e6338;">
                                <a target="_blank" href="' .$query->file.'"><i class="far fa-file"></i></a>
                        </span>';
                    }
                    elseif($query->type == FreeResource::AUDIO_FILES){
                        return '<span class="text-center" style="font-size: 48px; color: #054a95;">
                                <a target="_blank" href="' .$query->file.'"><i class="far fa-file-audio"></i></a>
                        </span>';
                    }
                    elseif($query->type == FreeResource::JW_VIDEO){
                        return '<img  width="100" height="60" src="https://cdn.jwplayer.com/v2/media/'.$query->media_id.'/poster.jpg?width=320">';
                    }
                })
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="resource-id" value="' . $query->id . '"></div>';
                })
                ->rawColumns(['action','type','file', 'order'])
                ->make(true);

        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'file', 'name' => 'file', 'title' => 'File'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.free_resources.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Builder $builder)
    {

        /**** Modfied by TE **********/

        $linked_packages=FreeResourcePackage::get();
        // $sel_pkg_name=''; 
        $sel_pkgs=[];
        $p_ids=[];
       if($linked_packages){
           foreach($linked_packages as $linked){
            $sel_pkgs[]=Package::findOrFail($linked->package_id);
            $p_ids[]=$linked->package_id;
           }

        }
        if (request()->ajax()) {
            $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user')->whereNotIn('id', $p_ids)->where('is_approved',1)
            ->where('is_archived',0)->latest();

            // if (request()->has('filter.status') && !empty(request('filter.status'))) {
            //     if (request('filter.status') == 'published') {
            //         $query->where('is_approved', 1);
            //     }

            //     if (request('filter.status') == 'unpublished') {
            //         $query->where('is_approved', 0);
            //     }
            // }

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

            // if (request()->filled('filter.type')) {
            //     $query->where('type', request()->input('filter.type'));
            // }

            // if (request()->filled('filter.language')) {
            //     $query->where('language_id', request()->input('filter.language'));
            // }
           
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
                ->editColumn('select', static function ($row) {
                    return '<input type="checkbox" name="packages[]" value="'.$row->id.'"/>';
                })->rawColumns(['select'])
              
                ->make(true);
        }
       
        $html = $builder->columns([
            ['data' => 'select', 'name' => 'id', 'title' => ''],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
        ])->parameters([
            'searching' => true,
            'ordering' => false,
            'lengthChange' => false,
            'bInfo' => true,
        ]);
        return view('pages.free_resources.create',compact('html'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->type == FreeResource::YOUTUBE_ID){
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'regex:/^([^<>]*)$/',
                'youtube_id' => 'regex:/^([^<>]*)$/',
            ])->validate();
        }
        if($request->type == FreeResource::IMAGE){
            $validator = Validator::make($request->all(), [
                'image' => 'mimes:jpeg,jpg,png,gif|max:10000',
                'title' => 'required',
                'description' => 'regex:/^([^<>]*)$/',
            ])->validate();
        }
        elseif($request->type == FreeResource::NOTES){
            $validator = Validator::make($request->all(), [
                'notes' => 'mimes:pdf|max:10000',
                'title' => 'required',
                'description' => 'regex:/^([^<>]*)$/',
            ])->validate();
        }
        elseif($request->type == FreeResource::AUDIO_FILES){
            $validator = Validator::make($request->all(), [
                'audio' => 'mimes:mp3',
                'title' => 'required',
                'description' => 'regex:/^([^<>]*)$/',
            ])->validate();
        }
        else{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'regex:/^([^<>]*)$/',
            ])->validate();
        }
        if($request->type !=5 ){
            $store = new FreeResource();
            $store->title = $request->title;
            $store->professor_id = $request->professor_id;
            $store->course_id = $request->course_id;
            $store->level_id = $request->level_id;
            $store->package_type_id =$request->package_type;
            $store->description = $request->description;
            $store->type = $request->type;
            if($request->youtube_id){
                $store->youtube_id = $request->youtube_id;
                //Added by TE
                $store->demo_package_id=$request->demo_package_id;
            }
            if ($request->input('image')) {
                $data = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                $filename = time().'.png';
                Storage::disk('public')->put("free_resources/thumbnails/$filename", $data);
                $store->thumbnail_file = $filename;
            }
            if($request->hasFile('notes')) {
                $file = $request->file('notes');
                $filename = str_replace(' ', '_', $request->title) . '.' . $file->getClientOriginalExtension();
                $request->file('notes')->storeAs('public/free_resources/', $filename);
                $store->file = $filename;
            }
            if($request->hasFile('audio')) {
                $file = $request->file('audio');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $request->file('audio')->storeAs('public/free_resources/', $filename);
                $store->file = $filename;
            }

            $count = FreeResource::all()->count();
            $store->order = $count+1;
            $store->save();
            if($request->youtube_id){
                $free_resource_id=$store->id;
            $linked_packages=$request->packages ?? null;
            if(isset($linked_packages)){
            foreach($linked_packages as $pkg)
            {
                $demoPack=New FreeResourcePackage();
                $demoPack->free_resource_id= $free_resource_id;
                $demoPack->package_id=$pkg;
                $demoPack->save();
            }
             }
            }
        }
    
        else{
            $jwplatform = new BotrAPI('7kHOkkQa', 'McDMAuOcJtkr7k6U172rSnjI');

            try {
                DB::beginTransaction();

                $params = [
                    'title' => $request->title,
                    'description' => $request->description,
                    'tags' => $request->title,
                ];

                $params = collect($params);

                $params = $params->map(function ($item) {
                    return html_entity_decode($item);
                });
                $params = $params->toArray();

                if ($request->video_file) {
                    $params['download_url'] = config('filesystems.disks.videos.url').$request->video_file;
                    $params['download_url'] = str_replace(' ', '%20', $params['download_url']);
                }

                $response = $jwplatform->call("/videos/create", $params);

                if ($response['status'] != 'ok') {
                    return redirect()->back()->withError("Video upload error");
                }

                $store = new FreeResource();
                $store->title = $request->title;
                $store->professor_id = $request->professor_id;
                $store->course_id = $request->course_id;
                $store->level_id = $request->level_id;
                $store->package_type_id =$request->package_type;
                $store->description = $request->description;
                $store->type = $request->type;
                if(array_key_exists('video', $response)){
                    $store->media_id = $response['video']['key'];
                    $store->video = $params['download_url'];
                }

                $count = FreeResource::all()->count();
                $store->order = $count+1;
                $store->save();

                DB::commit();
            } catch (\Exception $e) {
                info($e->getMessage());
                return redirect()->back()->withInput()->withError($e->getMessage());
            }
        }

        return redirect(route('free-resource.index'))->with('success', 'Free Resource uploaded successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Builder $builder,$id)
    {
        if (request()->ajax()) {
            $query = FreeResource::where('professor_id',$id)->get();

            return DataTables::of($query)
                ->addColumn('action', 'pages.free_resources.delete')

                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'file', 'name' => 'file', 'title' => 'File'],
            ['data' => 'description', 'name' => 'description', 'title' => 'Description'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.free_resources.show',compact('html'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Builder $builder,$id)
    {
       
             $freeResource = FreeResource::findOrFail($id);
             $linked_packages=FreeResourcePackage::where('free_resource_id',$id)->get();
            // $sel_pkg_name=''; 
            $sel_pkgs=[];
            $p_ids=[];
           if($linked_packages){
               foreach($linked_packages as $linked){
                $sel_pkgs[]=Package::findOrFail($linked->package_id);
                $p_ids[]=$linked->package_id;
               }
               
           }
           /**** Modfied by TE **********/
          // $p_ids=FreeResource::whereNotNull('demo_package_id')->get()->pluck('demo_package_id')->toArray();
           if (request()->ajax()) {
               $query = Package::with('course', 'level', 'subject','language', 'chapter', 'user')->whereNotIn('id', $p_ids)->where('is_approved',1)
               ->where('is_archived',0)->latest();
   
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
                   ->editColumn('select', static function ($row) {
                       return '<input type="checkbox" name="packages[]" value="'.$row->id.'"/>';
                   })->rawColumns(['select'])
                 
                   ->make(true);
           }
          
           $html = $builder->columns([
               ['data' => 'select', 'name' => 'id', 'title' => ''],
               ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
               ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
               ['data' => 'category', 'name' => 'category', 'title' => 'Category'],
               ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
               ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
               ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
               ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
               ['data' => 'approved_by', 'name' => 'approved_by', 'title' => 'Published By'],
           ])->parameters([
               'searching' => true,
               'ordering' => false,
               'lengthChange' => false,
               'bInfo' => true,
           ]);
        
           $types = LevelType::with(['packagetype'=> function($types){
            $types->where('is_enabled', TRUE);
             }])
            ->where('level_id',$freeResource->level_id)
            ->get();
        return view('pages.free_resources.edit', compact('freeResource','html','sel_pkgs','types'));
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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'resource_type' => 'required'
        ]);

        $freeResource = FreeResource::findOrFail($id);

        $freeResource->course_id = $request->input('course_id');
        $freeResource->level_id = $request->input('level_id');
        $freeResource->package_type_id =$request->input('package_type');
        $freeResource->professor_id = $request->input('professor_id');
        $freeResource->title = $request->input('title');
        $freeResource->description = $request->input('description');
        $freeResource->type = $request->input('resource_type');

        if ($request->input('resource_type') == FreeResource::YOUTUBE_ID) {
            $free_resource_id=$freeResource->id;
            $linked_packages=$request->packages;
            if(isset($request->packages)){
                foreach($linked_packages as $pkg)
                {
                    $demoPack=New FreeResourcePackage();
                    $demoPack->free_resource_id= $free_resource_id;
                    $demoPack->package_id=$pkg;
                    $demoPack->save();
                }
            }
            $freeResource->youtube_id = $request->input('youtube_id');
        }

        if ($request->input('resource_type') == FreeResource::NOTES) {
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $filename = str_replace(' ', '_', $request->input('title')) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/free_resources/', $filename);
                $freeResource->file = $filename;
            }

            if ($request->filled('cropped_thumbnail')) {
                $data = $request->input('cropped_thumbnail');
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                $filename = time() . '.png';
                Storage::disk('public')->put("free_resources/thumbnails/$filename", $data);
                $freeResource->thumbnail_file = $filename;
            }
        }

        $freeResource->save();

        return redirect(route('free-resource.index'))->with('success', 'Free Resource updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $free_resource = FreeResource::findOrFail($id);

        $free_resource->delete();

        return response()->json(true, 200);
    }

    public function changeOrder()
    {
        $resourceIDs = request()->input('resources');

        if ($resourceIDs) {
            $index = 1;

            foreach ($resourceIDs as $resourceID) {
                $resource = FreeResource::find($resourceID);

                if ($resource) {
                    $resource->order = $index;
                    $resource->save();
                }

                $index++;
            }
        }
    }
    public function UnlinkPackage(Request $request){
        
        $id=$request->id;
        $package_id=$request->package_id;
        $free_resource = FreeResourcePackage::where('free_resource_id',$id)->where('package_id',$package_id)->delete();
        return response()->json(true, 200);
    }
}
