<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionAuditTrail extends Model
{
    use HasFactory;

    protected $table = 'session_audit_trail';

    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'ip_address',
        'user_agent',
        'login_at',
        'logout_at',
        'session_duration',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for filtering by user type
    public function scopeUserType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    // Scope for filtering by date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('login_at', [$startDate, $endDate]);
    }

    // Helper method to get user display name
    public function getUserDisplayNameAttribute()
    {
        $user = $this->user;
        return $user ? "{$user->fname} {$user->lname}" : 'Unknown User';
    }

    // Helper method to get formatted session duration
    public function getFormattedDurationAttribute()
    {
        if (!$this->session_duration) return 'N/A';
        
        $hours = floor($this->session_duration / 3600);
        $minutes = floor(($this->session_duration % 3600) / 60);
        $seconds = $this->session_duration % 60;
        
        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        } else {
            return sprintf('%ds', $seconds);
        }
    }
}