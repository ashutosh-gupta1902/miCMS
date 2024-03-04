<?php

namespace App\Models\Blog;

use App\Models\Content\Page;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_visible',
        'seo_title',
        'seo_description',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_visible' => 'boolean',
    ];

    /** @return HasMany<Post> */
    public function post(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id');
    }

    public function page(): HasMany
    {
        return $this->hasMany(Page::class, 'page_category_id');
    }

    /**
     * Set the is_visible attribute.
     *
     * @param  string  $value
     * @return void  
     */
    public function getIsVisibleAttribute($value)
    {
        return $value == 1 ? 'Yes' : 'No';
    }
}
