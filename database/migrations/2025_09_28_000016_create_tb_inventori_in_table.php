<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_inventori_in', function (Blueprint $table) {
            $table->id('id_inventori_in');
            $table->unsignedBigInteger('id_inventori');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_inventori')->references('id_inventori')->on('tb_inventori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_inventori_in');
    }
};
