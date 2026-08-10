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
    Schema::create('artesao', function (Blueprint $table) {
        $table->id('ID_Artesao');
        $table->string('Nome');
        $table->string('Telefone');
        $table->string('Email')->unique();
        $table->string('Endereco')->nullable();
        $table->string('Nivel')->nullable();
        $table->string('StatusAprovacao')->nullable();
        $table->string('Aprovado_por')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesao');
    }
};
