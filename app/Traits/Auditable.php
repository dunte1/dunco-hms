<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            AuditLog::log(
                'user',
                auth()->id(),
                'created',
                class_basename($model),
                $model->id,
                null,
                $model->toArray(),
                static::class . ' created'
            );
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            $original = array_intersect_key($model->getOriginal(), $dirty);

            AuditLog::log(
                'user',
                auth()->id(),
                'updated',
                class_basename($model),
                $model->id,
                $original,
                $dirty,
                static::class . ' updated'
            );
        });

        static::deleted(function ($model) {
            AuditLog::log(
                'user',
                auth()->id(),
                'deleted',
                class_basename($model),
                $model->id,
                $model->toArray(),
                null,
                static::class . ' deleted'
            );
        });
    }
}
