<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Instruction;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class InstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Instruction::all();
        return view('quiz.instruction.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.instruction.create');
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name'        => 'required|max:255',
                'description' => 'required',
                'assigned_to' => 'required',
                'status'      => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = new Instruction();
            $data->name = $request->name;
            $data->description = $request->description;
            $data->assigned_to = $request->assigned_to;
            $data->status = $request->status;
            $data->added_by = 1;
            $data->updated_by = 1;
            $data->save();

            // $destinationPath = storage_path() . '/app/public/instruction/' . $data->id.$request->name . '/';
            // $request->attachment->move($destinationPath, $request->attachment);

            return redirect('/quiz/instruction')->with('message', 'Instruction Added Successefully');
            // return $this->redirectToIndex('instruction', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $data = Instruction::find($id);

        return view('quiz.instruction.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'description' => 'required',
                'assigned_to' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Instruction::find($id);
            $data->name = $request->name;
            $data->description = $request->description;
            $data->assigned_to = $request->assigned_to;
            $data->status = $request->status;
            $data->added_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/instruction')->with('message', 'Instruction Updated Successefully');
            // return $this->redirectToIndex('instruction', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Instruction::destroy($id);
        return redirect('/quiz/instruction')->with('message', 'Instruction Deleted Successefully');
        // return $this->redirectToIndex('instruction', config('constants.message.delete'));
    }

    public function updateImage(Request $request){
        dd($request->all());
    }

    public function view($id){
        $data = Instruction::find($id);
        return view('quiz.instruction.view')->with(['data' => $data]);
    }

}
