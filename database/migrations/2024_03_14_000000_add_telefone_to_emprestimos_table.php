<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            if (!Schema::hasColumn('emprestimos', 'telefone')) {
                $table->string('telefone', 20)->nullable()->after('status');
            }
            if (!Schema::hasColumn('emprestimos', 'cpf')) {
                $table->string('cpf', 20)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            if (Schema::hasColumn('emprestimos', 'telefone')) {
                $table->dropColumn('telefone');
            }
            if (Schema::hasColumn('emprestimos', 'cpf')) {
                $table->dropColumn('cpf');
            }
        });
    }
};
