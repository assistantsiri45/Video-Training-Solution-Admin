<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackageType;
use App\Models\LevelType;
use App\Models\Course;
use App\Models\Level;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class PackageTypeController extends Controller
{
    public function index(Builder $builder)
    {
        if (request()->ajax()) {
            $query = PackageType::query()->orderBy('id');
            return DataTables::of($query)
                ->addColumn('action', 'pages.package_type.action')
                ->editColumn('course.name', function($query) {
                    if(!empty($query->level_type)){
                        $val3='';
                        foreach($query->level_type as $val2){                            
                            if(!empty($val2->course->id)){
                                $val3.=$val2->course->name .' <br> ';                               
                            }
                        }
                         return $val3;
                       }
                    else 
                        return '-';
                })
                ->editColumn('level', function($query) {
                    if(!empty($query->level_type)){
                        $val3='';
                        foreach($query->level_type as $val2){                            
                            if(!empty($val2->level->id)){
                                $val3.=$val2->level->name .' <br> ';                               
                            }
                        }
                         return $val3;
                       }
                    else 
                        return '-';
                })
                ->rawColumns(['action','course.name','level'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'level', 'name' => 'level', 'title' => 'Level'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course','defaultContent' => ''],           
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false]
        ]);

        return view('pages.package_type.index', compact('html'));
    }
     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::where('is_enabled',TRUE)->orderBy('name')->get();
        return view('pages.package_type.create',compact('courses'));
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
            'name' => 'required',
        ]);
        $type = new PackageType();

        $type->name = $request->input('name');
        $type->is_enabled =1;
        $type->save();
        
       if($type->id){
        foreach($request->input('level_id') as $level_id){
            $lev_course=explode("|",$level_id);
            $levelId=$lev_course[0];
            $courseId=$lev_course[1];
        $level_type= new LevelType();
        $level_type->package_type_id=$type->id;
        $level_type->course_id=$courseId;
        $level_type->level_id=$levelId;
        $level_type->save();
        }
        
       }
        return redirect(route('type.index'))->with('success', 'Type successfully created');;
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = PackageType::findOrFail($id);
        $selected_levels=[];
        $selected_course=[];
        foreach($type->level_type as $level_typ){
        $selected_levels[]=@$level_typ->level->id;
        $selected_course[]=@$level_typ->course->id;
        }
        $courses = Course::where('is_enabled',TRUE)->orderBy('name')->get();
       
        return view('pages.package_type.edit', compact('type','selected_levels','selected_course','courses'));
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
            'name' => 'required'
        ]);

        $type = PackageType::findOrFail($id);
        $type->name = $request->input('name');

        $type->save();
        if($type->id){
           LevelType::where('package_type_id',$id)->delete();
            foreach($request->input('level_id') as $level_id){
                $lev_course=explode("|",$level_id);
                $levelId=$lev_course[0];
                $courseId=$lev_course[1]??null;
            $level_type= new LevelType();
            $level_type->package_type_id=$type->id;
            $level_type->course_id=$courseId;
            $level_type->level_id=$levelId;
            $level_type->save();
            }            
        }
        return redirect(route('type.index'))->with('success', 'Type successfully updated');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $type = PackageType::findOrFail($id);

        if($type->is_enabled==true){                
                $type->is_enabled= false;
        }
        else{
            $type->is_enabled=true;
        }        
        $type->save();
            return response()->json(true, 200);
    }

    public function getTypes(Request $request){

        $level_id = $request->id;
        $levels = explode(',',$level_id);
        $types = LevelType::with(['packagetype'=> function($types){
                          $types->where('is_enabled', TRUE);
                         }])
                    ->whereIn('level_id',$levels)
                    ->get();
        return json_encode($types);
    }
    
    public function getLevelsByCourse(Request $request){
       
       

        $levels = Level::whereIn('course_id', $request->course_ids)->where('is_enabled',true)->where('display',true)->orderBy('order')->get();
        //return $this->jsonResponse('Levels', $levels);
     
        return response()->json($levels, 200);
    }
    public function getSubjectsByLevels(Request $request)
    {
       
        if($request->type_id){
           // echo "hi";exit;
            $response = Subject::with('level')->where('level_id',$request->level_ids)->where('package_type_id', $request->type_id)->where('is_enabled',true)->orderBy('name')->get();

        }else{
             $response = Subject::with('level')->where('level_id', $request->level_ids)->where('is_enabled',true)->orderBy('name')->get();
        }
       

        return response()->json($response, 200);
    }
}
