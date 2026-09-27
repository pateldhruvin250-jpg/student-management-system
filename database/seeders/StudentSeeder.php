<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory()
            ->count(20)
            ->create()
            ->each(function (Student $student) {
                // A few attendance + grade records per student for realistic demo data
                foreach (['DBMS', 'Web Development', 'Data Structures'] as $subject) {
                    $student->attendances()->create([
                        'date' => now()->subDays(rand(1, 30)),
                        'status' => collect(['present', 'present', 'present', 'absent', 'late'])->random(),
                        'subject' => $subject,
                    ]);

                    $student->grades()->create([
                        'subject' => $subject,
                        'marks_obtained' => rand(40, 100),
                        'max_marks' => 100,
                        'semester' => 'Sem ' . $student->semester,
                    ]);
                }
            });
    }
}
