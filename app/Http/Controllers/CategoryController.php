<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Listar todas as categorias do usuário autenticado.
     */
    public function index(Request $request)
    {
        // Recuperar as categorias do usuário autenticado
        $query = $request->user()->categories();

        // Filtro por nome (opcional)
        if ($request->has('name') && $request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Ordenação (opcional)
        if ($request->has('sort_by') && in_array($request->sort_by, ['name', 'created_at'])) {
            $sortOrder = $request->get('sort_order', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort_by, $sortOrder);
        }

        // Paginação
        $categories = $query->paginate(10); // 10 categorias por página

        return response()->json($categories, 200);
    }

    /**
     * Listar todas as categorias de um usuário (sem paginação e sem pesquisa).
     */
    public function listAll(Request $request)
    {
        // Recuperar todos os status do usuário autenticado
        $category = $request->user()->categories;

        return response()->json([
            'message' => 'Lista de todas as categorias do usuário.',
            'categories' => $category,
        ], 200);
    }

    /**
     * Criar uma nova categoria.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:categories,name,NULL,id,user_id,' . $request->user()->id,
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O campo nome deve ser uma string.',
                'name.max' => 'O nome da categoria deve ter no máximo 100 caracteres.',
                'name.unique' => 'Já existe uma categoria com este nome.',
                'color.string' => 'A cor deve ser uma string.',
                'color.max' => 'A cor deve ter no máximo 7 caracteres.',
                'color.regex' => 'A cor deve estar no formato hexadecimal (exemplo: #FFFFFF).',
            ]);

            // Adicionar cor padrão se não for fornecida
            $validated['color'] = $validated['color'] ?? '#FFFFFF';

            $category = $request->user()->categories()->create($validated);

            return response()->json([
                'message' => 'Categoria criada com sucesso.',
                'category' => $category,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Exibir uma categoria específica.
     */
    public function show($id, Request $request)
    {
        $category = $request->user()->categories()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        return response()->json($category, 200);
    }

    /**
     * Atualizar uma categoria existente.
     */
    public function update(Request $request, $id)
    {
        try {
            $category = $request->user()->categories()->find($id);

            if (!$category) {
                return response()->json(['message' => 'Categoria não encontrada.'], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:100|unique:categories,name,' . $id . ',id,user_id,' . $request->user()->id,
                'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/', // Cor opcional no formato hexadecimal
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.string' => 'O campo nome deve ser uma string.',
                'name.max' => 'O nome da categoria deve ter no máximo 100 caracteres.',
                'name.unique' => 'Já existe uma categoria com este nome.',
                'color.string' => 'A cor deve ser uma string.',
                'color.max' => 'A cor deve ter no máximo 7 caracteres.',
                'color.regex' => 'A cor deve estar no formato hexadecimal (exemplo: #FFFFFF).',
            ]);

            // Adicionar cor padrão se não for fornecida
            $validated['color'] = $validated['color'] ?? '#FFFFFF';

            $category->update($validated);

            return response()->json([
                'message' => 'Categoria atualizada com sucesso.',
                'category' => $category,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Excluir uma categoria existente.
     */
    public function destroy($id, Request $request)
    {
        $category = $request->user()->categories()->find($id);

        if (!$category) {
            return response()->json(['message' => 'Categoria não encontrada.'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Categoria excluída com sucesso.'], 200);
    }

    public function destroyAll(Request $request)
    {
        // Recuperar as categorias do usuário autenticado
        $categories = $request->user()->categories();

        // Verificar se o usuário possui categorias
        if ($categories->count() === 0) {
            return response()->json(['message' => 'Nenhuma categoria encontrada para exclusão.'], 404);
        }

        // Excluir todas as categorias
        $categories->delete();

        return response()->json(['message' => 'Todas as categorias foram excluídas com sucesso.'], 200);
    }
}
