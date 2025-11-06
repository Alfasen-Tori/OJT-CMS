<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trails';

    protected $fillable = [
        'table_name',
        'record_id', 
        'action',
        'old_values',
        'new_values',
        'changes',
        'ip_address',
        'user_agent',
        'user_id',
        'user_type',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['user_display_name', 'formatted_created_at'];

    // Relationship to user who performed the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for user display name
    public function getUserDisplayNameAttribute()
    {
        $user = $this->user;
        return $user ? "{$user->fname} {$user->lname}" : 'Unknown User';
    }

    // Accessor for formatted created at
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('M j, Y g:i A') : 'N/A';
    }

    // Scopes for filtering
    public function scopeTableName($query, $tableName)
    {
        return $query->where('table_name', $tableName);
    }

    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}