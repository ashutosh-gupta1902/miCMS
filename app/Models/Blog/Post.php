<?php

namespace App\Models\Blog;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model
{
    use HasFactory;

    protected $table = 'blog_post';
    protected $fillable = [
        'title', 
        'content', 
        'color', 
        'category_id', 
        'status', 
        'thumbnail', 
        'slug', 
        'status',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    /** @return BelongsTo<Category,self> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
