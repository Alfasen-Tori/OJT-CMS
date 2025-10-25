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
     * Get the internship deployment that this evaluation belongs to.
     */
    public function internshipDeployment()
    {
        return $this->belongsTo(InternsHte::class, 'intern_hte_id');
    }

    /**
     * Get the intern through the deployment.
     */
    public function intern()
    {
        return $this->hasOneThrough(
            Intern::class,
            InternsHte::class,
            'id', // Foreign key on interns_hte table
            'id', // Foreign key on interns table
            'intern_hte_id', // Local key on intern_evaluations table
            'intern_id' // Local key on interns_hte table
        );
    }

    /**
     * Get the HTE through the deployment.
     */
    public function hte()
    {
        return $this->hasOneThrough(
            Hte::class,
            InternsHte::class,
            'id', // Foreign key on interns_hte table
            'id', // Foreign key on htes table
            'intern_hte_id', // Local key on intern_evaluations table
            'hte_id' // Local key on interns_hte table
        );
    }

    /**
     * Scope a query to only include evaluations for a specific HTE.
     */
    public function scopeForHte($query, $hteId)
    {
        return $query->whereHas('internshipDeployment', function ($q) use ($hteId) {
            $q->where('hte_id', $hteId);
        });
    }

    /**
     * Check if grade is passing (assuming 75 is passing grade).
     */
    public function isPassing()
    {
        return $this->grade >= 75;
    }

    /**
     * Get grade with letter equivalent.
     */
    public function getGradeWithLetterAttribute()
    {
        if ($this->grade >= 90) return 'A';
        if ($this->grade >= 80) return 'B';
        if ($this->grade >= 75) return 'C';
        return 'F';
    }
}