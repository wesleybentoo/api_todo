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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('name', 50); // Nome do status
            $table->text('description')->nullable(); // Descrição do status
            $table->string('color', 7); // Cor associada (ex.: "#FF5733")
            $table->unsignedInteger('order')->default(1);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Associado ao usuário
            $table->boolean('is_finalized')->default(false); // Define o campo com valor padrão
            $table->timestamps(); // Campos de auditoria
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statuses');
    }
};
