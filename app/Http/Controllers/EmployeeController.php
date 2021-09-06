<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
// use DataTables;
class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee');
    }

    public function getEmployee(Request $request)
    {
        if ($request->ajax()) {
            $data = Employee::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" id="yajraEdit" class="edit btn btn-success btn-sm">Edit</a> <a href="javascript:void(0)" id="yajraDelete" class="delete btn btn-danger btn-sm">Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    
    public function edit($id)
    {
        return response()->json(['data' => Employee::findOrFail($id)], 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        Employee::where('id', $id)->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Record updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        if($employee->delete()) {
            return response()->json(['success' => true, 'message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong try again'], 201);
        }
    }

    public function multipleDelete(Request $request)
    {
        if(Employee::destroy(collect($request->ids))) {
            return response()->json([
                'success' => true,
                'message' => "Records deleted successfully"
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Something went wrong try again later"
            ], 201);
        }
    }
}
