<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hte extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_name',
        'type',
        'status',
        'address',
        'description',
        'slots',
        'moa_path',
        'moa_is_signed',
        'first_login',
    ];

    protected $casts = [
        'status' => 'string',
        'type' => 'string'
    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'hte_skill', 'hte_id', 'skill_id')
                    ->withTimestamps();
    }

    /**
     * Relationship with User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Interns (if you have this relationship)
     */
    public function interns()
    {
        return $this->hasMany(Intern::class);
    }

    /**
     * Get the full MOA path with storage URL
     */
    public function getMoaUrlAttribute()
    {
        return $this->moa_path ? asset('storage/' . $this->moa_path) : null;
    }

    /**
     * Scope for active HTEs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for new HTEs
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function internsHte()
    {
        return $this->hasMany(\App\Models\InternsHte::class, 'hte_id');
    }

    public function internEvaluations()
    {
        return $this->hasManyThrough(
            InternEvaluation::class,
            InternsHte::class,
            'hte_id', // Foreign key on interns_hte table
            'intern_hte_id', // Foreign key on intern_evaluations table
            'id', // Local key on htes table
            'id' // Local key on interns_hte table
        );
    }
}