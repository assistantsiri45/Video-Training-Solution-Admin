<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Board;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class BoardController extends Controller
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
            return redirect('/quiz/board')->with('message', 'Board Added Successefully');
            // return redirect('/quiz/board', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $data = Board::find($id);
        return view('quiz.board.edit')->with(['data' => $data]);
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

            $data = Board::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/board')->with('message', 'Board Updated Successefully');
            // return $this->redirectToIndex('board', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        $data = Board::destroy($id);
        return redirect('/quiz/board')->with('message', 'Board Deleted Successefully');
        // return $this->redirectToIndex('board', config('constants.message.delete'));
    }
}
