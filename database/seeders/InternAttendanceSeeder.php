<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Intern;
use Carbon\Carbon;

class InternAttendanceSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Get deployed and completed students
        $deployedStudents = Intern::where('status', 'deployed')->get();
        $completedStudents = Intern::where('status', 'completed')->get();

        // Process deployed students
        foreach ($deployedStudents as $student) {
            $endorsement = DB::table('interns_hte')
                ->where('intern_id', $student->id)
                ->where('status', 'deployed')
                ->first();

            if ($endorsement && $endorsement->start_date) {
                $this->createAttendanceForIntern($endorsement, false);
            }
        }

        // Process completed students
        foreach ($completedStudents as $student) {
            $endorsement = DB::table('interns_hte')
                ->where('intern_id', $student->id)
                ->where('status', 'completed')
                ->first();

            if ($endorsement && $endorsement->start_date && $endorsement->end_date) {
                $this->createAttendanceForIntern($endorsement, true);
            }
        }

        $this->command->info('Attendance records created for all deployed and completed students!');
    }

    private function createAttendanceForIntern($endorsement, $isCompleted)
    {
        $now = now();
        $startDate = Carbon::parse($endorsement->start_date);
        
        if ($isCompleted) {
            $endDate = Carbon::parse($endorsement->end_date);
            $currentDate = $startDate->copy();
            
            // Create attendance for the entire internship period (excluding weekends)
            while ($currentDate->lessThanOrEqualTo($endDate)) {
                if (!$currentDate->isWeekend()) {
                    $this->createAttendanceRecord($endorsement->id, $currentDate);
                }
                $currentDate->addDay();
            }
        } else {
            // For current deployments, create attendance for past 2 weeks
            $today = Carbon::now();
            for ($i = 0; $i < 10; $i++) {
                $date = $startDate->copy()->addWeekdays($i);
                
                if ($date->lessThanOrEqualTo($today) && !$date->isWeekend()) {
                    $this->createAttendanceRecord($endorsement->id, $date);
                }
            }
        }
    }

    private function createAttendanceRecord($internHteId, $date)
    {
        $timeIn = $date->copy()->setHour(8)->setMinute(rand(0, 30));
        $timeOut = $date->copy()->setHour(17)->setMinute(rand(0, 30));
        $hoursRendered = 8.5 + (rand(-20, 20) / 100); // 8.3 to 8.7 hours
        
        DB::table('attendances')->updateOrInsert(
            [
                'intern_hte_id' => $internHteId,
                'date' => $date->format('Y-m-d'),
            ],
            [
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'hours_rendered' => $hoursRendered,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}