<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asset_file', function (Blueprint $table) {
            $table->id('id_asset_file');
            $table->unsignedBigInteger('id_asset');
            $table->string('nama_file', 255);
            $table->string('path', 500);
            $table->timestamps();

            $table->foreign('id_asset')->references('id_asset')->on('tb_asset')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asset_file');
    }
};
