<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_inventori_out', function (Blueprint $table) {
            $table->id('id_inventori_out');
            $table->unsignedBigInteger('id_inventori');
            $table->unsignedBigInteger('id_pengguna');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_inventori')->references('id_inventori')->on('tb_inventori')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tb_pengguna')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_inventori_out');
    }
};
