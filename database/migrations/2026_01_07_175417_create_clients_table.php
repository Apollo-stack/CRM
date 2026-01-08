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
        Schema::create('clients', function (Blueprint $table) {
            $table->id(); // Cria o ID automático
            
            // Relacionamento: Um cliente pertence a um Vendedor (User)
            // O constrained() garante que só pode salvar se o usuario existir
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->string('company_name')->nullable(); // Pode ser vazio
            $table->string('phone', 20)->nullable();
            $table->string('email', 150)->nullable();
            
            $table->timestamps(); // Cria created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
