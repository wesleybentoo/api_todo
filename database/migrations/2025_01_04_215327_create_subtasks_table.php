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
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('title', 150); // Título da subtarefa
            $table->text('description'); // Descrição detalhada
            $table->foreignId('status_id')->constrained('statuses'); // Chave estrangeira para statuses
            $table->foreignId('task_id')->constrained('tasks'); // Chave estrangeira para a tarefa principal
            $table->timestamps(); // Campos created_at e updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
