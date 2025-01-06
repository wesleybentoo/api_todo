<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Listar todas as subtarefas de uma tarefa com filtros e paginação.
     */
    public function index(Request $request, $taskId)
    {
        try {
            $task = $request->user()->tasks()->whereNull('deleted_at')->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $query = $task->subtasks()->with('status');

            // Aplicar filtros (exemplo: search)
            if ($request->has('search')) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            }

            $subtasks = $query->paginate(10);

            return response()->json([
                'message' => 'Lista de subtarefas.',
                'subtasks' => $subtasks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar subtarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar todas as subtarefas de uma tarefa sem filtros ou paginação.
     */
    public function listAll(Request $request, $taskId)
    {
        try {
            $task = $request->user()->tasks()->whereNull('deleted_at')->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $subtasks = $task->subtasks()->with('status')->get();

            return response()->json([
                'message' => 'Lista de todas as subtarefas.',
                'subtasks' => $subtasks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar subtarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Criar uma nova subtarefa associada a uma tarefa.
     */
    public function store(Request $request, $taskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'nullable|exists:statuses,id',
            ], [
                'title.required' => 'O campo nome é obrigatório.',
                'title.max' => 'O nome deve ter no máximo 255 caracteres.',
                'status_id.exists' => 'O status selecionado é inválido.',
            ]);

            $subtask = $task->subtasks()->create($validated);

            // Registrar no histórico
            ActivityLogController::log(
                'create',
                'subtask',
                $subtask->id,
                null,
                $validated['status_id'],
                'Subtarefa criada.'
            );


            return response()->json([
                'message' => 'Subtarefa criada com sucesso.',
                'subtask' => $subtask,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar subtarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibir detalhes de uma subtarefa específica.
     */
    public function show(Request $request, $taskId, $subtaskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            //$subtask = $task->subtasks()->find($subtaskId);
            $subtask = $task->subtasks()
                ->with([
                    'status',
                    'activityLogs.previousStatus',
                    'activityLogs.newStatus',
                    'activityLogs.user'
                ])
                ->find($subtaskId);

            if (!$subtask) {
                return response()->json(['message' => 'Subtarefa não encontrada.'], 404);
            }

            return response()->json([
                'message' => 'Detalhes da subtarefa.',
                'subtask' => $subtask,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao exibir subtarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar uma subtarefa.
     */
    public function update(Request $request, $taskId, $subtaskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $subtask = $task->subtasks()->find($subtaskId);

            if (!$subtask) {
                return response()->json(['message' => 'Subtarefa não encontrada.'], 404);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => 'nullable|exists:statuses,id',
                'observation' => 'nullable|string|max:500',
            ], [
                'title.required' => 'O campo nome é obrigatório.',
                'title.max' => 'O nome deve ter no máximo 255 caracteres.',
                'status_id.exists' => 'O status selecionado é inválido.',
            ]);

            $originalStatus = $subtask->status_id;

            $subtask->update($validated);

            $observation = $validated['observation'] ?? 'Subtarefa atualizada.';

            // Registrar no histórico
            ActivityLogController::log(
                'update',
                'subtask',
                $subtask->id,
                $originalStatus,
                $validated['status_id'],
                $observation
            );

            return response()->json([
                'message' => 'Subtarefa atualizada com sucesso.',
                'subtask' => $subtask,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar subtarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Excluir uma subtarefa.
     */
    public function destroy(Request $request, $taskId, $subtaskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $subtask = $task->subtasks()->find($subtaskId);

            if (!$subtask) {
                return response()->json(['message' => 'Subtarefa não encontrada.'], 404);
            }

            $subtask->delete();

            return response()->json(['message' => 'Subtarefa excluída com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir subtarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Excluir todas as subtarefas de uma tarefa.
     */
    public function destroyAll(Request $request, $taskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $task->subtasks()->delete();

            return response()->json(['message' => 'Todas as subtarefas foram excluídas com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir subtarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request, $subtaskId)
    {
        try {
            $subtask = $request->user()->tasks()
                ->whereHas('subtasks', function ($query) use ($subtaskId) {
                    $query->where('id', $subtaskId);
                })->first();

            if (!$subtask) {
                return response()->json(['message' => 'Subtarefa não encontrada.'], 404);
            }

            $logs = $subtask->activityLogs()
                ->with(['previousStatus', 'newStatus', 'user'])
                ->get();

            return response()->json([
                'message' => 'Histórico da subtarefa.',
                'logs' => $logs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar histórico da subtarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
