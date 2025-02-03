<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Grade;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;


class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas =  Grade::all();
        return view('quiz.grade.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.grade.create');
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

            $data = new Grade();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();
            return redirect('/quiz/grade')->with('message', 'Grade Added Successefully');
            // return $this->redirectToIndex('grade', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $data = Grade::find($id);
        return view('quiz.grade.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
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

            $data = Grade::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/grade')->with('message', 'Grade Updated Successefully');
            // return $this->redirectToIndex('grade', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Grade::destroy($id);
        return redirect('/quiz/grade')->with('message', 'Grade Deleted Successefully');
        // return $this->redirectToIndex('grade', config('constants.message.delete'));
    }

    public function getGrade(Request $request) {
        // dd($request->board);
        $board_id = $request->board;
        $grade = new Grade;
        $data =  $grade->getGrades($board_id);
        return $data;
    }
}
