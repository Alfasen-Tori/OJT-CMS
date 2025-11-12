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
        'quality_of_work',      // 20%
        'dependability',        // 15% 
        'timeliness',           // 20%
        'attendance',           // 15%
        'cooperation',          // 10%
        'judgment',             // 10%
        'personality',          // 5%
        'total_grade',          // Calculated total
        'comments',
        'evaluation_date'
    ];

    protected $casts = [
        'quality_of_work' => 'decimal:2',
        'dependability' => 'decimal:2',
        'timeliness' => 'decimal:2',
        'attendance' => 'decimal:2',
        'cooperation' => 'decimal:2',
        'judgment' => 'decimal:2',
        'personality' => 'decimal:2',
        'total_grade' => 'decimal:2',
        'evaluation_date' => 'date',
    ];

    /**
     * Calculate total grade based on weighted factors
     */
    public function calculateTotalGrade()
    {
        return (
            ($this->quality_of_work * 0.20) +      // 20%
            ($this->dependability * 0.15) +        // 15%
            ($this->timeliness * 0.20) +           // 20%
            ($this->attendance * 0.15) +           // 15%
            ($this->cooperation * 0.10) +          // 10%
            ($this->judgment * 0.10) +             // 10%
            ($this->personality * 0.05)            // 5%
        );
    }

    /**
     * Calculate GPA based on the 0-100 rating scale
     */
    public function calculateGPA()
    {
        $grade = $this->total_grade;
        
        if ($grade >= 95) return 1.00;
        if ($grade >= 90) return 1.25;
        if ($grade >= 85) return 1.50;
        if ($grade >= 80) return 1.75;
        if ($grade >= 75) return 2.00;
        if ($grade >= 70) return 2.25;
        if ($grade >= 65) return 2.50;
        if ($grade >= 60) return 2.75;
        if ($grade >= 55) return 3.00;
        if ($grade >= 50) return 4.00;
        return 5.00;
    }

/**
 * Get GPA description
 */
public function getGPADescription()
{
    $gpa = $this->calculateGPA();
    
    if ($gpa == 1.00) return 'Excellent';
    if ($gpa == 1.25) return 'Very Good';
    if ($gpa == 1.50) return 'Good';
    if ($gpa == 1.75) return 'Fairly Good';
    if ($gpa == 2.00) return 'Satisfactory';
    if ($gpa == 2.25) return 'Fair';
    if ($gpa == 2.50) return 'Passing';
    if ($gpa == 2.75) return 'Passing';
    if ($gpa == 3.00) return 'Passing ✅';
    if ($gpa == 4.00) return 'Conditional (Failed)';
    if ($gpa == 5.00) return 'Failed';
    
    return 'Unknown';
}

/**
 * Get grade with letter equivalent.
 */
public function getGradeWithLetterAttribute()
{
    $gpa = $this->calculateGPA();
    
    if ($gpa == 1.00) return 'A+';
    if ($gpa == 1.25) return 'A';
    if ($gpa == 1.50) return 'A-';
    if ($gpa == 1.75) return 'B+';
    if ($gpa == 2.00) return 'B';
    if ($gpa == 2.25) return 'B-';
    if ($gpa == 2.50) return 'C+';
    if ($gpa == 2.75) return 'C';
    if ($gpa == 3.00) return 'C-';
    if ($gpa == 4.00) return 'D';
    if ($gpa == 5.00) return 'F';
    
    return 'F';
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

    /**
     * Get individual factor descriptions
     */
    public static function getFactorDescriptions()
    {
        return [
            'quality_of_work' => [
                'label' => 'Quality of Work',
                'percentage' => '20%',
                'description' => 'Thoroughness, Accuracy, Neatness, Effectiveness'
            ],
            'dependability' => [
                'label' => 'Dependability & Reliability',
                'percentage' => '15%', 
                'description' => 'Ability to work with minimum amount of supervision'
            ],
            'timeliness' => [
                'label' => 'Timeliness',
                'percentage' => '20%',
                'description' => 'Able to complete work in allotted time'
            ],
            'attendance' => [
                'label' => 'Attendance',
                'percentage' => '15%',
                'description' => 'Regularity and punctuality in attendance and proper observation break/meet period'
            ],
            'cooperation' => [
                'label' => 'Cooperation',
                'percentage' => '10%',
                'description' => 'Works well with everyone, good teamwork'
            ],
            'judgment' => [
                'label' => 'Judgment',
                'percentage' => '10%',
                'description' => 'Sound decisions, ability to identify and evaluate pertinent factors'
            ],
            'personality' => [
                'label' => 'Pleasing Personality',
                'percentage' => '5%',
                'description' => 'Personal grooming, pleasant disposition'
            ]
        ];
    }
}