<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $data['countries'] = Country::all();
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
            'country_id'         => 'required',
            'state_id'           => 'required',
            // 'term_condition'     => 'required',
        ]);
        if($validator->passes()) {
            $data = $request->all();
            unset($data['_token']);
            $data['term_condition'] = $request->term_condition ?? 0;
            if($request->editForm) { //* Update Record
                unset($data['editForm']);
                $updateData = Student::find($request->editForm);
                // dd($updateData);
                if (Storage::disk('public')->exists($updateData->profile_photo_path)) {
                    Storage::disk('public')->delete($updateData->profile_photo_path);
                }
                $image = $request->profile_photo_path;
                // $path = $request->file('profile_photo_path')->store('images', 'public');

                $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $imageName = Str::of($name)->slug('_') . time() . '.' . $image->getClientOriginalExtension();

                // $path = $request->file('profile_photo_path')->storeAs('images', $imageName, 'public');
                $path = $request->file('profile_photo_path')->move(public_path('storage/images'), $imageName);

                $data['profile_photo_path'] = 'images/' . $imageName;
                Student::where('id', $request->editForm)->update($data);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully'
                ], 200);
            } else { //* Insert Record
                // dd($request->all());
                if ($request->hasFile('profile_photo_path')) {
                    $image = $request->profile_photo_path;
                    // $path = $request->file('profile_photo_path')->store('images', 'public');
                    
                    $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                    $imageName = Str::of($name)->slug('_').time().'.'.$image->getClientOriginalExtension();
                    
                    // $path = $request->file('profile_photo_path')->storeAs('images', $imageName, 'public');
                    $path = $request->file('profile_photo_path')->move(public_path('storage/images'), $imageName);

                    $data['profile_photo_path'] = 'images/'. $imageName;
                }
                // dd($data);
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

    
    public function destroy($id)
    {
        $student = Student::find($id);
        if(Storage::disk('public')->exists($student->profile_photo_path)) {
            Storage::disk('public')->delete($student->profile_photo_path);
        }
        if($student->delete()) {
            return response()->json(['success' => true, 'message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong try again later'], 201);
        }
    }
}
