<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori', 255);
            $table->unsignedBigInteger('id_tipe');
            $table->timestamps();

            $table->foreign('id_tipe')->references('id_tipe')->on('tb_tipe')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_kategori');
    }
};
