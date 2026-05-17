<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_komponen_out', function (Blueprint $table) {
            $table->id('id_komponen_out');
            $table->unsignedBigInteger('id_komponen');
            $table->unsignedBigInteger('id_asset');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_komponen')->references('id_komponen')->on('tb_komponen')->onDelete('cascade');
            $table->foreign('id_asset')->references('id_asset')->on('tb_asset')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_komponen_out');
    }
};
