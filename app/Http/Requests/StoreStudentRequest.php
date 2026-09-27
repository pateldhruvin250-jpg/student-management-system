<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorization is handled by route middleware (role:admin,staff)
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id;

        return [
            'roll_number' => ['required', 'string', 'max:50', Rule::unique('students', 'roll_number')->ignore($studentId)],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('students', 'email')->ignore($studentId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'department' => ['required', 'string', 'max:100'],
            'semester' => ['required', 'integer', 'min:1', 'max:8'],
            'date_of_birth' => ['nullable', 'date'],
        ];
    }
}
