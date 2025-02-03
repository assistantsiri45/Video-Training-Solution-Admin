<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Paragraph;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class ParagraphController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Paragraph::all();
        return view('quiz.paragraph.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.paragraph.create');
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|max:255',
                'description' => 'required',
                'status'      => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = new Paragraph();
            $data->name = $request->name;
            $data->description = $request->description;
            $data->status = $request->status;
            $data->added_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/paragraph')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('paragraph', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $data = Paragraph::find($id);

        return view('quiz.paragraph.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Paragraph::find($id);
            $data->name = $request->name;
            $data->description = $request->description;
            $data->status = $request->status;
            $data->added_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/paragraph')->with('message', 'Successfully updated the information');
            // return $this->redirectToIndex('paragraph', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Paragraph::destroy($id);
        return redirect('/quiz/paragraph')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('paragraph', config('constants.message.delete'));
    }

    public function view($id){
        $data = Paragraph::find($id);
        return view('quiz.paragraph.view')->with(['data' => $data]);
    }

}
