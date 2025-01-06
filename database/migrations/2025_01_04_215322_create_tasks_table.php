<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Nome da tarefa
            $table->text('description')->nullable(); // Descrição da tarefa
            $table->unsignedBigInteger('user_id'); // ID do usuário (relacionamento)
            $table->unsignedBigInteger('status_id'); // ID do status (relacionamento)
            $table->unsignedBigInteger('category_id')->nullable(); // ID da categoria (relacionamento)
            $table->date('due_date')->nullable(); // Data de vencimento
            $table->timestamps();
            $table->softDeletes();

            // Relacionamentos
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
