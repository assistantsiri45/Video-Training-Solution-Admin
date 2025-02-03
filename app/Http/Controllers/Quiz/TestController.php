<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Board;
// use App\Models\Quiz\Chapter;
use App\Models\Quiz\Chapters;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Modules;
use App\Models\Quiz\Question;
// use App\Models\Quiz\Subject;
use App\Models\Quiz\Subjects;
use App\Models\Quiz\TestContent;
use App\Models\Quiz\TestModuleQuestions;
use App\Models\Quiz\TestModulesAuto;
use Illuminate\Http\Request;
use App\Models\Quiz\Test;
use App\Models\Quiz\TestModules;
use App\Models\Quiz\Instruction;
use App\Models\Quiz\ContentLibrary;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class TestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas =  Test::all();
        return view('quiz.test.index')->with(['datas' => $datas]);
    }

    public function create(Request $request) {
        $data = array();
        if($request->id){
            $data  = Test::find($request->id);
        }
        $instructions = Instruction::where('status',1)->get();
        $boards = Board::get();
        $grades = Grade::get();
        return view('quiz.test.create')->with(['instructions' => $instructions, 'boards' => $boards, 'grades' => $grades , 'data' => $data]);
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'board' => 'required',
                'grade' => 'required',
                'test_type' => 'required',
                'ques_type' => 'required',
                'instruction' => 'required',
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

            if($request->updated_id){
                $data = Test::find($request->updated_id);
            }else{
                $data = new Test();
            }

            $data->board_id = $request->board;
            $data->grade_id = $request->grade;
            $data->instruction_id = $request->instruction;
            $data->name = $request->name;
            $data->show_camera = $request->show_camera;
            $data->test_type = $request->test_type;
            $data->sections = $request->sections;
            $data->ques_selection_type = $request->ques_type;
            if($request->ques_type == 'auto'){
                $data->auto_selection_type = $request->auto_ques_type;
                $data->is_difficulty = $request->is_difficulty;
            }
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

//            return $this->redirectToIndex('test', config('constants.message.save'));
            return \redirect(route('quiz.test.getStep2', ['ID' => $data->id]));
//            return \redirect(route('quiz.test.edit', ['test' => $data->id]));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $data = Test::find($id);
        $instructions = Instruction::where('status',1)->get();
        $boards = Board::get();
        $grades = Grade::get();
        $modules = TestModules::where('test_id', $id)->get();
        if($data->ques_selection_type == 'manual'){
            $ques = TestModuleQuestions::where('test_id', $id)->pluck('question_id')->toArray();
            $concept_ids = Question::whereIn('id', $ques)->pluck('concept_id')->toArray();
            $learnings = ContentLibrary::whereIn('concept_id', $concept_ids)->get();
        }else{
            $learnings = null;
        }
        return view('quiz.test.edit')->with(['data' => $data, 'instructions' => $instructions, 'learnings' => $learnings, 'boards' => $boards, 'grades' => $grades, 'modules' => $modules]);
    }

    public function update(Request $request, $id) {
//        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'test_type' => 'required',
                'instruction' => 'required',
                'negative' => 'required',
                'is_feedback' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Test::find($id);
            $data->instruction_id = $request->instruction;
            $data->name = $request->name;
            $data->test_type = $request->test_type;
            $data->sections = $request->sections;
            if($data->ques_type == 'auto'){
                $data->is_difficulty = $request->is_difficulty;
            }
            $data->show_camera = $request->show_camera;
            $data->attempt = $request->attempt;
            $data->negative = $request->negative;
            // $data->order_by = $request->order;
            $data->is_feedback = $request->is_feedback;
            if($request->is_feedback == 1){
                $data->feedback_type = $request->feedback;
            }
            $data->ques_ordered = $request->has('qorder') ? 1 : 0;
            $data->ans_ordered = $request->has('aorder') ? 1 : 0;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            $del = TestContent::where('test_id', $id)->delete();

            if(!empty($request->learning_id)) {
                foreach ($request->learning_id as $learning_id) {

                    $data = new TestContent();
                    $data->test_id = $id;
                    $data->content_id = $learning_id;
                    $data->status = 1;
                    $data->created_by = 1;
                    $data->updated_by = 1;
                    $data->save();
                }
            }

//            return $this->redirectToIndex('test', config('constants.message.update'));
            return \redirect(route('quiz.test.index'));
//        }catch (\Exception $e) {
//            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
//                ->withInput();
//        }
    }

    public function destroy($id) {

        Test::destroy($id);
        return redirect('/quiz/test')->with('message', config('constants.message.delete'));
        // redirect('test', config('constants.message.delete'));
    }

    public function refreshTable (Request $request, $test_id, $sub_id, $chap_id){
        $sub_ids = explode(',', $sub_id);
        $chap_ids = explode(',', $chap_id);
        // $con_ids = explode(',', $con_id);
        $con_ids = '';
        $test= Test::find($test_id);
        // dd(count($test->getModules->pluck('id')->toArray()));
        $secques = [];
        if(count($test->getModules->pluck('id')->toArray()) > 0){
            $secques = TestModuleQuestions::whereIn('module_id', $test->getModules->pluck('id')->toArray())->pluck('question_id')->toArray();
        }

        $query = Question::where('board_id', $test->board_id)->where('grade_id', $test->grade_id);
        $questions = $query->get();
        // dd($questions);
        $subj = $query->distinct('subject_id')->pluck('subject_id')->toArray();
        if(!empty($sub_id)) {
            $chap = $query->whereIn('subject_id', $sub_ids)->distinct('chapter_id')->pluck('chapter_id')->toArray();
            if(!empty($chap_id)) {
                $con = $query->whereIn('chapter_id', $chap_ids)->distinct('concept_id')->pluck('concept_id')->toArray();
            }else{
                $con = $query->whereIn('subject_id', $sub_ids)->distinct('concept_id')->pluck('concept_id')->toArray();
            }
        }else{
            $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
            if(!empty($chap_id)) {
                $con = $query->whereIn('chapter_id', $chap_ids)->distinct('concept_id')->pluck('concept_id')->toArray();
            }else{
                $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
            }

        }
        $subjects = Subjects::whereIn('id', $subj)->get();
        $chapters = Chapters::whereIn('id', $chap)->get();
        $concepts = Concept::whereIn('id', $con)->get();

        if($test->ques_selection_type == 'manual'){
        $query1 = Question::where('board_id', $test->board_id)->where('grade_id', $test->grade_id);
        if(!empty($sub_id))
            $query1->whereIn('subject_id', $sub_ids);
        if(!empty($chap_id))
            $query1->whereIn('chapter_id', $chap_ids);
        if(!empty($con_id))
            $query1->whereIn('concept_id', $con_ids);
        if(count($secques) > 0){
            $query1->whereNotIn('id', $secques);
        }
        $questions = $query1->get();
        return view('quiz.test.manual-table')->with(['questions' => $questions, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'con_ids' => $con_ids]);
        }elseif($test->ques_selection_type == 'auto'){
            if($test->auto_selection_type == 'subject'){
                if(!empty($sub_id)){
                    $subjects1 = Subjects::whereIn('id', $sub_ids)->get();
                }else{
                    $subjects1 = Subjects::whereIn('id', $subj)->get();
                }
                return view('quiz.test.auto-table')->with(['data' => $test, 'query' => $query, 'subjects' => $subjects, 'sub_ids' => $sub_ids, 'subjects1' => $subjects1]);
            }elseif($test->auto_selection_type == 'chapter'){
                if(!empty($chap_id)){
                    $chapters1 = Chapters::whereIn('id', $chap_ids)->get();
                    $subjects1_ids = Chapters::whereIn('id', $chap_ids)->distinct('subject_id')->pluck('subject_id')->toArray();
                }else{
                    $chapters1 = Chapters::whereIn('id', $chap)->get();
                    $subjects1_ids = Chapters::whereIn('id', $chap)->distinct('subject_id')->pluck('subject_id')->toArray();
                }

                $subjects1 = Subjects::whereIn('id', $subjects1_ids)->get();
                return view('quiz.test.auto-table')->with(['data' => $test, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'subjects1' => $subjects1, 'chapters1' => $chapters1]);
            }elseif($test->auto_selection_type == 'concept'){
                if(!empty($con_id)){
                    $concepts1 = Concept::whereIn('id', $con_ids)->get();
                    $chapters1_ids = Concept::whereIn('id', $con_ids)->distinct('chapter_id')->pluck('chapter_id')->toArray();
                }else{
                    $concepts1 = Concept::whereIn('id', $con)->get();
                    $chapters1_ids = Concept::whereIn('id', $con)->distinct('chapter_id')->pluck('chapter_id')->toArray();
                }

                $chapters1 = Chapters::whereIn('id', $chapters1_ids)->get();
                $subjects1_ids = Chapters::whereIn('id', $chapters1_ids)->distinct('subject_id')->pluck('subject_id')->toArray();
                $subjects1 = Subjects::whereIn('id', $subjects1_ids)->get();
//                dd($chapters1);
                return view('quiz.test.auto-table')->with(['data' => $test, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'con_ids' => $con_ids, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1]);
            }
        }

    }

    public function getSelectedChapter(Request $request){
        $test= Test::find($request->test);

        $query = Question::where('board_id', $test->board_id)->where('grade_id', $test->grade_id)->whereIn('subject_id', $request->subject);
        $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
        $chapters = Chapters::whereIn('id', $chap)->get()->toArray();

        return $chapters;
    }

    public function getSelectedConcept(Request $request){
        $test= Test::find($request->test);

        $query = Question::where('board_id', $test->board_id)->where('grade_id', $test->grade_id)->whereIn('chapter_id', $request->chapter);
        $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
        $concepts = Concept::whereIn('id', $con)->get()->toArray();

        return $concepts;
    }

    public function getStep2(Request $request,$id) {
        // $data1 = array();
        $test_questions = array();
        if ($request->ids) {
            $test_modules = TestModules::where('test_id',$request->ids)->first();
            $test_questions = TestModuleQuestions::where('test_id',$request->ids)->where('module_id',$test_modules->id)->pluck('question_id')->toArray();
            // echo "<pre>"; print_r($test_questions); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");

            // dd($test_questions);
            $data = Test::find($request->ids);
            $secques = [];
            if(count($data->getModules->pluck('id')->toArray()) > 0){
            $secques = TestModuleQuestions::whereIn('module_id', $data->getModules->pluck('id')->toArray())->pluck('question_id')->toArray();
            }
            if(count($secques) > 0){
                $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->orwhereIn('id',$test_questions)->where('status',1)->whereNotIn('id', $secques);
            }else{
                $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->orwhereIn('id',$test_questions)->where('status',1);
            }
            // dd($data);
            $questions = $query->get();
            $subj = $query->distinct('subject_id')->pluck('subject_id')->toArray();
            $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
            $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
            $subjects = Subjects::whereIn('id', $subj)->get();
            $chapters = Chapters::whereIn('id', $chap)->get();
            $concepts = Concept::whereIn('id', $con)->get();

            if($data->ques_selection_type == 'manual'){
                return view('quiz.test.step-2')->with(['data' => $data, 'questions' => $questions, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'test_modules' => $test_modules, 'test_questions' => $test_questions ]);
            }elseif($data->ques_selection_type == 'auto'){
                $subjects1 = Subjects::whereIn('id', $subj)->get();
                $chapters1 = Chapters::whereIn('id', $chap)->get();
                $concepts1 = Concept::whereIn('id', $con)->get();
                if($data->auto_selection_type == 'subject'){
                    return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'subjects1' => $subjects1, 'test_modules' => $test_modules, 'test_questions' => $test_questions]);
                }elseif($data->auto_selection_type == 'chapter'){
                    return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'test_modules' => $test_modules, 'test_questions' => $test_questions]);
                }elseif($data->auto_selection_type == 'concept'){
                    return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1, 'test_modules' => $test_modules, 'test_questions' => $test_questions]);
                }
            }
        }
        $data = Test::find($id);
        $secques = [];
        if(count($data->getModules->pluck('id')->toArray()) > 0){
           $secques = TestModuleQuestions::whereIn('module_id', $data->getModules->pluck('id')->toArray())->pluck('question_id')->toArray();
        }
        // echo "<pre>"; print_r($test_questions); echo "</pre>"; die('anil testing'); echo date("l jS \of F Y h:i:s A");
        if(count($secques) > 0){
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->orwhereIn('id',$test_questions)->where('status',1)->whereNotIn('id', $secques);
        }else{
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->orwhereIn('id',$test_questions)->where('status',1);
        }
        // dd($data);
        $questions = $query->get();
        $subj = $query->distinct('subject_id')->pluck('subject_id')->toArray();
        $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
        $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
        $subjects = Subjects::whereIn('id', $subj)->get();
        $chapters = Chapters::whereIn('id', $chap)->get();
        $concepts = Concept::whereIn('id', $con)->get();

        if($data->ques_selection_type == 'manual'){
            return view('quiz.test.step-2')->with(['data' => $data, 'questions' => $questions, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts]);
        }elseif($data->ques_selection_type == 'auto'){
            $subjects1 = Subjects::whereIn('id', $subj)->get();
            $chapters1 = Chapters::whereIn('id', $chap)->get();
            $concepts1 = Concept::whereIn('id', $con)->get();
            if($data->auto_selection_type == 'subject'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'subjects1' => $subjects1]);
            }elseif($data->auto_selection_type == 'chapter'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'subjects1' => $subjects1, 'chapters1' => $chapters1]);
            }elseif($data->auto_selection_type == 'concept'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1]);
            }
        }
    }

    public function Step2Submit(Request $request){

       // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'test_id' => 'required',
                'total' => 'required|min:1',
                'minutes' => 'required',
                'seconds' => 'required',
                'question_id' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $test= Test::find($request->test_id);
            if($request->update_modules)
            {
                $data = TestModules::find($request->update_modules);
            }
            else{
                $data = new TestModules();
            }
            $data->test_id = $request->test_id;
            $data->name = $request->name;
            $data->time = ($request->minutes * 60) + $request->seconds;
            $data->no_of_ques = $request->total;
            $data->cut_off = $request->cutoff;
            $data->order_by = 1;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            $d = TestModuleQuestions::where('module_id',$request->update_modules)->get();
            foreach ($d as $key => $value) {
                TestModuleQuestions::where('id',$value->id)->delete();
            }
            foreach($request->question_id as $question){
                // if($request->update_modules){
                //     $data1 = TestModuleQuestions::find($request->update_modules);
                // }
                // else{
                    $data1 = new TestModuleQuestions();
                // }
                $data1->test_id = $request->test_id;
                $data1->module_id = $data->id;
                $data1->question_id = $question;
                // $data1->order_by = $request->order;
                $data1->status = $request->status;
                $data1->created_by = 1;
                $data1->updated_by = 1;
                $data1->save();

            }

            $modules = TestModules::where('test_id', $request->test_id)->count();

            if($request->has('flag')){
                return \redirect(route('quiz.test.edit', ['test' => $request->test_id]));
            }
            if($test->sections <= $modules){
                return \redirect(route('quiz.test.getStep3', ['ID' => $request->test_id]));

            }else{
                return \redirect(route('quiz.test.getStep2', ['ID' => $request->test_id]));
            }
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function getStep3($id) {

        $data = Test::find($id);
        // dd($data);
        if($data->ques_selection_type == 'manual'){
            $ques = TestModuleQuestions::where('test_id', $id)->pluck('question_id')->toArray();
            $concept_ids = Question::whereIn('id', $ques)->pluck('concept_id')->toArray();
            $learnings = ContentLibrary::whereIn('concept_id', $concept_ids)->get();
        }else{
            $learnings = null;
        }
        return view('quiz.test.step-3')->with(['data' => $data, 'learnings' => $learnings]);
    }

    public function Step3Submit(Request $request, $id){

//        try {
            $validator = Validator::make($request->all(), [
                'negative' => 'required',
                'is_feedback' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $test= Test::find($id);
            if($test->test_type !== 'Practice') {
                $test->attempt = $request->attempt;
            }
            $test->negative = $request->negative;
            // $test->order_by = $request->order;
            $test->is_feedback = $request->is_feedback;
            if($request->is_feedback == 1){
                $test->feedback_type = $request->feedback;
            }
            $test->ques_ordered = $request->has('qorder') ? 1 : 0;
            $test->ans_ordered = $request->has('aorder') ? 1 : 0;
            $test->status = $request->status;
            $test->save();

            if(!empty($request->learning_id)) {
                foreach ($request->learning_id as $learning_id) {

                    $data = new TestContent();
                    $data->test_id = $id;
                    $data->content_id = $learning_id;
                    $data->status = 1;
                    $data->created_by = 1;
                    $data->updated_by = 1;
                    $data->save();
                }
            }
//        dd($request->all());
            return \redirect(route('quiz.test.index'));
            // return $this->redirectToIndex('test', config('constants.message.update'));

//        }catch (\Exception $e) {
//            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
//                ->withInput();
//        }

    }

    public function Step2SubmitAuto(Request $request){

//        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'test_id' => 'required',
                'total' => 'required|min:1',
                'minutes' => 'required',
                'seconds' => 'required',
                'cutoff' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $test= Test::find($request->test_id);

            $data = new TestModules();
            $data->test_id = $request->test_id;
            $data->name = $request->name;
            $data->time = ($request->minutes * 60) + $request->seconds;
            $data->no_of_ques = $request->total;
            $data->cut_off = $request->cutoff;
            $data->order_by = 1;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();



                foreach($request->selection as $key => $selection){

                    $diff = ['easy' => $request->input('easy_'.$key), 'medium' => $request->input('medium_'.$key), 'hard' => $request->input('hard_'.$key)];

                    $data1 = new TestModulesAuto();
                    $data1->test_id = $request->test_id;
                    $data1->module_id = $data->id;
                    $data1->selection_id = $key;
                    $data1->questions = $selection;
                    $data1->difficulty = json_encode($diff);
//                    $data1->order_by = $request->order;
                    $data1->status = $request->status;
                    $data1->created_by = 1;
                    $data1->updated_by = 1;
                    $data1->save();

                }


            $modules = TestModules::where('test_id', $request->test_id)->count();

            if($request->has('flag')){
                return \redirect(route('quiz.test.edit', ['test' => $request->test_id]));
            }
            if($test->sections <= $modules){
                return \redirect(route('quiz.test.getStep3', ['ID' => $request->test_id]));

            }else{
                return \redirect(route('quiz.test.getStep2', ['ID' => $request->test_id]));
            }
//        }catch (\Exception $e) {
//            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
//                ->withInput();
//        }

    }
}
