<?php

namespace App\Http\Controllers\API;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $query = Country::query();

        if (request()->filled('search')) {
            $query->ofSearch(request()->get('search'));
        }

        $countries = $query->simplePaginate();

        return response()->json(['data' => $countries]);
    }
}
