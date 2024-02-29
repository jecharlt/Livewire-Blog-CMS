<?php

namespace Jecharlt\LivewireBlogCMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo_light',
        'logo_dark'
    ];
}
