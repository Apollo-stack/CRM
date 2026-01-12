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
        // Índices na tabela de leads
        Schema::table('leads', function (Blueprint $table) {
            $table->index('status'); // Consultas por status são muito frequentes
            $table->index('user_id'); // Filtros por usuário
            $table->index('client_id'); // Para joins com clients
            $table->index(['status', 'user_id']); // Índice composto para queries comuns
        });

        // Índices na tabela de clients
        Schema::table('clients', function (Blueprint $table) {
            $table->index('company_name'); // Filtros e buscas por empresa
            $table->index('email'); // Buscas por email
            $table->index('user_id'); // Filtros por usuário
        });

        // Índices na tabela de notes
        Schema::table('notes', function (Blueprint $table) {
            $table->index('lead_id'); // Para joins e buscas por lead
            $table->index('created_at'); // Para ordenação por data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['client_id']);
            $table->dropIndex(['status', 'user_id']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex(['company_name']);
            $table->dropIndex(['email']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('notes', function (Blueprint $table) {
            $table->dropIndex(['lead_id']);
            $table->dropIndex(['created_at']);
        });
    }
};
