<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Module;
use App\Models\Subject;
use App\Models\LevelType;
use DemeterChain\C;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Validator;
use App\Models\PackageVideo;
use App\Models\PackageType;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {

        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $chapters = Chapter::orderBy('name')->get();

        if (request()->ajax()) {
            $query = Module::query()
                ->with('level')
                ->with('course')
                ->with('package_type')
                ->with('subject')
                ->with('chapter')
                ->whereHas('course');

            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function($query) {
                            $query->where('name', 'like', '%' . request()->input('filter.search') . '%')
                                ->orWhere(function($query) {
                                    $query->whereHas('course', function($query) {
                                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                });
                        });
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
                    if (request()->filled('filter.chapter')) {
                        $query->where('chapter_id', request()->input('filter.chapter'));
                    }
                })
                ->addColumn('action', 'pages.modules.action')
                ->editColumn('chapter', function($query) {
                    return $query->chapter->name ?? '-';
                })
                ->editColumn('subject', function($query) {
                    return $query->subject->name ?? '-';
                })
                ->editColumn('package_type.name', function($query) {
                    if ($query->package_type) {
                        return $query->package_type->name;
                    }
                    return '-';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Module'],
            ['data' => 'chapter', 'name' => 'name', 'title' => 'Chapter'],
            ['data' => 'subject', 'name' => 'name', 'title' => 'Subject'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'orderable' => false],
            ['data' => 'level.name', 'name' => 'name', 'title' => 'Level',  'searchable' => false, 'orderable' => false],
            ['data' => 'course.name', 'name' => 'name', 'title' => 'Course',  'searchable' => false, 'orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ]);

        return view('pages.modules.index', compact('html','courses','subjects','chapters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = PackageType::where('is_enabled',true)->get();
        return view('pages.modules.create',compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
        ])->validate();

        $module = new Module();
        $module->name = $request->input('name');
        $module->course_id = $request->input('course_id');
        $module->level_id = $request->input('level_id');
        $module->package_type_id =$request->input('package_type');
        $module->subject_id = $request->input('subject_id');
        $module->chapter_id = $request->input('chapter_id');
        $module->save();

        return redirect(route('modules.index'))->with('success', 'Module successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function show(Chapter $chapter)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::with('course', 'level', 'subject', 'chapter')->findOrFail($id);

        $types = LevelType::with(['packagetype'=> function($types){
                                   $types->where('is_enabled', TRUE);
                                }])
                            ->where('level_id',$module->level_id)
                            ->get();
        return view('pages.modules.edit', compact('module','types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
        ])->validate();

        $module = Module::findOrFail($id);

        $module->course_id = $request->course_id;
        $module->level_id = $request->level_id;
        $module->package_type_id =$request->package_type;
        $module->subject_id = $request->subject_id;
        $module->chapter_id = $request->chapter_id;
        $module->name = $request->name;

        $module->save();

        return redirect(route('modules.index'))->with('success', 'Chapter successfully updated');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $module = Module::findOrFail($id);

         // $module->delete();

        // return response()->json(true, 200);
        /*********Modified by TE********************/
        if($module->is_enabled==true){
           
            $module->is_enabled= false;
    }
    else{
        $module->is_enabled=true;
    }   
    $module->save();
        return response()->json(true, 200);    

    /***************TE ends*************/
    }
    public function ChapterModule(Request  $request){
        $chapterId = $request->id;
        $modules = Module::where('chapter_id', $chapterId)
            ->orderBy('name','asc')
            ->get();

        return json_encode($modules);
    }
}
