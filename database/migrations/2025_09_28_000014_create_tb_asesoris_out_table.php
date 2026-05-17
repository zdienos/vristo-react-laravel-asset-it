<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asesoris_out', function (Blueprint $table) {
            $table->id('id_asesoris_out');
            $table->unsignedBigInteger('id_asesoris');
            $table->unsignedBigInteger('id_pengguna');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_asesoris')->references('id_asesoris')->on('tb_asesoris')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tb_pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asesoris_out');
    }
};
