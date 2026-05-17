<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('nik', 50)->unique();
            $table->string('nama_pengguna', 255);
            $table->unsignedBigInteger('id_departemen');
            $table->unsignedBigInteger('id_lokasi');
            $table->string('telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_departemen')->references('id_departemen')->on('tb_departemen')->onDelete('cascade');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('tb_lokasi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_pengguna');
    }
};
