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
        $table->string('Classificacao')->nullable();
        $table->integer('Vagas')->nullable();
        $table->string('Localizacao')->nullable();
        $table->dateTime('Dia')->nullable();
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
