<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuditTrailService
{
    /**
     * Log user creation with role
     */
    public static function logUserCreation($newUserId, $userData, $role = null, Request $request = null)
    {
        $performer = Auth::user();
        if (!$performer) return null;

        $userType = self::getUserType($performer);
        
        // Build description with role if provided
        $description = "User account created: {$userData['fname']} {$userData['lname']} ({$userData['email']})";
        if ($role) {
            $description = ucfirst($role) . " account created: {$userData['fname']} {$userData['lname']} ({$userData['email']})";
        }
        
        return AuditTrail::create([
            'table_name' => 'users',
            'record_id' => $newUserId,
            'action' => 'created',
            'old_values' => null,
            'new_values' => $userData,
            'changes' => $description,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'user_id' => $performer->id,
            'user_type' => $userType,
        ]);
    }

    /**
     * Log user profile updates
     */
    public static function logUserUpdate($userId, $oldData, $newData, Request $request = null)
    {
        $performer = Auth::user();
        if (!$performer) return null;

        $userType = self::getUserType($performer);

        // Find what changed
        $changes = [];
        foreach ($newData as $field => $newValue) {
            if (isset($oldData[$field]) && $oldData[$field] != $newValue) {
                $changes[] = "{$field}: '{$oldData[$field]}' → '{$newValue}'";
            }
        }

        $changesDescription = "User profile updated: " . implode(', ', $changes);

        return AuditTrail::create([
            'table_name' => 'users',
            'record_id' => $userId,
            'action' => 'updated',
            'old_values' => $oldData,
            'new_values' => $newData,
            'changes' => $changesDescription,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'user_id' => $performer->id,
            'user_type' => $userType,
        ]);
    }

    /**
     * Log user deletion with role context
     */
    public static function logUserDeletion($userId, $userData, $role = null, Request $request = null)
    {
        $performer = Auth::user();
        if (!$performer) return null;

        $userType = self::getUserType($performer);

        // Build description with role if provided
        $description = "User account deleted: {$userData['fname']} {$userData['lname']} ({$userData['email']})";
        if ($role) {
            $description = ucfirst($role) . " account deleted: {$userData['fname']} {$userData['lname']} ({$userData['email']})";
        }

        return AuditTrail::create([
            'table_name' => 'users',
            'record_id' => $userId,
            'action' => 'deleted',
            'old_values' => $userData,
            'new_values' => null,
            'changes' => $description,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'user_id' => $performer->id,
            'user_type' => $userType,
        ]);
    }

    /**
     * Log role-specific updates (for coordinator permissions, etc.)
     */
    public static function logRoleUpdate($userId, $role, $oldData, $newData, Request $request = null)
    {
        $performer = Auth::user();
        if (!$performer) return null;

        $userType = self::getUserType($performer);

        // Find what changed
        $changes = [];
        foreach ($newData as $field => $newValue) {
            if (isset($oldData[$field]) && $oldData[$field] != $newValue) {
                $changes[] = "{$field}: '{$oldData[$field]}' → '{$newValue}'";
            }
        }

        $changesDescription = ucfirst($role) . " profile updated: " . implode(', ', $changes);

        return AuditTrail::create([
            'table_name' => 'users',
            'record_id' => $userId,
            'action' => 'updated',
            'old_values' => $oldData,
            'new_values' => $newData,
            'changes' => $changesDescription,
            'ip_address' => $request ? $request->ip() : request()->ip(),
            'user_agent' => $request ? $request->userAgent() : request()->userAgent(),
            'user_id' => $performer->id,
            'user_type' => $userType,
        ]);
    }

    /**
     * Get user management audit trail data
     */
    public static function getUserManagementAuditTrail($filters = [])
    {
        $query = AuditTrail::with(['user'])
            ->where('table_name', 'users') // Only user management activities
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('changes', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('fname', 'like', "%{$search}%")
                        ->orWhere('lname', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    /**
     * Get user type for audit trail
     */
    private static function getUserType($user)
    {
        if ($user->admin) return 'admin';
        if ($user->coordinator) return 'coordinator';
        if ($user->intern) return 'intern';
        if ($user->hte) return 'hte';
        return 'unknown';
    }
}