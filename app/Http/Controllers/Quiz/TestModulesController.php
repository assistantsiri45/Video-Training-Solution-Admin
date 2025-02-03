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
use App\Models\Quiz\TestModuleQuestions;
use App\Models\Quiz\TestModulesAuto;
use Illuminate\Http\Request;
use App\Models\Quiz\Test;
use App\Models\Quiz\TestModules;
use App\Models\Quiz\Instruction;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use DB;

class TestModulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Board::all();

        return view('quiz.board.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.board.create');
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = new Board();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return $this->redirectToIndex('board', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $module = TestModules::find($id);
        $data = $module->getTest;
        $selQuestions = $module->getQuestions;

        $secques = [];
        if(count($data->getModules->pluck('id')->toArray()) > 0){
            $secques = TestModuleQuestions::whereIn('module_id', $data->getModules->pluck('id')->toArray())->pluck('question_id')->toArray();
        }

        if(count($secques) > 0){
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->whereNotIn('id', $secques);
        }else{
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id);
        }


        $subj = $query->distinct('subject_id')->pluck('subject_id')->toArray();
        $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
        $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
        $subjects = Subjects::whereIn('id', $subj)->get();
        $chapters = Chapters::whereIn('id', $chap)->get();
        $concepts = Concept::whereIn('id', $con)->get();
        $questions = $query->whereNotIn('id', $module->getQuestions->pluck('question_id')->toArray())->get();

        if($data->ques_selection_type == 'manual'){
//            dd($questions);
            return view('quiz.modules.manual-edit')->with(['module' => $module,'data' => $data,'selQuestions' => $selQuestions, 'questions' => $questions, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts]);
        }elseif($data->ques_selection_type == 'auto'){
//            dd(1);
            $subjects1 = Subjects::whereIn('id', $subj)->get();
            $chapters1 = Chapters::whereIn('id', $chap)->get();
            $concepts1 = Concept::whereIn('id', $con)->get();

            $sections = $module->getSelections;

            if($data->auto_selection_type == 'subject'){
                return view('quiz.modules.rubrik-edit')->with(['module' => $module, 'data' => $data, 'sections' => $sections, 'query' => $query, 'subjects' => $subjects, 'subjects1' => $subjects1]);
            }elseif($data->auto_selection_type == 'chapter'){
                return view('quiz.modules.rubrik-edit')->with(['module' => $module, 'data' => $data, 'sections' => $sections, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'subjects1' => $subjects1, 'chapters1' => $chapters1]);
            }elseif($data->auto_selection_type == 'concept'){
                return view('quiz.modules.rubrik-edit')->with(['module' => $module, 'data' => $data, 'sections' => $sections, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1]);
            }
        }
//        return view('quiz.modules.manual-edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
//        try {
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

            $data = TestModules::find($id);
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

            $exist = $data->getQuestions->pluck('question_id')->toArray();
            foreach($request->question_id as $question){
                if(!in_array($question, $exist)){
                    $data1 = new TestModuleQuestions();
                    $data1->test_id = $request->test_id;
                    $data1->module_id = $id;
                    $data1->question_id = $question;
                    $data1->order_by = $request->order;
                    $data1->status = $request->status;
                    $data1->created_by = 1;
                    $data1->updated_by = 1;
                    $data1->save();
                }
            }
            $exist1 = $data->getQuestions->pluck('question_id')->toArray();
            foreach($exist1 as $question1){
                if(!in_array($question1, $request->question_id)){
                    $data11 = TestModuleQuestions::where('test_id', $request->test_id)->where('module_id', $id)->where('question_id', $question1)->delete();
                }
            }

                return \redirect(route('quiz.test.edit', ['test' => $request->test_id]));

//        }catch (\Exception $e) {
//            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
//                ->withInput();
//        }
    }

    public function destroy($id) {
        $data = TestModules::destroy($id);
        return back()->with(config('constants.message.delete'));
    }

    public function refreshTable (Request $request, $test_id, $module_id, $sub_id, $chap_id, $con_id){
        $sub_ids = explode(',', $sub_id);
        $chap_ids = explode(',', $chap_id);
        $con_ids = explode(',', $con_id);
        $test= Test::find($test_id);
        $data= Test::find($test_id);
        $module = TestModules::find($module_id);
        $selQuestions = $module->getQuestions;
        $secques = [];
        if(count($data->getModules->whereNotIn('id', $module->id)->pluck('id')->toArray()) > 0){
            $secques = TestModuleQuestions::whereIn('module_id', $data->getModules->whereNotIn('id', $module->id)->pluck('id')->toArray())->pluck('question_id')->toArray();
        }
        $query = Question::where('board_id', $test->board_id)->where('grade_id', $test->grade_id);
//        $questions = $query->get();
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
            $questions = $query1->whereNotIn('id', $module->getQuestions->pluck('question_id')->toArray())->get();
            return view('quiz.modules.manual-table')->with(['questions' => $questions, 'selQuestions' => $selQuestions, 'data' => $data, 'module' => $module, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'con_ids' => $con_ids]);
        }elseif($test->ques_selection_type == 'auto'){

            $sections = $module->getSelections;

            if($test->auto_selection_type == 'subject'){
                if(!empty($sub_id)){
                    $subjects1 = Subjects::whereIn('id', array_merge($sub_id, $sections->pluck('selection_id')->toArray()))->get();
                }else{
                    $subjects1 = Subjects::whereIn('id', array_merge($subj, $sections->pluck('selection_id')->toArray()))->get();
                }
                return view('quiz.modules.rubrik-table')->with(['module' => $module, 'data' => $test, 'query' => $query, 'sections' => $sections, 'subjects' => $subjects, 'sub_ids' => $sub_ids, 'subjects1' => $subjects1]);
            }elseif($test->auto_selection_type == 'chapter'){
                if(!empty($chap_id)){
                    $chapters1 = Chapters::whereIn('id', array_merge($chap_ids, $sections->pluck('selection_id')->toArray()))->get();
                    $subjects1_ids = Chapters::whereIn('id', array_merge($chap_ids, $sections->pluck('selection_id')->toArray()))->distinct('subject_id')->pluck('subject_id')->toArray();
                }else{
                    $chapters1 = Chapters::whereIn('id', array_merge($con, $sections->pluck('selection_id')->toArray()))->get();
                    $subjects1_ids = Chapters::whereIn('id', array_merge($chap, $sections->pluck('selection_id')->toArray()))->distinct('subject_id')->pluck('subject_id')->toArray();
                }

                $subjects1 = Subjects::whereIn('id', $subjects1_ids)->get();
                return view('quiz.modules.rubrik-table')->with(['module' => $module, 'data' => $test, 'query' => $query, 'sections' => $sections, 'subjects' => $subjects, 'chapters' => $chapters, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'subjects1' => $subjects1, 'chapters1' => $chapters1]);
            }elseif($test->auto_selection_type == 'concept'){
                if(!empty($con_id)){
                    $concepts1 = Concept::whereIn('id', array_merge($con_ids, $sections->pluck('selection_id')->toArray()))->get();
                    $chapters1_ids = Concept::whereIn('id', array_merge($con_ids, $sections->pluck('selection_id')->toArray()))->distinct('chapter_id')->pluck('chapter_id')->toArray();
//                    dd($concepts1);
                }else{
                    $concepts1 = Concept::whereIn('id', array_merge($con, $sections->pluck('selection_id')->toArray()))->get();
                    $chapters1_ids = Concept::whereIn('id', array_merge($con, $sections->pluck('selection_id')->toArray()))->distinct('chapter_id')->pluck('chapter_id')->toArray();
                }

                $chapters1 = Chapters::whereIn('id', $chapters1_ids)->get();
                $subjects1_ids = Chapters::whereIn('id', $chapters1_ids)->distinct('subject_id')->pluck('subject_id')->toArray();
                $subjects1 = Subjects::whereIn('id', $subjects1_ids)->get();
//                dd($chapters1);
                return view('quiz.modules.rubrik-table')->with(['module' => $module, 'data' => $test, 'query' => $query, 'sections' => $sections, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'sub_ids' => $sub_ids, 'chap_ids' => $chap_ids, 'con_ids' => $con_ids, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1]);
            }
        }

    }

    public function autoUpdate(Request $request, $id){


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

        $data = TestModules::find($id);
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

        $exist = $data->getSelections->pluck('selection_id')->toArray();
        foreach($request->selection as $key => $selection) {
            if($selection > 0){
            $diff = ['easy' => $request->input('easy_' . $key), 'medium' => $request->input('medium_' . $key), 'hard' => $request->input('hard_' . $key)];
            if (!in_array($key, $exist)) {
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

            } else {
                $data1 = TestModulesAuto::where('module_id', $id)->where('selection_id', $key)->first();
                $data1->questions = $selection;
                $data1->difficulty = json_encode($diff);
//                    $data1->order_by = $request->order;
                $data1->status = $request->status;
                $data1->created_by = 1;
                $data1->updated_by = 1;
                $data1->save();
            }
            }
        }

        $exist1 = $data->getSelections->pluck('selection_id')->toArray();

        $dta = array_filter($request->selection, function ($var) {
            return ($var === "0");
        });

        foreach($dta as $key => $question1){
            $data11 = TestModulesAuto::where('test_id', $request->test_id)->where('module_id', $id)->where('selection_id', $key)->delete();
        }

        return \redirect(route('quiz.test.edit', ['test' => $request->test_id]));
    }

    public function getStep2($id) {

        $data = Test::find($id);
        $secques = [];
        if(count($data->getModules->pluck('id')->toArray()) > 0){
            $secques = TestModuleQuestions::whereIn('module_id', $data->getModules->pluck('id')->toArray())->pluck('question_id')->toArray();
        }
        if(count($secques) > 0){
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id)->whereNotIn('id', $secques);
        }else{
            $query = Question::where('board_id', $data->board_id)->where('grade_id', $data->grade_id);
        }
        $questions = $query->get();
        // dd($questions[0]);
        $subj = $query->distinct('subject_id')->pluck('subject_id')->toArray();
        $chap = $query->distinct('chapter_id')->pluck('chapter_id')->toArray();
        $con = $query->distinct('concept_id')->pluck('concept_id')->toArray();
        $subjects = Subjects::whereIn('id', $subj)->get();
        $chapters = Chapters::whereIn('id', $chap)->get();
        $concepts = Concept::whereIn('id', $con)->get();

        if($data->ques_selection_type == 'manual'){
            return view('quiz.test.step-2')->with(['data' => $data, 'questions' => $questions, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'flag' => 'edit']);
        }elseif($data->ques_selection_type == 'auto'){
            $subjects1 = Subjects::whereIn('id', $subj)->get();
            $chapters1 = Chapters::whereIn('id', $chap)->get();
            $concepts1 = Concept::whereIn('id', $con)->get();
            if($data->auto_selection_type == 'subject'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'subjects1' => $subjects1, 'flag' => 'edit']);
            }elseif($data->auto_selection_type == 'chapter'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'flag' => 'edit']);
            }elseif($data->auto_selection_type == 'concept'){
                return view('quiz.test.rubrik')->with(['data' => $data, 'query' => $query, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'subjects1' => $subjects1, 'chapters1' => $chapters1, 'concepts1' => $concepts1, 'flag' => 'edit']);
            }
        }
    }
}
