<?php

namespace App\Http\Controllers;

use App\Models\Hte;
use App\Models\User;
use App\Models\Admin;
use App\Models\Intern;
use App\Models\Coordinator;

use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use App\Services\AuditTrailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // For sending emails
use Illuminate\Support\Str;       // For generating tokens
use App\Mail\CoordinatorSetupMail; // Your custom mail class
use Illuminate\Support\Facades\DB; // For database operations

class AuthController extends Controller
{
    public function adminLogin()
    {
        return view('auth.login-admin');
    }

    public function coordinatorLogin()
    {
        return view('auth.login-coordinator');
    }

    public function internLogin()
    {
        return view('auth.login-intern');
    }

    public function hteLogin()
    {
        return view('auth.login-hte');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $userType = $this->getUserType($user);
        
        // Log logout action
        $sessionAuditId = $request->session()->get('session_audit_id');
        AuditTrailService::logLogout($user, $userType, $request, $sessionAuditId);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function adminAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'faculty_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::with('user')->where('faculty_id', $credentials['faculty_id'])->first();

        if (!$admin || !$admin->user) {
            return back()->withErrors([
                'faculty_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('faculty_id');
        }

        if (Auth::attempt([
            'email' => $admin->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            
            // Log login action
            $sessionAudit = AuditTrailService::logLogin(Auth::user(), 'admin', $request);
            $request->session()->put('session_audit_id', $sessionAudit->id);
            
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('faculty_id');
    }

    public function coordinatorAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'faculty_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $coordinator = Coordinator::with('user')->where('faculty_id', $credentials['faculty_id'])->first();

        if (!$coordinator || !$coordinator->user) {
            return back()->withErrors([
                'faculty_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('faculty_id');
        }

        if (Auth::attempt([
            'email' => $coordinator->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            
            // Log login action
            $sessionAudit = AuditTrailService::logLogin(Auth::user(), 'coordinator', $request);
            $request->session()->put('session_audit_id', $sessionAudit->id);
            
            return redirect()->intended('coordinator/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('faculty_id');
    }

    public function internAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'student_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $intern = Intern::with('user')->where('student_id', $credentials['student_id'])->first();

        if (!$intern || !$intern->user) {
            return back()->withErrors([
                'student_id' => 'The provided credentials do not match our records.',
            ])->onlyInput('student_id');
        }

        if (Auth::attempt([
            'email' => $intern->user->email,
            'password' => $credentials['password']
        ], $request->remember)) {
            $request->session()->regenerate();
            
            // Log login action
            $sessionAudit = AuditTrailService::logLogin(Auth::user(), 'intern', $request);
            $request->session()->put('session_audit_id', $sessionAudit->id);
            
            return redirect()->intended('intern/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('student_id');
    }

    public function hteAuthenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $hte = Hte::with('user')->whereHas('user', function($query) use ($credentials) {
            $query->where('email', $credentials['email']);
        })->first();

        if (!$hte) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ], $request->remember)) {
            if (!auth()->user()->hte) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'This account is not registered as an HTE.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            
            // Log login action
            $sessionAudit = AuditTrailService::logLogin(Auth::user(), 'hte', $request);
            $request->session()->put('session_audit_id', $sessionAudit->id);
            
            return redirect()->intended('hte/dashboard');
        }

        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('email');
    }

    private function getUserType($user)
    {
        if ($user->admin) return 'admin';
        if ($user->coordinator) return 'coordinator';
        if ($user->intern) return 'intern';
        if ($user->hte) return 'hte';
        return 'unknown';
    }

public function showSetupForm($token, $role)
{
    // Validate the role first
    if (!in_array($role, ['intern', 'coordinator', 'hte'])) {
        return redirect()->route('login')
            ->with('error', 'Invalid account type.');
    }

    $tokenData = DB::table('password_setup_tokens')
        ->where('token', $token)
        ->where('created_at', '>', now()->subHours(24))
        ->first();

    if (!$tokenData) {
        return redirect()->route('login')
            ->with('error', 'Invalid or expired activation link.');
    }

    // Check if user exists and has the correct role
    $user = User::where('email', $tokenData->email)->first();
    if (!$user) {
        return redirect()->route('home')
            ->with('error', 'User account not found.');
    }

    return view('auth.setup-password', [
        'token' => $token,
        'email' => $tokenData->email,
        'role' => $role
    ]);
}

    public function processSetup(Request $request, $token, $role)
    {
        $request->validate([
            'password' => 'required|confirmed|min:8',
        ]);

        $tokenData = DB::table('password_setup_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(24))
            ->first();

        if (!$tokenData) {
            return back()->with('error', 'Invalid or expired activation link.');
        }

        $user = User::where('email', $tokenData->email)->firstOrFail();
        
        // Optional role verification
        if ($role === 'intern' && !$user->intern) {
            return back()->with('error', 'This account is not an intern.');
        }
        
        if ($role === 'coordinator' && !$user->coordinator) {
            return back()->with('error', 'This account is not a coordinator.');
        }

        $user->update([
                'password' => Hash::make($request->password),
                'temp_password' => false
            ]);

            // Explicitly specify the guard
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            DB::table('password_setup_tokens')->where('token', $token)->delete();

            return redirect()->route("{$role}.dashboard")
                ->with('success', 'Password set successfully!');
    }

        public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'We could not find a user with that email address.'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate token
        $token = Str::random(64);
        
        // Determine user role
        $role = $this->determineUserRole($user);

        // Store token in database (expires in 1 hour)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $token,
                'role' => $role,
                'created_at' => now()
            ]
        );

        // Send email
        Mail::to($user->email)->send(new PasswordResetMail($token, $role, $user->name));

        return back()->with('status', 'We have emailed your password reset link!');
    }

    /**
     * Show password reset form
     */
    public function showResetForm($token)
    {
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$tokenData) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid or expired reset link.');
        }

        return view('auth.password-reset', [
            'token' => $token,
            'email' => $tokenData->email,
            'role' => $tokenData->role
        ]);
    }

    // In resetPassword method:
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Find the token
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Invalid or expired reset link.'])
                        ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])
                        ->withInput();
        }

        try {
            // Update password ONLY
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            // Log the user in
            Auth::login($user);

            // Determine redirect route based on role
            $redirectRoute = $this->getDashboardRoute($tokenData->role);

            return redirect()->route($redirectRoute)
                ->with('success', 'Password has been reset successfully!');

        } catch (\Exception $e) {
            \Log::error('Error resetting password: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'An error occurred. Please try again.'])
                        ->withInput();
        }
    }

    /**
     * Determine user role
     */
    private function determineUserRole($user)
    {
        if ($user->admin) return 'admin';
        if ($user->coordinator) return 'coordinator';
        if ($user->intern) return 'intern';
        if ($user->hte) return 'hte';
        return 'user';
    }

    /**
     * Get dashboard route based on role
     */
    private function getDashboardRoute($role)
    {
        return match($role) {
            'admin' => 'admin.dashboard',
            'coordinator' => 'coordinator.dashboard',
            'intern' => 'intern.dashboard',
            'hte' => 'hte.dashboard',
            default => 'home'
        };
    }
}
