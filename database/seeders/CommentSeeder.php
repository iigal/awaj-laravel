<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    public function run()
    {
        Comment::create([
            'issue_id' => 1,
            'user_id' => 2,  // Assuming John Doe made the complaint
            'message' => 'When will the power be restored?'
        ]);

        Comment::create([
            'issue_id' => 2,
            'user_id' => 3,  // Assuming Jane Doe made the complaint
            'message' => 'Can someone check on this issue ASAP?'
        ]);
    }
}
