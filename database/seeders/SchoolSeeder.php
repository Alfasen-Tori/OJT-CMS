<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Colleges (column names: name, short_name)
        $colleges = [
            ['name' => 'Engineering', 'short_name' => 'COE'],
            ['name' => 'Arts and Sciences', 'short_name' => 'CAS'],
            ['name' => 'Education', 'short_name' => 'CED'],
            ['name' => 'Technology', 'short_name' => 'COT'],
            ['name' => 'Administration and Entrepreneurship', 'short_name' => 'CAE'],
            ['name' => 'Architecture and Allied Discipline', 'short_name' => 'CAAD'],
        ];

        // Insert colleges (id will be the 'id' column)
        foreach ($colleges as $college) {
            DB::table('colleges')->updateOrInsert(
                ['name' => $college['name']],
                ['short_name' => $college['short_name'], 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // Grab college ids
        $ids = [];
        foreach ($colleges as $college) {
            $ids[$college['name']] = DB::table('colleges')->where('name', $college['name'])->value('id');
        }

        // Departments / programs (columns: dept_name, short_name, college_id)
        $departments = [
            // Engineering (you listed these)
            ['dept_name' => 'Information Technology',   'short_name' => 'IT',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Civil Engineering',        'short_name' => 'CE',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Mechanical Engineering',   'short_name' => 'ME',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Electrical Engineering',   'short_name' => 'EE',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Chemical Engineering',     'short_name' => 'ChE', 'college_id' => $ids['Engineering']],
            ['dept_name' => 'Geodetic Engineering',     'short_name' => 'GE',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Industrial Engineering',   'short_name' => 'IE',  'college_id' => $ids['Engineering']],
            ['dept_name' => 'Electronics Engineering',  'short_name' => 'ECE', 'college_id' => $ids['Engineering']],

            // Arts and Sciences (examples)
            ['dept_name' => 'English',                  'short_name' => 'ENG', 'college_id' => $ids['Arts and Sciences']],
            ['dept_name' => 'Mathematics',              'short_name' => 'MATH','college_id' => $ids['Arts and Sciences']],
            ['dept_name' => 'Political Science',        'short_name' => 'PS',  'college_id' => $ids['Arts and Sciences']],

            // Education
            ['dept_name' => 'Elementary Education',     'short_name' => 'BEED','college_id' => $ids['Education']],
            ['dept_name' => 'Secondary Education',      'short_name' => 'BSED','college_id' => $ids['Education']],
            ['dept_name' => 'Physical Education',       'short_name' => 'BPE', 'college_id' => $ids['Education']],

            // Technology
            ['dept_name' => 'Automotive Technology',    'short_name' => 'AT',  'college_id' => $ids['Technology']],
            ['dept_name' => 'Electronics Technology',   'short_name' => 'ET',  'college_id' => $ids['Technology']],
            ['dept_name' => 'Drafting Technology',      'short_name' => 'DT',  'college_id' => $ids['Technology']],

            // Administration and Entrepreneurship
            ['dept_name' => 'Business Administration',  'short_name' => 'BBA', 'college_id' => $ids['Administration and Entrepreneurship']],
            ['dept_name' => 'Entrepreneurship',         'short_name' => 'ENT', 'college_id' => $ids['Administration and Entrepreneurship']],
            ['dept_name' => 'Hospitality Management',   'short_name' => 'HM',  'college_id' => $ids['Administration and Entrepreneurship']],

            // Architecture and Allied Discipline
            ['dept_name' => 'Architecture',             'short_name' => 'ARCH','college_id' => $ids['Architecture and Allied Discipline']],
            ['dept_name' => 'Interior Design',          'short_name' => 'ID',  'college_id' => $ids['Architecture and Allied Discipline']],
            ['dept_name' => 'Landscape Architecture',   'short_name' => 'LA',  'college_id' => $ids['Architecture and Allied Discipline']],
        ];

        // Insert departments (updateOrInsert avoids duplicates on repeated runs)
        foreach ($departments as $dept) {
            DB::table('departments')->updateOrInsert(
                ['dept_name' => $dept['dept_name'], 'college_id' => $dept['college_id']],
                ['short_name' => $dept['short_name'], 'created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
