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
    Schema::table('artesaos', function (Blueprint $table) {
        // Adiciona o campo Senha (string para suportar o hash do bcrypt)
        $table->string('Senha')->after('Email'); 
    });
}

public function down(): void
{
    Schema::table('artesaos', function (Blueprint $table) {
        $table->dropColumn('Senha');
    });
}
};
