<?php

namespace App\Http\Controllers;

use App\Models\Department;
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
        ->where('status', 'deployed')
        ->orderBy('deployed_at', 'desc')
        ->get();

    return view('hte.interns', compact('deployedInterns'));
}

public function submitEvaluation(Request $request, $deploymentId)
{
    try {
        $request->validate([
            'grade' => 'required|numeric|min:0|max:100',
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

        // Create evaluation
        InternEvaluation::create([
            'intern_hte_id' => $deploymentId,
            'grade' => $request->grade,
            'comments' => $request->comments,
            'evaluation_date' => now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evaluation submitted successfully!',
            'gpa' => number_format((100 - $request->grade) / 20, 1)
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