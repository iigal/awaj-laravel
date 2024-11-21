<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['title','parent_id'];

    // Self-referencing relationship: Parent comment (for replies)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Self-referencing relationship: Replies to this comment
    public function reply()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
