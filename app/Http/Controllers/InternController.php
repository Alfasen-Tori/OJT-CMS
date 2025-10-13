<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Intern;
use App\Models\Attendance;
use App\Models\InternsHte;
use App\Models\WeeklyReport;
use Illuminate\Http\Request;
use App\Models\InternDocument;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

        $internHte = $intern->hteAssignment;
        $hteDetails = $internHte ? $internHte->hte : null;

        // Get today's attendance (if any)
        $today = Carbon::today();
        $attendance = null;
        if ($internHte) {
            $attendance = Attendance::where('intern_hte_id', $internHte->id)
                ->whereDate('date', $today)
                ->first();
        }

        // Calculate progress if deployed
        $progress = null;
        if ($intern->status === 'deployed' && $internHte) {
            $totalRendered = Attendance::where('intern_hte_id', $internHte->id)
                ->sum('hours_rendered');  // Total hours from all attendances

            $requiredHours = $internHte->no_of_hours ?? 0;
            $percentage = $requiredHours > 0 ? min(100, round(($totalRendered / $requiredHours) * 100)) : 0;

            $progress = [
                'total_rendered' => round($totalRendered, 1),
                'required_hours' => $requiredHours,
                'percentage' => $percentage
            ];
        }

        return view('student.dashboard', [  // Adjust view name if needed (e.g., 'intern.dashboard')
            'status' => $intern->status,
            'semester' => $intern->semester,
            'academic_year' => $intern->academic_year,
            'documents' => $intern->documents,
            'hteDetails' => $hteDetails,
            'internHte' => $internHte,
            'attendance' => $attendance,
            'progress' => $progress  // New: Pass initial progress data
        ]);
    }

    public function getProgress()
    {
        $intern = auth()->user()->intern;
        if ($intern->status !== 'deployed') {
            return response()->json(['error' => 'Not deployed'], 400);
        }

        $internHte = $intern->hteAssignment;
        if (!$internHte) {
            return response()->json(['error' => 'No assigned HTE'], 400);
        }

        $totalRendered = Attendance::where('intern_hte_id', $internHte->id)
            ->sum('hours_rendered');

        $requiredHours = $internHte->no_of_hours ?? 0;
        $percentage = $requiredHours > 0 ? min(100, round(($totalRendered / $requiredHours) * 100)) : 0;

        return response()->json([
            'total_rendered' => round($totalRendered, 1),
            'required_hours' => $requiredHours,
            'percentage' => $percentage
        ]);
    }

    public function punchIn(Request $request)
    {
        $request->validate(['student_id' => 'required|string']);

        $intern = auth()->user()->intern;

        if ($intern->student_id !== $request->student_id) {
            return response()->json(['error' => 'Invalid Student ID. Please try again.'], 401);
        }

        $internHte = $intern->hteAssignment;
        if (!$internHte) {
            return response()->json(['error' => 'No assigned HTE found.'], 400);
        }

        $today = Carbon::today();

        // Prevent multiple punch-ins for same day
        $existing = Attendance::where('intern_hte_id', $internHte->id)
            ->whereDate('date', $today)
            ->first();

        if ($existing && $existing->time_in) {
            return response()->json(['error' => 'You already punched in today.'], 400);
        }

        $attendance = Attendance::create([
            'intern_hte_id' => $internHte->id,
            'date' => $today,
            'time_in' => Carbon::now(),  // UTC/app timezone
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Punch in recorded successfully!',
            'time_in' => $attendance->time_in->format('h:i A'),  // Formatted for display
            'time_in_raw' => $attendance->time_in->toDateTimeString(),  // UTC string for JS (e.g., "2024-09-24 01:00:00")
        ]);
    }

    public function punchOut(Request $request)
    {
        $request->validate(['student_id' => 'required|string']);

        $intern = auth()->user()->intern;

        if ($intern->student_id !== $request->student_id) {
            return response()->json(['error' => 'Invalid Student ID. Please try again.'], 401);
        }

        $internHte = $intern->hteAssignment;
        $today = Carbon::today();

        $attendance = Attendance::where('intern_hte_id', $internHte->id)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->time_in) {
            return response()->json(['error' => 'You have not punched in today.'], 400);
        }

        if ($attendance->time_out) {
            return response()->json(['error' => 'You already punched out today.'], 400);
        }

        $attendance->time_out = Carbon::now();  // UTC/app timezone
        $attendance->hours_rendered = round($attendance->time_out->floatDiffInHours($attendance->time_in), 2);
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Punch out recorded successfully!',
            'time_in' => $attendance->time_in->format('h:i A'),
            'time_out' => $attendance->time_out->format('h:i A'),
            'hours' => $attendance->hours_rendered,
            'time_in_raw' => $attendance->time_in->toDateTimeString(),  // UTC for JS
            'time_out_raw' => $attendance->time_out->toDateTimeString(),  // UTC for JS
        ]);
    }

    public function getAttendanceStatus()
    {
        $internHte = auth()->user()->intern->hteAssignment;

        if (!$internHte) {
            return response()->json(['error' => 'No assigned HTE found.'], 400);
        }

        $today = Carbon::today();
        $attendance = Attendance::where('intern_hte_id', $internHte->id)
            ->whereDate('date', $today)
            ->first();

        return response()->json([
            'attendance' => $attendance ? [
                'time_in' => optional($attendance->time_in)->format('h:i A'),
                'time_out' => optional($attendance->time_out)->format('h:i A'),
                'hours' => $attendance->hours_rendered ?? 0,
                'time_in_raw' => optional($attendance->time_in)->toDateTimeString(),  // UTC string
                'time_out_raw' => optional($attendance->time_out)->toDateTimeString(),  // UTC string
            ] : null
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

    public function reports()
    {
        $intern = Intern::where('user_id', Auth::id())->first();
        
        if (!$intern) {
            return view('student.reports')->with('error', 'Intern profile not found.');
        }

        // Get current internship
        $internship = InternsHte::where('intern_id', $intern->id)
            ->where('status', 'deployed')
            ->first();

        if (!$internship) {
            return view('student.reports')->with('error', 'No active internship found.');
        }

        // Generate weekly report entries
        $this->generateWeeklyReports($intern->id, $internship);

        // Get all weekly reports for this intern
        $weeklyReports = WeeklyReport::where('intern_id', $intern->id)
            ->orderBy('week_no', 'asc')
            ->get();

        // Calculate week information
        $weekInfo = $this->calculateWeekInformation($internship, $weeklyReports);

        return view('student.reports', compact('weeklyReports', 'weekInfo', 'internship'));
    }

    private function generateWeeklyReports($internId, $internship)
    {
        $startDate = Carbon::parse($internship->start_date);
        $currentDate = Carbon::now();
        
        // Calculate total weeks from start date to current date
        $totalWeeks = $startDate->diffInWeeks($currentDate) + 1; // +1 to include current week
        
        for ($weekNo = 1; $weekNo <= $totalWeeks; $weekNo++) {
            // Calculate week start and end dates
            $weekStart = $startDate->copy()->addWeeks($weekNo - 1);
            $weekEnd = $weekStart->copy()->addDays(4); // Friday (5-day work week)
            
            // Check if week has passed (after Saturday)
            $weekPassed = $currentDate->gt($weekEnd->copy()->addDays(1)); // Saturday
            
            // Check if report for this week already exists
            $existingReport = WeeklyReport::where('intern_id', $internId)
                ->where('week_no', $weekNo)
                ->first();
                
            if (!$existingReport) {
                WeeklyReport::create([
                    'intern_id' => $internId,
                    'week_no' => $weekNo,
                    'report_path' => null,
                    'submitted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function calculateWeekInformation($internship, $weeklyReports)
    {
        $startDate = Carbon::parse($internship->start_date);
        $currentDate = Carbon::now();
        
        $weekInfo = [];
        
        foreach ($weeklyReports as $report) {
            $weekStart = $startDate->copy()->addWeeks($report->week_no - 1);
            $weekEnd = $weekStart->copy()->addDays(4); // Friday
            
            // Determine status
            $isCurrentWeek = $currentDate->between($weekStart, $weekEnd);
            $weekPassed = $currentDate->gt($weekEnd->copy()->addDays(1)); // After Saturday
            
            $status = 'upcoming';
            if ($isCurrentWeek) {
                $status = 'current';
            } elseif ($weekPassed) {
                $status = $report->report_path ? 'submitted' : 'pending';
            }
            
            $weekInfo[$report->week_no] = [
                'start_date' => $weekStart->format('M d'),
                'end_date' => $weekEnd->format('M d'),
                'status' => $status,
                'is_submitted' => !is_null($report->report_path),
                'can_submit' => $weekPassed && is_null($report->report_path)
            ];
        }
        
        return $weekInfo;
    }

    public function schedule(){
        return view('student.schedule');
    }
}
