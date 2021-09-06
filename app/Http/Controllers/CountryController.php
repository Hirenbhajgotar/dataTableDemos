<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function get(Country $country)
    {
        return response()->json($country);
    }
}
