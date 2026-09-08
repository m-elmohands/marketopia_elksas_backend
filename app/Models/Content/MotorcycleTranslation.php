<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Model;

class MotorcycleTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'summary', 'details'];
}
