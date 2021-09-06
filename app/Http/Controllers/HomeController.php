<?php

namespace App\Http\Controllers;

use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;

class HomeController extends Controller
{
    public function index()
    {
        
        return view('welcome');
    }

    public function getHomeDataApi(Request $request)
    {
        return Home::all();
        // return response()->json(Home::all(), 200);
    }
    public function getHomeData(Request $request)
    {
        // return Home::all();
        $data['data'] = Home::all();
        return response()->json($data, 200);
    }
    public function addHomeData(Request $request)
    {
        // dd($request->all());
        Home::create($request->all());
        return response()->json(
            [
                'success' => true,
                'message' => 'Record inserted successfully'
            ],
            200
        );
    }

    public function deleteHomeData($id)
    {
        $home = Home::find($id);
        if($home->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Record Deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong try again later'
            ], 201);
        }
    }

    //* delete multiple records
    public function deleteMultiRecords(Request $request)
    {
        if(Home::destroy(collect($request->ids))) {
            return response()->json([
                'success' => true,
                'message' => 'Records deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong try again later'
            ], 201);
        }
    } 

    //* get single record
    public function getSingleRecord(Home $id)
    {
        return response()->json(['data' => $id], 200);
    } 

    public function updateSingleRecord(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->all();
        unset($data['_token']);
        Home::where('id', $id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully'
        ], 200);
    }
}
