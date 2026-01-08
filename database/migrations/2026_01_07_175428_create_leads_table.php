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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            // Relacionamento com Cliente
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            
            // Relacionamento com Vendedor (Dono do negócio)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('title'); // Ex: "Venda de Software"
            $table->decimal('value', 10, 2)->default(0); // 10 digitos, 2 decimais
            
            // Status simples por enquanto (depois podemos melhorar)
            $table->string('status')->default('new'); // new, negotiation, won, lost
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
