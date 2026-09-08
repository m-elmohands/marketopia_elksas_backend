<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class ArticleTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['title', 'excerpt', 'body'];
}
