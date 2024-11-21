<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IssueSeeder extends Seeder
{
    public function run()
    {
        DB::table('issues')->insert([
            [
                'title' => 'Network Issue',
                'description' => 'There is a problem with the office Wi-Fi connectivity.',
                'images' => json_encode(['issue1_img1.png', 'issue1_img2.png']),
                'user_id' => 1, // Adjust this based on existing users in your `users` table
                'status' => 'Queue',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Hardware Issue',
                'description' => 'The desktop computer is not starting up.',
                'images' => json_encode(['issue2_img1.png']),
                'user_id' => 2,
                'status' => 'Progress',
                'is_published' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Software Crash',
                'description' => 'The accounting software crashes on start.',
                'images' => json_encode(['issue3_img1.png', 'issue3_img2.png', 'issue3_img3.png']),
                'user_id' => 3,
                'status' => 'Success',
                'is_published' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
