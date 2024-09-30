<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;

class IssueSeeder extends Seeder
{
    public function run()
    {
        Issue::create([
            'title' => 'Electricity outage in area A',
            'slug'=> 'electricity-outage-in-area-a',
            'description' => 'There has been a power outage for over 24 hours.',
            'status' => 'Queue',
            'user_id' => 1  // Assuming this is John Doe's user id
        ]);

        Issue::create([
            'title' => 'Water leakage in the main pipeline',
            'slug' => 'water-leakaage-in-the-main-pipeline',
            'description' => 'Major leakage detected in the city’s main water supply pipeline.',
            'status' => 'Progress',
            'user_id' => 1  // Assuming this is Jane Doe's user id
        ]);
    }
}
