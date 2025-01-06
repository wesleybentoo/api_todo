<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StatusController extends Controller
{
    /**
     * Listar todos os status do usuário autenticado com pesquisa
     */
    public function index(Request $request)
    {
        $query = $request->user()->statuses();

        // Filtro por nome
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Filtro por descrição
        if ($request->has('description') && $request->description) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Ordenação
        if ($request->has('sort_by') && in_array($request->sort_by, ['name', 'created_at'])) {
            $sortOrder = $request->get('sort_order', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $sortOrder);
        }

        // Finalizado
        if ($request->has('is_finalized')) {
            $query->where('is_finalized', $request->is_finalized);
        }

        // Paginação
        $statuses = $query->paginate(10);

        return response()->json($statuses, 200);
    }

    /**
     * Listar todos os status de um usuário (sem paginação e sem pesquisa).
     */
    public function listAll(Request $request)
    {
        // Recuperar todos os status do usuário autenticado
        $statuses = $request->user()->statuses()->orderBy('order', 'asc')->get();

        return response()->json([
            'message' => 'Lista de todos os status do usuário.',
            'statuses' => $statuses,
        ], 200);
    }

    /**
     * Criar um novo status.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:statuses,name,NULL,id,user_id,' . $request->user()->id,
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/', // Cor opcional no formato hexadecimal
                'description' => 'nullable|string|max:1000', // Descrição opcional
                'order' => 'nullable|integer|min:1|unique:statuses,order,NULL,id,user_id,' . $request->user()->id,
                'is_finalized' => 'nullable|boolean',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O campo nome deve ser uma string.',
                'name.max' => 'O nome do status deve ter no máximo 100 caracteres.',
                'name.unique' => 'Já existe um status com este nome.',
                'color.string' => 'A cor deve ser uma string.',
                'color.max' => 'A cor deve ter no máximo 7 caracteres.',
                'color.regex' => 'A cor deve estar no formato hexadecimal (exemplo: #FFFFFF).',
                'description.string' => 'A descrição deve ser uma string.',
                'description.max' => 'A descrição deve ter no máximo 1000 caracteres.',
                'order.integer' => 'The order field must be an integer.',
                'order.min' => 'The order field must be at least 1.',
                'order.unique' => 'A status with this order already exists.',
            ]);

            // Adicionar cor padrão se não for fornecida
            $validated['color'] = $validated['color'] ?? '#FFFFFF';

            if (!isset($validated['order'])) {
                $validated['order'] = $request->user()->statuses()->max('order') + 1;
            }

            $status = $request->user()->statuses()->create($validated);

            return response()->json([
                'message' => 'Status criado com sucesso.',
                'status' => $status,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Exibir um status específico.
     */
    public function show($id, Request $request)
    {
        $status = $request->user()->statuses()->find($id);

        if (!$status) {
            return response()->json(['message' => 'Status não encontrado.'], 404);
        }

        return response()->json($status, 200);
    }

    /**
     * Atualizar um status existente.
     */
    public function update(Request $request, $id)
    {
        try {
            $status = $request->user()->statuses()->find($id);

            if (!$status) {
                return response()->json(['message' => 'Status não encontrado.'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:statuses,name,' . $id . ',id,user_id,' . $request->user()->id,
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/', // Cor opcional no formato hexadecimal
                'description' => 'nullable|string|max:1000', // Descrição opcional
                'order' => 'nullable|integer|min:1|unique:statuses,order,' . $id . ',id,user_id,' . $request->user()->id,
                'is_finalized' => 'nullable|boolean',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O campo nome deve ser uma string.',
                'name.max' => 'O nome do status deve ter no máximo 100 caracteres.',
                'name.unique' => 'Já existe um status com este nome.',
                'color.string' => 'A cor deve ser uma string.',
                'color.max' => 'A cor deve ter no máximo 7 caracteres.',
                'color.regex' => 'A cor deve estar no formato hexadecimal (exemplo: #FFFFFF).',
                'description.string' => 'A descrição deve ser uma string.',
                'description.max' => 'A descrição deve ter no máximo 1000 caracteres.',
                'order.integer' => 'The order field must be an integer.',
                'order.min' => 'The order field must be at least 1.',
                'order.unique' => 'A status with this order already exists.',
            ]);

            $status->update($validated);

            return response()->json([
                'message' => 'Status atualizado com sucesso.',
                'status' => $status,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Excluir um status existente.
     */
    public function destroy($id, Request $request)
    {
        $status = $request->user()->statuses()->find($id);

        if (!$status) {
            return response()->json(['message' => 'Status não encontrado.'], 404);
        }

        $status->delete();

        return response()->json(['message' => 'Status excluído com sucesso.'], 200);
    }


    /**
     * Excluir todos os status do usuário autenticado.
     */
    public function destroyAll(Request $request)
    {
        // Recuperar todos os status do usuário autenticado
        $statuses = $request->user()->statuses();

        // Verificar se há status para excluir
        if ($statuses->count() === 0) {
            return response()->json([
                'message' => 'Nenhum status encontrado para exclusão.'
            ], 404);
        }

        // Excluir todos os status
        $statuses->delete();

        return response()->json([
            'message' => 'Todos os status foram excluídos com sucesso.'
        ], 200);
    }
}
