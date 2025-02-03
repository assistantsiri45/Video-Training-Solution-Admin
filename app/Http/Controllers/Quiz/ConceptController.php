<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Chapter;
use App\Models\Quiz\Subject;
use App\Models\Quiz\Concept;
use App\Models\Quiz\Grade;
use App\Models\Quiz\Board;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class ConceptController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Concept::orderBy('order_by','asc')->get();
        return view('quiz.concept.index')->with(['datas' => $datas]);
    }

    public function create() {
        $subjects = Subject::where('status',1)->get();
        return view('quiz.concept.create')->with(['subjects' => $subjects]);
    }

    public function store(Request $request) {
        // dd($request->input());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'subject' => 'required|numeric',
                'chapter' => 'required',
                'status' => 'required',
                ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $data = new Concept();
            $data->name = $request->name;
            $data->chapter_id = $request->chapter;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/concept')->with('message', 'Concept Added Successefully');
            // return $this->redirectToIndex('concept', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $data      = Concept::find($id);
        $subjects  = Subject::where('status',1)->get();
        $chapters  = Chapter::where('subject_id',$data->getChapter->getSubject->id)->get();

        return view('quiz.concept.edit')->with(['data' => $data, 'subjects' => $subjects, 'chapters' => $chapters]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'order_by' => 'required|numeric',
                'subject' => 'required|numeric',
                'chapter' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = Concept::find($id);
            $data->name = $request->name;
            $data->chapter_id = $request->chapter;
            $data->order_by = $request->order_by;
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return redirect('/quiz/concept')->with('message', 'Concept Updated Successefully');
            // return $this->redirectToIndex('concept', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Concept::destroy($id);
        return redirect('/quiz/concept')->with('message', 'Concept Deleted Successefully');
        // return $this->redirectToIndex('concept', config('constants.message.delete'));
    }

    public function getConcept(Request $request) {

        return  Concept::where('chapter_id', $request->chapter)->where('status', 1)->get()->toArray();

    }

    public function allCParentId($data){
      $result_id['board']   = $data->getChapter->getSubject->getGrade->getBoard->id;
      $result_id['grade']   = $data->getChapter->getSubject->getGrade->id;
      $result_id['subject'] = $data->getChapter->getSubject->id;
      $result_id['chapter'] = $data->getChapter->id;
      return $result_id;
    }
}
