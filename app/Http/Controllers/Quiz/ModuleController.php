<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Question;
use App\Models\Quiz\Subject;
use App\Models\Quiz\Subjects;
use Illuminate\Http\Request;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Board;
use App\Models\Quiz\Modules;
use App\Models\Quiz\Chapter;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas =  Modules::all();
        return view('quiz.module.index')->with(['datas' => $datas]);
    }

    public function create() {
        $boards = Board::all();
        return view('quiz.module.create')->with(['boards' => $boards]);
    }

    public function store(Request $request) {
        // dd($request->all());
        // try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'board' => 'required',
                'grade' => 'required',
                'subject' => 'required',
                'chapter' => 'required',
                // 'concept' => 'required',
                'ques' => 'required|numeric',
                'minutes' => 'required|numeric',
                'seconds' => 'required|numeric',
                'easy' => 'required|numeric',
                'medium' => 'required|numeric',
                'hard' => 'required|numeric',
                'status' => 'required',
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

            $data = new Modules();
            $data->subject_id = $request->subject;
            $data->chapter_id = $request->chapter;
            // $data->concept_id = $request->concept;
            $data->name = $request->name;
            $data->no_of_ques = $request->ques;
            $data->time = ($request->minutes * 60) + $request->seconds;
            $data->easy_ques = $request->easy;
            $data->medium_ques = $request->medium;
            $data->hard_ques = $request->hard;
            $data->ques_ordered = $request->has('qorder') ? 1 : 0;
            $data->ans_ordered = $request->has('aorder') ? 1 : 0;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();
//            dd($request->all());
            return redirect('/quiz/module')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('module', config('constants.message.save'));
        // }catch (\Exception $e) {
        //     return Redirect::back()
        //         ->withErrors('Something went wrong. Please try again!')
        //         ->withInput();
        // }

    }

    public function edit($id) {

        $module = Modules::find($id);
        $boards = Board::all();
        $grades = Grade::all();
        $sel_subject = Subjects::where('id',$module->subject_id)->first();
        $sel_board = Board::where('id',$sel_subject->course_id)->first();
        $sel_grade = Grade::where('course_id',$sel_board->id)->get();
        // dd($sel_grade);
        $subjects = Subjects::all();
        $chapters = Chapter::all();
        $concepts = Concept::all();
        $easy = Question::where('concept_id', $module->concept_id)->where('status', 1)->where('difficulty', 'Easy')->count();
        $medium = Question::where('concept_id', $module->concept_id)->where('status', 1)->where('difficulty', 'Medium')->count();
        $hard = Question::where('concept_id', $module->concept_id)->where('status', 1)->where('difficulty', 'Hard')->count();

        return view('quiz.module.edit')->with(['module' => $module, 'boards' => $boards, 'grades' => $grades, 'subjects' => $subjects, 'chapters' => $chapters, 'concepts' => $concepts, 'easy' => $easy, 'medium' => $medium, 'hard' => $hard, 'sel_subject' => $sel_subject, 'sel_grade' => $sel_grade]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'board' => 'required',
                'grade' => 'required',
                'subject' => 'required',
                'chapter' => 'required',
                // 'concept' => 'required',
                'ques' => 'required|numeric',
                'minutes' => 'required|numeric',
                'seconds' => 'required|numeric',
                'easy' => 'required|numeric',
                'medium' => 'required|numeric',
                'hard' => 'required|numeric',
                'status' => 'required',
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

            $data = Modules::find($id);
            $data->subject_id = $request->subject;
            $data->chapter_id = $request->chapter;
            // $data->concept_id = $request->concept;
            $data->name = $request->name;
            $data->no_of_ques = $request->ques;
            $data->time = ($request->minutes * 60) + $request->seconds;
            $data->easy_ques = $request->easy;
            $data->medium_ques = $request->medium;
            $data->hard_ques = $request->hard;
            $data->ques_ordered = $request->has('qorder') ? 1 : 0;
            $data->ans_ordered = $request->has('aorder') ? 1 : 0;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/module')->with('message', 'Successfully Updated the information');
            // return $this->redirectToIndex('module', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Modules::destroy($id);
        return redirect('/quiz/module')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('grade', config('constants.message.delete'));
    }

    public function getQuestions(Request $request) {

        $data = Question::where('concept_id', $request->concept)->where('status', 1);

        $count = [];

        $count['easy'] = $data->where('difficulty', 'Easy')->count();
        $count['medium'] = $data->where('difficulty', 'Medium')->count();
        $count['hard'] = $data->where('difficulty', 'Hard')->count();

        return $count;
    }

    public function getModules(Request $request) {

        $data =  Modules::where('concept_id', $request->concept)->where('status', 1)->get()->toArray();
        return $data;
    }
}
