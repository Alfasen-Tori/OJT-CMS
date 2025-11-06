<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Coordinator;
use App\Models\Intern;
use Carbon\Carbon;

class CoordinatorStudentSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $password = bcrypt('password123');

        // Get IT department ID
        $itDept = DB::table('departments')->where('short_name', 'BSIT')->first();
        if (!$itDept) {
            throw new \Exception('IT department not found. Please run SchoolSeeder first.');
        }

        // ===== CREATE MAIN COORDINATOR (Mark Villanueva) =====
        $markUser = User::create([
            'email' => 'mark.villanueva@evsu.edu.ph',
            'password' => $password,
            'fname' => 'Mark',
            'lname' => 'Villanueva',
            'sex' => 'male',
            'contact' => '09123456789',
            'pic' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $markCoordinator = Coordinator::create([
            'faculty_id' => 'M091518V', // Mark Villanueva, hired: September 15, 2018
            'user_id' => $markUser->id,
            'dept_id' => $itDept->dept_id,
            'can_add_hte' => '1',
            'status' => 'pending documents',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // ===== CREATE STUDENTS WITH DIFFERENT STATUSES =====
        $students = [
            // Pending Requirements
            [
                'student_id' => '2020-09701',
                'fname' => 'Maria',
                'lname' => 'Santos',
                'email' => 'maria.santos@evsu.edu.ph',
                'status' => 'pending requirements',
                'section' => 'a',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09702',
                'fname' => 'Juan',
                'lname' => 'dela Cruz',
                'email' => 'juan.delacruz@evsu.edu.ph',
                'status' => 'pending requirements',
                'section' => 'b',
                'year_level' => 4,
            ],

            // Ready for Deployment
            [
                'student_id' => '2020-09703',
                'fname' => 'Ana',
                'lname' => 'Reyes',
                'email' => 'ana.reyes@evsu.edu.ph',
                'status' => 'ready for deployment',
                'section' => 'a',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09704',
                'fname' => 'Carlos',
                'lname' => 'Gonzales',
                'email' => 'carlos.gonzales@evsu.edu.ph',
                'status' => 'ready for deployment',
                'section' => 'c',
                'year_level' => 4,
            ],

            // Endorsed
            [
                'student_id' => '2020-09705',
                'fname' => 'Sofia',
                'lname' => 'Lim',
                'email' => 'sofia.lim@evsu.edu.ph',
                'status' => 'endorsed',
                'section' => 'b',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09706',
                'fname' => 'Miguel',
                'lname' => 'Torres',
                'email' => 'miguel.torres@evsu.edu.ph',
                'status' => 'endorsed',
                'section' => 'a',
                'year_level' => 4,
            ],

            // Processing
            [
                'student_id' => '2020-09707',
                'fname' => 'Liza',
                'lname' => 'Moreno',
                'email' => 'liza.moreno@evsu.edu.ph',
                'status' => 'processing',
                'section' => 'c',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09708',
                'fname' => 'James',
                'lname' => 'Tan',
                'email' => 'james.tan@evsu.edu.ph',
                'status' => 'processing',
                'section' => 'b',
                'year_level' => 4,
            ],

            // Deployed
            [
                'student_id' => '2020-09709',
                'fname' => 'Andrea',
                'lname' => 'Chua',
                'email' => 'andrea.chua@evsu.edu.ph',
                'status' => 'deployed',
                'section' => 'a',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09710',
                'fname' => 'Ryan',
                'lname' => 'Sy',
                'email' => 'ryan.sy@evsu.edu.ph',
                'status' => 'deployed',
                'section' => 'c',
                'year_level' => 4,
            ],

            // Completed
            [
                'student_id' => '2020-09711',
                'fname' => 'Patricia',
                'lname' => 'Ong',
                'email' => 'patricia.ong@evsu.edu.ph',
                'status' => 'completed',
                'section' => 'b',
                'year_level' => 4,
            ],
            [
                'student_id' => '2020-09712',
                'fname' => 'Daniel',
                'lname' => 'Wong',
                'email' => 'daniel.wong@evsu.edu.ph',
                'status' => 'completed',
                'section' => 'a',
                'year_level' => 4,
            ],
        ];

        $createdInterns = [];
        foreach ($students as $student) {
            $user = User::create([
                'email' => $student['email'],
                'password' => $password,
                'fname' => $student['fname'],
                'lname' => $student['lname'],
                'sex' => in_array($student['fname'], ['Maria', 'Ana', 'Sofia', 'Liza', 'Andrea', 'Patricia']) ? 'female' : 'male',
                'contact' => '09' . rand(10000000, 99999999),
                'pic' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $intern = Intern::create([
                'student_id' => $student['student_id'],
                'user_id' => $user->id,
                'dept_id' => $itDept->dept_id,
                'coordinator_id' => $markCoordinator->id,
                'birthdate' => Carbon::now()->subYears(rand(20, 22))->subMonths(rand(1, 12)),
                'section' => $student['section'],
                'year_level' => $student['year_level'],
                'academic_year' => '2024-2025',
                'semester' => '2nd',
                'status' => $student['status'],
                'first_login' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $createdInterns[$student['status']][] = $intern;
        }

        // ===== ADD SKILLS TO STUDENTS =====
        $itSkills = DB::table('skills')->where('dept_id', $itDept->dept_id)->pluck('skill_id');
        
        foreach ($createdInterns as $statusInterns) {
            foreach ($statusInterns as $intern) {
                // Each student gets 3-5 random IT skills
                $randomSkills = $itSkills->random(min(5, $itSkills->count()));
                foreach ($randomSkills as $skillId) {
                    DB::table('student_skill')->insert([
                        'intern_id' => $intern->id,
                        'skills_id' => $skillId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        $this->command->info('Coordinator Mark Villanueva and students with various statuses created successfully!');
    }
}