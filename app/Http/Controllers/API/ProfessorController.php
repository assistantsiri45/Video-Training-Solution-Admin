<?php
namespace App\Http\Controllers\API;


use App\Models\Professor;

class ProfessorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Professor::query();

        if (request()->filled('q')) {
            $query->search(request()->get('q'));
        }

        $courses = $query->simplePaginate();

        return response()->json(['data' => $courses]);
    }
}