<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Module;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $query = Module::query();

        $query = Module::get();

        if (request()->filled('chapter_id')) {
            $query = Module::where('chapter_id', request()->get('chapter_id'))->get();
        }

        return response()->json(['data' => $query]);
    }
}
