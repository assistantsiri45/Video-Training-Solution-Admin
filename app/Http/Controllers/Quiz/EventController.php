<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use App\Models\Quiz\Chapter;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Instruction;
use App\Models\Quiz\Modules;
use App\Models\Quiz\Subject;
use App\Models\Quiz\Test;
use App\Models\Quiz\TestModules;
use Illuminate\Http\Request;
use App\Models\Quiz\Board;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Alert;
use App\Models\Quiz\Event;
use App\Models\Quiz\EventDetails;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Calcutta"); 

    }

    public function index() {
        $datas = Event::all();

        return view('quiz.event.index')->with(['datas' => $datas]);
    }

    public function create() {
        $boards = Board::get();
        $grades = Grade::get();
        $instructions = Instruction::where('status',1)->get();
        $tests = Test::where('test_type','Practice')->get();
        $practices = Test::where('test_type','Practice')->get();
        // $quizs = Test::where('test_type','Quiz')->get();
        $competitions = Test::where('test_type','Competition')->get();
        return view('quiz.event.create')->with(['boards' => $boards, 'grades' => $grades, 'instructions' => $instructions, 'practices' => $practices, 'competitions' => $competitions, 'tests' => $tests]);
    }

    public function store(Request $request) {
    //    dd($request->start_time);
        try {
            $validator = Validator::make($request->all(), [
                'board' => 'required',
                'grade' => 'required',
                'name' => 'required',
                'instruction' => 'required',
                // 'device' => 'required',
                'attachment' => 'required',
                'type' => 'required',
                'access_type' => 'required',
                // 'mode' => 'required',
                'rounds' => 'required||max:6',
                'start_time' => 'required_if:type,Competition,Olympiad',
                'end_time' => 'required_if:type,Competition,Olympiad',
                'start_date' => 'required_if:type,Competition,Olympiad',
                'end_date' => 'required_if:type,Competition,Olympiad',
                'quiz_test' => 'required',
                'enroll_start_date' => 'required_if:type,Quiz,Olympiad',
                'enroll_end_date' => 'required_if:type,Quiz,Olympiad',
                'is_free' => 'required',
                'price' => 'required_if:is_free,==,0',
                'sample_test' => 'required_if:is_sample,1',
                'is_published' => 'required',
                // 'status' => 'required',
            ],
            [
                'quiz_test.required' => 'The Test filed is required.',
                'price.required_if' => 'The price field is required when is free is no.',
                'grade.required' => 'The level field is required.',
                'board.required' => 'The course field is required.',
            ]
        );

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
//        dd($request->attachment);
            if( $request->hasFile('attachment') ){
                $file_url = mv_upload( $request->attachment, 'uploads' );
            }
//            dd($file_url);
            $data = new Event();
            $data->board_id = $request->board;
            $data->grade_id = $request->grade;
            $data->event_details = $request->instruction;
            $data->name = $request->name;
            $data->event_type = $request->type;
            $data->mode = $request->mode;
            $data->device = $request->device;
            $data->rewards = NULL;
            $data->price = $request->price;
            $data->is_free = $request->is_free;
            $data->rounds = $request->rounds;
            if($request->type != 'Practice'){
                $data->start_date = date('Y-m-d', strtotime($request->start_date));
                $data->end_date = date('Y-m-d', strtotime($request->end_date));
                $data->enroll_start_date = date('Y-m-d', strtotime($request->enroll_start_date));
                $data->enroll_end_date = date('Y-m-d', strtotime($request->enroll_end_date));
                $data->is_sample = $request->is_sample;
                $data->sample_test = $request->sample_test;
            }

            if( $request->hasFile('attachment') ) {
                $data->logo = $file_url;
            }
            $data->access_type = $request->access_type;

            $data->is_published = $request->is_published;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();
            if($request->type == 'Olympiad'){
                return \redirect(route('quiz.event.edit', ['event' => $data->id]));
            }else{
                $data1 = new EventDetails();
                $data1->event_id = $data->id;
                if($request->type == 'Practice') {
                    $data1->test_id = $request->practice_test;
                }
                if($request->type == 'Competition') {
                    $data1->test_id = $request->quiz_test;
                    $data1->start_datetime = date('Y-m-d H:i',strtotime($request->enroll_start_date.$request->start_time));
                    $data1->end_datetime = date('Y-m-d H:i', strtotime($request->enroll_end_date.$request->end_time));
                }
                $data1->order_by = $request->order;
                $data1->status = $request->status;
                $data1->created_by = 1;
                $data1->updated_by = 1;
                $data1->save();
                return redirect('/quiz/event')->with('message', 'Event Added Successefully');
                // return $this->redirectToIndex('event', config('constants.message.save'));
            }
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $data = Event::find($id);
//        dd($data->getEventDetails()->first()->end_datetime);
//        dd($data->getEventDetails()->first()->id);
        $boards = Board::get();
        $grades = Grade::get();
        $instructions = Instruction::where('status',1)->get();
        $tests = Test::where('test_type','Practice')->where('status',1)->get();
        $practices = Test::where('test_type','Practice')->where('status',1)->get();
        $quizs = Test::where('test_type','Competition')->where('status',1)->get();
        // dd($quizs);
        $olympiads = Test::where('test_type','Olympiad')->where('status',1)->get(); 
//
        return view('quiz.event.edit')->with(['data' => $data, 'boards' => $boards, 'tests' => $tests, 'grades' => $grades,'instructions' => $instructions, 'practices' => $practices, 'quizs' => $quizs, 'olympiads' => $olympiads]);
    }

    public function update(Request $request, $id) {
//        dd($request->all());
//        dd(date('Y-m-d', strtotime($request->enroll_end_date)));
        try {
            $validator = Validator::make($request->all(), [
                'board' => 'required',
                'grade' => 'required',
                'name' => 'required',
                'instruction' => 'required',
                // 'device' => 'required',
                // 'attachment' => 'required',
                'type' => 'required',
                'access_type' => 'required',
                // 'mode' => 'required',
                'rounds' => 'required||max:6',
                'start_time' => 'required_if:type,Competition,Olympiad',
                'end_time' => 'required_if:type,Competition,Olympiad',
                'start_date' => 'required_if:type,Competition,Olympiad',
                'end_date' => 'required_if:type,Competition,Olympiad',
                'quiz_test' => 'required',
                'enroll_start_date' => 'required_if:type,Quiz,Olympiad',
                'enroll_end_date' => 'required_if:type,Quiz,Olympiad',
                'is_free' => 'required',
                'price' => 'required_if:is_free,==,0',
                'sample_test' => 'required_if:is_sample,1',
                'is_published' => 'required',
                // 'status' => 'required',
            ],
            [
                'quiz_test.required' => 'The Test filed is required.',
                'price.required_if' => 'The price field is required when is free is no.',
                'grade.required' => 'The level field is required.',
                'board.required' => 'The course field is required.',
            ]
        );

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            if( $request->hasFile('attachment') ){
                $file_url = mv_upload( $request->attachment, 'uploads' );
            }
//            dd($file_url);
            $data = Event::find($id);
            $data->board_id = $request->board;
            $data->grade_id = $request->grade;
            $data->event_details = $request->instruction;
            $data->name = $request->name;
            $data->event_type = $request->type;
            $data->mode = $request->mode;
            $data->device = $request->device;
            $data->rewards = NULL;
            $data->price = $request->price;
            $data->is_free = $request->is_free;
            $data->rounds = $request->rounds;
            if($request->type == 'Practice'){
                $data->start_date = NULL;
                $data->end_date = NULL;
                $data->enroll_start_date = NULL;
                $data->enroll_end_date = NULL;
            }else{
                $data->start_date = date('Y-m-d', strtotime($request->start_date));
                $data->end_date = date('Y-m-d', strtotime($request->end_date));
                $data->enroll_start_date = date('Y-m-d', strtotime($request->enroll_start_date));
                $data->enroll_end_date = date('Y-m-d', strtotime($request->enroll_end_date));
            }
            if( $request->hasFile('attachment') ) {
                $data->logo = $file_url;
            }
            $data->access_type = $request->access_type;
            $data->is_sample = $request->is_sample;
            $data->sample_test = $request->sample_test;
            $data->is_published = $request->is_published;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();
            if($request->type == 'Olympiad'){
                return \redirect(route('quiz.event.edit', ['event' => $data->id]));
            }else{
                $round_id = $data->getEventRounds()->first()->id;
                $data1 = EventDetails::find($round_id);
                $data1->event_id = $data->id;
                if($request->type == 'Practice') {
                    $data1->test_id = $request->practice_test;
                }
                if($request->type == 'Competition') {
                    $data1->test_id = $request->quiz_test;
                    $data1->start_datetime = date('Y-m-d H:i',strtotime($request->enroll_start_date.$request->start_time));
                    $data1->end_datetime = date('Y-m-d H:i',strtotime($request->enroll_end_date.$request->end_time));
                }
                $data1->order_by = $request->order;
                $data1->status = $request->status;
                $data1->created_by = 1;
                $data1->updated_by = 1;
                $data1->save();
                return redirect('/quiz/event')->with('message', 'Event Updated Successefully');
                // return $this->redirectToIndex('event', config('constants.message.update'));
            }
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Event::destroy($id);
        return redirect('/quiz/event')->with('message', 'Event Deleted Successefully');
        // return $this->redirectToIndex('event', config('constants.message.delete'));
    }

    public function addRound(Request $request) {
//dd($request->all());
        $data = new EventDetails();
        $data->event_id = $request->event_id;
        $data->test_id = $request->test_id;
        $data->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_date_time.' '.$request->start_time));
        $data->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_date_time.' '.$request->end_time));
        $data->order_by = $request->order;
        $data->status = $request->status;
        $data->created_by = 1;
        $data->updated_by = 1;
        $data->save();

        return \redirect(route('quiz.event.edit', ['event' => $request->event_id]));
    }

    public function editRound(Request $request) {

        $test= Event::find($request->eid);
        $data['details'] = $test->getTest($request->tid);
        return $data;
    }

    public function updateRound(Request $request) {
        $data = EventDetails::find($request->round_id);
        $data->event_id = $request->event_id;
        $data->test_id = $request->test;
        $data->start_datetime = date('Y-m-d H:i:s', strtotime($request->start_date_time.' '.$request->start_time));
        $data->end_datetime = date('Y-m-d H:i:s', strtotime($request->end_date_time.' '.$request->end_time));
        $data->order_by = $request->order;
        $data->status = $request->status;
        $data->created_by = 1;
        $data->updated_by = 1;
        $data->save();

        return \redirect(route('quiz.event.edit', ['event' => $request->event_id]));
    }

//    public function deleteRound($event_id,$id){
//        EventDetails::destroy($id);
//        return \redirect(route('quiz.event.edit', ['event' => $event_id]));
//    }
}
