<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Subject;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;
use DB;


class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Subject::orderBy('order_by','asc')->get();
        return view('quiz.subject.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.subject.create');
    }

    public function store(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'status' => 'required',
                'icon'  => "dimensions:max_width=100,max_height=100",
                ]);

            $data = new Subject();
            $data->name = $request->name;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->colour = $request->colour;
            if( $request->hasFile('icon') ){
                $file_url = mv_upload( $request->icon, 'uploads' );
                $data->icon          = $file_url;
            }
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/subject')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('subject', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
                ->withErrors($validator)
                ->withInput();
        }

    }

    public function edit($id) {
        $data   = Subject::find($id);
        return view('quiz.subject.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'status' => 'required',
                'icon'  => "dimensions:max_width=100,max_height=100",
            ]);


            $data = Subject::find($id);
            $data->name = $request->name;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->colour = $request->colour;
            if( $request->hasFile('icon') ){
                $file_url = mv_upload( $request->icon, 'uploads' );
                $data->icon          = $file_url;
            }
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/subject')->with('message', 'Successfully updated the information');
            // return $this->redirectToIndex('subject', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
//                ->withErrors('Something went wrong. Please try again!')
                ->withErrors($validator)
                ->withInput();
        }
    }

    public function destroy($id) {
        Subject::destroy($id);
        return redirect('/quiz/subject')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('subject', config('constants.message.delete'));
    }

    public function getSubject(Request $request) {
        // dd($request->all());
        $grade_id = $request->grade;
        $subject = DB::table('subjects');
        $res = $subject->where('subjects.level_id',$grade_id)->get();

        foreach ($res as $key => $value) {
            $response[$value->id] = $value->name;
        }
        return $response;
        // $grade_id = $request->grade;
        // $subject = new Subject;
        // $data =  $subject->getSubjects($grade_id);
        // return $data;
    }
}
