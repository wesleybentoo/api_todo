<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Listar todos os usuários.
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * Registrar um novo usuário.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]+$/',
            ], [
                'name.required' => 'O campo nome é obrigatório.',
                'name.regex' => 'O nome deve conter apenas letras e espaços.',
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.',
                'email.unique' => 'O e-mail já está em uso.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.max' => 'A senha deve ter no máximo 20 caracteres.',
                'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Usuário registrado com sucesso.',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Fazer login do usuário.
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ], [
                'email.required' => 'O campo e-mail é obrigatório.',
                'email.email' => 'O e-mail deve ser válido.',
                'password.required' => 'O campo senha é obrigatório.',
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json(['message' => 'Credenciais inválidas.'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Login realizado com sucesso.',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Exibir um usuário específico.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Atualizar os dados de um usuário.
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'Usuário não encontrado.'], 404);
            }

            $validated = $request->validate([
                'name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'nullable|email|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8|max:20|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d@$!%*?&]+$/',
            ], [
                'name.regex' => 'O nome deve conter apenas letras e espaços.',
                'email.email' => 'O e-mail deve ser válido.',
                'email.unique' => 'O e-mail já está em uso.',
                'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
                'password.max' => 'A senha deve ter no máximo 20 caracteres.',
                'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número.',
            ]);

            $user->update([
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
                'password' => isset($validated['password']) ? Hash::make($validated['password']) : $user->password,
            ]);

            return response()->json(['message' => 'Usuário atualizado com sucesso.', 'user' => $user], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Os dados fornecidos são inválidos.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Excluir um usuário.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuário excluído com sucesso.'], 200);
    }

    /**
     * Logout do usuário.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
    }
}
