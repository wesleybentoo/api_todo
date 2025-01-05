<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->foreignId('task_id')->nullable()->constrained('tasks'); // Chave estrangeira para tasks (opcional)
            $table->foreignId('subtask_id')->nullable()->constrained('subtasks'); // Chave estrangeira para subtasks (opcional)
            $table->foreignId('status_previous_id')->nullable()->constrained('statuses'); // Status anterior (opcional)
            $table->foreignId('status_new_id')->constrained('statuses'); // Novo status
            $table->text('observation')->nullable(); // Observações sobre a alteração
            $table->foreignId('user_id')->constrained('users'); // Usuário que realizou a alteração
            $table->timestamp('changed_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Data/hora da alteração
            $table->timestamps(); // Campos created_at e updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
