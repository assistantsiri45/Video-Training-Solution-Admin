<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Chapter;
use App\Models\Quiz\Subject;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Chapter::orderBy('order_by','asc')->get();
        return view('quiz.chapter.index')->with(['datas' => $datas]);
    }

    public function create() {
        $subjects = Subject::where('status',1)->get();
        return view('quiz.chapter.create')->with(['subjects' => $subjects]);
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'subject' => 'required',
                'status' => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $data = new Chapter();
            $data->name = $request->name;
            $data->subject_id = $request->subject;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/chapter')->with('message', 'Chapter Added Successefully');
            // return $this->redirectToIndex('chapter', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $subjects = Subject::where('status',1)->get();
        $data     = Chapter::find($id);
        return view('quiz.chapter.edit')->with(['data' => $data, 'subjects' => $subjects]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'subject' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Chapter::find($id);
            $data->name = $request->name;
            $data->subject_id = $request->subject;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/chapter')->with('message', 'Chapter Updated Successefully');
            // return $this->redirectToIndex('chapter', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Chapter::destroy($id);
        return redirect('/quiz/chapter')->with('message', 'Chapter Deleted Successefully');
        // return $this->redirectToIndex('chapter', config('constants.message.delete'));
    }

    public function getChapter(Request $request) {
        // dd($request->subject);
        $data =  Chapter::where('subject_id',$request->subject)->get()->toArray();
        // dd($data);
        return $data;
    }

    
}
