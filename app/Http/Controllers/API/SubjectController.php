<?php
namespace App\Http\Controllers\API;


use App\Models\Level;
use App\Models\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $query = Subject::query();

        if (request()->has('course_id')) {
            $query->ofCourse(request()->get('course_id'));
        }

        if (request()->has('level_id')) {
            $query->ofLevel(request()->get('level_id'));
        }

        if (request()->filled('q')) {
            $query->search(request()->get('q'));
        }

        $levels = $query->simplePaginate();

        return response()->json(['data' => $levels]);
    }
}