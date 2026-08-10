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
    Schema::create('candidatura', function (Blueprint $table) {
        $table->foreignId('ID_Artesao')->constrained('artesao', 'ID_Artesao')->onDelete('cascade');
        $table->foreignId('ID_Evento')->constrained('eventos', 'ID_Evento')->onDelete('cascade');
        $table->string('StatusDaCandidatura')->nullable();
        $table->primary(['ID_Artesao', 'ID_Evento']);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidatura');
    }
};
