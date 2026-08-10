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
    Schema::create('artesao_especialidade', function (Blueprint $table) {
        $table->foreignId('ID_Artesao')->constrained('artesao', 'ID_Artesao')->onDelete('cascade');
        $table->foreignId('ID_Especialidade')->constrained('especialidades', 'ID_Especialidade')->onDelete('cascade');
        $table->primary(['ID_Artesao', 'ID_Especialidade']);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artesao_especialidade');
    }
};
