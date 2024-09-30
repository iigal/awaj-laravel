<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['title' => 'Electrical']);
        Category::create(['title' => 'Plumbing']);
        Category::create(['title' => 'Road Maintenance']);
        Category::create(['title' => 'Sanitation']);
    }
}
