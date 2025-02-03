<?php

namespace App\Http\Controllers\API;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $query = State::query();
        if (request()->has('country_id')) {
            $query = $query->ofCountry(request()->get('country_id'));
        }

        if (request()->filled('search')) {
            $query->ofSearch(request()->get('search'));
        }


        $states = $query->simplePaginate();

        return response()->json(['data' => $states]);
    }
}
