<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsRole(string $role): User
    {
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_authenticated_user_can_list_students(): void
    {
        $this->actingAsRole('student');
        Student::factory()->count(3)->create();

        $response = $this->getJson('/api/students');

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    public function test_student_role_cannot_create_student(): void
    {
        $this->actingAsRole('student');

        $response = $this->postJson('/api/students', [
            'roll_number' => 'R1001',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'department' => 'IT',
            'semester' => 3,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_student(): void
    {
        $this->actingAsRole('admin');

        $response = $this->postJson('/api/students', [
            'roll_number' => 'R2001',
            'first_name' => 'New',
            'last_name' => 'Student',
            'email' => 'new.student@example.com',
            'department' => 'IT',
            'semester' => 1,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('students', ['roll_number' => 'R2001']);
    }

    public function test_grade_letter_is_calculated_automatically(): void
    {
        $admin = $this->actingAsRole('admin');
        $student = Student::factory()->create();

        $response = $this->postJson("/api/students/{$student->id}/grades", [
            'subject' => 'DBMS',
            'marks_obtained' => 92,
            'max_marks' => 100,
            'semester' => 'Sem 3',
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['grade_letter' => 'A+']);
    }
}
