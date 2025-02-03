<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Taxonomy;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use File;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class TaxonomyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Taxonomy::all();
        return view('quiz.taxonomy.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.taxonomy.create');
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

            $data = new Taxonomy();
            $data->name = $request->name;
            $data->status = $request->status;
            $data->save();

            return redirect('/quiz/taxonomy')->with('message', 'Successfully saved the information');
            // return $this->redirectToIndex('taxonomy', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {
        $data = Taxonomy::find($id);

        return view('quiz.taxonomy.edit')->with(['data' => $data]);
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

            $data = Taxonomy::find($id);
            $data->name = $request->name;
            $data->status = $request->status;
            $data->save();

            return redirect('/quiz/taxonomy')->with('message', 'Successfully updated the information');
            // return $this->redirectToIndex('taxonomy', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        Taxonomy::destroy($id);
        return redirect('/quiz/taxonomy')->with('message', 'Successfully deleted the information');
        // return $this->redirectToIndex('taxonomy', config('constants.message.delete'));
    }

}
