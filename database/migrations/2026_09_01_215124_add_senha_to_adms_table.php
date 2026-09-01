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
    Schema::table('adm', function (Blueprint $table) {
        $table->string('Senha')->after('Email');
    });
}

public function down(): void
{
    Schema::table('adm', function (Blueprint $table) {
        $table->dropColumn('Senha');
    });
}
};
