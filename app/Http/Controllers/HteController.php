<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\Department;
use App\Models\HTE;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\InternsHte;
use Illuminate\Http\Request;
use App\Models\InternEvaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HteController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Check if HTE exists
        if (!$user->hte) {
            abort(403, 'HTE profile not found');
        }
        
        if ($user->hte->first_login === 1) {
            return redirect()->route('hte.first-login.details');
        }
        
        if ($user->hte->first_login === 2) {
            return redirect()->route('hte.first-login.skills');
        }

        $moaStatus = $user->hte->moa_path ? 'Submitted' : 'Missing';

        
        return view('hte.dashboard', [
            'moaStatus' => $moaStatus
        ]);
    }

    public function profile()
    {
        $hte = auth()->user()->hte;
        $skills = Skill::all(); // Get all skills for HTE to choose from
        $selectedSkills = $hte->skills->pluck('skill_id')->toArray();

        return view('hte.profile', compact('hte', 'skills', 'selectedSkills'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'organization_name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'description' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            $user = User::findOrFail(auth()->id());
            $hte = HTE::where('user_id', auth()->id())->firstOrFail();
            
            // Update user info
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->contact = $request->contact;
            
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            
            if (!$user->save()) {
                throw new \Exception('Failed to save user');
            }

            // Update HTE info
            $hte->organization_name = $request->organization_name;
            $hte->address = $request->address;
            $hte->description = $request->description;
            
            if (!$hte->save()) {
                throw new \Exception('Failed to save HTE information');
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

    public function updateSkills(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:5',
            'skills.*' => 'exists:skills,skill_id',
        ]);

        try {
            $hte = HTE::where('user_id', auth()->id())->firstOrFail();
            
            // Sync skills
            $hte->skills()->sync($request->skills);

            return response()->json([
                'success' => true,
                'message' => 'Skills updated successfully',
                'skills' => $hte->skills->pluck('name')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating skills: ' . $e->getMessage()
            ], 400);
        }
    }

public function interns()
{
    // Get the authenticated HTE's ID
    $hteId = auth()->user()->hte->id;
    
    // Get all deployed interns for this HTE with evaluation relationship
    $deployedInterns = \App\Models\InternsHte::with([
            'intern.user', 
            'intern.department',
            'coordinator.user',
            'evaluation' // Load the evaluation relationship
        ])
        ->where('hte_id', $hteId)
        ->whereIn('status', ['deployed', 'completed'])
        ->orderBy('deployed_at', 'desc')
        ->get();

    return view('hte.interns', compact('deployedInterns'));
}

public function showIntern($id)
{
    // Get the authenticated HTE's ID
    $hteId = auth()->user()->hte->id;
    
    $intern = Intern::with([
            'user', 
            'department', 
            'skills', 
            'coordinator.user'
        ])
        ->findOrFail($id);
    
    // Get current deployment for this HTE
    $currentDeployment = \App\Models\InternsHte::with('evaluation')
        ->where('intern_id', $id)
        ->where('hte_id', $hteId)
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

    return view('hte.intern_show', compact(
        'intern', 
        'currentDeployment', 
        'progress', 
        'evaluation'
    ));
}

public function submitEvaluation(Request $request, $deploymentId)
{
    try {
        $request->validate([
            'quality_of_work' => 'required|numeric|min:0|max:100',
            'dependability' => 'required|numeric|min:0|max:100',
            'timeliness' => 'required|numeric|min:0|max:100',
            'attendance' => 'required|numeric|min:0|max:100',
            'cooperation' => 'required|numeric|min:0|max:100',
            'judgment' => 'required|numeric|min:0|max:100',
            'personality' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string|max:1000'
        ]);

        $deployment = InternsHte::findOrFail($deploymentId);
        
        // Check if the deployment belongs to the authenticated HTE
        if ($deployment->hte_id !== auth()->user()->hte->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Check if intern is completed
        if ($deployment->intern->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Intern must have completed status to be evaluated.'
            ], 422);
        }

        // Check if already evaluated
        if ($deployment->evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'This intern has already been evaluated.'
            ], 422);
        }

        // Calculate total grade
        $totalGrade = (
            ($request->quality_of_work * 0.20) +
            ($request->dependability * 0.15) +
            ($request->timeliness * 0.20) +
            ($request->attendance * 0.15) +
            ($request->cooperation * 0.10) +
            ($request->judgment * 0.10) +
            ($request->personality * 0.05)
        );

        // Create evaluation
        $evaluation = InternEvaluation::create([
            'intern_hte_id' => $deploymentId,
            'quality_of_work' => $request->quality_of_work,
            'dependability' => $request->dependability,
            'timeliness' => $request->timeliness,
            'attendance' => $request->attendance,
            'cooperation' => $request->cooperation,
            'judgment' => $request->judgment,
            'personality' => $request->personality,
            'total_grade' => $totalGrade,
            'comments' => $request->comments,
            'evaluation_date' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!',
            'total_grade' => number_format($totalGrade, 2),
            'gpa' => number_format($evaluation->calculateGPA(), 2)
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation error.',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while submitting evaluation.'
        ], 500);
    }
}

    public function showDetailsForm()
    {
        $hte = Auth::user()->hte;
        if (!$hte) {
            abort(403, 'HTE profile not found');
        }
        
        return view('hte.first-login-details', compact('hte'));
    }

    public function confirmDetails(Request $request)
    {
        $user = Auth::user();
        $hte = $user->hte;

        if (!$hte) {
            abort(403, 'HTE profile not found');
        }

        $request->validate([
            'contact_first_name' => 'required|string|max:255',
            'contact_last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:private,government,ngo,educational,other',
            'slots' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        // Update user details using DB
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'fname' => $request->contact_first_name,
                'lname' => $request->contact_last_name,
                'contact' => $request->contact_number,
            ]);

        // Update HTE details using DB
        DB::table('htes')
            ->where('id', $hte->id)
            ->update([
                'address' => $request->address,
                'organization_name' => $request->organization_name,
                'type' => $request->organization_type,
                'slots' => $request->slots,
                'description' => $request->description,
                'first_login' => 2,
            ]);

        return redirect()->route('hte.first-login.skills');
    }

    public function showSkillsForm()
    {
        // Get all skills grouped by department
        $departments = Department::with('skills')->get();
        
        // Fix the ambiguous column issue by specifying table
        $selectedSkills = DB::table('hte_skill')
                        ->where('hte_id', auth()->user()->hte->id)
                        ->pluck('skill_id')
                        ->toArray();

        return view('hte.first-login-skills', compact('departments', 'selectedSkills'));
    }

    public function saveSkills(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:5',
            'skills.*' => 'exists:skills,skill_id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $hteId = auth()->user()->hte->id;
                
                DB::table('hte_skill')->where('hte_id', $hteId)->delete();
                
                $skillsData = array_map(function($skillId) use ($hteId) {
                    return [
                        'hte_id' => $hteId,
                        'skill_id' => $skillId,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }, $request->skills);
                
                DB::table('hte_skill')->insert($skillsData);
                
                DB::table('htes')
                    ->where('id', $hteId)
                    ->update(['first_login' => 0]);
            });

            return response()->json([
                'redirect' => route('hte.dashboard'),
                'message' => 'Skills saved successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving skills: ' . $e->getMessage()
            ], 500);
        }
    }

public function moa()
{
    // Get the HTE associated with the currently authenticated user
    $hte = auth()->user()->hte;
    
    if (!$hte) {
        abort(404, 'HTE record not found');
    }

    return view('hte.moa', compact('hte'));
}

public function uploadMOA(Request $request)
{
    $request->validate([
        'moa_file' => 'required|file|mimes:pdf|max:5120' // 5MB max
    ]);

    $hte = auth()->user()->hte;
    
    // Delete existing MOA if any
    if ($hte->moa_path) {
        Storage::delete($hte->moa_path);
    }

    // Store new MOA
    $path = $request->file('moa_file')->store('moa-documents', 'public');
    
    $hte->update([
        'moa_path' => $path,
        'status' => 'active'
    ]);

    return response()->json([
        'message' => 'MOA uploaded! Please stand by for verification.',
        'file_url' => Storage::url($path),
        'status' => 'success'
    ]);
}

public function deleteMOA()
{
    $hte = auth()->user()->hte;
    
    if (!$hte->moa_path) {
        return response()->json([
            'message' => 'No MOA found to remove',
            'status' => 'error'
        ], 404);
    }

    Storage::delete($hte->moa_path);
    $hte->update(['moa_path' => null]);

    return response()->json([
        'message' => 'MOA removed. Please upload a new one.',
        'status' => 'success'
    ]);
}

}