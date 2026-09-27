<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject',
        'marks_obtained',
        'max_marks',
        'grade_letter',
        'semester',
    ];

    protected static function booted(): void
    {
        static::saving(function (Grade $grade) {
            $grade->grade_letter = self::calculateLetter($grade->marks_obtained, $grade->max_marks);
        });
    }

    public static function calculateLetter(int $marksObtained, int $maxMarks): string
    {
        $percentage = $maxMarks > 0 ? ($marksObtained / $maxMarks) * 100 : 0;

        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
