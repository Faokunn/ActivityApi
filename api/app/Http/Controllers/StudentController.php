<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all();
        return response()->json(['students' => $students]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'FirstName' => 'required|string|max:15',
            'LastName' => 'required|string|max:15',
            'Department' => 'required|string|max:10',
            'Year' => 'required|integer|between:1,4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation Failed',
                'errors'=>$validator->errors()
            ],422);
        }

        $student = Student::create([
            'first_name'=>$request->FirstName,
            'last_name'=>$request->LastName,
            'department'=>$request->Department,
            'year'=>$request->Year
        ]);
        
        return response()->json([
            'message'=> 'Student Added',
            'data'=>$student
            ],200);
    }

    public function show($id)
    {
        $student = Student::all()->find($id);
        if(!$student){
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json(['student' => $student]);
    } 

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'FirstName' => 'required|string|max:15',
            'LastName' => 'required|string|max:15',
            'Department' => 'required|string|max:10',
            'Year' => 'required|integer|between:1,4',
        ]);

        $student = Student::find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $student->update($validator);

        return response()->json([
            'message' => 'Student updated successfully',
            'profile' => $student
        ], 200);
    }

    public function destroy($id)
    {
        $student = Student::find($id);
        $student -> delete();
        return response()-> json(['message' => 'Student Removed']);
    }
}
