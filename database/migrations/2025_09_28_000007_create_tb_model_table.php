<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_model', function (Blueprint $table) {
            $table->id('id_model');
            $table->string('nama_model', 255);
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_manufaktur');
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('tb_kategori')->onDelete('cascade');
            $table->foreign('id_manufaktur')->references('id_manufaktur')->on('tb_manufaktur')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_model');
    }
};
