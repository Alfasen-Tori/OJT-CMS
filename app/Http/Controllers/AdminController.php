<?php

namespace App\Http\Controllers;

use App\Models\Coordinator;
use App\Models\Department;
use App\Models\User;
use App\Models\Skill;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; // For sending emails
use Illuminate\Support\Str;       // For generating tokens
use App\Mail\CoordinatorSetupMail; // Your custom mail class
use Illuminate\Support\Facades\DB; // For database operations

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function showCoordinators()
    {
        $coordinators = Coordinator::with(['user', 'department'])->get();
        return view('admin.coordinators', compact('coordinators'));
    }

    public function newCoordinator()
    {
        $departments = Department::all();
        return view('admin.new-coordinator', compact('departments'));
    }

    public function registerCoordinator(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'faculty_id' => 'required|string|unique:coordinators|regex:/^[A-Za-z]\d{2}\d{2}\d{2}[A-Za-z]{2}$/',            'dept_id' => 'required|exists:departments,dept_id',
            'can_add_hte' => 'required|boolean',
        ]);

        // Generate a strong temporary password
        $tempPassword = Str::random(16);

        // Create user with temporary password
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($tempPassword),
            'fname' => $validated['fname'],
            'lname' => $validated['lname'],
            'contact' => $validated['contact'],
            'pic' => 'profile-pictures/profile.jpg',
            'temp_password' => true
        ]);

        // Create coordinator record
        $coordinator = Coordinator::create([
            'faculty_id' => $validated['faculty_id'],
            'user_id' => $user->id,
            'dept_id' => $validated['dept_id'],
            'can_add_hte' => $validated['can_add_hte']
        ]);

        // Generate password setup token (expires in 24 hours)
        $token = Str::random(60);
        DB::table('password_setup_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send email with setup link
        $setupLink = route('password.setup', [
            'token' => $token,
            'role' => 'coordinator'
        ]);
        $coordinatorName = $validated['fname'] . ' ' . $validated['lname'];
        
        Mail::to($user->email)->send(new CoordinatorSetupMail(
            $setupLink, 
            $coordinatorName,
            $tempPassword // Include temp password in email (optional)
        ));
        
        return redirect()->route('admin.coordinators')
            ->with('success', 'Coordinator added successfully. Activation email sent.');
    }

    /* DEPARTMENTS */
    public function departments()
    {
        $departments = Department::withCount(['interns as students_count', 'coordinators as coordinators_count'])
            ->get();

        return view('admin.departments', compact('departments'));
    }

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'dept_name' => 'required|string|max:255|unique:departments',
            'short_name' => 'required|string|max:50|unique:departments'
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments')->with('success', 'Department added successfully');
    }

    public function deleteDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return redirect()->route('admin.departments')->with('success', 'Department deleted successfully');
    }


    

    /* SKILLS */
public function skills()
{
    $skills = Skill::withCount('students')->with('department')->get();
    $departments = Department::all();
    return view('admin.skills', compact('skills', 'departments'));
}

public function storeSkill(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'dept_id' => 'required|exists:departments,dept_id'
    ]);

    Skill::create($validated);

    return redirect()->route('admin.skills')->with('success', 'Skill added successfully');
}

public function updateSkill(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'dept_id' => 'required|exists:departments,dept_id'
    ]);

    $skill = Skill::findOrFail($id);
    $skill->update($validated);

    return redirect()->route('admin.skills')->with('success', 'Skill updated successfully');
}

public function deleteSkill($id)
{
    $skill = Skill::findOrFail($id);
    $skill->delete();

    return redirect()->route('admin.skills')->with('success', 'Skill deleted successfully');
}

    public function coordinatorDocuments($id)
    {
        $coordinator = Coordinator::with(['user', 'department', 'documents'])->findOrFail($id);
        $documents = $coordinator->documents;
        
        return view('admin.documents', compact('coordinator', 'documents'));
    }

    public function updateCoordinatorStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:eligible for claim,claimed'
        ]);

        $coordinator = Coordinator::findOrFail($id);
        
        // Validate status transitions
        if ($request->status === 'eligible for claim' && $coordinator->status !== 'for validation') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition. Coordinator must be in "for validation" status.'
            ], 422);
        }

        if ($request->status === 'claimed' && $coordinator->status !== 'eligible for claim') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition. Coordinator must be in "eligible for claim" status.'
            ], 422);
        }

        $coordinator->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'new_status' => $coordinator->status,
            'display_status' => ucfirst($coordinator->status)
        ]);
    }

    
}
