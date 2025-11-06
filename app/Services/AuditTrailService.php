<?php

namespace App\Services;

use App\Models\SessionAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditTrailService
{
    public static function logLogin($user, $userType, Request $request)
    {
        return SessionAuditTrail::create([
            'user_id' => $user->id,
            'user_type' => $userType,
            'action' => 'login',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
        ]);
    }

    public static function logLogout($user, $userType, Request $request, $sessionAuditId = null)
    {
        // If session audit ID is provided, update that record
        if ($sessionAuditId) {
            $sessionAudit = SessionAuditTrail::find($sessionAuditId);
            if ($sessionAudit) {
                $sessionAudit->update([
                    'logout_at' => now(),
                    'session_duration' => now()->diffInSeconds($sessionAudit->login_at),
                ]);
                return $sessionAudit;
            }
        }

        // Otherwise, find the latest login record for this user
        $latestLogin = SessionAuditTrail::where('user_id', $user->id)
            ->where('user_type', $userType)
            ->where('action', 'login')
            ->whereNull('logout_at')
            ->latest()
            ->first();

        if ($latestLogin) {
            $latestLogin->update([
                'logout_at' => now(),
                'session_duration' => now()->diffInSeconds($latestLogin->login_at),
            ]);
            return $latestLogin;
        }

        // Create a new logout record if no open login found
        return SessionAuditTrail::create([
            'user_id' => $user->id,
            'user_type' => $userType,
            'action' => 'logout',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logout_at' => now(),
        ]);
    }

    public static function getSessionAuditData($filters = [])
    {
        $query = SessionAuditTrail::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if (!empty($filters['user_type'])) {
            $query->where('user_type', $filters['user_type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('login_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('login_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('user', function($q) use ($search) {
                $q->where('fname', 'like', "%{$search}%")
                  ->orWhere('lname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query;
    }
}