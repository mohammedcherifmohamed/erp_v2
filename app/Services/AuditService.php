<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(string $action, $model = null, ?array $oldValues = null, ?array $newValues = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logCreate($model, array $data): AuditLog
    {
        return $this->log('created', $model, null, $data);
    }

    public function logUpdate($model, array $oldValues, array $newValues): AuditLog
    {
        return $this->log('updated', $model, $oldValues, $newValues);
    }

    public function logDelete($model, array $data): AuditLog
    {
        return $this->log('deleted', $model, $data, null);
    }

    public function logAuth(string $action, ?array $data = null): AuditLog
    {
        return $this->log($action, null, null, $data);
    }
}