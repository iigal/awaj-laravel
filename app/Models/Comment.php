<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'user_id',
        'complaint_id',
        'parent_id',  // For replies
    ];

    // Relationship: Each comment belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Each comment belongs to a complaint
    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    // Self-referencing relationship: Parent comment (for replies)
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Self-referencing relationship: Replies to this comment
    public function reply()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
