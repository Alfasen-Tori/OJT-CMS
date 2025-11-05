<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CoordinatorStudentSeeder::class,
            HTEEndorsementSeeder::class,
            InternAttendanceSeeder::class,
        ]);
    }
}