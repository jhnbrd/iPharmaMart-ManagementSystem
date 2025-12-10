<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity(): void
    {
        // Log when a model is created
        static::created(function ($model) {
            $description = class_basename($model) . ' created';
            if (isset($model->name)) {
                $description .= ': ' . $model->name;
            } elseif (isset($model->id)) {
                $description .= ' #' . $model->id;
            }

            self::logActivity(
                'created',
                $description,
                $model,
                null,
                $model->getAttributes()
            );
        });

        // Log when a model is updated
        static::updated(function ($model) {
            $description = class_basename($model) . ' updated';
            if (isset($model->name)) {
                $description .= ': ' . $model->name;
            } elseif (isset($model->id)) {
                $description .= ' #' . $model->id;
            }

            self::logActivity(
                'updated',
                $description,
                $model,
                $model->getOriginal(),
                $model->getChanges()
            );
        });

        // Log when a model is deleted
        static::deleted(function ($model) {
            $description = class_basename($model) . ' deleted';
            if (isset($model->name)) {
                $description .= ': ' . $model->name;
            } elseif (isset($model->id)) {
                $description .= ' #' . $model->id;
            }

            self::logActivity(
                'deleted',
                $description,
                $model,
                $model->getAttributes(),
                null
            );
        });
    }

    public static function logActivity(string $action, string $description, $model = null, array $oldValues = null, array $newValues = null): void
    {
        // Skip logging for AuditLog model to avoid infinite loop
        if ($model instanceof AuditLog) {
            return;
        }

        // Get user ID - if not authenticated, check if user 1 exists, otherwise use null
        $userId = Auth::id();
        if (!$userId && \App\Models\User::where('id', 1)->exists()) {
            $userId = 1;
        }

        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id ?? null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'CLI/Seeder',
        ]);
    }
}
