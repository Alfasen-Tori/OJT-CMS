<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InternDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InternController extends Controller
{
    public function dashboard()
    {
        $intern = auth()->user()->intern;

        if ($intern->first_login) {
            return redirect()->route('intern.skills.select');
        }

        $documents = $intern->documents; // actual documents collection
        $internHte = $intern->hteAssignment;
        $hteDetails = $internHte ? $internHte->hte : null;

        return view('student.dashboard', [
            'status' => $intern->status,
            'semester' => $intern->semester,
            'academic_year' => $intern->academic_year,
            'documents' => $documents,
            'hteDetails' => $hteDetails
        ]);
    }

    public function updateStatus(Request $request) {
        $request->validate(['status' => 'required|in:ready for deployment,pending requirements']);
        
        auth()->user()->intern->update(['status' => $request->status]);
        
        return response()->json(['message' => 'Status updated']);
    }

    public function checkDocuments()
    {
        $documentCount = auth()->user()->intern->documents()->count();
        return response()->json(['documentCount' => $documentCount]);
    }

    public function checkDocumentsComplete()
    {
        $count = auth()->user()->intern->documents()->count();
        return response()->json([
            'complete' => $count === 9,
            'count' => $count
        ]);
    }

    public function profile()
    {
        $skills = Skill::where('dept_id', auth()->user()->intern->dept_id)
                    ->orderBy('name')
                    ->get();

        return view('student.profile', compact('skills'));
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
    
    public function updateSkills(Request $request)
    {
        try {
            $request->validate([
                'skills' => 'sometimes|array',
                'skills.*' => 'exists:skills,skill_id',
            ]);

            $intern = auth()->user()->intern;
            
            if (!$intern) {
                throw new \Exception('Intern profile not found');
            }

            $intern->skills()->sync($request->skills ?? []);

            return redirect()->route('intern.profile')
                ->with('success', 'Skills updated successfully');
            
        } catch (\Exception $e) {
            return redirect()->route('intern.profile')
                ->with('error', 'Error updating skills: ' . $e->getMessage());
        }
    }

    public function selectSkills()
    {
        // Get skills matching the intern's department
        $skills = Skill::where('dept_id', auth()->user()->intern->dept_id)
                    ->orderBy('name')
                    ->get();

        return view('student.skills', compact('skills'));
    }

    public function saveSkills(Request $request)
    {
        $request->validate([
            'skills' => 'required|array|min:3',
            'skills.*' => 'exists:skills,skill_id'
        ]);

        DB::transaction(function () use ($request) {
            // Attach selected skills
            auth()->user()->intern->skills()->sync($request->skills);

            // Mark first login as complete
            auth()->user()->intern->update(['first_login' => false]);
        });

        return redirect()->route('intern.dashboard')
                    ->with('success', 'Skills selected successfully!');
    }

    public function documents() {
        $documents = auth()->user()->intern->documents;
        return view('student.documents', compact('documents'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'type' => 'required|in:' . implode(',', array_keys(InternDocument::typeLabels())),
            'document' => 'required|file|mimes:pdf|max:5120'
        ]);

        $intern = auth()->user()->intern;
        
        // Delete existing if any
        $intern->documents()->where('type', $request->type)->delete();

        // Store new document
        $path = $request->file('document')->store('intern-documents', 'public');
        
        $document = $intern->documents()->create([
            'type' => $request->type,
            'file_path' => $path,
            'original_name' => $request->file('document')->getClientOriginalName()
        ]);

        // Check if this was the 8th document
        $isComplete = $intern->documents()->count() === 9;
        if ($isComplete) {
            $intern->update(['status' => 'ready for deployment']);
        }

        return response()->json([
            'message' => 'Document uploaded successfully',
            'file_url' => Storage::url($path),
            'document_id' => $document->id,
            'new_status' => $isComplete ? 'ready for deployment' : null,
            'created_at' => $document->created_at->format('Y-m-d')
        ]);
    }

    public function deleteDocument(Request $request)
    {
        $document = InternDocument::findOrFail($request->id);
        $intern = auth()->user()->intern;
        
        // Verify ownership
        if ($document->intern_id !== $intern->id) {
            abort(403);
        }

        // Check if we're deleting from a complete state
        $wasComplete = $intern->documents()->count() === 8;
        
        Storage::delete($document->file_path);
        $document->delete();

        // Always set to incomplete when deleting
        $intern->update(['status' => 'pending requirements']);

        return response()->json([
            'message' => 'Document removed',
            'new_status' => $wasComplete ? 'pending requirements' : null
        ]);
    }

    public function reports(){
        return view('student.reports');
    }

    public function schedule(){
        return view('student.schedule');
    }
}
