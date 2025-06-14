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
        Schema::table('conta_pagars', function (Blueprint $table) {
            $table->unsignedBigInteger('idUsuario')->nullable()->after('id');
        });

        // Adiciona a chave estrangeira depois de criar a coluna
        Schema::table('conta_pagars', function (Blueprint $table) {
            $table->foreign('idUsuario')->references('idUsuario')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conta_pagars', function (Blueprint $table) {
            $table->dropForeign(['idUsuario']);
            $table->dropColumn('idUsuario');
        });
    }
};
