<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Listar todas as tarefas do usuário autenticado com pesquisa e filtros.
     */
    public function index(Request $request)
    {
        try {
            $query = $request->user()->tasks()->with(['subtasks', 'status', 'category']);

            // Aplicar pesquisa
            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            }

            // Aplicar ordenação
            if ($request->has('sort_by')) {
                $query->orderBy($request->sort_by, $request->sort_order ?? 'asc');
            }

            $tasks = $query->paginate(10);

            return response()->json([
                'message' => 'Lista de tarefas com filtros.',
                'tasks' => $tasks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar tarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Listar todas as tarefas do usuário autenticado (sem paginação e sem pesquisa).
     */
    public function listAll(Request $request)
    {
        try {
            $tasks = $request->user()->tasks()->with(['subtasks', 'status', 'category'])->get();

            return response()->json([
                'message' => 'Lista de todas as tarefas.',
                'tasks' => $tasks,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao listar todas as tarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Criar uma nova tarefa para o usuário autenticado.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => [
                    'required',
                    Rule::exists('statuses', 'id')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user()->id);
                    }),
                ],
                'category_id' => [
                    'nullable',
                    Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user()->id);
                    }),
                ],
                'due_date' => 'nullable|date|after_or_equal:today',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.max' => 'O nome da tarefa deve ter no máximo 255 caracteres.',
                'status_id.required' => 'O campo status é obrigatório.',
                'status_id.exists' => 'O status selecionado é inválido ou não pertence a este usuário.',
                'category_id.exists' => 'A categoria selecionada é inválida ou não pertence a este usuário.',
                'due_date.date' => 'A data de vencimento deve ser uma data válida.',
                'due_date.after_or_equal' => 'A data de vencimento não pode ser anterior a hoje.',
            ]);

            $task = $request->user()->tasks()->create($validated);

            // Registrar no histórico
            ActivityLogController::log(
                'create',
                'task',
                $task->id,
                null,
                $validated['status_id'],
                'Tarefa criada.'
            );

            return response()->json([
                'message' => 'Tarefa criada com sucesso.',
                'task' => $task,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao criar a tarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibir uma tarefa específica do usuário autenticado.
     */
    public function show(Request $request, $id)
    {
        try {
            //$task = $request->user()->tasks()->with(['subtasks', 'status', 'category'])->find($id);
            $task = $request->user()->tasks()
                ->with([
                    'category',
                    'status',
                    'subtasks',
                    'activityLogs.previousStatus',
                    'activityLogs.newStatus',
                    'activityLogs.user'])
                ->find($id);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            return response()->json([
                'message' => 'Detalhes da tarefa.',
                'task' => $task,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao exibir a tarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Atualizar uma tarefa específica do usuário autenticado.
     */
    public function update(Request $request, $id)
    {
        try {
            $task = $request->user()->tasks()->find($id);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status_id' => [
                    'required',
                    Rule::exists('statuses', 'id')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user()->id);
                    }),
                ],
                'category_id' => [
                    'nullable',
                    Rule::exists('categories', 'id')->where(function ($query) use ($request) {
                        return $query->where('user_id', $request->user()->id);
                    }),
                ],
                'due_date' => 'nullable|date|after_or_equal:today',
                'observation' => 'nullable|string|max:500',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.max' => 'O nome da tarefa deve ter no máximo 255 caracteres.',
                'status_id.required' => 'O campo status é obrigatório.',
                'status_id.exists' => 'O status selecionado é inválido ou não pertence a este usuário.',
                'category_id.exists' => 'A categoria selecionada é inválida ou não pertence a este usuário.',
            ]);

            $originalStatus = $task->status_id;

            $task->update($validated);

            $observation = $validated['observation'] ?? 'Tarefa atualizada.';

            // Registrar no histórico
            ActivityLogController::log(
                'update',
                'task',
                $task->id,
                $originalStatus,
                $validated['status_id'],
                $observation
            );

            return response()->json([
                'message' => 'Tarefa atualizada com sucesso.',
                'task' => $task,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao atualizar a tarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Excluir uma tarefa específica do usuário autenticado.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $task = $request->user()->tasks()->find($id);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $task->delete();

            return response()->json(['message' => 'Tarefa excluída com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir a tarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Excluir todas as tarefas do usuário autenticado.
     */
    public function destroyAll(Request $request)
    {
        try {
            $tasks = $request->user()->tasks();

            if ($tasks->count() === 0) {
                return response()->json(['message' => 'Nenhuma tarefa encontrada para exclusão.'], 404);
            }

            $tasks->delete();

            return response()->json(['message' => 'Todas as tarefas foram excluídas com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao excluir todas as tarefas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request, $taskId)
    {
        try {
            $task = $request->user()->tasks()->find($taskId);

            if (!$task) {
                return response()->json(['message' => 'Tarefa não encontrada.'], 404);
            }

            $logs = $task->activityLogs()
                ->with(['previousStatus', 'newStatus', 'user'])
                ->get();

            return response()->json([
                'message' => 'Histórico da tarefa.',
                'logs' => $logs,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar histórico da tarefa.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
