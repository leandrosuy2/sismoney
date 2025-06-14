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
                $table->string('telefone')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            if (Schema::hasColumn('emprestimos', 'telefone')) {
                $table->dropColumn('telefone');
            }
        });
    }
};
