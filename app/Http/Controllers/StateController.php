<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function getState(State $state)
    {
        return response()->json($state, 200);
    }
    // get country
    public function getCountry($id)
    {
        $data = State::where('country_id', $id)->get();
        if($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => $data
            ], 201);
        }
    }
}
