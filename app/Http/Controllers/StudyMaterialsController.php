<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Level;
use App\Models\Professor;
use App\Models\StudyMaterial;
use App\Models\StudyMaterialV1;
use App\Models\LevelType;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\PackageType;

class StudyMaterialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = StudyMaterialV1::with('course','level','subject','chapter','language','professor','user','package_type')->orderBy('created_at','desc');
        $subjects = Subject::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        $professors = Professor::orderBy('name')->get();

        if (request()->ajax()) {
            return DataTables::of($query)
                ->filter(function($query) {
                    if (request()->filled('filter.search')) {
                        $query->where(function($query) {
                            $query->where('title', 'like', '%' . request()->input('filter.search') . '%')
                                ->orWhere(function($query) {
                                    $query->whereHas('professor', function($query) {
                                        $query->where('name', 'like', '%' . request()->input('filter.search') . '%');
                                    });
                                });
                        });
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
                        $query->where('package_type_id', request()->input('filter.package_type'));
                    }
                    
                    if (request()->filled('filter.subject')) {
                        $query->where('subject_id', request()->input('filter.subject'));
                    }
                    if (request()->filled('filter.professor')) {
                        $query->where('professor_id', request()->input('filter.professor'));
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
                ->editColumn('package_type.name', function($query) {
                    if ($query->package_type) {
                        return $query->package_type->name;
                    }
                    return '-';
                })
//                ->editColumn('chapter.name', function($query) {
//                    if($query->chapter){
//                        return $query->chapter->name;
//                    }
//                    else{
//                        return '-';
//                    }
//
//                })
                ->editColumn('file_name', function($query) {
                    if($query->file_name )
                        return '<a target="_blank" href="'.$query->file.'">'. substr($query->file_name,10).'</a>';
                })
                ->editColumn('created_at', function ($query) {
                    return Carbon::parse($query->created_at)->format('d M Y');
                })
                ->editColumn('user.name', function($query) {
                    if($query->user){
                        return $query->user->name;
                    }
                    else{
                        return '-';
                    }
                })
                ->addColumn('action', 'pages.study_materials.action')
                ->rawColumns(['type','file_name','action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'title', 'name' => 'title', 'title' => 'Title'],
            ['data' => 'course.name', 'name' => 'course.name', 'title' => 'Course', 'defaultContent' => ''],
            ['data' => 'level.name', 'name' => 'level.name', 'title' => 'Level', 'defaultContent' => ''],
            ['data' => 'package_type.name', 'name' => 'package_type.name', 'title' => ' Package Type', 'defaultContent' => ''],
            ['data' => 'subject.name', 'name' => 'subject.name', 'title' => 'Subject', 'defaultContent' => ''],
            ['data' => 'chapter.name', 'name' => 'chapter.name', 'title' => 'Chapter', 'defaultContent' => ''],
            ['data' => 'language.name', 'name' => 'language.name', 'title' => 'Language', 'defaultContent' => ''],
            ['data' => 'professor.name', 'name' => 'professor.name', 'title' => 'Professor', 'defaultContent' => ''],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Date of Upload', 'defaultContent' => ''],
            ['data' => 'user.name', 'name' => 'user.name', 'title' => 'Added By','orderable' => false,'defaultContent' => ''],
            ['data' => 'type', 'name' => 'type', 'title' => 'Type'],
            ['data' => 'file_name', 'name' => 'file_name', 'title' => 'File'],
            ['data' => 'action', 'name' => 'action', 'title' => '', 'searchable' => false, 'orderable' => false, 'width' => '10%']
        ])->parameters([
            'searching' => true,
            'ordering' => true,
            'lengthChange' => false,
            'bInfo' => false,
            'stateSave'=> true,
        ]);

        return view('pages.study_materials.index', compact('html','courses','subjects','professors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = PackageType::where('is_enabled',true)->get();
        return view('pages.study_materials.create',compact('types'));
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
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'language_id' => 'required',
            'professor_id' => 'required',
            'type' => 'required',
            'study_materials.*' => 'mimes:pdf',
            'title.*' => 'required'
        ])->validate();

        if($request->hasFile('study_materials')){
            $count = count($request->file('study_materials'));
        }
        else{
            return;
        }
        for($i = 0 ; $i<= $count-1 ; $i++) {
            $store = new StudyMaterialV1();
            $store->course_id = $request->course_id;
            $store->level_id = $request->level_id;
            $store->package_type_id =$request->package_type;
            $store->subject_id = $request->subject_id;
            if($request->chapter_id){
                $store->chapter_id = $request->chapter_id;
            }
            $store->language_id = $request->language_id;
            $store->professor_id = $request->professor_id;
            $store->type = $request->type;
            $files = $request->file('study_materials');

            $filename = time() . $files[$i]->getClientOriginalName();
            $files[$i]->storeAs('public/study_materials/', $filename);
            $store->file_name = $filename;

            $title = $request->input('title');
            $store->title = $title[$i];
            $store->added_by = Auth::id();

            $store->save();
        }
        return redirect(route('study-materials.index'))->with('success', 'Study Material uploaded successfully');
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
        $studyMaterial = StudyMaterialV1::findOrFail($id);
        $types = LevelType::with('packagetype')
                ->where('level_id',$studyMaterial->level_id)
                ->get();
        return view('pages.study_materials.edit', compact('studyMaterial','types'));
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

//        return $request->all();
        $validator = Validator::make($request->all(), [
            'course_id' => 'required',
            'level_id' => 'required',
            'subject_id' => 'required',
            'language_id' => 'required',
            'professor_id' => 'required',
            'type' => 'required',
//            'file_name' => 'mimes:pdf',
            'title' => 'required'
        ])->validate();


        $studyMaterial = StudyMaterialV1::findorFail($id);
        $studyMaterial->course_id = $request->course_id;
        $studyMaterial->level_id = $request->level_id;
        $studyMaterial->package_type_id =$request->package_type;
        $studyMaterial->subject_id = $request->subject_id;
            if($request->chapter_id){
                $studyMaterial->chapter_id = $request->chapter_id;
            }
        $studyMaterial->language_id = $request->language_id;
        $studyMaterial->professor_id = $request->professor_id;
        $studyMaterial->type = $request->type;
        $studyMaterial->title = $request->input('title');
        if($request->hasFile('study_material')) {
            $file = $request->file('study_material');
            $filename = time() . $file->getClientOriginalName();
            $file->storeAs('public/study_materials/', $filename);
            $studyMaterial->file_name = $filename;
            $studyMaterial->created_at=now();
            $studyMaterial->added_by = Auth::id();
        }

        $studyMaterial->save();
        return redirect(route('study-materials.index'))->with('success', 'Study Material uploaded successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $study_material = StudyMaterialV1::findOrFail($id);

         // $study_material->delete();

        // return response()->json(true, 200);
        if($study_material->is_enabled==true){
            $study_material->is_enabled= false;
        }
    else{
        $study_material->is_enabled=true;
        
    }       
    $study_material->save();
    return response()->json(true, 200);
    /***************TE ends*************/
    }

    public function getTableStudyMaterials()
    {
        if (request()->ajax()) {

            $query = StudyMaterialV1::with('course','level','subject','chapter','language','professor');

            if (request()->filled('filter.search')) {
                $query->where('title', 'like', '%' . request()->input('filter.search') . '%');
            }

            if (request()->filled('filter.type')) {
                $query->where('type', request()->input('filter.type'));
            }

            if (request()->filled('filter.language')) {
                $query->where('language_id', request()->input('filter.language'));
            }

            if (request()->filled('filter.professor')) {
                $query->where('professor_id', request()->input('filter.professor'));
            }

            if (request()->filled('filter.package_details')) {
                $query->where(function($query) {
                    $query->whereIn('chapter_id', request()->input('filter.package_details.chapters'))
                        ->orWhereIn('subject_id', request()->input('filter.package_details.subjects'))
                        ->orWhereIn('professor_id', request()->input('filter.package_details.professors'));
                });
            }

//            if (request()->filled('filter.professors')) {
//                $query->whereIn('professor_id', request()->input('filter.professors'));
//            }
//            if (request()->filled('filter.chapter')) {
//                $query->where('chapter_id', request()->input('filter.chapter'));
//            }

//            if (request()->filled('filter.subject')) {
//                $query->where('subject_id',  request()->input('filter.subject'));
//            }


            if (request()->ajax()) {
                return DataTables::of($query)
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
                        if($query->file_name ) {
                            return '<a target="_blank" href="'.$query->file.'"><i class="fas fa-file"></i></a>';
                        }

                        return '';
                    })
                    ->rawColumns(['type','file_name'])
                    ->make(true);
            }
        }
    }
}
