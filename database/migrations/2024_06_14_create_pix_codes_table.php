<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pix_codes', function (Blueprint $table) {
            $table->id();
            $table->string('pix_id')->unique();
            $table->foreignId('conta_receber_id')->constrained('conta_recebers');
            $table->decimal('valor', 10, 2);
            $table->string('empresa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pix_codes');
    }
};
