<?php

namespace App\Http\Controllers;

use import;
use Exception;

use Carbon\Carbon;

use App\Models\Hte;
use App\Models\User;
use App\Models\Intern;
use App\Mail\HteSetupMail;
use App\Models\InternsHte;

use App\Models\Coordinator;
use Illuminate\Support\Str;


use Illuminate\Http\Request;
use App\Mail\InternSetupMail;
use App\Imports\InternsImport;
use Illuminate\Support\Facades\DB;
use App\Mail\StudentDeploymentMail;
use App\Models\CoordinatorDocument;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

use App\Services\UserAuditTrailService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Validator;


class CoordinatorController extends Controller
{
public function dashboard() {
    // Get the currently logged-in coordinator
    $coordinator = auth()->user()->coordinator;
    
    // Count students added by this coordinator
    $myStudentsCount = Intern::where('coordinator_id', $coordinator->id)->count();
    $totalHtesCount = Hte::count();
    
    // Count deployments and status breakdown
    $activeDeploymentsCount = InternsHte::where('coordinator_id', $coordinator->id)
        ->where('status', 'deployed')
        ->count();
    
    $endorsedCount = InternsHte::where('coordinator_id', $coordinator->id)
        ->where('status', 'endorsed')
        ->count();
    
    // Count interns by all statuses
    $pendingRequirementsCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'pending requirements')
        ->count();
    
    $readyForDeploymentCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'ready for deployment')
        ->count();
    
    $endorsedInternsCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'endorsed')
        ->count();
    
    $processingCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'processing')
        ->count();
    
    $deployedCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'deployed')
        ->count();
    
    $completedCount = Intern::where('coordinator_id', $coordinator->id)
        ->where('status', 'completed')
        ->count();
    
    // Recent activity (last 5 deployments)
    $recentDeployments = InternsHte::with(['intern.user', 'hte'])
        ->where('coordinator_id', $coordinator->id)
        ->latest()
        ->take(5)
        ->get();

    return view('coordinator.dashboard', [
        'myStudentsCount' => $myStudentsCount,
        'totalHtesCount' => $totalHtesCount,
        'activeDeploymentsCount' => $activeDeploymentsCount,
        'endorsedCount' => $endorsedCount,
        'pendingRequirementsCount' => $pendingRequirementsCount,
        'readyForDeploymentCount' => $readyForDeploymentCount,
        'endorsedInternsCount' => $endorsedInternsCount,
        'processingCount' => $processingCount,
        'deployedCount' => $deployedCount,
        'completedCount' => $completedCount,
        'recentDeployments' => $recentDeployments,
        'coordinator' => $coordinator
    ]);
}

    public function profile()
    {
        $coordinator = auth()->user()->coordinator;
        return view('coordinator.profile', compact('coordinator'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail(auth()->id());
            
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->contact = $request->contact;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            if (!$user->save()) {
                throw new \Exception('Failed to save user');
            }

            return back()->with('success', 'Profile updated successfully');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $userId = auth()->id();
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                throw new \Exception('User not found');
            }

            if ($request->hasFile('profile_pic')) {
                // Delete old picture if exists
                if ($user->pic) {
                    Storage::delete($user->pic);
                }

                // Store new picture
                $path = $request->file('profile_pic')->store('profile-pictures', 'public');
                
                // Update database directly
                $updated = DB::table('users')
                            ->where('id', $userId)
                            ->update(['pic' => $path]);
                
                if (!$updated) {
                    throw new \Exception('Failed to update profile picture in database');
                }

                return response()->json([
                    'url' => asset('storage/'.$path),
                    'message' => 'Profile picture updated successfully'
                ]);
            }

            throw new \Exception('No file uploaded');
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Intern Methods
    public function showInterns()
    {
        // Get the authenticated user's coordinator ID
        $coordinatorId = auth()->user()->coordinator->id;
        
        // Filter interns by the coordinator's ID, ordered by newest first
        $interns = Intern::with(['user', 'department'])
                    ->where('coordinator_id', $coordinatorId)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('coordinator.interns', compact('interns'));
    }

    public function newIntern() {
        return view('coordinator.new-intern');
    }

    public function registerIntern(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'student_id' => 'required|string|unique:interns|regex:/^\d{4}-\d{5}$/',
            'birthdate' => 'required|date',
            'sex' => 'required|in:male,female',
            'year_level' => 'required|integer|between:1,4',
            'section' => 'required|in:a,b,c,d,e,f',
            'academic_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,midyear',
            'dept_id' => 'required|exists:departments,dept_id'
        ]);

        // Generate temporary password
        $tempPassword = Str::random(16);

        // Create user account with default profile picture
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'fname' => $validated['first_name'],
            'lname' => $validated['last_name'],
            'contact' => $validated['contact'],
            'sex' => $validated['sex'],
            'pic' => null, // Default profile picture
            'temp_password' => true,
            'username' => $validated['student_id']
        ]);

        // Create intern record
        $intern = Intern::create([
            'student_id' => $validated['student_id'],
            'user_id' => $user->id,
            'dept_id' => $validated['dept_id'],
            'birthdate' => $validated['birthdate'],
            'coordinator_id' => auth()->user()->coordinator->id, // Set from logged-in coordinator
            'year_level' => $validated['year_level'],
            'section' => $validated['section'],
            'academic_year' => $validated['academic_year'],
            'semester' => $validated['semester'],
            'status' => 'pending requirements' // Default status, 
        ]);

        // AUDIT TRAIL: Log intern creation
        UserAuditTrailService::logUserCreation(
            $user->id,
            [
                'fname' => $validated['first_name'],
                'lname' => $validated['last_name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'student_id' => $validated['student_id'],
                'year_level' => $validated['year_level'],
                'section' => $validated['section']
            ],
            'intern',
            $request
        );

        // Generate activation token
        $token = Str::random(60);
        DB::table('password_setup_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send activation email
        $setupLink = route('password.setup', [
            'token' => $token, 
            'role' => 'intern'
        ]);
        $internName = $validated['first_name'] . ' ' . $validated['last_name'];
        
        Mail::to($user->email)->send(new InternSetupMail(
            $setupLink,
            $internName,
            $tempPassword
        ));

        return redirect()->route('coordinator.interns')
            ->with('success', 'Intern registered successfully. Activation email sent.');
    }

    public function editIntern($id)
    {
        // Get the intern with related user data
        $intern = Intern::with('user')->findOrFail($id);
        
        // Check if the coordinator has permission to edit this intern
        if (auth()->user()->coordinator->id !== $intern->coordinator_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('coordinator.interns-edit', compact('intern'));
    }

    public function updateIntern(Request $request, $id)
    {
        // Find the intern
        $intern = Intern::findOrFail($id);
        
        // Check if the coordinator has permission to edit this intern
        if (auth()->user()->coordinator->id !== $intern->coordinator_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validation rules (same as registration)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'sex' => 'required|in:male,female',
            'email' => 'required|email|unique:users,email,' . $intern->user_id,
            'contact' => 'required|string|max:20',
            'student_id' => 'required|regex:/^\d{4}-\d{5}$/|unique:interns,student_id,' . $id,
            'academic_year' => 'required|regex:/^\d{4}-\d{4}$/',
            'semester' => 'required|in:1st,2nd,midyear',
            'year_level' => 'required|integer|between:1,4',
            'section' => 'required|in:a,b,c,d,e,f',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Store old data for audit trail
            $oldUserData = [
                'fname' => $intern->user->fname,
                'lname' => $intern->user->lname,
                'email' => $intern->user->email,
                'contact' => $intern->user->contact,
                'sex' => $intern->user->sex
            ];

            $oldInternData = [
                'student_id' => $intern->student_id,
                'birthdate' => $intern->birthdate,
                'academic_year' => $intern->academic_year,
                'semester' => $intern->semester,
                'year_level' => $intern->year_level,
                'section' => $intern->section
            ];
            
            // Update user information
            $user = User::findOrFail($intern->user_id);
            $user->update([
                'fname' => $validated['first_name'],
                'lname' => $validated['last_name'],
                'email' => $validated['email'],
                'contact' => $validated['contact'],
                'sex' => $validated['sex'],
            ]);
            
            // Update intern information
            $intern->update([
                'student_id' => $validated['student_id'],
                'birthdate' => $validated['birthdate'],
                'academic_year' => $validated['academic_year'],
                'semester' => $validated['semester'],
                'year_level' => $validated['year_level'],
                'section' => $validated['section'],
            ]);

            // AUDIT TRAIL: Log user profile update
            UserAuditTrailService::logUserUpdate(
                $intern->user_id,
                $oldUserData,
                [
                    'fname' => $validated['first_name'],
                    'lname' => $validated['last_name'],
                    'email' => $validated['email'],
                    'contact' => $validated['contact'],
                    'sex' => $validated['sex']
                ],
                $request
            );

            // AUDIT TRAIL: Log intern-specific updates
            $internChanges = [];
            if ($oldInternData['student_id'] != $validated['student_id']) {
                $internChanges[] = "Student ID: {$oldInternData['student_id']} → {$validated['student_id']}";
            }
            if ($oldInternData['birthdate'] != $validated['birthdate']) {
                $internChanges[] = "Birthdate: {$oldInternData['birthdate']} → {$validated['birthdate']}";
            }
            if ($oldInternData['academic_year'] != $validated['academic_year']) {
                $internChanges[] = "Academic Year: {$oldInternData['academic_year']} → {$validated['academic_year']}";
            }
            if ($oldInternData['semester'] != $validated['semester']) {
                $internChanges[] = "Semester: {$oldInternData['semester']} → {$validated['semester']}";
            }
            if ($oldInternData['year_level'] != $validated['year_level']) {
                $internChanges[] = "Year Level: {$oldInternData['year_level']} → {$validated['year_level']}";
            }
            if ($oldInternData['section'] != $validated['section']) {
                $internChanges[] = "Section: {$oldInternData['section']} → {$validated['section']}";
            }

            if (!empty($internChanges)) {
                UserAuditTrailService::logRoleUpdate(
                    $intern->user_id,
                    'intern',
                    $oldInternData,
                    [
                        'student_id' => $validated['student_id'],
                        'birthdate' => $validated['birthdate'],
                        'academic_year' => $validated['academic_year'],
                        'semester' => $validated['semester'],
                        'year_level' => $validated['year_level'],
                        'section' => $validated['section']
                    ],
                    $request
                );
            }
            
            DB::commit();
            
            return redirect()->route('coordinator.interns')->with('success', 'Intern updated successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update intern: ' . $e->getMessage());
        }
    }

    public function destroyIntern($id)
    {
        try {
            DB::beginTransaction();

            $intern = Intern::findOrFail($id);
            $userId = $intern->user_id;

            // Store user data for audit trail before deletion
            $userData = [
                'fname' => $intern->user->fname,
                'lname' => $intern->user->lname,
                'email' => $intern->user->email,
                'contact' => $intern->user->contact,
                'student_id' => $intern->student_id
            ];
            
            $intern->delete();

            // Check if user exists in other tables
            $userStillHasRoles = DB::table('admins')->where('user_id', $userId)->exists()
                || DB::table('coordinators')->where('user_id', $userId)->exists()
                || DB::table('htes')->where('user_id', $userId)->exists();

            if (!$userStillHasRoles) {
                User::destroy($userId);
            }

            // AUDIT TRAIL: Log intern deletion
            UserAuditTrailService::logUserDeletion(
                $userId,
                $userData,
                'intern',
                request()
            );

            DB::commit();

            return redirect()->route('coordinator.interns')
                ->with('success', 'Intern deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('coordinator.interns')
                ->with('error', 'Failed to delete intern: ' . $e->getMessage());
        }
    }

    public function showIntern($id)
    {
        $intern = Intern::with([
                'user', 
                'department', 
                'skills', 
                'coordinator.user',
                'weeklyReports'
            ])
            ->findOrFail($id);
        
        // Get current deployment if any
        $currentDeployment = \App\Models\InternsHte::with('evaluation')
            ->where('intern_id', $id)
            ->whereIn('status', ['deployed', 'completed'])
            ->latest()
            ->first();
        
        $progress = [];
        $evaluation = null;
        
        if ($currentDeployment) {
            // Calculate progress
            $totalHours = \App\Models\Attendance::where('intern_hte_id', $currentDeployment->id)
                ->sum('hours_rendered');
            $requiredHours = $currentDeployment->no_of_hours;
            $percentage = $requiredHours > 0 ? min(100, ($totalHours / $requiredHours) * 100) : 0;
            
            $progress = [
                'total_rendered' => $totalHours,
                'required_hours' => $requiredHours,
                'percentage' => round($percentage, 1)
            ];
            
            // Get evaluation if exists
            $evaluation = $currentDeployment->evaluation;
        }
        
        // Get weekly reports
        $weeklyReports = $intern->weeklyReports()->orderBy('week_no')->get();

        return view('coordinator.intern_show', compact(
            'intern', 
            'currentDeployment', 
            'progress', 
            'evaluation',
            'weeklyReports'
        ));
    }

    // HTE Methods
    public function htes() {
        $htes = Hte::withCount('internsHte')->get();
        return view('coordinator.htes', compact('htes'));
    }

    public function newHTE() {
        return view('coordinator.new-hte');
    }

public function registerHTE(Request $request)
{
    $validated = $request->validate([
        'contact_email' => 'required|email|unique:users,email',
        'contact_first_name' => 'required|string|max:255',
        'contact_last_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'organization_name' => 'required|string|max:255',
        'organization_type' => 'required|in:private,government,ngo,educational,other',
        'hte_status' => 'required|in:active,new',
        'description' => 'nullable|string',
        'coordinator_id' => 'required|exists:coordinators,id'
    ]);

    $tempPassword = Str::random(16);

    $user = User::create([
        'email' => $validated['contact_email'],
        'password' => Hash::make($tempPassword),
        'fname' => $validated['contact_first_name'],
        'lname' => $validated['contact_last_name'],
        'contact' => $validated['contact_number'],
        'pic' => 'profile-pictures/profile.jpg',
        'temp_password' => true,
        'username' => $validated['contact_email']
    ]);

    $hte = Hte::create([
        'user_id' => $user->id,
        'status' => $validated['hte_status'],
        'type' => $validated['organization_type'],
        'address' => $validated['address'],
        'description' => $validated['description'],
        'organization_name' => $validated['organization_name'],
        'slots' => 0,
        'moa_path' => null
    ]);

    // AUDIT TRAIL: Log HTE creation
    UserAuditTrailService::logUserCreation(
        $user->id,
        [
            'fname' => $validated['contact_first_name'],
            'lname' => $validated['contact_last_name'],
            'email' => $validated['contact_email'],
            'contact' => $validated['contact_number'],
            'organization_name' => $validated['organization_name'],
            'organization_type' => $validated['organization_type'],
            'hte_status' => $validated['hte_status']
        ],
        'hte',
        $request
    );

    $token = Str::random(60);
    DB::table('password_setup_tokens')->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now()
    ]);

    $setupLink = route('password.setup', [
        'token' => $token,
        'role' => 'hte'
    ]);
    $contactName = $validated['contact_first_name'] . ' ' . $validated['contact_last_name'];

    $moaAttachmentPath = null;
    $generatedDocxPath = null; // Declare outside if block to track for deletion

    if ($validated['hte_status'] === 'new') {
        $templatePath = public_path('templates/moa-template.docx');
        $generatedDocxPath = storage_path('app/public/moa-documents/generated-moa-' . $hte->id . '.docx');

        // Fill DOCX template
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor->setValue('organization_name', $validated['organization_name']);
        $templateProcessor->setValue('org_name', strtoupper($validated['organization_name']));
        $templateProcessor->setValue('address', $validated['address']);
        $templateProcessor->setValue('contact_name', $contactName);
        // Add more placeholders as needed
        $templateProcessor->saveAs($generatedDocxPath);

        if (file_exists($generatedDocxPath)) {
            $moaAttachmentPath = $generatedDocxPath;
        }
    }

    // Send email synchronously and delete file only on success
    try {
        Mail::to($user->email)->send(new HteSetupMail(
            $setupLink,
            $contactName,
            $validated['organization_name'],
            $tempPassword,
            $moaAttachmentPath,
            $user->email
        ));

        // Delete the generated DOCX only if email sent successfully
        if ($generatedDocxPath && file_exists($generatedDocxPath)) {
            unlink($generatedDocxPath);
        }

        return redirect()->route('coordinator.htes')
            ->with('success', 'HTE registered successfully. Activation email sent.');
    } catch (\Exception $e) {
        // Log the error for debugging
        
        // Optional: Delete the file even on failure to avoid leftovers (uncomment if preferred)
        // if ($generatedDocxPath && file_exists($generatedDocxPath)) {
        //     unlink($generatedDocxPath);
        //     \Log::info('Temporary DOCX deleted after email failure: ' . $generatedDocxPath);
        // }

        return redirect()->route('coordinator.htes')
            ->with('error', 'HTE registered, but failed to send activation email. Please check logs and retry.');
    }
}

public function updateHte(Request $request, $id)
{
    // Find the HTE
    $hte = Hte::findOrFail($id);
    
    // Validation rules (same as registration)
    $validated = $request->validate([
        'contact_first_name' => 'required|string|max:255',
        'contact_last_name' => 'required|string|max:255',
        'contact_email' => 'required|email|unique:users,email,' . $hte->user_id,
        'contact_number' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'organization_name' => 'required|string|max:255',
        'organization_type' => 'required|in:private,government,ngo,educational,other',
        'description' => 'nullable|string',
    ]);
    
    try {
        DB::beginTransaction();
        
        // Store old data for audit trail
        $oldUserData = [
            'fname' => $hte->user->fname,
            'lname' => $hte->user->lname,
            'email' => $hte->user->email,
            'contact' => $hte->user->contact
        ];

        $oldHteData = [
            'organization_name' => $hte->organization_name,
            'type' => $hte->type,
            'address' => $hte->address,
            'description' => $hte->description
        ];
        
        // Update user information
        $user = User::findOrFail($hte->user_id);
        $user->update([
            'fname' => $validated['contact_first_name'],
            'lname' => $validated['contact_last_name'],
            'email' => $validated['contact_email'],
            'contact' => $validated['contact_number'],
        ]);
        
        // Update HTE information
        $hte->update([
            'organization_name' => $validated['organization_name'],
            'type' => $validated['organization_type'],
            'address' => $validated['address'],
            'description' => $validated['description'] ?? null,
        ]);

        // AUDIT TRAIL: Log user profile update
        UserAuditTrailService::logUserUpdate(
            $hte->user_id,
            $oldUserData,
            [
                'fname' => $validated['contact_first_name'],
                'lname' => $validated['contact_last_name'],
                'email' => $validated['contact_email'],
                'contact' => $validated['contact_number']
            ],
            $request
        );

        // AUDIT TRAIL: Log HTE-specific updates
        $hteChanges = [];
        if ($oldHteData['organization_name'] != $validated['organization_name']) {
            $hteChanges[] = "Organization Name: {$oldHteData['organization_name']} → {$validated['organization_name']}";
        }
        if ($oldHteData['type'] != $validated['organization_type']) {
            $hteChanges[] = "Organization Type: {$oldHteData['type']} → {$validated['organization_type']}";
        }
        if ($oldHteData['address'] != $validated['address']) {
            $hteChanges[] = "Address updated";
        }
        if ($oldHteData['description'] != $validated['description']) {
            $hteChanges[] = "Description updated";
        }

        if (!empty($hteChanges)) {
            UserAuditTrailService::logRoleUpdate(
                $hte->user_id,
                'hte',
                $oldHteData,
                [
                    'organization_name' => $validated['organization_name'],
                    'type' => $validated['organization_type'],
                    'address' => $validated['address'],
                    'description' => $validated['description'] ?? null
                ],
                $request
            );
        }
        
        DB::commit();
        
        return redirect()->route('coordinator.htes')->with('success', 'HTE updated successfully.');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update HTE: ' . $e->getMessage());
    }
}   

public function destroyHTE($id)
{
    try {
        DB::beginTransaction();

        // Find the HTE
        $hte = HTE::findOrFail($id);

        // Store user data for audit trail before deletion
        $userData = [
            'fname' => $hte->user->fname,
            'lname' => $hte->user->lname,
            'email' => $hte->user->email,
            'contact' => $hte->user->contact,
            'organization_name' => $hte->organization_name
        ];

        // Store user ID for later check
        $userId = $hte->user_id;

        // Delete the HTE record (cascade will handle related records like hte_skill)
        $hte->delete();

        // Check if user has other roles
        $hasOtherRoles = DB::table('admins')
            ->where('user_id', $userId)
            ->orWhereExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                      ->from('coordinators')
                      ->where('user_id', $userId);
            })
            ->orWhereExists(function ($query) use ($userId) {
                $query->select(DB::raw(1))
                      ->from('interns')
                      ->where('user_id', $userId);
            })
            ->exists();

        // Delete user only if they don't have other roles
        if (!$hasOtherRoles) {
            User::where('id', $userId)->delete();
        }

        // AUDIT TRAIL: Log HTE deletion
        UserAuditTrailService::logUserDeletion(
            $userId,
            $userData,
            'hte',
            request()
        );

        DB::commit();

        return redirect()->route('coordinator.htes')
            ->with('success', 'HTE account unregistered successfully.');

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        Log::error('HTE not found: ' . $e->getMessage());
        return redirect()->route('coordinator.htes')
            ->with('error', 'HTE account not found.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error deleting HTE: ' . $e->getMessage());
        return redirect()->route('coordinator.htes')
            ->with('error', 'An error occurred while unregistering the HTE: ' . $e->getMessage());
    }
}

    public function showHTE($id)
    {
        $hte = Hte::with(['user', 'skills', 'skills.department'])
            ->findOrFail($id);

        // Load all interns_htes for this HTE with coordinator information
        $endorsedInterns = \App\Models\InternsHte::with([
                'intern.user', 
                'intern.department',
                'coordinator.user' // Load coordinator details
            ])
            ->where('hte_id', $id)
            ->get();

        // Group by coordinator_id for the new table
        $groupedByCoordinator = $endorsedInterns->groupBy('coordinator_id');

        $endorsedCount = $endorsedInterns->count();
        $availableSlots = $hte->slots - $endorsedCount;
        $availableSlots = max(0, $availableSlots);

        $canManage = auth()->user()->coordinator->can_add_hte == 1;

        return view('coordinator.hte_show', compact(
            'hte', 
            'canManage', 
            'endorsedInterns', 
            'availableSlots', 
            'groupedByCoordinator' 
        ));
    }

    public function toggleMoaStatus($id)
    {
        $hte = Hte::findOrFail($id);
        
        // Toggle the MOA status
        $hte->moa_is_signed = $hte->moa_is_signed === 'yes' ? 'no' : 'yes';
        $hte->save();
        
        return response()->json([
            'success' => true,
            'new_status' => $hte->moa_is_signed,
            'message' => 'MOA status updated successfully'
        ]);
    }

    public function editHte($id)
    {
        // Get the HTE with related user data
        $hte = Hte::with('user')->findOrFail($id);
        
        return view('coordinator.htes-edit', compact('hte'));
    }

    public function removeEndorsement($id)
    {
        try {
            $endorsement = \App\Models\InternsHte::findOrFail($id);

            $intern = $endorsement->intern;
            if ($intern) {
                $intern->status = 'ready for deployment';
                $intern->save();
            }

            $endorsement->delete();

            return response()->json(['success' => true, 'message' => 'Intern endorsement removed successfully.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Endorsement record not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to remove endorsement.'], 500);
        }
    }

public function cancelEndorsement($hteId)
{
    try {
        // Find all intern_hte records for this HTE
        $internHtes = InternsHte::where('hte_id', $hteId)->get();
        
        if ($internHtes->isEmpty()) {
            return redirect()->back()->with('error', 'No endorsement records found for this HTE.');
        }
        
        // Get all intern IDs before deletion
        $internIds = $internHtes->pluck('intern_id')->toArray();
        
        // Delete all intern_hte records for this HTE
        InternsHte::where('hte_id', $hteId)->delete();
        
        // Update all interns status back to "ready for deployment"
        Intern::whereIn('id', $internIds)->update([
            'status' => 'ready for deployment'
        ]);
        
        return redirect()->route('coordinator.deployments')->with('success', 'Endorsement cancelled successfully. All interns status have been reverted to "Ready for Deployment".');
        
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while cancelling the endorsement: ' . $e->getMessage());
    }
}

    public function showImportForm()
    {
        return view('coordinator.interns.import');
    }

    public function importInterns(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv',
            'coordinator_id' => 'required|exists:coordinators,id',
            'dept_id' => 'required|exists:departments,dept_id'
        ]);

        try {
            $import = new InternsImport(
                $request->coordinator_id,
                $request->dept_id
            );
            
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('import_file'));
            
            return response()->json([
                'success' => true,
                'success_count' => $import->getSuccessCount(),
                'fail_count' => $import->getFailCount(),
                'failures' => $import->getFailures()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error during import: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

public function endorse() {
    $coordinatorId = auth()->user()->coordinator->id;
    
    $htes = \App\Models\Hte::with('skills')
        ->where('moa_is_signed', 'yes')
        ->withCount('internsHte')
        ->where(function($query) use ($coordinatorId) {
            // Include HTEs that have NO endorsements from this coordinator
            $query->whereDoesntHave('internsHte', function($q) use ($coordinatorId) {
                $q->where('coordinator_id', $coordinatorId);
            })
            // OR include HTEs that have endorsements with status 'endorsed' from this coordinator
            ->orWhereHas('internsHte', function($q) use ($coordinatorId) {
                $q->where('coordinator_id', $coordinatorId)
                  ->where('status', 'endorsed');
            });
        })
        ->havingRaw('slots > interns_hte_count')
        ->get();

    return view('coordinator.endorse', compact('htes'));
}

    public function getRecommendedInterns(Request $request) {
        $hteId = $request->input('hte_id');
        $requiredSkillIds = $request->input('required_skills', []);
        
        // Get current coordinator's ID
        $currentCoordinatorId = auth()->user()->coordinator->id;
        
        // Get only interns that the current coordinator registered/added
        $interns = Intern::with(['user', 'department', 'skills'])
            ->where('coordinator_id', $currentCoordinatorId) // Only coordinator's interns
            ->whereIn('status', ['pending requirements', 'ready for deployment'])
            ->orderByRaw("FIELD(status, 'ready for deployment', 'pending requirements')")
            ->get();

        // Calculate skill matches for each intern
        $internsWithMatches = $interns->map(function($intern) use ($requiredSkillIds) {
            $internSkills = $intern->skills->pluck('skill_id')->toArray();
            
            // Find matching skills
            $matchingSkills = array_intersect($internSkills, $requiredSkillIds);
            
            // Calculate match percentage
            // $matchPercentage = count($requiredSkillIds) > 0 
            //     ? round((count($matchingSkills) / count($requiredSkillIds)) * 100) : 0;

                $matchPercentage = count($internSkills) > 0 
                ? round((count($matchingSkills) / count($internSkills)) * 100) 
                : 0;
            
            // Get skill names for display
            $matchingSkillNames = $intern->skills
                ->whereIn('skill_id', $matchingSkills)
                ->pluck('name')
                ->toArray();
            
            return [
                'id' => $intern->id,
                'student_id' => $intern->student_id,
                'fname' => $intern->user->fname,
                'lname' => $intern->user->lname,
                'department' => $intern->department->short_name,
                'status' => $intern->status,
                'matching_skills' => $matchingSkillNames,
                'match_percentage' => $matchPercentage,
                'total_matches' => count($matchingSkills)
            ];
        });
        
        // Sort by match percentage (descending) and then by total matches (descending)
        $sortedInterns = $internsWithMatches->sortByDesc(function($intern) {
            return [$intern['match_percentage'], $intern['total_matches']];
        })->values()->all();
        
        return response()->json([
            'success' => true,
            'interns' => $sortedInterns
        ]);
    }

    public function getEndorsedCount(Request $request)
    {
        $count = \App\Models\InternsHte::where('hte_id', $request->hte_id)->count();
        return response()->json(['count' => $count]);
    }

    public function batchEndorseInterns(Request $request)
    {
        $request->validate([
            'hte_id' => 'required|exists:htes,id',
            'intern_ids' => 'required|array|min:1',
            'intern_ids.*' => 'exists:interns,id',
        ]);

        $hteId = $request->hte_id;
        $internIds = $request->intern_ids;
        $coordinatorId = auth()->user()->coordinator->id; // Get current coordinator

        // Filter interns that are "ready for deployment" and not already endorsed for this HTE
        $readyInterns = \App\Models\Intern::whereIn('id', $internIds)
            ->where('status', 'ready for deployment')
            ->get();

        if ($readyInterns->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No interns are eligible for endorsement.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($readyInterns as $intern) {
                // Check if already endorsed for this HTE
                $exists = InternsHte::where('intern_id', $intern->id)
                    ->where('hte_id', $hteId)
                    ->exists();

                if (!$exists) {
                    InternsHte::create([
                        'intern_id' => $intern->id,
                        'hte_id' => $hteId,
                        'coordinator_id' => $coordinatorId, // Add coordinator_id
                        'status' => 'endorsed',
                        'endorsed_at' => now(),
                    ]);

                    // Update intern status to 'endorsed'
                    $intern->update(['status' => 'endorsed']);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Selected interns have been successfully endorsed.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the actual error for debugging
            Log::error('Batch endorsement error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while endorsing interns: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deployHTE(Request $request, Hte $hte)
    {
        // Validate new inputs
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|after_or_equal:today',
            'no_of_hours' => 'required|integer|min:1|max:1000', // Adjust max as needed
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $startDate = Carbon::parse($request->start_date);
        $noOfHours = (int) $request->no_of_hours;
        $noOfWeeks = (int) ceil($noOfHours / 40); // 40 hours/week (8 hours/day * 5 days/week)
        $endDate = $startDate->copy()->addWeeks($noOfWeeks)->format('Y-m-d');

        Log::info('Deployment params: HTE ID=' . $hte->id . ', Start=' . $startDate->format('Y-m-d') . 
                ', Hours=' . $noOfHours . ', Weeks=' . $noOfWeeks . ', End=' . $endDate);

        Log::info('Deployment started for HTE ID: ' . $hte->id);

        // Create directories if needed
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            if (!mkdir($tempDir, 0755, true)) {
                Log::error('Failed to create temp directory: ' . $tempDir);
                return redirect()->back()->with('error', 'Failed to create temporary directory. Check permissions.');
            }
            Log::info('Temp directory created: ' . $tempDir);
        }

        $endorsementDir = storage_path('app/public/endorsement-letters');
        if (!file_exists($endorsementDir)) {
            if (!mkdir($endorsementDir, 0755, true)) {
                Log::error('Failed to create endorsement letters directory: ' . $endorsementDir);
                return redirect()->back()->with('error', 'Failed to create endorsement letters directory. Check permissions.');
            }
            Log::info('Endorsement letters directory created: ' . $endorsementDir);
        }

        // Fetch endorsed interns
        $endorsedInterns = InternsHte::where('hte_id', $hte->id)
            ->where('status', 'endorsed')
            ->with(['intern.user', 'intern.department'])
            ->get();

        Log::info('Found ' . $endorsedInterns->count() . ' endorsed interns for HTE: ' . $hte->organization_name);

        if ($endorsedInterns->isEmpty()) {
            Log::warning('No endorsed interns found for HTE ID: ' . $hte->id);
            return redirect()->back()->with('error', 'No interns to deploy.');
        }

        // Get shared data (from current coordinator) with null checks
        $coordinator = auth()->user()->coordinator;
        if (!$coordinator) {
            Log::error('No coordinator record found for user ID: ' . auth()->id());
            return redirect()->back()->with('error', 'Coordinator data not found. Contact admin.');
        }

        $department = $coordinator->department;
        if (!$department) {
            Log::error('No department found for coordinator ID: ' . $coordinator->id);
            return redirect()->back()->with('error', 'Department data not found.');
        }

        $college = $department->college;
        if (!$college) {
            Log::error('No college found for department ID: ' . $department->dept_id);
            return redirect()->back()->with('error', 'College data not found.');
        }

        $collegeName = $college->name;
        Log::info('College name resolved: ' . $collegeName);

        // HTE shared data
        if (!$hte->user) {
            Log::error('No user found for HTE ID: ' . $hte->id);
            return redirect()->back()->with('error', 'HTE user data not found.');
        }

        $hteName = $hte->organization_name;
        $hteAddress = $hte->address ?? 'No address provided';
        $repFullname = $hte->user->fname . ' ' . $hte->user->lname;
        Log::info('HTE data: Name=' . $hteName . ', Address=' . $hteAddress . ', Rep=' . $repFullname);

        Log::info('Placeholder values to replace:');
        Log::info('- college_name: ' . $collegeName);
        Log::info('- hte_name: ' . $hteName);
        Log::info('- hte_address: ' . $hteAddress);
        Log::info('- rep_fullname: ' . $repFullname);

        // Step 0: Generate SINGLE Endorsement Letter for this HTE (shared, outside loop)
        $timestamp = now()->format('Ymd-His'); // e.g., 20250924-080107
        $endorsementFilename = 'endorsement-' . $hte->id . '-' . $timestamp . '.docx';
        $endorsementFullPath = $endorsementDir . '/' . $endorsementFilename;
        $endorsementRelativePath = 'endorsement-letters/' . $endorsementFilename; // Shared path for all interns_htes records

        $endorsementTempPath = storage_path('app/public/temp/endorsement-' . $hte->id . '-' . $timestamp . '.docx');
        $endorsementDebugPath = storage_path('app/public/temp/endorsement-' . $hte->id . '-' . $timestamp . '-debug.docx');

        // Build shared intern list for the letter (formatted string for ${intern_list})
        $internList = '';
        foreach ($endorsedInterns as $index => $endorsement) {
            $intern = $endorsement->intern;
            if ($intern && $intern->user) {
                $deptName = $intern->department?->dept_name ?? 'N/A';
                $internList .= ($index + 1) . '. ' . $intern->user->fname . ' ' . $intern->user->lname . ' (ID: ' . ($intern->student_id ?? 'N/A') . '), ' . $deptName . '; ';
            }
        }
        $internList = rtrim($internList, '; '); // Clean trailing semicolon
        if (empty($internList)) {
            $internList = 'No interns listed.';
        }
        Log::info('Shared intern list for endorsement: ' . $internList);

        // Get shared semester/year (use first intern's or fallback; assume uniform for HTE)
        $firstIntern = $endorsedInterns->first()?->intern;
        $semester = $firstIntern?->semester ?? '1st';
        $year = $firstIntern?->academic_year ?? date('Y') . '-' . (date('Y') + 1);

        $endorsementSuccess = false;
        try {
            $endorsementTemplatePath = public_path('document-templates/endorsement-letter-template.docx');
            Log::info('Endorsement template path: ' . $endorsementTemplatePath);
            if (!file_exists($endorsementTemplatePath)) {
                throw new Exception('Endorsement template file not found at: ' . $endorsementTemplatePath);
            }

            $endorsementProcessor = new TemplateProcessor($endorsementTemplatePath);

            $todayDate = now()->format('F j, Y'); // e.g., "September 24, 2025"

            Log::info('Endorsement placeholders: date=' . $todayDate . ', semester=' . $semester . ', year=' . $year . ', college_name=' . $collegeName . ', hte_address=' . $hteAddress . ', hte_name=' . $hteName . ', rep_fullname=' . $repFullname . ', intern_list=' . substr($internList, 0, 100) . '...');

            // Use setValue() for all placeholders (ensuring hte_name and rep_fullname are set)
            $endorsementProcessor->setValue('date', $todayDate);
            $endorsementProcessor->setValue('college_name', $collegeName);
            $endorsementProcessor->setValue('hte_address', $hteAddress);
            $endorsementProcessor->setValue('semester', $semester);
            $endorsementProcessor->setValue('year', $year);
            $endorsementProcessor->setValue('hte_name', $hteName); // Ensures replacement for ${hte_name}
            $endorsementProcessor->setValue('rep_fullname', $repFullname); // Ensures replacement for ${rep_fullname}
            $endorsementProcessor->setValue('intern_list', $internList); // Shared list of all interns

            // Save to temp, create debug, then move to permanent
            $endorsementProcessor->saveAs($endorsementTempPath);
            if (!file_exists($endorsementTempPath)) {
                throw new Exception('Failed to create endorsement temp file for HTE ID: ' . $hte->id);
            }

            // Create debug copy for verification (check hte_name, rep_fullname, intern_list here)
            copy($endorsementTempPath, $endorsementDebugPath);
            Log::info('Shared endorsement debug file created for HTE ' . $hte->id . ': ' . $endorsementDebugPath . ' - Open in Word to verify ${hte_name}, ${rep_fullname}, and ${intern_list}!');

            rename($endorsementTempPath, $endorsementFullPath);
            Log::info('Shared endorsement saved to permanent location (per HTE): ' . $endorsementFullPath);

            if (!file_exists($endorsementFullPath)) {
                throw new Exception('Failed to move endorsement to permanent location for HTE ID: ' . $hte->id);
            }

            $endorsementSuccess = true;
            Log::info('Endorsement generation successful for HTE ID: ' . $hte->id);

        } catch (Exception $e) {
            Log::error("Failed to generate shared endorsement letter for HTE ID {$hte->id}: " . $e->getMessage());
            $endorsementRelativePath = null; // No path to save if failed
        }

        // Now process per-intern contracts, emails, status updates, and shared endorsement path
        $successCount = 0;
        $errorCount = 0;

        foreach ($endorsedInterns as $endorsement) {
            $intern = $endorsement->intern;
            if (!$intern) {
                Log::error('No intern found for endorsement ID: ' . $endorsement->id);
                $errorCount++;
                continue;
            }

            $studentEmail = $intern->user?->email;
            $studentName = $intern->user?->fname . ' ' . $intern->user?->lname ?? 'Unknown';
            if (!$studentEmail) {
                Log::error('No email found for intern ID: ' . $intern->id . ' (' . $studentName . ')');
                $errorCount++;
                continue;
            }

            Log::info('Processing intern contract/email for: ' . $studentName . ' (' . $studentEmail . ')');

            $contractTempPath = storage_path('app/public/temp/contract-' . $intern->id . '-' . Str::random(8) . '.docx');

            try {
                // Step 1: Generate Student Internship Contract (temp, for email only)
                $contractTemplatePath = public_path('document-templates/student-internship-contract-template.docx');
                if (!file_exists($contractTemplatePath)) {
                    throw new Exception('Contract template file not found at: ' . $contractTemplatePath);
                }

                $contractProcessor = new TemplateProcessor($contractTemplatePath);
                $contractProcessor->setValue('college_name', $collegeName);
                $contractProcessor->setValue('hte_name', $hteName);
                $contractProcessor->setValue('hte_address', $hteAddress);
                $contractProcessor->setValue('rep_fullname', $repFullname);

                $contractProcessor->saveAs($contractTempPath);
                if (!file_exists($contractTempPath)) {
                    throw new Exception('Failed to create contract file for intern ID: ' . $intern->id);
                }

                // Step 2: Send email (contract only)
                Mail::to($studentEmail)->send(new StudentDeploymentMail(
                    $studentName,
                    $hteName,
                    $contractTempPath
                ));
                Log::info('Email sent successfully to: ' . $studentEmail);

                // Step 3: Update per-intern statuses and new fields
                $intern->update(['status' => 'processing']);
                Log::info("Intern status updated to 'processing' for ID: " . $intern->id . " ({$studentName})");

                $endorsement->update([
                    'status' => 'processing', 
                    'deployed_at' => now(),
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate,
                    'no_of_hours' => $noOfHours,
                    'no_of_weeks' => $noOfWeeks
                ]);
                Log::info("Pivot status updated to 'deployed' and dates/hours set for endorsement ID: " . $endorsement->id . 
                        " (Start: {$startDate->format('Y-m-d')}, End: {$endDate}, Hours: {$noOfHours}, Weeks: {$noOfWeeks})");

                // Step 4: Save shared endorsement path to this interns_hte record (if generation succeeded)
                if ($endorsementSuccess && $endorsementRelativePath) {
                    $endorsement->update(['endorsement_letter_path' => $endorsementRelativePath]);
                    Log::info("Shared endorsement path saved to endorsement ID: " . $endorsement->id . " - Path: " . $endorsementRelativePath);
                }

                // Step 5: Clean up contract temp
                unlink($contractTempPath);

                $successCount++;
                Log::info('Success for ' . $studentName . ' (shared endorsement assigned)');

            } catch (Exception $e) {
                Log::error("Failed to process contract/email for intern ID {$intern->id} ({$studentName}): " . $e->getMessage());
                if (file_exists($contractTempPath)) {
                    unlink($contractTempPath);
                }
                $errorCount++;
            }
        }

        // Step Final: Response
        Log::info("Deployment summary for HTE {$hte->id}: Success={$successCount}, Errors={$errorCount}, Endorsement Success=" . ($endorsementSuccess ? 'Yes' : 'No'));
        if ($successCount > 0) {
            $message = "Deployment processed: {$successCount} intern(s) emailed and set to processing/deployed.";
            if ($errorCount > 0) {
                $message .= " {$errorCount} failed (check logs).";
            }
            if ($endorsementSuccess) {
                $message .= " Shared endorsement letter generated and assigned to all.";
            } else {
                $message .= " Endorsement letter failed (check logs and template).";
            }
            $message .= " Deployment dates set: Start {$startDate->format('Y-m-d')}, End {$endDate} ({$noOfWeeks} weeks, {$noOfHours} hours).";
            return redirect()->back()->with('success', $message);
        } else {
            return redirect()->back()->with('error', 'Deployment failed for all interns. Check logs for details.');
        }
    }

    public function officiallyDeploy($internHteId)
    {
        try {
            // Find the intern_hte record to get the HTE ID
            $internHte = InternsHte::findOrFail($internHteId);
            
            // Update all intern_hte records for this HTE that are in processing status
            $updatedCount = InternsHte::where('hte_id', $internHte->hte_id)
                ->where('status', 'processing')
                ->update([
                    'status' => 'deployed',
                    'deployed_at' => now()
                ]);
            
            // Update corresponding intern statuses using a join
            if ($updatedCount > 0) {
                Intern::whereIn('id', function($query) use ($internHte) {
                    $query->select('intern_id')
                        ->from('interns_hte')
                        ->where('hte_id', $internHte->hte_id)
                        ->where('status', 'deployed');
                })->update([
                    'status' => 'deployed'
                ]);
            }
            
            return redirect()->back()->with('success', "{$updatedCount} intern(s) successfully deployed! Status has been updated to 'Deployed'.");
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'Endorsement record not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deploying the interns: ' . $e->getMessage());
        }
    }

    public function deployments() {
        $coordinatorId = auth()->user()->coordinator->id;
        
        // Get all HTEs where this coordinator has made endorsements, grouped by HTE
        $deployments = \App\Models\InternsHte::with(['hte'])
            ->where('coordinator_id', $coordinatorId)
            ->get()
            ->groupBy('hte_id');
        
        return view('coordinator.deployments', compact('deployments'));
    }

    public function showDeployment($id)
    {
        $hte = Hte::with(['user', 'skills', 'skills.department'])
            ->findOrFail($id);

        // Get only the current coordinator's endorsements for this HTE
        $currentCoordinatorId = auth()->user()->coordinator->id;
        
        $endorsedInterns = \App\Models\InternsHte::with(['intern.user', 'intern.department'])
            ->where('hte_id', $id)
            ->where('coordinator_id', $currentCoordinatorId) // Only show current coordinator's endorsements
            ->get();

        $endorsedCount = $endorsedInterns->count();
        $availableSlots = $hte->slots - $hte->internsHte()->count(); // Total available slots for HTE
        $availableSlots = max(0, $availableSlots);

        // Check for deploy conditions - only for current coordinator's endorsements
        $hasEndorsedForDeploy = $endorsedInterns->where('status', 'endorsed')->isNotEmpty();
        $hasDeployed = $endorsedInterns->where('status', 'deployed')->isNotEmpty();
        $isProcessing = $endorsedInterns->where('status', 'processing')->isNotEmpty();
        $endorsementPath = $hasDeployed ? $endorsedInterns->where('status', 'deployed')->first()->endorsement_letter_path : null;

        $canManage = auth()->user()->coordinator->can_add_hte == 1;

        return view('coordinator.deployment_show', compact(
            'hte', 
            'canManage', 
            'endorsedInterns', 
            'availableSlots', 
            'hasEndorsedForDeploy', 
            'hasDeployed', 
            'isProcessing',
            'endorsementPath'
        ));
    }

    public function documents()
    {
        // Get the coordinator with their documents
        $coordinator = Coordinator::with('documents')
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $documents = $coordinator->documents;
        
        return view('coordinator.documents', compact('coordinator', 'documents'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(CoordinatorDocument::typeLabels())),
            'document' => 'required|file|mimes:pdf|max:5120' // 5MB max
        ]);

        $coordinator = Coordinator::where('user_id', Auth::id())->firstOrFail();
        
        // Check if document type already exists
        $existingDocument = $coordinator->documents()->where('type', $request->type)->first();
        if ($existingDocument) {
            return response()->json(['error' => 'Document type already exists'], 422);
        }

        // Store file
        $filePath = $request->file('document')->store('coordinator_documents', 'public');

        // Create document record
        $document = $coordinator->documents()->create([
            'type' => $request->type,
            'file_path' => $filePath
        ]);

        // Update coordinator status
        $coordinator->updateStatus();

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'file_path' => Storage::url($document->file_path)
            ],
            'status' => $coordinator->fresh()->status,
            'document_count' => $coordinator->documents()->count()
        ]);
    }

    public function deleteDocument($id)
    {
        $coordinator = Coordinator::where('user_id', Auth::id())->firstOrFail();
        $document = $coordinator->documents()->findOrFail($id);

        // Delete file from storage
        Storage::disk('public')->delete($document->file_path);

        // Delete document record
        $document->delete();

        // Update coordinator status
        $coordinator->updateStatus();

        return response()->json([
            'success' => true,
            'status' => $coordinator->fresh()->status,
            'document_count' => $coordinator->documents()->count()
        ]);
    }
}