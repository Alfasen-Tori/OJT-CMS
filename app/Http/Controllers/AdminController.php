<?php

namespace App\Http\Controllers;

use App\Models\Hte;
use App\Models\User;
use App\Models\Skill;
use App\Models\Intern;

use App\Models\Department;
use App\Models\InternsHte;
use App\Models\Coordinator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail; // For sending emails
use Illuminate\Support\Str;       // For generating tokens
use App\Mail\CoordinatorSetupMail; // Your custom mail class
use Illuminate\Support\Facades\DB; // For database operations

class AdminController extends Controller
{
    public function dashboard()
    {
        $counts = [
            'internsCount' => Intern::count(),
            'coordinatorsCount' => Coordinator::count(),
            'htesCount' => Hte::count(),
            'departmentsCount' => Department::count(),
            'skillsCount' => Skill::count(),
            'activeDeploymentsCount' => InternsHte::where('status', 'deployed')->count(),
        ];

        return view('admin.dashboard', $counts);
    }

    public function profile()
    {
        $admin = auth()->user()->admin;
        return view('admin.profile', compact('admin'));
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

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $userId = auth()->id();
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                throw new \Exception('User not found');
            }

            if ($request->hasFile('profile_picture')) {
                // Delete old picture if exists and is not default
                if ($user->pic && $user->pic !== 'profile_pics/profile.jpg') {
                    Storage::disk('public')->delete($user->pic);
                }

                // Store new picture
                $path = $request->file('profile_picture')->store('profile_pics', 'public');
                
                // Update database directly
                $updated = DB::table('users')
                            ->where('id', $userId)
                            ->update(['pic' => $path]);
                
                if (!$updated) {
                    throw new \Exception('Failed to update profile picture in database');
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Profile picture updated successfully',
                    'image_url' => asset('storage/'.$path)
                ]);
            }

            throw new \Exception('No file uploaded');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
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

    public function editCoordinator($id)
    {
        $coordinator = Coordinator::with(['user', 'department'])->findOrFail($id);
        $departments = Department::all();
        
        return view('admin.edit-coordinator', compact('coordinator', 'departments'));
    }

    public function updateCoordinator(Request $request, $id)
    {
        $coordinator = Coordinator::with('user')->findOrFail($id);

        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($coordinator->user_id)
            ],
            'contact' => 'required|string|max:20',
            'faculty_id' => [
                'required',
                'string',
                'max:20',
                Rule::unique('coordinators')->ignore($coordinator->id)
            ],
            'dept_id' => 'required|exists:departments,dept_id',
            'can_add_hte' => 'required|boolean'
        ]);

        try {
            // Update user data
            $coordinator->user->update([
                'fname' => $request->fname,
                'lname' => $request->lname,
                'email' => $request->email,
                'contact' => $request->contact
            ]);

            // Update coordinator data
            $coordinator->update([
                'faculty_id' => $request->faculty_id,
                'dept_id' => $request->dept_id,
                'can_add_hte' => $request->can_add_hte
            ]);

            return redirect()->route('admin.coordinators')
                ->with('success', 'Coordinator updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating coordinator: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroyCoordinator($id)
    {
        try {
            $coordinator = Coordinator::with('user')->findOrFail($id);
            
            // Delete coordinator (this should cascade to coordinator_documents if set up properly)
            $coordinator->delete();
            
            // Also delete the associated user
            $coordinator->user->delete();
            
            return redirect()->route('admin.coordinators')
                ->with('success', 'Coordinator deleted successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.coordinators')
                ->with('error', 'Error deleting coordinator: ' . $e->getMessage());
        }
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
