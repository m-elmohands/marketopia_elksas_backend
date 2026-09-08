<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class SiteSettingTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['value'];
}
