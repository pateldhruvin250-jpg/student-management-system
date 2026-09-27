<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /** GET /api/students/{student}/attendance */
    public function index(Student $student): JsonResponse
    {
        return response()->json(
            $student->attendances()->orderByDesc('date')->paginate(30)
        );
    }

    /** POST /api/students/{student}/attendance */
    public function store(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'status' => ['required', Rule::in(['present', 'absent', 'late'])],
            'subject' => ['required', 'string', 'max:100'],
        ]);

        $attendance = $student->attendances()->create($validated);

        return response()->json($attendance, 201);
    }

    /** PUT /api/attendance/{attendance} */
    public function update(Request $request, Attendance $attendance): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['present', 'absent', 'late'])],
        ]);

        $attendance->update($validated);

        return response()->json($attendance);
    }

    /** DELETE /api/attendance/{attendance} */
    public function destroy(Attendance $attendance): JsonResponse
    {
        $attendance->delete();

        return response()->json(['message' => 'Attendance record deleted.']);
    }
}
