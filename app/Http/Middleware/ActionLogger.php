<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class ActionLogger
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check()) {
            $user = Auth::user();
            $method = $request->method(); // Método HTTP (GET, POST, etc.)
            $route = $request->path(); // Endpoint acessado
            $ipAddress = $request->ip(); // IP do usuário
            $userAgent = $request->header('User-Agent'); // Navegador/Dispositivo
            $details = $this->getActionDetails($method, $route); // Descrição da ação

            // Criar registro no log
            Log::create([
                'user_id' => $user->id,
                'action' => strtoupper($method), // Ação (ex.: POST, GET, etc.)
                'endpoint' => $route,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'details' => $details,
                'action_date' => now(),
            ]);
        }

        return $response;
    }

    /**
     * Definir uma descrição detalhada da ação (opcional).
     */
    protected function getActionDetails($method, $route)
    {
        $details = "Método $method acessou $route";

        // Personalizar ações específicas para categorias
        if (str_contains($route, 'categories')) {
            if ($method === 'POST') {
                $details = 'Categoria criada.';
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $details = 'Categoria atualizada.';
            } elseif ($method === 'DELETE') {
                $details = 'Categoria excluída.';
            }
        }

        // Personalizar ações específicas para status
        elseif (str_contains($route, 'statuses')) {
            if ($method === 'POST') {
                $details = 'Status criado.';
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $details = 'Status atualizado.';
            } elseif ($method === 'DELETE') {
                $details = 'Status excluído.';
            }
        }

        // Personalizar ações específicas para tarefas
        elseif (str_contains($route, 'tasks')) {
            if ($method === 'POST') {
                $details = 'Tarefa criada.';
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $details = 'Tarefa atualizada.';
            } elseif ($method === 'DELETE') {
                $details = 'Tarefa excluída.';
            }
        }

        // Personalizar ações específicas para subtarefas
        elseif (str_contains($route, 'subtasks')) {
            if ($method === 'POST') {
                $details = 'Subtarefa criada.';
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $details = 'Subtarefa atualizada.';
            } elseif ($method === 'DELETE') {
                $details = 'Subtarefa excluída.';
            }
        }

        // Ações genéricas (opcional)
        else {
            if ($method === 'POST') {
                $details = 'Registro criado.';
            } elseif ($method === 'PUT' || $method === 'PATCH') {
                $details = 'Registro atualizado.';
            } elseif ($method === 'DELETE') {
                $details = 'Registro excluído.';
            }
        }

        return $details;
    }
}
