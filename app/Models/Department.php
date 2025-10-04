<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'dept_id';
    public $incrementing = true;

    protected $fillable = [
        'dept_name',
        'short_name'
    ];

    public function interns()
    {
        return $this->hasMany(Intern::class, 'dept_id', 'dept_id');
    }

    public function coordinators()
    {
        return $this->hasMany(Coordinator::class, 'dept_id');
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'dept_id', 'dept_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }
}