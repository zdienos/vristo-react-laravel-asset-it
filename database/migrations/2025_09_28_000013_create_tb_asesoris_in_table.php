<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asesoris_in', function (Blueprint $table) {
            $table->id('id_asesoris_in');
            $table->unsignedBigInteger('id_asesoris');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_asesoris')->references('id_asesoris')->on('tb_asesoris')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asesoris_in');
    }
};
