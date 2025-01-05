<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    /**
     * Registra uma nova entrada no histórico.
     *
     * @param string $action Ação realizada (create, update, delete).
     * @param string $entity Entidade afetada (task ou subtask).
     * @param int|null $entityId ID da entidade afetada.
     * @param int|null $previousStatus ID do status anterior (opcional).
     * @param int|null $newStatus ID do novo status (obrigatório).
     * @param string|null $observation Observação adicional (opcional).
     * @return void
     */
    public static function log(
        string $action,
        string $entity,
        ?int $entityId,
        ?int $previousStatus,
        int $newStatus,
        ?string $observation = null
    ) {
        ActivityLog::create([
            'task_id' => $entity === 'task' ? $entityId : null,
            'subtask_id' => $entity === 'subtask' ? $entityId : null,
            'status_previous_id' => $previousStatus,
            'status_new_id' => $newStatus,
            'observation' => $observation,
            'user_id' => Auth::id(),
            'changed_at' => now(),
        ]);
    }
}
