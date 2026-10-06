<?php

namespace App\Models\Concerns;

use App\Models\ActivityLog;

/**
 * Writes an audit-log row whenever a signed-in admin creates, updates or deletes the model.
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                if (! auth()->check()) {
                    return;
                }

                $changes = $event === 'updated'
                    ? array_keys(array_diff_key($model->getChanges(), array_flip(['updated_at', 'remember_token', 'password', 'last_login_at'])))
                    : null;

                if ($event === 'updated' && ! $changes) {
                    return;
                }

                ActivityLog::record($event, $model, class_basename($model).' '.$event, $changes ? ['fields' => $changes] : null);
            });
        }
    }
}
