<?php

namespace App\Models\Concerns;

use App\Models\AdminAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

trait AuditsContentChanges
{
    protected static function bootAuditsContentChanges(): void
    {
        static::saved(function (Model $model): void {
            Cache::flush();
            self::audit($model, $model->wasRecentlyCreated ? 'created' : 'updated');
        });
        static::deleted(function (Model $model): void {
            Cache::flush();
            $forceDeleted = method_exists($model, 'isForceDeleting') && $model->isForceDeleting();
            self::audit($model, $forceDeleted ? 'force_deleted' : 'deleted');
        });
    }

    private static function audit(Model $model, string $action): void
    {
        if (! Auth::check()) {
            return;
        }

        AdminAudit::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'changes' => $model->getChanges(),
        ]);
    }
}
