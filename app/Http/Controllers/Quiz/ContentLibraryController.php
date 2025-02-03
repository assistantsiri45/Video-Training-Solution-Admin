<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\ContentLibrary;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Chapter;
use App\Models\Quiz\Subject;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Board;
use App\Models\Quiz\LearningStage;
use App\Models\Quiz\Taxonomy;


class ContentLibraryController extends Controller
{
    protected $grade;
    protected $subject;
    protected $chapter;
    protected $concept;

    public function __construct(Grade $grade, Subject $subject, Chapter $chapter, Concept $concept)
    {
        $this->middleware('auth');
        $this->grade   = $grade;
        $this->subject = $subject;
        $this->chapter = $chapter;
        $this->concept = $concept;
    }

    public function index() {
        $datas = ContentLibrary::all();
        // dd($datas[0]->getGrade);
        return view('quiz.content_library.index')->with(['datas' => $datas]);
    }

    public function create() {
        $boards   = Board::where('status',1)->get();
        $grades   = Grade::where('status',1)->get();
        $subjects = Subject::where('status',1)->get();
        $learning_stage = LearningStage::where('status',1)->get();
        $taxonomy = Taxonomy::where('status',1)->get();
        return view('quiz.content_library.create',['boards' => $boards,'grades' => $grades,'subjects' => $subjects, 'learning_stage' => $learning_stage, 'taxonomy' => $taxonomy]);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'concept' => 'required',
                'board' => 'required',
                'grade' => 'required',
                'subject' => 'required',
                'chapter' => 'required',
                'content_type' => 'required',
                'taxonomy' => 'required|numeric',
                'learning_stage' => 'required|numeric',
//                'attachment' => 'required | mimes:jpeg,jpg,png,html,mp3,mp4,pdf,doc,docx,excel,flv',
                'attachment' => 'required',
                'thumbnail' => 'required',
                'status' => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

//            if( $request->hasFile('attachment') ){
                $file_url = mv_upload( $request->attachment, 'uploads' );
                $file_url1 = mv_upload( $request->thumbnail, 'uploads' );
//            }
            $data = new ContentLibrary();
            $data->name = $request->name;
            $data->board_id = $request->board;
            $data->grade_id = $request->grade;
            $data->subject_id = $request->subject;
            $data->chapter_id = $request->chapter;
            $data->concept_id = $request->concept;
            $data->content_type = $request->content_type;
            $data->taxonomy_id = $request->taxonomy;
            $data->learning_stage_id = $request->learning_stage;
            $data->content_original_name = $request->attachment->getClientOriginalName();
            $data->url = $file_url;
            $data->thumbnail = $file_url1;
            $data->status = $request->status;
            $data->added_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/content_library')->with('message', 'Content Added Successefully');
            // return $this->redirectToIndex('content_library', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
     $boards    = Board::where('status',1)->get();
     $grades   = Grade::where('status',1)->get();
     $subjects = Subject::where('status',1)->get();
     $learning_stage = LearningStage::where('status',1)->get();
     $taxonomy = Taxonomy::where('status',1)->get();
     $data      = ContentLibrary::find($id);
     $master_id = $this->allParentId($data);
     $chapters  = Chapter::where('subject_id',$data->subject_id)->get();
     $concepts  = Concept::where('chapter_id',$data->chapter_id)->get();
     return view('quiz.content_library.edit')->with(['data' => $data, 'boards' => $boards, 'grades' => $grades, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'learning_stage' => $learning_stage, 'taxonomy' => $taxonomy]);
    }

    public function allParentId($data){
        // dd($data->getConcept->getChapter->getSubject->getGrade);
      $result_id['board']   = Board::getAllBoard();
      $result_id['grade']   = Grade::get_all_grade();
      $result_id['subject'] = $data->getConcept->getChapter->getSubject->id;
      $result_id['chapter'] = $data->getConcept->getChapter->id;
      $result_id['concept'] = $data->getConcept->id;
      return $result_id;
    }

    public function update(Request $request, $id) {
        try {
//        dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'board' => 'required',
                'grade' => 'required',
                'subject' => 'required',
                'chapter' => 'required',
                'concept' => 'required',
                'content_type' => 'required',
                'taxonomy' => 'required|numeric',
                'learning_stage' => 'required|numeric',
//                'attachment' => 'required | mimes:jpeg,jpg,png,html,mp3,mp4,pdf,doc,docx,excel,flv',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if($request->hasFile('attachment')){
            $file_url = mv_upload( $request->attachment, 'uploads' );
            }
            if($request->hasFile('thumbnail')){
                $file_url1 = mv_upload( $request->thumbnail, 'uploads' );
            }

            $data = ContentLibrary::find($id);
            $data->name           = $request->name;
            $data->board_id = $request->board;
            $data->grade_id = $request->grade;
            $data->subject_id = $request->subject;
            $data->chapter_id = $request->chapter;
            $data->concept_id = $request->concept;
            $data->content_type    = $request->content_type;
            $data->taxonomy_id = $request->taxonomy;
            $data->learning_stage_id = $request->learning_stage;
            if( $request->hasFile('attachment') ){
            $data->content_original_name = $request->attachment->getClientOriginalName();
            $data->url          = $file_url;
            }
            if($request->hasFile('thumbnail')){
                $data->thumbnail          = $file_url1;
            }
            $data->status       = $request->status;
            $data->added_by     = 1;
            $data->updated_by   = 1;
            $data->save();

            return redirect('/quiz/content_library')->with('message', 'Content Updated Successefully');
            // return $this->redirectToIndex('content_library', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        ContentLibrary::destroy($id);
        return redirect('/quiz/content_library')->with('message', 'Content Deleted Successefully');
        // return $this->redirectToIndex('content_library', config('constants.message.delete'));
    }


}
