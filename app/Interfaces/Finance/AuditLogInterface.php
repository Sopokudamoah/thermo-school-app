<?php

namespace App\Interfaces\Finance;

interface AuditLogInterface
{
    public function log($user_id, $action, $auditable_type, $auditable_id, $old_values = null, $new_values = null);

    public function getAll(array $filters = []);

    public function getForModel($auditable_type, $auditable_id);
}
