<?php

namespace Jecharlt\LivewireBlogCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogArticleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
}
