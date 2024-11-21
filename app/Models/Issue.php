<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Issue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id',
        'is_published',
    ];

    protected $casts = [
        'images' => 'json',
    ];

    // Relationship: Each complaint belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: Each complaint belongs to a category
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function subCategories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Each complaint can have many comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
