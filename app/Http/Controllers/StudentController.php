<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        // $data['states'] = State::all();
        $data['countries'] = Country::all();
        // dd($data['data'][0]->State);
        return view('students.student_list', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'               => 'required',
            'phone'              => 'required',
            'email'              => 'required|email',
            'profile_photo_path' => 'required|image|mimes:jpeg,png,jpg',
            'gender'             => 'required',
            'state_id'           => 'required',
        ]);
        if($validator->passes()) {
            $data = $request->all();
            if ($request->hasFile('profile_photo_path')) {
                $image = $request->file('profile_photo_path');
                $path = $request->file('profile_photo_path')->store('images', 'public');
                $data['profile_photo_path'] = $path;
            }
            unset($data['_token']);
            if (Student::create($data)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record added successfully'
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong try again'
                ], 201);
            }
        } else {
            return response()->json(['error' => $validator->errors()]);
        }
        
    }

    public function show(Request $request)
    {
        $data['data'] = Student::with('state')->get();
        return response()->json($data, 200);
    }

    
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
