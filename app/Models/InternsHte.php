<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternsHte extends Model
{
    use HasFactory;

    protected $table = 'interns_hte';

    protected $fillable = [
        'intern_id',
        'hte_id',
        'coordinator_id',
        'status',
        'endorsed_at',
        'deployed_at',
        'start_date',
        'end_date',
        'endorsement_letter_path',
    ];

    protected $casts = [
        'endorsed_at' => 'datetime',
        'deployed_at' => 'datetime',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    // Relationships
    public function intern()
    {
        return $this->belongsTo(Intern::class, 'intern_id');
    }

    public function hte()
    {
        return $this->belongsTo(Hte::class, 'hte_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class, 'coordinator_id');
    }

    // Accessor: duration in weeks (if both dates are set)
    public function getDurationWeeksAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInWeeks($this->end_date);
        }
        return null;
    }
}
