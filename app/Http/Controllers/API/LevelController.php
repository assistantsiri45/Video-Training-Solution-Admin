<?php
namespace App\Http\Controllers\API;


use App\Models\Level;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $query = Level::query();

        if (request()->has('course_id')) {
            $query = $query->ofCourse(request()->get('course_id'));
        }

        if (request()->filled('q')) {
            $query->search(request()->get('q'));
        }

        $levels = $query->simplePaginate();

        return response()->json(['data' => $levels]);
    }
}