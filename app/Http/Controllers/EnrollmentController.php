<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::all();
        return response()->json(['enrollments' => $enrollments], Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $enrollment = Enrollment::create($validatedData);

        return response()->json(['enrollment' => $enrollment], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $entrollment = Enrollment::findOrFail($id);
        return response()->json(['enrollment' => $entrollment], Response::HTTP_OK);
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        $enrollment->update($request->all());

        return response()->json(['enrollment' => $enrollment], Response::HTTP_OK);
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
