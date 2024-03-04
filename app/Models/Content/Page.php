<?php

namespace App\Models\Content;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'color',
        'status',
        'thumbnail',
        'slug',
        'seo_title',
        'seo_description',
        'tags',
        'page_category_id',
        'status',
    ];

  protected $casts = [
    'tags'=> 'array',
  ];

  public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'page_category_id');
    }

}
