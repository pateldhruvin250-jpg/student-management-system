<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /** GET /api/students/{student}/grades */
    public function index(Student $student): JsonResponse
    {
        return response()->json($student->grades()->orderBy('semester')->get());
    }

    /** POST /api/students/{student}/grades */
    public function store(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:100'],
            'marks_obtained' => ['required', 'integer', 'min:0'],
            'max_marks' => ['required', 'integer', 'min:1'],
            'semester' => ['required', 'string', 'max:20'],
        ]);

        $grade = $student->grades()->create($validated); // grade_letter auto-calculated on save

        return response()->json($grade, 201);
    }

    /** PUT /api/grades/{grade} */
    public function update(Request $request, Grade $grade): JsonResponse
    {
        $validated = $request->validate([
            'marks_obtained' => ['required', 'integer', 'min:0'],
            'max_marks' => ['required', 'integer', 'min:1'],
        ]);

        $grade->update($validated);

        return response()->json($grade);
    }

    /** DELETE /api/grades/{grade} */
    public function destroy(Grade $grade): JsonResponse
    {
        $grade->delete();

        return response()->json(['message' => 'Grade record deleted.']);
    }
}
