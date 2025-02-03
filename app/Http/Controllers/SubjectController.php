<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use App\Models\Subject;
use App\Models\PackageType;
use App\Models\LevelType;
use App\Models\Video;
use App\Models\Professor;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = Subject::query()
                ->with('level')
                ->with('package_type')
                ->with('course')
                ->whereHas('course')
                ->whereHas('level');

            if (request()->filled('filter.search')) {
                $query->where(function ($query) {
                    $query->where('name','like','%'. request()->input('filter.search') .'%')
                        ->orWhere(function ($query) {
                            $query->where(function ($query) {
                                $query->whereHas('course', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('level', function ($query) {
                                    $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                })->orWhereHas('package_type', function ($query) {
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
            if (request()->filled('filter.package_type')) {
                $query->where('package_type_id', request()->input('filter.package_type'));
            }

            return DataTables::of($query)

                ->addColumn('action', 'pages.subjects.action')
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Subject'],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => 'Type', 'orderable' => false],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'orderable' => false],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'orderable' => false],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]
        ]);

        return view('pages.subjects.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = PackageType::where('is_enabled',true)->get();
        return view('pages.subjects.create',compact('types'));
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
        ])->validate();

        $subject = new Subject();
        $subject->name = $request->input('name');
        $subject->course_id = $request->input('course_id');
        $subject->package_type_id =$request->input('package_type');
        $subject->level_id = $request->input('level_id');
        $subject->save();

        return redirect(route('subjects.index'))->with('success', 'Subject successfully created');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subject = Subject::with('course', 'level','package_type')->findOrFail($id);
        $types = LevelType::with(['packagetype'=> function($types){
                                  $types->where('is_enabled', TRUE);
                                }])
                        ->where('level_id',$subject->level_id)
                        ->get();
        return view('pages.subjects.edit', compact('subject','types'));
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'course_id' => 'required',
            'level_id' => 'required',
        ])->validate();

        $subject = Subject::findOrFail($id);

        $subject->course_id = $request->course_id;
        $subject->level_id = $request->level_id;
        $subject->package_type_id =$request->package_type;
        $subject->name = $request->name;

        $subject->save();

        return redirect(route('subjects.index'))->with('success', 'Subject successfully updated');;
    }

//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);

        // $subject->delete();

        // return response()->json(true, 200);

         /***************TE Modified***********/
         if($subject->is_enabled==true){           
            $subject->is_enabled= false;                
        }
    else{
        $subject->is_enabled=true;
        
    }       
    $subject->save();
        return response()->json(true, 200);
        
    /***************TE ends*************/
    }
//
//    public function level_from_course($course_id){
//
//        $level_list = Level::where('course_id', $course_id)->get();
//
//        $str ="";
//
//        foreach ($level_list as $level)
//        {
//            $str .= '<option value="'.$level->id.'">';
////            if( $level_id== $level->id)
////                $str .= 'selected="selected">';
////            else
////                $str .= '>';
//            $str .= $level->name.'</option>';
//        }
//        echo $str;
//    }

    public function LevelSubjects(Request $request) {

        $levelId = $request->id;
        $subjects = Subject::where('level_id', $levelId)
            ->orderBy('name','asc')
            ->get();

        return json_encode($subjects);

    }

    public function SubjectProfessors(Request $request){

        $professorIds = Video::where('subject_id', $request->id)->pluck('professor_id');
        $professorIds = collect($professorIds);
        $professorIds = $professorIds->unique();
        $professors = Professor::whereIn('id', $professorIds)->orderBy('name')->get();
        return json_encode($professors);
    }
}
