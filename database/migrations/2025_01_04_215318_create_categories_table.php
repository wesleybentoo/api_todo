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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('name', 100); // Nome da categoria
            $table->string('color', 7); // Cor associada (ex.: "#FF5733")
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Associado ao usuário
            $table->timestamps(); // Campos created_at e updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
