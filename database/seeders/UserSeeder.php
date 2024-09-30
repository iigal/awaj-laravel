<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'address' => 'Kathmandu',
            'phonenumber' => '9841405030',
            'voterid' => '007',
            'is_verified' => TRUE,
            'role' => 'admin',  // Assuming roles column exists
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password'),
            'address' => 'Bhaktapur',
            'phonenumber' => '9841405031',
            'voterid' => '007',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => Hash::make('password'),
            'address' => 'Lalitpur',
            'phonenumber' => '9841405032',
            'voterid' => '007',
            'is_verified' => TRUE,
            'role' => 'user',
        ]);
    }
}
