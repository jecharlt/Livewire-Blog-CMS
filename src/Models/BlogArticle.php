<?php

namespace Jecharlt\LivewireBlogCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jecharlt\LivewireBlogCMS\Models\BlogCategory;
use Jecharlt\LivewireBlogCMS\Models\BlogArticleType;
use Illuminate\Support\Facades\Cache;

class BlogArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'slug',
        'category',
        'article_type',
        'featured_image',
        'content',
        'is_published',
        'published_time',
        'last_edited_time'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('blog_articles');
        });

        static::updated(function () {
            Cache::forget('blog_articles');
        });

        static::deleted(function () {
            Cache::forget('blog_articles');
        });
    }

    public function category() {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function articleType() {
        return $this->belongsTo(BlogArticleType::class, 'article_type_id');
    }
}
