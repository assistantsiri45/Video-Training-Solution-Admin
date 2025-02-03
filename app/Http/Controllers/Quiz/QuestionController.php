<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Paragraph;
use Illuminate\Http\Request;
use App\Models\Quiz\Question;
use App\Models\Quiz\Answer;
use App\Models\Quiz\Instruction;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Chapters;
use App\Models\Quiz\Subjects;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Board;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use DB;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use App\Models\Quiz\Chapter;
use Illuminate\Support\Facades\Log;


class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Question::orderBy('id','DESC')->get();
        return view('quiz.question.index')->with(['datas' => $datas]);
    }

    public function show() {
        $datas = Question::where('is_paragraph', 1)->distinct('paragraph_id')->get();
       // dd($datas[0]->getParagraph->getQuestions[0]->question);
        return view('quiz.question.index2')->with(['datas' => $datas]);
    }

    public function create(Request $request) {
        $data = array();
        if($request->id){
            $data  = Question::find($request->id);
            $chapter = Chapter::where('subject_id',$data->subject_id)->get();
        }
        else{
            $chapter = Chapter::all();
        }
        // echo "<pre>"; print_r($request->all()); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");
        $boards = Board::all();
        $grades = Grade::all();
        $subjects = Subjects::all();

        return view('quiz.question.create',['boards' => $boards,'data'=>$data, 'grades' => $grades, 'subjects' => $subjects, 'chapter' => $chapter ]);
    }

    public function store(Request $request) {
         try {
             $validator = Validator::make($request->all(), [
                 'board' => 'required',
                 'grade' => 'required',
                 'subject' => 'required',
                 'chapter' => 'required',
                 // 'concept' => 'required',
                 'question_type' => 'required',
                 'content_type' => 'required',
                 'options' => 'required',
             ],
                 [
                    'grade.required' => 'The level field is required.',
                    'board.required' => 'The course field is required.',
                ]
                );

             if ($validator->fails()) {
                 return Redirect::back()
                     ->withErrors($validator)
                     ->withInput();
             }
            // dd($request->all());
             // echo "<pre>"; print_r($request->all()); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");
            if($request->updated_id){
                $data = Question::find($request->updated_id);
            }else{
                $data = new Question();
            }
            
            $data->board_id     = $request->board;
            $data->grade_id     = $request->grade;
            $data->subject_id     = $request->subject;
            $data->chapter_id     = $request->chapter;
            // $data->concept_id     = $request->concept;
            $data->question_type  = $request->question_type;
            $data->content_type   = $request->content_type;
            $data->no_of_options  = $request->options;
            $data->negative       = $request->negative;
            $data->is_paragraph   = $request->has('is_paragraph') ? 1 : 0;
            $data->created_by     = 1;
            $data->updated_by     = 1;
            $data->save();

            return redirect(route('quiz.question.step-2', ['QID' => $data->id]));

         }catch (\Exception $e) {
             return Redirect::back()
                 ->withErrors('Something went wrong. Please try again!')
                 ->withInput();
         }

    }

    public function Step2($QID) {

        $data      = Question::find($QID);
        $instructions  = Instruction::all();
        $paragraphs  = Paragraph::all();
        return view('quiz.question.step')->with(['ques_details' => $data, 'paragraphs' => $paragraphs, 'instructions' => $instructions]);
    }

    public function StepSubmit(Request $request, $QID) {

        try{
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'question_description' => 'required',
            'difficulty' => 'required',
            'instruction' => 'required',
            'score' => 'required',
            'minutes' => 'required',
            'seconds' => 'required',
            // 'order_by' => 'required',
            'ans_content_type.*' => 'required',
            'answer.*' => 'required',
            'is_correct' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = Question::find($QID);
        $data->question = $request->question;
        $data->question_desc = $request->question_description;
        $data->difficulty = $request->difficulty;
        $data->instruction_id = $request->instruction;
        $data->score = $request->score;
        $data->status = $request->status;
        $data->time = ($request->minutes * 60) + $request->seconds;
        // $data->order_by = $request->order_by;
        $data->correct_feedback = $request->correct_feedback;
        $data->incorrect_feedback = $request->incorrect_feedback;
        $data->partially_feedback = $request->partially_feedback;
        if($data->is_paragraph){
            $data->paragraph_id = $request->paragraph;
        }
        $data->save();

        for($i=0; $i<$data->no_of_options; $i++)
        {

        if(isset($request->is_correct[$i])){
        $is_correct = 1;
        }else{
        $is_correct = 0;
        }

        $data1 = new Answer();
        $data1->question_id  = $QID;
        $data1->content_type = $request->ans_content_type[$i];
        $data1->answer       = $request->answer[$i];
        $data1->is_correct   = $is_correct;
        $data1->feedback     = $request->feedback[$i];
        $data1->order_by     = $i+1;
        $data1->status       = 1;
        $data1->created_by   = 1;
        $data1->updated_by   = 1;
        $data1->save();
        }

        if($request->has('save')){
            return redirect('/quiz/question')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('question', config('constants.message.save'));
        }elseif($request->has('next')){
            return redirect(route('quiz.question.step-2', ['QID' => $QID]));
        }
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function edit($id) {
        $data      = Question::find($id);
        $instructions  = Instruction::all();
        $paragraphs  = Paragraph::all();
        return view('quiz.question.edit')->with(['ques_details' => $data, 'instructions' => $instructions,'paragraphs' => $paragraphs]);
    }

    public function update(Request $request, $id) {
        try{
            $validator = Validator::make($request->all(), [
                'question' => 'required',
                'question_description' => 'required',
                'difficulty' => 'required',
                'instruction' => 'required',
                'score' => 'required',
                'minutes' => 'required',
                'seconds' => 'required',
                // 'order_by' => 'required',
                'ans_content_type' => 'required',
                'answer.*' => 'required',
                'is_correct' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Question::find($id);
            $data->question = $request->question;
            $data->question_desc = $request->question_description;
            $data->difficulty = $request->difficulty;
            $data->instruction_id = $request->instruction;
            $data->score = $request->score;
            $data->status = $request->status;
            $data->time = ($request->minutes * 60) + $request->seconds;
            // $data->order_by = $request->order_by;
            $data->correct_feedback = $request->correct_feedback;
            $data->incorrect_feedback = $request->incorrect_feedback;
            $data->partially_feedback = $request->partially_feedback;
            if($data->is_paragraph){
                $data->paragraph_id = $request->paragraph;
            }
            $data->save();
            $ids = $data->getOptions->pluck('id')->toArray();
            $i = 0;
            foreach($ids as $id)
            {

                if(isset($request->is_correct[$i])){
                    $is_correct = 1;
                }else{
                    $is_correct = 0;
                }

                $data1 = Answer::find($id);
                $data1->content_type = $request->ans_content_type[$i];
                $data1->answer       = $request->answer[$i];
                $data1->is_correct   = $is_correct;
                $data1->feedback     = $request->feedback[$i];
                $data1->order_by     = $i+1;
                $data1->status       = 1;
                $data1->created_by   = 1;
                $data1->updated_by   = 1;
                $data1->save();

                $i++;
            }

            return redirect('/quiz/question')->with('message', 'Successfully updated the information');
            // return $this->redirectToIndex('question', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Question::destroy($id);
        return redirect('/quiz/question')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('question', config('constants.message.delete'));
    }

    public function allParentId($data){
      $result_id['board']   = $data->getConcept->getChapter->getSubject->getGrade->getBoard->id;
      $result_id['grade']   = $data->getConcept->getChapter->getSubject->getGrade->id;
      $result_id['subject'] = $data->getConcept->getChapter->getSubject->id;
      $result_id['chapter'] = $data->getConcept->getChapter->id;
      // $result_id['concept'] = $data->getConcept->id;
      return $result_id;
    }

    public function uploadExcelView()
    {
        return view('quiz.excel.upload_excel');
    }

    public function uploadQuestionExcel(Request $request)
    {
        $data=$request->all();
        $question = new Question;
        DB::beginTransaction();
        try {
            $result=$question->uploadQuestion($data);
            $readexcel = $result['readexcel'];
        $insert_data = array();
        $result_show = array();
        $sheet_data =array_filter($readexcel['sheet_data']);
        unset($sheet_data[1]);
        foreach ($sheet_data as $sheet_data_loop) {
            // echo "<pre>"; print_r($sheet_data_loop); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");
            
            $validator = Validator::make($sheet_data_loop, [
                'A' => 'required',
                'B' => 'required',
                'C' => 'required',
                'D' => 'required',
                'E' => 'required',
                'G' => 'required',
                'H' => 'required',
                'I' => 'required',
                'J' => 'required',
                'K' => 'required_if:G,Text',
                'L' => 'required_if:G,!=,Text',
                'P' => 'required_if:P,Yes',
                'Q' => 'required_if:P,Yes',
                'R' => 'required_if:P,Yes',
                'S' => 'required',
                'T' => 'required',
                'U' => 'required',
                'V' => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $questions    = new \App\Models\Quiz\Question;
            
            $board       = new \App\Models\Quiz\Board;
            $grade       = new \App\Models\Quiz\Grade;
            $subject     = new \App\Models\Quiz\Subjects;
            $chapter     = new \App\Models\Quiz\Chapters;
            $concept     = new \App\Models\Quiz\Concept;
            $instruction = new \App\Models\Quiz\Instruction;
            $paragraph = new \App\Models\Quiz\Paragraph;

            $board_id       = $board->getBoardId($sheet_data_loop['A']);
            $grade_id       = $grade->getGradeId($sheet_data_loop['B']);
            $subject_id     = $subject->getSubjectId($sheet_data_loop['C']);
            $chapter_id     = $chapter->getChapterId($sheet_data_loop['D']);
            // $concept_id     = $concept->getConceptId($sheet_data_loop['E']);
            $instruction_id = $instruction->getInstructionId($sheet_data_loop['E']);
            if($instruction_id != 0){
                $questions->instruction_id  = $instruction_id;
            }
            $paragraph_id   = $paragraph->getParagraphId($sheet_data_loop['P']);
            if($paragraph_id != 0){
                $questions->paragraph_id  = $paragraph_id;
            }
            $ans_count = $sheet_data_loop['U'];
            // dd($sheet_data_loop['J']);
            $questions->board_id           = $board_id;
            $questions->grade_id           = $grade_id;
            $questions->subject_id         = $subject_id;
            $questions->chapter_id         = $chapter_id;
            $questions->concept_id         = 1;
            
            $questions->content_type       = $sheet_data_loop['F'];
            $questions->question_type      = $sheet_data_loop['G'];
            $questions->difficulty         = $sheet_data_loop['H'];
            $questions->question           = $sheet_data_loop['I'];
            $questions->question_desc      = $sheet_data_loop['J'];
            $questions->attachment         = $sheet_data_loop['K'];
            $questions->correct_feedback   = $sheet_data_loop['L'];
            $questions->incorrect_feedback = $sheet_data_loop['M'];
            $questions->partially_feedback = $sheet_data_loop['N'];
            if($sheet_data_loop['O'] =='Yes'){
                $questions->is_paragraph       = 1;
            }
            
            $questions->score              = $sheet_data_loop['R'];
            $questions->order_by           = $sheet_data_loop['S'];
            $questions->time               = $sheet_data_loop['S']/60;
            $questions->status             = 1;
            $questions->created_by         = 1;
            $questions->updated_by         = 1;
            $questions->save();
            $j ='V';
            for($i=0; $i<$ans_count; $i++)
            {
                $answer               = new \App\Models\Quiz\Answer;
                $answer->question_id  = $questions->id;
                $answer->content_type = $sheet_data_loop[$j];
                $j++;
                $answer->answer       = $sheet_data_loop[$j];
                $j++;
                $answer->attachment   = $sheet_data_loop[$j];
                $j++;
                if($sheet_data_loop[$j] =='Yes'){
                    $answer->is_correct = 1;
                }
                $j++;
                $answer->feedback   = $sheet_data_loop[$j];
                $j++;
                // dd($j);
                $answer->order_by     = 1;
                $answer->status       = 1;
                $answer->created_by   = 1;
                $answer->updated_by   = 1;
                $answer->save();  
                  
            }
            
        }
            DB::commit();
            return redirect('/quiz/question')->with('message',$result['error_message']);

      } catch (\Exception $e) {
            DB::rollback();
            $response['status'] = 500;
            $response['error'] = $e->getMessage();

         return $response;
      }
    }

    public function view($id){
        $data = Question::find($id);
        // dd($data);
        // dd($data->getOptions);
        if(!empty($data->paragraph_id) && $data->is_paragraph == 1)
        {
        //   $data = Paragraph::find($data->paragraph_id);
          return view('quiz.question.viewpara')->with(['data' => $data]);
        }else{
          return view('quiz.question.view')->with(['data' => $data]);
        }
        // $id   = $data->getOptions;
        // dd($id);
    }

}
