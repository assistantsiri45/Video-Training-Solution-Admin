<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\LearningStage;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class LearningStageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = LearningStage::all();
        return view('quiz.learning_stage.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.learning_stage.create');
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|max:255',
                'status'      => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = new LearningStage();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->save();

            return redirect('/quiz/learning_stage')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('learning_stage', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $data = LearningStage::find($id);

        return view('quiz.learning_stage.edit')->with(['data' => $data]);
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

            $data = LearningStage::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->save();

            return redirect('/quiz/learning_stage')->with('message', 'Successfully updated the information');
            // return $this->redirectToIndex('learning_stage', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        LearningStage::destroy($id);
        return redirect('/quiz/learning_stage')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('learning_stage', config('constants.message.delete'));
    }

}
