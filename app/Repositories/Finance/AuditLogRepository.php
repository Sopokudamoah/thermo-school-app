<?php

namespace App\Repositories\Finance;

use App\Interfaces\Finance\AuditLogInterface;
use App\Models\Finance\AuditLog;

class AuditLogRepository implements AuditLogInterface
{
    public function log($user_id, $action, $auditable_type, $auditable_id, $old_values = null, $new_values = null)
    {
        return AuditLog::create([
            'user_id' => $user_id,
            'action' => $action,
            'auditable_type' => $auditable_type,
            'auditable_id' => $auditable_id,
            'old_values' => $old_values,
            'new_values' => $new_values,
            'ip_address' => request()->ip(),
        ]);
    }

    public function getAll(array $filters = [])
    {
        $query = AuditLog::with('user');

        if (!empty($filters['auditable_type'])) {
            $query->where('auditable_type', $filters['auditable_type']);
        }
        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }
        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }
        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function getForModel($auditable_type, $auditable_id)
    {
        return AuditLog::with('user')
            ->where('auditable_type', $auditable_type)
            ->where('auditable_id', $auditable_id)
            ->orderByDesc('created_at')
            ->get();
    }
}
