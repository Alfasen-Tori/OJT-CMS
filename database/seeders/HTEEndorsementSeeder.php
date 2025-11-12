<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Hte;
use App\Models\Coordinator;
use App\Models\Intern;
use App\Models\InternEvaluation;
use Carbon\Carbon;

class HTEEndorsementSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $password = bcrypt('password123');

        // Get department IDs
        $itDept = DB::table('departments')->where('short_name', 'BSIT')->first();
        $ceDept = DB::table('departments')->where('short_name', 'BSCE')->first();

        // ===== CREATE DEFAULT SKILLS IF NONE EXIST =====
        $this->createDefaultSkills($itDept, $ceDept, $now);

        // ===== CREATE ADDITIONAL COORDINATORS =====
        $coordinators = [
            // Second IT Coordinator
            [
                'faculty_id' => 'R061220J', // Ramon Javier, hired: June 12, 2020
                'fname' => 'Ramon',
                'lname' => 'Javier',
                'email' => 'ramon.javier@evsu.edu.ph',
                'dept_id' => $itDept->dept_id,
            ],
            // Third IT Coordinator
            [
                'faculty_id' => 'L030519M', // Lourdes Mendoza, hired: March 5, 2019
                'fname' => 'Lourdes',
                'lname' => 'Mendoza',
                'email' => 'lourdes.mendoza@evsu.edu.ph',
                'dept_id' => $itDept->dept_id,
            ],
            // Different Department Coordinator (Civil Engineering)
            [
                'faculty_id' => 'A110817P', // Antonio Perez, hired: November 8, 2017
                'fname' => 'Antonio',
                'lname' => 'Perez',
                'email' => 'antonio.perez@evsu.edu.ph',
                'dept_id' => $ceDept->dept_id,
            ],
        ];

        $createdCoordinators = [];
        foreach ($coordinators as $coordinatorData) {
            $user = User::create([
                'email' => $coordinatorData['email'],
                'password' => $password,
                'fname' => $coordinatorData['fname'],
                'lname' => $coordinatorData['lname'],
                'sex' => in_array($coordinatorData['fname'], ['Lourdes']) ? 'female' : 'male',
                'contact' => '09' . rand(10000000, 99999999),
                'pic' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $coord = Coordinator::create([
                'faculty_id' => $coordinatorData['faculty_id'],
                'user_id' => $user->id,
                'dept_id' => $coordinatorData['dept_id'],
                'can_add_hte' => '1',
                'status' => 'pending documents',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Store the coordinator data for reference
            $coord->fname = $coordinatorData['fname'];
            $coord->lname = $coordinatorData['lname'];
            $createdCoordinators[] = $coord;
        }

        // ===== CREATE MULTIPLE HTEs (including some with no interns) =====
        $htes = [
            // HTEs that WILL have interns
            [
                'email' => 'techsolutions.inc@gmail.com',
                'fname' => 'Roberto',
                'lname' => 'Silva',
                'organization_name' => 'TechSolutions Inc.',
                'type' => 'private',
                'address' => '123 Tech Park, IT Center, Tacloban City',
                'description' => 'Leading technology solutions provider specializing in web development and IT services.',
                'slots' => 8,
                'has_interns' => true,
            ],
            [
                'email' => 'webcreatives.studio@gmail.com',
                'fname' => 'Maria',
                'lname' => 'Tan',
                'organization_name' => 'WebCreatives Studio',
                'type' => 'private',
                'address' => '456 Digital Avenue, Palo, Leyte',
                'description' => 'Creative web development agency focused on modern web applications and UI/UX design.',
                'slots' => 6,
                'has_interns' => true,
            ],
            [
                'email' => 'datasys.corp@gmail.com',
                'fname' => 'Carlos',
                'lname' => 'Lim',
                'organization_name' => 'DataSystems Corporation',
                'type' => 'private',
                'address' => '789 Data Street, Ormoc City',
                'description' => 'Data management and analytics company providing business intelligence solutions.',
                'slots' => 10,
                'has_interns' => true,
            ],
            [
                'email' => 'innovate.gov.ph',
                'fname' => 'Eduardo',
                'lname' => 'Reyes',
                'organization_name' => 'DOST Regional Office',
                'type' => 'government',
                'address' => '321 Government Center, Tacloban City',
                'description' => 'Government agency promoting science and technology innovation in the region.',
                'slots' => 5,
                'has_interns' => true,
            ],
            // HTEs that will have NO interns
            [
                'email' => 'startup.tech@gmail.com',
                'fname' => 'Jennifer',
                'lname' => 'Chen',
                'organization_name' => 'StartupTech Innovations',
                'type' => 'private',
                'address' => '555 Startup Hub, Baybay City',
                'description' => 'Emerging tech startup focused on mobile applications and AI solutions.',
                'slots' => 4,
                'has_interns' => false,
            ],
            [
                'email' => 'local.gov.ph',
                'fname' => 'Ricardo',
                'lname' => 'Diaz',
                'organization_name' => 'City Planning Office',
                'type' => 'government',
                'address' => '100 City Hall, Tacloban City',
                'description' => 'Local government unit managing urban development and infrastructure projects.',
                'slots' => 3,
                'has_interns' => false,
            ],
            [
                'email' => 'digital.agency@gmail.com',
                'fname' => 'Samantha',
                'lname' => 'Lee',
                'organization_name' => 'Digital Momentum Agency',
                'type' => 'private',
                'address' => '222 Marketing Street, Ormoc City',
                'description' => 'Full-service digital marketing agency specializing in social media and SEO.',
                'slots' => 5,
                'has_interns' => false,
            ],
        ];

        $createdHTEs = [];
        foreach ($htes as $hteData) {
            $hteUser = User::create([
                'email' => $hteData['email'],
                'password' => $password,
                'fname' => $hteData['fname'],
                'lname' => $hteData['lname'],
                'sex' => in_array($hteData['fname'], ['Maria', 'Jennifer', 'Samantha']) ? 'female' : 'male',
                'contact' => '09' . rand(20000000, 29999999),
                'pic' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $hte = Hte::create([
                'user_id' => $hteUser->id,
                'organization_name' => $hteData['organization_name'],
                'type' => $hteData['type'],
                'status' => 'active',
                'address' => $hteData['address'],
                'description' => $hteData['description'],
                'slots' => $hteData['slots'],
                'moa_path' => null,
                'first_login' => 1,
                'moa_is_signed' => 'yes',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $hte->has_interns = $hteData['has_interns']; // Store for later use
            $createdHTEs[] = $hte;
        }

        // ===== ADD SKILLS TO ALL HTEs =====
        $itSkills = DB::table('skills')->where('dept_id', $itDept->dept_id)->pluck('skill_id')->toArray();
        $ceSkills = DB::table('skills')->where('dept_id', $ceDept->dept_id)->pluck('skill_id')->toArray();
        
        foreach ($createdHTEs as $hte) {
            // Each HTE gets 4-6 random skills
            $numSkills = rand(4, 6);
            
            // Mix IT and CE skills for diversity
            $allSkills = array_merge($itSkills, $ceSkills);
            
            // Safety check: if no skills exist, skip
            if (empty($allSkills)) {
                $this->command->warn("No skills found for HTE {$hte->organization_name}. Skipping skill assignment.");
                continue;
            }
            
            $numSkills = min($numSkills, count($allSkills));
            $randomSkillIndices = array_rand($allSkills, $numSkills);
            
            if (!is_array($randomSkillIndices)) {
                $randomSkillIndices = [$randomSkillIndices];
            }
            
            foreach ($randomSkillIndices as $skillIndex) {
                DB::table('hte_skill')->insert([
                    'hte_id' => $hte->id,
                    'skill_id' => $allSkills[$skillIndex],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ===== CREATE ENDORSEMENTS FOR MARK VILLANUEVA'S STUDENTS =====
        $mainCoordinator = Coordinator::where('faculty_id', 'M091518V')->first();
        
        // Get Mark's students
        $endorsedStudents = Intern::where('coordinator_id', $mainCoordinator->id)
                                ->where('status', 'endorsed')
                                ->get();
        $processingStudents = Intern::where('coordinator_id', $mainCoordinator->id)
                                ->where('status', 'processing')
                                ->get();
        $deployedStudents = Intern::where('coordinator_id', $mainCoordinator->id)
                                ->where('status', 'deployed')
                                ->get();
        $completedStudents = Intern::where('coordinator_id', $mainCoordinator->id)
                                ->where('status', 'completed')
                                ->get();

        // Endorsed students -> TechSolutions Inc. (2 students)
        foreach ($endorsedStudents->take(2) as $student) {
            DB::table('interns_hte')->insert([
                'intern_id' => $student->id,
                'hte_id' => $createdHTEs[0]->id, // TechSolutions
                'coordinator_id' => $mainCoordinator->id,
                'status' => 'endorsed',
                'endorsed_at' => $now->subDays(2),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Processing students -> WebCreatives Studio (2 students)
        foreach ($processingStudents->take(2) as $student) {
            DB::table('interns_hte')->insert([
                'intern_id' => $student->id,
                'hte_id' => $createdHTEs[1]->id, // WebCreatives
                'coordinator_id' => $mainCoordinator->id,
                'status' => 'processing',
                'endorsed_at' => $now->subDays(5),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Deployed students -> DataSystems Corporation (2 students)
        foreach ($deployedStudents->take(2) as $student) {
            DB::table('interns_hte')->insert([
                'intern_id' => $student->id,
                'hte_id' => $createdHTEs[2]->id, // DataSystems
                'coordinator_id' => $mainCoordinator->id,
                'status' => 'deployed',
                'endorsed_at' => $now->subDays(15),
                'deployed_at' => $now->subDays(10),
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(60),
                'no_of_hours' => 240,
                'no_of_weeks' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Completed students -> DOST (2 students)
        foreach ($completedStudents->take(2) as $student) {
            DB::table('interns_hte')->insert([
                'intern_id' => $student->id,
                'hte_id' => $createdHTEs[3]->id, // DOST
                'coordinator_id' => $mainCoordinator->id,
                'status' => 'completed',
                'endorsed_at' => $now->subDays(90),
                'deployed_at' => $now->subDays(85),
                'start_date' => Carbon::now()->subDays(85),
                'end_date' => Carbon::now()->subDays(15),
                'no_of_hours' => 240,
                'no_of_weeks' => 8,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ===== CREATE MULTI-COORDINATOR SCENARIO =====
        // Use TechSolutions HTE for the multi-coordinator scenario
        $multiCoordinatorHTE = $createdHTEs[0]; // TechSolutions
        
        // Create temporary students for the multi-coordinator scenario
        $multiCoordinatorStudents = [];
        $studentNames = [
            ['fname' => 'Michael', 'lname' => 'Rodriguez'],
            ['fname' => 'Sarah', 'lname' => 'Gutierrez'],
            ['fname' => 'Kevin', 'lname' => 'Castillo'],
        ];
        
        foreach ($createdCoordinators as $index => $coordinator) {
            $studentNumber = $coordinator->id * 10 + $index + 1;
            $studentName = $studentNames[$index];
            
            $user = User::create([
                'email' => "multicoord.student{$studentNumber}@evsu.edu.ph",
                'password' => $password,
                'fname' => $studentName['fname'],
                'lname' => $studentName['lname'],
                'sex' => in_array($studentName['fname'], ['Sarah']) ? 'female' : 'male',
                'contact' => '09' . rand(10000000, 99999999),
                'pic' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $intern = Intern::create([
                'student_id' => '2020-' . sprintf('%05d', $studentNumber + 300),
                'user_id' => $user->id,
                'dept_id' => $coordinator->dept_id,
                'coordinator_id' => $coordinator->id,
                'birthdate' => Carbon::now()->subYears(rand(20, 22)),
                'section' => ['a', 'b', 'c'][array_rand(['a', 'b', 'c'])],
                'year_level' => 4,
                'academic_year' => '2024-2025',
                'semester' => '2nd',
                'status' => 'endorsed',
                'first_login' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Add skills to temporary student
            $deptSkills = DB::table('skills')->where('dept_id', $coordinator->dept_id)->pluck('skill_id')->toArray();
            if (!empty($deptSkills)) {
                $numSkills = rand(3, min(5, count($deptSkills)));
                $randomSkillIndices = array_rand($deptSkills, $numSkills);
                if (!is_array($randomSkillIndices)) {
                    $randomSkillIndices = [$randomSkillIndices];
                }
                foreach ($randomSkillIndices as $skillIndex) {
                    DB::table('student_skill')->insert([
                        'intern_id' => $intern->id,
                        'skills_id' => $deptSkills[$skillIndex],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $multiCoordinatorStudents[] = [
                'student' => $intern,
                'coordinator' => $coordinator
            ];
        }

        // Create endorsements for the multi-coordinator HTE
        foreach ($multiCoordinatorStudents as $data) {
            if ($data['student'] && $data['coordinator']) {
                DB::table('interns_hte')->insert([
                    'intern_id' => $data['student']->id,
                    'hte_id' => $multiCoordinatorHTE->id,
                    'coordinator_id' => $data['coordinator']->id,
                    'status' => 'endorsed',
                    'endorsed_at' => $now->subDays(rand(1, 3)),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ===== CREATE EVALUATIONS FOR COMPLETED STUDENTS =====
        $completedEndorsements = DB::table('interns_hte')
            ->whereIn('intern_id', $completedStudents->pluck('id'))
            ->get();

        foreach ($completedEndorsements as $endorsement) {
            // Generate individual factor scores that will result in a good total grade
            $quality_of_work = rand(85, 98); // 20%
            $dependability = rand(80, 95);   // 15%
            $timeliness = rand(85, 97);      // 15%
            $attendance = rand(90, 99);      // 15%
            $cooperation = rand(88, 98);     // 10%
            $judgment = rand(82, 95);        // 10%
            $personality = rand(90, 100);    // 5%

            // Calculate total grade using weighted formula
            $total_grade = (
                ($quality_of_work * 0.20) +
                ($dependability * 0.15) +
                ($timeliness * 0.15) +
                ($attendance * 0.15) +
                ($cooperation * 0.10) +
                ($judgment * 0.10) +
                ($personality * 0.05)
            );

            // Create evaluation with new structure
            InternEvaluation::create([
                'intern_hte_id' => $endorsement->id,
                'quality_of_work' => $quality_of_work,
                'dependability' => $dependability,
                'timeliness' => $timeliness,
                'attendance' => $attendance,
                'cooperation' => $cooperation,
                'judgment' => $judgment,
                'personality' => $personality,
                'total_grade' => $total_grade,
                'comments' => 'Excellent performance throughout the internship. Demonstrated strong technical skills and good work ethic.',
                'evaluation_date' => Carbon::parse($endorsement->end_date)->subDays(5),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command->info('Multiple HTEs created successfully!');
        $this->command->info('4 HTEs with interns and 3 HTEs with NO interns created!');
        $this->command->info('All HTEs have skills assigned!');
        $this->command->info('Multi-coordinator scenario created: TechSolutions HTE has endorsements from:');
        $this->command->info('- 2 IT Coordinators (Ramon Javier & Lourdes Mendoza)');
        $this->command->info('- 1 CE Coordinator (Antonio Perez)');
        $this->command->info('Evaluations created with new job factor structure!');
    }

    private function createDefaultSkills($itDept, $ceDept, $now)
    {
        // Check if skills exist for IT department
        $itSkillCount = DB::table('skills')->where('dept_id', $itDept->dept_id)->count();
        if ($itSkillCount == 0) {
            $this->command->info('Creating default IT skills...');
            $itSkills = [
                'Web Development',
                'Frontend Development', 
                'Backend Development',
                'Database Management',
                'Network Administration',
                'Cybersecurity',
                'Cloud Computing',
                'Software Testing',
            ];
            
            foreach ($itSkills as $skill) {
                DB::table('skills')->insert([
                    'dept_id' => $itDept->dept_id,
                    'name' => $skill,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Check if skills exist for CE department  
        $ceSkillCount = DB::table('skills')->where('dept_id', $ceDept->dept_id)->count();
        if ($ceSkillCount == 0) {
            $this->command->info('Creating default Civil Engineering skills...');
            $ceSkills = [
                'Structural Design',
                'AutoCAD',
                'Construction Management',
                'Surveying',
                'Project Estimation',
                'Building Codes',
                'Concrete Technology',
                'Urban Planning',
            ];
            
            foreach ($ceSkills as $skill) {
                DB::table('skills')->insert([
                    'dept_id' => $ceDept->dept_id,
                    'name' => $skill,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}