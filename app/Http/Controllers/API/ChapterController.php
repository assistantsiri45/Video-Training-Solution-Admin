<?php
namespace App\Http\Controllers\API;


use App\Models\Chapter;
use App\Models\Level;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $query = Chapter::query();

        if (request()->has('course_id')) {
            $query = $query->ofCourse(request()->get('course_id'));
        }

        if (request()->has('level_id')) {
            $query = $query->ofLevel(request()->get('level_id'));
        }

        if (request()->has('subject_id')) {
            $query = $query->ofSubject(request()->get('subject_id'));
        }

        if (request()->filled('q')) {
            $query->search(request()->get('q'));
        }

        $levels = $query->simplePaginate();

        return response()->json(['data' => $levels]);
    }
}