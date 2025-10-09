<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'intern_id',
        'week_no',
        'report_path',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'week_no' => 'integer'
    ];

    /**
     * Get the intern that owns the weekly report
     */
    public function intern()
    {
        return $this->belongsTo(Intern::class);
    }
}