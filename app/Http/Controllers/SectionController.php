<?php

namespace App\Http\Controllers;

use App\Models\Associate;
use App\Models\Course;
use App\Models\Level;
use App\Models\Package;
use App\Models\PackageVideo;
use App\Models\Professor;
use App\Models\Section;
use App\Models\SectionPackage;
use App\Models\Subject;
use App\Models\Video;
use App\Models\SubjectPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Section::query()->orderBy('order');

            return DataTables::of($query)
                ->addColumn('action', 'pages.sections.action')
                ->editColumn('order', function($query) {
                    return '<div class="order">' . $query->order . '<input type="hidden" class="section-id" value="' . $query->id . '"></div>';
                })
                ->editColumn('is_enabled', function ($query) {
                    return Section::$status[$query->is_enabled];
                })
                ->rawColumns(['action', 'order'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'order', 'name' => 'order', 'title' => 'Order'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'is_enabled', 'name' => 'is_enabled', 'title' => 'Enabled/Disabled'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]

        ])->orderBy(0, 'desc');

        return view('pages.sections.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.sections.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $section = new Section();
        $section->name = $request->name;
        $section->is_enabled = $request->input('is_enabled');
        $section->order = Section::query()->count() + 1;

        $section->save();

        return redirect(route('sections.index'))->with('success', 'Section successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Builder $builder, $id)
    {
        $section = Section::findOrFail($id);
        $query = SectionPackage::query()->with('package','section')->where('section_id', $id)->orderBy('order');

        if (request()->ajax()) {
            $dataTable = DataTables::of($query)
                ->filter(function ($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where(function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
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
                            });
                        });
                    }

                    if (request()->filled('filter.course')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('course_id', request()->input('filter.course'));
                            });
                        });
                    }

                    if (request()->filled('filter.level')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('level_id', request()->input('filter.level'));
                            });
                        });
                    }

                    if (request()->filled('filter.subject')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('subject_id', request()->input('filter.subject'));
                            });
                        });
                    }

                    if (request()->filled('filter.chapter')) {
                        $query->where(function ($query) {
                            $query->whereHas('package', function ($query) {
                                $query->where('chapter_id', request()->input('filter.chapter'));
                            });
                        });
                    }

                    if (request()->filled('filter.language')) {
                        $query->whereHas('package', function ($query) {
                            $query->where('language_id', request()->input('filter.language'));
                        });
                    }

                    if (request()->filled('filter.professor')) {
                        $query->whereHas('package', function ($query) {
                            $query->whereHas('chapterVideos', function ($query) {
                                $query->where('professor_id', request()->input('filter.professor'));
                            });
                        });
                    }
                })
                ->editColumn('duration',function ($query){
                    if($query->package->total_duration){
                        return gmdate("H:i:s",$query->package->total_duration);
                    }
                    else{
                        return '-';
                    }
                })
                ->addColumn('action', function($query) {
                    return '<a href="' . url('packages/videos') . '?package_id=' . $query->package_id . '"><i class="fas fa-video"></i></a>';
                })
                ->rawColumns(['action']);

            return $dataTable->make(true);
        }

        $html = $builder->columns([
            ['data' => 'package.name','name' =>'id','title' => 'Package', 'defaultContent' => ''],
            ['data' => 'duration','name' =>'duration','title' => 'Duration', 'defaultContent' => ''],
            ['data' => 'package.description','name' =>'package.description','title' => 'Description', 'defaultContent' => ''],
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => true,
            'pageLength'=> 15,
            'bInfo' => true
        ])
            ->addAction(['title' => '', 'class' => 'text-right p-3', 'width' => 70])
            ->setTableId('section-packages');

        return view('pages.sections.show', compact('html', 'section'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /** @var Section $section */
        $section = Section::findOrFail($id);

        return view('pages.sections.edit', compact('section'));
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
            'name' => 'required',
        ]);

        $section = Section::findOrFail($id);
        $section->name = $request->name;
        $section->is_enabled = $request->input('is_enabled');
        $section->save();

        return redirect(route('sections.index'))->with('success', 'Section successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return response()->json(true, 200);
    }

    public function destroySelectedPackages($id)
    {
        $sectionPackageIDs = request()->input('selected_package_ids');
        $sectionPackageIDs = json_decode($sectionPackageIDs);
        if(!$sectionPackageIDs){
            return redirect(url('sections/' . $id . '/section-packages'))->with([
                'error' => 'Please select at least one package to delete',
            ]);
        }
        $sectionPackages = SectionPackage::query()->whereIn('id', $sectionPackageIDs)->get();

        if ($sectionPackages) {
            foreach ($sectionPackages as $sectionPackage) {
                $sectionPackage->delete();
            }
        }

        return redirect(url('sections/' . $id . '/section-packages'))->with([
            'success' => 'Section Package successfully deleted',
        ]);

    }

    public function changeOrder()
    {
        $sectionIDs = request()->input('sections');

        if ($sectionIDs) {
            $index = 1;

            foreach ($sectionIDs as $sectionID) {
                $section = Section::find($sectionID);

                if ($section) {
                    $section->order = $index;
                    $section->save();
                }

                $index++;
            }
        }
    }

    public function orderSectionPackages($id)
    {
        $sectionPackageIDs = request()->input('section_packages');

        if ($sectionPackageIDs) {
            $index = 1;

            foreach ($sectionPackageIDs as $sectionPackageID) {
                $sectionPackage = SectionPackage::where('section_id', $id)->find($sectionPackageID);

                if ($sectionPackage) {
                    $sectionPackage->order = $index;
                    $sectionPackage->save();
                }

                $index++;
            }
        }
    }

    public function createSectionPackages(Request $request, Builder $builder, $id)
    {
        $section = Section::where('id',$id)->first();
        $sectionPackageIDs = SectionPackage::where('section_id', $section->id)->get()->pluck('package_id');

        if (request()->ajax()) {
            $query = Package::query()->with('course', 'level', 'subject', 'chapter', 'language')
                ->approved()
                ->ofNotPreBooked()
                ->ofActive(true);

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

            return \Yajra\DataTables\DataTables::of($query)
                ->editColumn('duration',function ($query) {
                    if($query->total_duration_formatted) {
                        return $query->total_duration_formatted;
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

        $selectedPackagesCheckbox = '<div class="custom-control custom-checkbox text-center">
                        <input id="select_all_selected_packages" class="custom-control-input select_all_selected_packages" name="select_all_selected_packages" type="checkbox">
                        <label for="select_all_selected_packages" class="custom-control-label"></label>
                    </div>';

        $sectionPackages = app(Builder::class)->columns([
            ['data' => 'package.name','name' =>'id','title' => 'Package', 'defaultContent' => ''],
            ['data' => 'duration','name' =>'duration','title' => 'Duration', 'defaultContent' => ''],
            ['data' => 'package.description','name' =>'package.description','title' => 'Description', 'defaultContent' => ''],
            ['data' => 'id', 'name' => 'id', 'title' => $selectedPackagesCheckbox   , 'render' => 'renderSelectedPackageCheckbox(data)']
        ])->parameters([
            'searching' => false,
            'ordering' => false,
            'lengthChange' => true,
            'pageLength'=> 15,
            'bInfo' => true
        ])
            ->addAction(['title' => '', 'class' => 'text-right p-3', 'width' => 70])
            ->ajax(route('packages.section-packages.index', $id))
            ->setTableId('section-packages');

        return view('pages.sections.packages.index', compact('html','section', 'sectionPackageIDs', 'sectionPackages'));
    }

    public function storeSectionPackages($id)
    {
        $sectionPackageIDs = request()->input('package_ids');
        $sectionPackages = SectionPackage::query()->where('section_id', $id)->get();

        if ($sectionPackages) {
            foreach ($sectionPackages as $sectionPackage) {
                $sectionPackage->delete();
            }
        }

        $sectionPackageIDs = json_decode($sectionPackageIDs);

        if ($sectionPackageIDs) {
            foreach ($sectionPackageIDs as $sectionPackageID) {
                $subjectPackage = new SectionPackage();
                $subjectPackage->section_id = $id;
                $subjectPackage->package_id = $sectionPackageID;
                $subjectPackage->save();
            }
        }
        return redirect(url('sections/' . $id))->with([
            'success' => 'Section Package successfully updated',
        ]);
    }

    public function changePackageOrder($id)
    {
        /** @var Section $section */
        $section = Section::findOrFail($id);

        /** @var array $packageVideoIDs */
        $sectionPackageIDs = SectionPackage::where('section_id', $id)->pluck('package_id');

        return view('pages.sections.order.index', compact('section', 'sectionPackageIDs'));
    }

    public function savePackageOrder($id)
    {
        $sectionPackages = SectionPackage::where('section_id', $id)->get();

        if ($sectionPackages) {
            foreach ($sectionPackages as $sectionPackage) {
                $sectionPackage->delete();
            }
        }

        $sectionPackageIDs = request()->input('package_id');
        $sectionPackageOrder = request()->input('package_order');

        if ($sectionPackageIDs) {
            foreach ($sectionPackageIDs as $index => $sectionPackageID) {
                $subjectPackage = new SectionPackage();
                $subjectPackage->section_id = $id;
                $subjectPackage->package_id = $sectionPackageID;
                $subjectPackage->order = $sectionPackageOrder[$index];
                $subjectPackage->save();
            }
        }

        return redirect(url('sections/' . $id))->with('success', 'Package order successfully updated');
    }

    public function Courselevels(Request $request) {

        $courseId = $request->id;
        $levels = Level::where('course_id', $courseId)
        ->where('is_enabled',TRUE)
            ->orderBy('name','asc')
            ->get();
        return json_encode($levels);
    }

    public function getlevels(Request $request){

        $course_id = $request->id;
        $courses = explode(',',$course_id);
        $levels = Level::whereIn('course_id', $courses)
                    ->where('is_enabled',TRUE)
                    ->orderBy('name','asc')
                    ->get();
        return json_encode($levels);
    }

    public function getSubjects(Request $request){

        $levelId = $request->id;
        $levels = explode(',',$levelId);
        $subjects = Subject::whereIn('level_id', $levels)
        ->where('is_enabled',TRUE)
            ->orderBy('name','asc')
            ->get();

        return json_encode($subjects);
    }

    public function getprofessors(Request $request){

        $subjectId = $request->id;
        $subjects = explode(',',$subjectId);
        $professorIds = Video::whereIn('subject_id', $subjects)->pluck('professor_id');
        $professorIds = collect($professorIds);
        $professorIds = $professorIds->unique();
        $professors = Professor::whereIn('id', $professorIds)->orderBy('name')->get();
        echo json_encode($professors);

        
    }

}
