<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAudit extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'action', 'auditable_type', 'auditable_id', 'changes'];

    protected function casts(): array
    {
        return ['changes' => 'array', 'created_at' => 'datetime'];
    }
}
