<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /** GET /api/students — list + search + pagination */
    public function index(Request $request): JsonResponse
    {
        $query = Student::query();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('roll_number', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($department = $request->query('department')) {
            $query->where('department', $department);
        }

        $students = $query->orderBy('roll_number')->paginate($request->integer('per_page', 15));

        return response()->json($students);
    }

    /** POST /api/students */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = Student::create($request->validated());

        return response()->json($student, 201);
    }

    /** GET /api/students/{student} */
    public function show(Student $student): JsonResponse
    {
        $student->load(['attendances', 'grades']);
        $student->append(['full_name', 'attendance_percentage']);

        return response()->json($student);
    }

    /** PUT/PATCH /api/students/{student} */
    public function update(StoreStudentRequest $request, Student $student): JsonResponse
    {
        $student->update($request->validated());

        return response()->json($student);
    }

    /** DELETE /api/students/{student} */
    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully.']);
    }
}
