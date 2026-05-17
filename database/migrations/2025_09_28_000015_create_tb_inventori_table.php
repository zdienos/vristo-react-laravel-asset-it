<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_inventori', function (Blueprint $table) {
            $table->id('id_inventori');
            $table->unsignedBigInteger('id_model');
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('id_model')->references('id_model')->on('tb_model')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_inventori');
    }
};
