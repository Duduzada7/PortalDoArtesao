<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('artesao', function (Blueprint $table) {
            // 1. Remove a coluna antiga de endereço (se ela existir)
            if (Schema::hasColumn('artesao', 'Endereco')) {
                $table->dropColumn('Endereco');
            }

            // 2. Cria os novos campos de endereço detalhado
            $table->string('Rua')->nullable()->after('Email');
            $table->string('Numero', 20)->nullable()->after('Rua');
            $table->string('Bairro')->nullable()->after('Numero');

            // 3. Cria o campo que controla a Fila de Prioridade
            $table->integer('posicao_fila')->default(0)->after('StatusAprovacao');
        });
    }

    /**
     * Reverte as alterações caso você dê um rollback.
     */
    public function down(): void
    {
        Schema::table('artesao', function (Blueprint $table) {
            $table->string('Endereco')->nullable();
            $table->dropColumn(['Rua', 'Numero', 'Bairro', 'posicao_fila']);
        });
    }
};