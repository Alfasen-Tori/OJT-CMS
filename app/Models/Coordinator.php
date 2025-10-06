<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coordinator extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'faculty_id',
        'user_id',
        'dept_id',
        'can_add_hte'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'can_add_hte' => 'boolean',
    ];

    /**
     * Get the user associated with the coordinator.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department associated with the coordinator.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    /**
     * Get the interns associated with the coordinator.
    */
    public function interns()
    {
        return $this->hasMany(Intern::class);
    }

    public function internsHte()
    {
        return $this->hasMany(InternsHte::class, 'coordinator_id');
    }

    /**
     * Accessor for full name
     */
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->fname.' '.$this->user->lname : 'N/A';
    }

    /**
     * Accessor for email
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : 'N/A';
    }

    /**
     * Accessor for contact number
     */
    public function getContactAttribute()
    {
        return $this->user ? $this->user->contact : 'N/A';
    }

    /**
     * Scope for coordinators who can add HTE
     */
    public function scopeCanAddHte($query)
    {
        return $query->where('can_add_hte', true);
    }

    /**
     * Scope for coordinators who cannot add HTE
     */
    public function scopeCannotAddHte($query)
    {
        return $query->where('can_add_hte', false);
    }
}
