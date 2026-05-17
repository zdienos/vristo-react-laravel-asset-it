<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_komponen_in', function (Blueprint $table) {
            $table->id('id_komponen_in');
            $table->unsignedBigInteger('id_komponen');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_komponen')->references('id_komponen')->on('tb_komponen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_komponen_in');
    }
};
