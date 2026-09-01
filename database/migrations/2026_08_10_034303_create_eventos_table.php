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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id('ID_Evento');
            $table->foreignId('idADM')->nullable()->constrained('adm', 'Id_ADM')->onDelete('set null');
            $table->string('Nome');
            
            // Vagas e Especialidades
            $table->integer('Vagas')->default(0);
            $table->json('especialidades_vagas')->nullable(); // Guarda o JSON das vagas por especialidade
            
            // Endereço padronizado igual Artesão
            $table->string('Rua')->nullable();
            $table->string('Numero')->nullable();
            $table->string('Bairro')->nullable();
            
            // Detalhes e Datas
            $table->text('Descricao')->nullable();
            $table->dateTime('Dia'); // Data/Hora de Início
            $table->dateTime('DataFim')->nullable(); // Data/Hora de Término
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};