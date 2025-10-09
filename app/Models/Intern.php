<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intern extends Model
{
    // protected $primaryKey = 'student_id'; // since you're using student_id as PK
    // public $incrementing = false; // because student_id is not auto-increment
    // protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'user_id',
        'dept_id',
        'coordinator_id', 
        'birthdate',
        'year_level',
        'section',
        'academic_year',
        'semester',
        'status',
        'first_login',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function department() {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(Coordinator::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'student_skill', 'intern_id', 'skills_id');
    }

    public function documents()
    {
        return $this->hasMany(InternDocument::class);
    }

    public function hteAssignment()
    {
        return $this->hasOne(InternsHte::class, 'intern_id');
    }

    // Helper method to check specific document
    public function hasDocument(string $type): bool
    {
        return $this->documents()->where('type', $type)->exists();
    }

    // Helper method to get document path
    public function getDocumentPath(string $type): ?string
    {
        return $this->documents()->where('type', $type)->first()?->file_path;
    }
}

