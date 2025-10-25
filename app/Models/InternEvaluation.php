<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternEvaluation extends Model
{
    use HasFactory;

    protected $table = 'intern_evaluations';

    protected $fillable = [
        'intern_hte_id',
        'grade',
        'comments',
        'evaluation_date'
    ];

    protected $casts = [
        'grade' => 'decimal:2',
        'evaluation_date' => 'date',
    ];

    /**
     * Calculate GPA based on the 0-100 rating scale
     */
    public function calculateGPA()
    {
        $grade = $this->grade;
        
        if ($grade >= 96) return 1.00;
        if ($grade >= 91) return 1.25;
        if ($grade >= 86) return 1.50;
        if ($grade >= 81) return 1.75;
        if ($grade >= 76) return 2.00;
        if ($grade >= 71) return 2.25;
        if ($grade >= 66) return 2.50;
        if ($grade >= 61) return 2.75;
        if ($grade >= 56) return 3.00;
        if ($grade >= 51) return 4.00;
        return 5.00;
    }

    /**
     * Get GPA description
     */
    public function getGPADescription()
    {
        $gpa = $this->calculateGPA();
        
        $descriptions = [
            1.00 => 'Excellent',
            1.25 => 'Very Good',
            1.50 => 'Good',
            1.75 => 'Fairly Good',
            2.00 => 'Satisfactory',
            2.25 => 'Fair',
            2.50 => 'Passing',
            2.75 => 'Passing',
            3.00 => 'Passing ✅',
            4.00 => 'Conditional (Failed)',
            5.00 => 'Failed'
        ];
        
        return $descriptions[$gpa] ?? 'Unknown';
    }

    /**
     * Get grade with letter equivalent.
     */
    public function getGradeWithLetterAttribute()
    {
        $gpa = $this->calculateGPA();
        
        $letterGrades = [
            1.00 => 'A+',
            1.25 => 'A',
            1.50 => 'A-',
            1.75 => 'B+',
            2.00 => 'B',
            2.25 => 'B-',
            2.50 => 'C+',
            2.75 => 'C',
            3.00 => 'C-',
            4.00 => 'D',
            5.00 => 'F'
        ];
        
        return $letterGrades[$gpa] ?? 'F';
    }

    /**
     * Get GPA color based on performance
     */
    public function getGPAColor()
    {
        $gpa = $this->calculateGPA();
        
        if ($gpa <= 1.50) return '#28a745'; // Green for Excellent/Good
        if ($gpa <= 2.25) return '#17a2b8'; // Blue for Satisfactory/Fair
        if ($gpa <= 3.00) return '#ffc107'; // Yellow for Passing
        if ($gpa <= 4.00) return '#fd7e14'; // Orange for Conditional
        return '#dc3545'; // Red for Failed
    }

    // ... rest of your existing model code ...
}