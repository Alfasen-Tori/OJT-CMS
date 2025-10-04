<?php
namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create the admin user
        $user = User::create([
            'email' => 'amadeuz.gravozo@evsu.edu.ph',
            'password' => bcrypt('admin123'), // Hashed
            'fname' => 'Jessie',
            'lname' => 'Paragas',
            'contact' => '09507395757',
            'pic' => 'profile_pictures/profile.jpg',
        ]);

        // Create the admin record linked to the user
        Admin::create([
            'faculty_id' => 'A090803GL',
            'user_id' => $user->id, // Automatically links to the user above
        ]);
    }
}
