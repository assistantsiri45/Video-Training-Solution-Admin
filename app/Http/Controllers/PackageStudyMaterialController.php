<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageStudyMaterial;
use App\Models\PackageVideo;
use App\Models\PrivateCoupon;
use App\Models\Professor;
use App\Models\StudyMaterialV1;
use App\Models\SubjectPackage;
use App\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Carbon\Carbon;

class PackageStudyMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder, $package_id = null)
    {
        //$query = StudyMaterialV1::query()->with('course','level','subject','chapter','language','professor','packages');
        $query = PackageStudyMaterial::with('package','study_material','study_material.course','study_material.level','study_material.subject','study_material.chapter','study_material.language','study_material.professor','study_material.user','study_material.package_type' );

        if($package_id != null)
        {
            $query->where('package_id',$package_id);
        }

        if($package_id != null)
        {
            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query
                        ->WhereHas('package',function ($query){
                            $query->where('name','like','%'. request('filter.search') . '%');
                        })
                        ->orWhereHas('study_material',function ($query){
                            $query->where('title','like','%'. request('filter.search') . '%');
                        });
                });
            }

            if (request()->filled('filter.type')) {
//            info(request('filter.type'));
                $query->where(function ($query) {
                    $query
                        ->WhereHas('study_material',function ($query){
                            $query->where('type',request('filter.type'));
                        });
                });
            }

            if (request()->filled('filter.language')) {
                $query->where(function ($query) {
                    $query
                        ->WhereHas('study_material.language',function ($query){
                            $query->where('id',request('filter.language'));
                        });
                });
            }
            if (request()->filled('filter.professor')) {
                $query->where(function ($query) {
                    $query
                        ->WhereHas('study_material.professor',function ($query){
                            $query->where('id',request('filter.professor'));
                        });
                });
            }
        }


        if (request()->ajax()) {
            $dataTable = DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.package')) {
                        $query->where(function ($query) {
                            $query
                                ->WhereHas('package',function ($query){
                                    $query->where('id', request('filter.package'));
                                });
                        });
                    }
                })
                ->editColumn('study_material.chapter.name', function($query) {
                    if($query->study_material) {
                        if ($query->study_material->chapter) {
                            $query->study_material->chapter->name;
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('type', function($query) {
                    if ($query->study_material) {
                        if ($query->study_material->type) {
                            if ($query->study_material->type == StudyMaterialV1::STUDY_MATERIALS) {
                                return 'STUDY MATERIAL';
                            } else if ($query->study_material->type == StudyMaterialV1::STUDY_PLAN) {
                                return 'STUDY PLAN';
                            } else if ($query->study_material->type == StudyMaterialV1::TEST_PAPER) {
                                return 'TEST PAPER';
                            } else {
                                return '';
                            }
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                })
                ->editColumn('package_type', function($query) {
                    if ($query->study_material) {
                        if ($query->study_material->package_type) {
                         
                            return $query->study_material->package_type->name;
                          
                    } else {
                        return '';
                    }
                }
                })
                ->editColumn('file_name', function($query) {
                    if($query->study_material) {
                        if ($query->study_material->file_name) {
                            return '<a target="_blank" href="'.$query->study_material->file.'">'. substr($query->study_material->file_name,10).'</a>';
                        } else {
                            return '-';
                        }
                    }
                })
                ->editColumn('created_at', function ($query) {
                    if($query->study_material) {
                        if ($query->study_material->created_at) {
                            return Carbon::parse($query->study_material->created_at)->format('d M Y');
                        }
                        else{
                            return '-';
                        }
                      }
                    else{
                        return '-';
                    }
                })
                ->editColumn('study_material.user.name', function($query) {
                    if($query->study_material){
                            if ($query->study_material->user) {
                              return $query->study_material->user->name;
                            }
                            else{
                                return '-';
                            }
                    }
                    else{
                        return '-';
                    }
                })
               ->rawColumns(['type','file_name']);

            return $dataTable->make(true);
        }

        $html = $builder->columns([
            ['data' => 'package.name','name' =>'package.name','title' => 'Package', 'defaultContent' => ''],
             ['data' => 'study_material.professor.name', 'name' => 'study_material.professor.name', 'title' => 'Professor', 'defaultContent' => ''],
            ['data' => 'study_material.title', 'name' => 'study_material.title', 'title' => 'Title', 'defaultContent' => ''],
            ['data' => 'study_material.course.name', 'name' => 'study_material.course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'study_material.level.name', 'name' => 'study_material.level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type','name' =>'package_type','title' => 'Package type', 'defaultContent' => ''],
            ['data' => 'study_material.subject.name', 'name' => 'study_material.subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'study_material.chapter.name', 'name' => 'study_material.chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'study_material.language.name', 'name' => 'study_material.language.name', 'title' => 'Language', 'defaultContent' => ''],
           
            ['data' => 'type', 'name' => 'type', 'title' => 'Type','defaultContent' => ''],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File','defaultContent' => ''],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload', 'defaultContent' => ''],
            ['data' => 'study_material.user.name', 'name' => 'study_material.user.name', 'title' => 'Added By','orderable' => false,'defaultContent' => ''],
//            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ])->parameters([
            'searching' => true,
            'ordering' => true,
            'lengthChange' => true,
//            'pageLength'=> 2,
            'bInfo' => false
        ]);

        return view('pages.packages.study_materials.index',compact('html'));
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
        if($request->filled('selected_study_materials')) {
            foreach ($request->selected_study_materials as $selected_study_material) {
                $package_study_materials  = PackageStudyMaterial::updateOrCreate(
                                            ['package_id' => $request->package_id,
                                             'study_material_id' => $selected_study_material
                                            ]);
                $package_study_materials->save();
            }
        }
        if($request->filled('removed_study_materials')) {
            foreach ($request->removed_study_materials as $removed_study_material) {
                $package_study_materials  = PackageStudyMaterial::where('study_material_id',$removed_study_material)
                                                                 ->where('package_id',$request->package_id)
                                                                 ->first();
                if($package_study_materials){
                    $package_study_materials->delete();
                }

            }
        }
        return redirect()->back()->with('success', 'Study Materials updated successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Builder $builder,$id)
    {
        $package = Package::find($id);

        $packageIDs = [];

        if ($package->type == 2) {
            $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');

            foreach ($chapterPackageIDs as $chapterPackageID) {
                $packageIDs[] = $chapterPackageID;
            }
        } else {
            $packageIDs[] = $package->id;
        }

        $videoIDs = PackageVideo::whereIn('package_id', $packageIDs)->get()->pluck('video_id');

        $chapterIDs = Package::whereIn('id', $packageIDs)->get()->pluck('chapter_id')->unique()->values();
        $subjectIDs = Package::whereIn('id', $packageIDs)->get()->pluck('subject_id')->unique()->values();
        $professorIDs = Video::whereIn('id', $videoIDs)->get()->pluck('professor_id')->unique()->values();


        $query = StudyMaterialV1::query()->with('course','level','subject','chapter','language','professor','packages')->withCount(['packages']);

        $query->where(function($query) use($chapterIDs, $subjectIDs, $professorIDs) {
            $query->whereIn('chapter_id', $chapterIDs)
                ->orWhereIn('subject_id', $subjectIDs)
                ->orWhereIn('professor_id', $professorIDs);
        });

        if (request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('flag', function($study_material) use($id){
                    if($study_material->packages_count) {
                        foreach ($study_material->packages as $package) {
                            if ($package->id == $id) {
                                return true;
                            }
                        }
                    }
                    else{
                        return false;
                    }

                })
                ->editColumn('type', function($query) {
                    if($query->type == StudyMaterialV1::STUDY_MATERIALS)
                        return 'STUDY MATERIAL';
                    else if($query->type == StudyMaterialV1::STUDY_PLAN)
                        return 'STUDY PLAN';
                    else{
                        return 'TEST PAPER';
                    }
                })
                ->editColumn('chapter.name', function($query) {
                    if($query->chapter){
                        return $query->chapter->name;
                    }
                    else{
                        return '-';
                    }

                })
                ->editColumn('file_name', function($query) {
                    if($query->file_name )
                        return '<a target="_blank" href="'.$query->file.'">'. substr($query->file_name,10).'</a>';
                })
                ->addColumn('action', 'pages.study_materials.action')
                ->rawColumns(['type','file_name','action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id','name' => 'id', 'title' => '<input class="select-all" name="select_all" type="checkbox">','render' => ' renderCheckbox(data, type, full, meta)', 'searchable' => false, 'orderable' => false, 'width' => '50px' ],
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course'],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level'],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject'],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter'],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language'],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor'],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
//            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ])->parameters([
            'searching' => true,
            'ordering' => true,
            'lengthChange' => true,
//            'pageLength'=> 2,
            'bInfo' => false
        ]);

        return view('pages.packages.study_materials.create',compact('id','html'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchProfessorsFromVideos(Request  $request)
    {
        $videos = $request->videos;

        $professorIDs = PackageVideo::with('video')->whereHas('video',function ($query)use($videos){
                                        $query->whereIn('id',$videos);
                                    })->get()
            ->pluck('video.professor_id')->unique();

        return $professorIDs;
    }

    public function fetchProfessorsFromPackages(Request  $request)
    {
        $packageIDs = $request->packages;

        $professorIDs = PackageVideo::whereIn('package_id', $packageIDs)
                                    ->with('video')
                                    ->get()
                                    ->pluck('video.professor_id')
                                    ->unique();
        return $professorIDs;
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
        //
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

    public function getDataForStudyMaterials()
    {
        $packages = request('packages');

        $packages = Package::whereIn('id', $packages)->get();

        $packageIDs = [];

        foreach ($packages as $package) {
            if ($package->type == 2) {
                $chapterPackageIDs = SubjectPackage::where('package_id', $package->id)->get()->pluck('chapter_package_id');

                foreach ($chapterPackageIDs as $chapterPackageID) {
                    $packageIDs[] = $chapterPackageID;
                }
            } else {
                $packageIDs[] = $package->id;
            }
        }

        $videoIDs = PackageVideo::whereIn('package_id', $packageIDs)->get()->pluck('video_id');

        $chapterIDs = Package::whereIn('id', $packageIDs)->get()->pluck('chapter_id')->unique()->values();
        $subjectIDs = Package::whereIn('id', $packageIDs)->get()->pluck('subject_id')->unique()->values();
        $professorIDs = Video::whereIn('id', $videoIDs)->get()->pluck('professor_id')->unique()->values();

        return response()->json(['chapters' => $chapterIDs, 'subjects' => $subjectIDs, 'professors' => $professorIDs]);
    }
}
