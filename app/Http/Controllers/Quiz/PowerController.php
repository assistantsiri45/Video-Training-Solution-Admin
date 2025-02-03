<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz\Power;
use Illuminate\Support\Facades\Redirect;
use Alert;
use Validator;
use Illuminate\Validation\Rule;
use App\Exceptions\Handler;
use Illuminate\Support\Facades\Log;


class PowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $datas = Power::all();

        return view('quiz.power.index')->with(['datas' => $datas]);
    }

    public function create() {
        return view('quiz.power.create');
    }

    public function store(Request $request) {

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'slug' => [
                    'required',
                    Rule::in(['double','50-50','reward','extra-life','stop-timer']),
                ],
                'attachment' => 'required',
                'attachment_hover' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if( $request->hasFile('attachment') ){

                $file_url = mv_upload( $request->attachment, 'uploads' );
            }

            if( $request->hasFile('attachment_hover') ){
                $file_url1 = mv_upload( $request->attachment_hover, 'uploads' );
            }

            $data = new Power();
            $data->name = $request->name;
            $data->slug = $request->slug;
            if( $request->hasFile('attachment') ) {
                $data->attachment = $file_url;
            }
            if( $request->hasFile('attachment_hover') ) {
                $data->attachment_hover = $file_url1;
            }
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return $this->redirectToIndex('power', config('constants.message.save'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }

    }

    public function edit($id) {

        $data = Power::find($id);
        return view('quiz.power.edit')->with(['data' => $data]);
    }

    public function update(Request $request, $id) {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'slug' => [
                    'required',
                    Rule::in(['double','50-50','reward','extra-life','stop-timer']),
                ],
//                'attachment' => 'required',
//                'attachment_hover' => 'required',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput();
            }
            if( $request->hasFile('attachment') ){
                $file_url = mv_upload( $request->attachment, 'uploads' );
            }

            if( $request->hasFile('attachment_hover') ){
                $file_url1 = mv_upload( $request->attachment_hover, 'uploads' );
            }
            $data = Power::find($id);
            $data->name = $request->name;
            $data->slug = $request->slug;
            if( $request->hasFile('attachment') ) {
                $data->attachment = $file_url;
            }
            if( $request->hasFile('attachment_hover') ) {
                $data->attachment_hover = $file_url1;
            }
            $data->status = $request->status;
            $data->created_by = 1;
            $data->updated_by = 1;
            $data->save();

            return $this->redirectToIndex('power', config('constants.message.update'));
        }catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Something went wrong. Please try again!')
                ->withInput();
        }
    }

    public function destroy($id) {
        $data = Power::destroy($id);
        return $this->redirectToIndex('power', config('constants.message.delete'));
    }
}
