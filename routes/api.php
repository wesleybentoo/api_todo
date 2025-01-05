<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use Illuminate\Support\Facades\Route;

// Rotas públicas para autenticação de usuários
Route::post('/login', [UserController::class, 'login']); // Login
Route::post('/register', [UserController::class, 'store']); // Registro de usuário

// Rotas protegidas por middleware auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Usuários
    Route::apiResource('users', UserController::class); // CRUD padrão para usuários
    Route::post('/logout', [UserController::class, 'logout']); // Logout

    // CATEGORIAS
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']); // Listar categorias com filtros
        Route::get('/all', [CategoryController::class, 'listAll']); // Listar todas as categorias (sem paginação)
        Route::post('/', [CategoryController::class, 'store']); // Criar categoria
        Route::get('/{id}', [CategoryController::class, 'show']); // Exibir categoria
        Route::put('/{id}', [CategoryController::class, 'update']); // Atualizar categoria
        Route::delete('/{id}', [CategoryController::class, 'destroy']); // Excluir categoria
        Route::delete('/', [CategoryController::class, 'destroyAll']); // Excluir todas as categorias
    });

    // STATUS
    Route::prefix('statuses')->group(function () {
        Route::get('/', [StatusController::class, 'index']); // Listar status com filtros e paginação
        Route::get('/all', [StatusController::class, 'listAll']); // Listar todos os status (sem paginação)
        Route::post('/', [StatusController::class, 'store']); // Criar novo status
        Route::get('/{id}', [StatusController::class, 'show']); // Exibir um status específico
        Route::put('/{id}', [StatusController::class, 'update']); // Atualizar um status específico
        Route::delete('/{id}', [StatusController::class, 'destroy']); // Excluir um status específico
        Route::delete('/', [StatusController::class, 'destroyAll']); // Excluir todos os status
    });

    // TAREFAS
    Route::middleware('auth:sanctum')->prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index']); // Listar tarefas com filtros
        Route::get('/all', [TaskController::class, 'listAll']); // Listar todas as tarefas (sem paginação e sem filtros)
        Route::post('/', [TaskController::class, 'store']); // Criar uma nova tarefa
        Route::get('/{id}', [TaskController::class, 'show']); // Exibir detalhes de uma tarefa específica
        Route::put('/{id}', [TaskController::class, 'update']); // Atualizar uma tarefa
        Route::delete('/{id}', [TaskController::class, 'destroy']); // Excluir uma tarefa específica
        Route::delete('/', [TaskController::class, 'destroyAll']); // Excluir todas as tarefas do usuário
    });

    // SUB TASKS
    Route::middleware('auth:sanctum')->prefix('tasks/{taskId}/subtasks')->group(function () {
        Route::get('/', [SubTaskController::class, 'index']); // Listar subtarefas com filtros
        Route::get('/all', [SubTaskController::class, 'listAll']); // Listar todas as subtarefas
        Route::post('/', [SubTaskController::class, 'store']); // Criar uma nova subtarefa
        Route::get('/{subtaskId}', [SubTaskController::class, 'show']); // Exibir detalhes de uma subtarefa
        Route::put('/{subtaskId}', [SubTaskController::class, 'update']); // Atualizar uma subtarefa
        Route::delete('/{subtaskId}', [SubTaskController::class, 'destroy']); // Excluir uma subtarefa
        Route::delete('/', [SubTaskController::class, 'destroyAll']); // Excluir todas as subtarefas
    });



});
