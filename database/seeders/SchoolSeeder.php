<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Colleges (column names: name, short_name)s
        $colleges = [
            ['name' => 'College of Architecture and Allied Discipline', 'short_name' => 'CAAD'],
            ['name' => 'College of Arts & Sciences', 'short_name' => 'CAS'],
            ['name' => 'College of Business & Entrepreneurship', 'short_name' => 'COBE'],
            ['name' => 'College of Education', 'short_name' => 'COED'],
            ['name' => 'College of Engineering', 'short_name' => 'COE'],
            ['name' => 'College of Technology', 'short_name' => 'COT'],
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
            // College of Architecture and Allied Discipline
            ['dept_name' => 'Architecture', 'short_name' => 'BSARCH', 'college_id' => $ids['College of Architecture and Allied Discipline']],
            ['dept_name' => 'Interior Design', 'short_name' => 'BSID', 'college_id' => $ids['College of Architecture and Allied Discipline']],

            // College of Arts & Sciences
            ['dept_name' => 'English Language', 'short_name' => 'BAEL', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Economics', 'short_name' => 'BSECON', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Environmental Science', 'short_name' => 'BSES', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Chemistry', 'short_name' => 'BSCHEM', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Mathematics', 'short_name' => 'BSMATH', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Statistics', 'short_name' => 'BSSTAT', 'college_id' => $ids['College of Arts & Sciences']],
            ['dept_name' => 'Filipino', 'short_name' => 'BSFIL', 'college_id' => $ids['College of Arts & Sciences']],

            // College of Business & Entrepreneurship
            ['dept_name' => 'Accountancy', 'short_name' => 'BSA', 'college_id' => $ids['College of Business & Entrepreneurship']],
            ['dept_name' => 'Business Administration', 'short_name' => 'BSBA-MKT', 'college_id' => $ids['College of Business & Entrepreneurship']],
            ['dept_name' => 'Entrepreneurship', 'short_name' => 'BSENTREP', 'college_id' => $ids['College of Business & Entrepreneurship']],
            ['dept_name' => 'Office Administration', 'short_name' => 'BSOA', 'college_id' => $ids['College of Business & Entrepreneurship']],

            // // College of Education
            // ['dept_name' => 'Culture & Arts Education', 'short_name' => 'BCAED', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Elementary Education', 'short_name' => 'BEED', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Secondary Education major in Mathematics', 'short_name' => 'BSED-MATH', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Secondary Education major in Science', 'short_name' => 'BSED-SCI', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Physical Education', 'short_name' => 'BPE', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Automotive Technology', 'short_name' => 'BTVTED-AT', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Civil and Construction Technology', 'short_name' => 'BTVTED-CCT', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Electronics Technology', 'short_name' => 'BTVTED-ET', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Electrical Technology', 'short_name' => 'BTVTED-ELT', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Food and Service Management', 'short_name' => 'BTVTED-FSM', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Garments, Fashion and Design', 'short_name' => 'BTVTED-GFD', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Heating, Ventilating, Air-Conditioning and Refrigeration Technology', 'short_name' => 'BTVTED-HVAC', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Mechanical Technology', 'short_name' => 'BTVTED-MT', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technical-Vocational Teacher Education major in Welding and Fabrication Technology', 'short_name' => 'BTVTED-WFT', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technology & Livelihood Education major in Home Economics', 'short_name' => 'BTLE-HE', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Bachelor of Technology & Livelihood Education major in Industrial Arts', 'short_name' => 'BTLE-IA', 'college_id' => $ids['College of Education']],
            // ['dept_name' => 'Diploma in Teaching Secondary', 'short_name' => 'DTS', 'college_id' => $ids['College of Education']],

            // College of Engineering
            ['dept_name' => 'Civil Engineering', 'short_name' => 'BSCE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Chemical Engineering', 'short_name' => 'BSCHE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Electronics Engineering', 'short_name' => 'BSECE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Electrical Engineering', 'short_name' => 'BSEE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Geodetic Engineering', 'short_name' => 'BSGE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Industrial Engineering', 'short_name' => 'BSIE', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Information Technology', 'short_name' => 'BSIT', 'college_id' => $ids['College of Engineering']],
            ['dept_name' => 'Mechanical Engineering', 'short_name' => 'BSME', 'college_id' => $ids['College of Engineering']],

            // // College of Technology
            // ['dept_name' => 'Bachelor of Science in Hospitality Management', 'short_name' => 'BSHM', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Civil and Construction', 'short_name' => 'BSIT-CCT', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Clothing and Fashion Design', 'short_name' => 'BSIT-CFD', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Culinary Arts', 'short_name' => 'BSIT-CA', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Electricity', 'short_name' => 'BSIT-ELEC', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Electronics', 'short_name' => 'BSIT-ECE', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Graphics Arts and Printing', 'short_name' => 'BSIT-GAP', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Industrial Technology major in Refrigeration and Air-Conditioning', 'short_name' => 'BSIT-RAC', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Mechanical Technology major in Automotive', 'short_name' => 'BSMT-AUTO', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Mechanical Technology major in Machine Shop', 'short_name' => 'BSMT-MS', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Mechanical Technology major in Metallurgy', 'short_name' => 'BSMT-MET', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Mechanical Technology major in Welding and Fabrication', 'short_name' => 'BSMT-WF', 'college_id' => $ids['College of Technology']],
            // ['dept_name' => 'Bachelor of Science in Nutrition and Dietetics', 'short_name' => 'BSND', 'college_id' => $ids['College of Technology']],
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