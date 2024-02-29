<?php

namespace Jecharlt\LivewireBlogCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BlogCategory extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function () {
            Cache::forget('blog_categories');
        });

        static::updated(function () {
            Cache::forget('blog_categories');
        });

        static::deleted(function () {
            Cache::forget('blog_categories');
        });
    }
    protected $fillable = [
        'name',
        'path',
        'ranking'
    ];
}
