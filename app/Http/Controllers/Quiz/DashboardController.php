<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect(route('Quiz.user'));
//        return view('Quiz.dashboard.dashboard');
    }

    public function imageUpload(Request $request){

        if( $request->hasFile('file') ){
            $file_url = mv_upload( $request->file, 'uploads' );

            return json_encode(['location' => asset($file_url)]);

        }
    }
}
