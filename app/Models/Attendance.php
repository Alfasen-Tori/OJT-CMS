<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_hte_id',
        'date',
        'time_in',
        'time_out',
        'hours_rendered'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'hours_rendered' => 'decimal:2'
    ];

    /**
     * Get the internship deployment relationship
     */
    public function internHte()
    {
        return $this->belongsTo(InternsHte::class, 'intern_hte_id');
    }

    /**
     * Get the intern through the internship deployment
     */
    public function intern()
    {
        return $this->hasOneThrough(
            Intern::class,
            InternsHte::class,
            'id', // Foreign key on interns_hte table
            'id', // Foreign key on interns table
            'intern_hte_id', // Local key on attendances table
            'intern_id' // Local key on interns_hte table
        );
    }
}