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
        Schema::create('logs', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->foreignId('user_id')->nullable()->constrained('users'); // Relacionado ao usuário que realizou a ação
            $table->string('action', 255); // Descrição da ação realizada
            $table->string('endpoint')->nullable(); // Rota ou endpoint acessado
            $table->string('ip_address', 45)->nullable(); // IP do usuário
            $table->string('user_agent')->nullable(); // Informações sobre o navegador/dispositivo
            $table->text('details')->nullable(); // Informações adicionais sobre a ação
            $table->timestamp('action_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Data e hora da ação
            $table->timestamps(); // Campos created_at e updated_at
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
