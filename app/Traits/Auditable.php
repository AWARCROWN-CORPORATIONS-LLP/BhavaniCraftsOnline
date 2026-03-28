<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function (Model $model) {
            $model->audit('created');
        });

        static::updated(function (Model $model) {
            $model->audit('updated');
        });

        static::deleted(function (Model $model) {
            $model->audit('deleted');
        });
    }

    protected function audit($action)
    {
        // Don't log if running in console (e.g., migrations, seeders) without a web user
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        $userId = auth()->id() ?? null;
        $ipAddress = request()->ip() ?? null;
        $userAgent = request()->userAgent() ?? null;

        $oldValues = [];
        $newValues = [];

        if ($action === 'updated') {
            $oldValues = $this->getOriginal();
            $newValues = $this->getChanges();
        } elseif ($action === 'created') {
            $newValues = $this->getAttributes();
        } elseif ($action === 'deleted') {
            $oldValues = $this->getAttributes();
        }

        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->getKey(),
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }
}
