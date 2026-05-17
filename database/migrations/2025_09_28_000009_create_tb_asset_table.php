<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asset', function (Blueprint $table) {
            $table->id('id_asset');
            $table->string('asset_tag', 50)->unique();
            $table->string('nama_asset', 255);
            $table->string('no_seri', 100)->nullable();
            $table->unsignedBigInteger('id_model');
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('id_pengguna')->nullable();
            $table->unsignedBigInteger('id_lokasi')->nullable();
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->string('assign_type', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_model')->references('id_model')->on('tb_model')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('tb_status')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('tb_pengguna')->onDelete('set null');
            $table->foreign('id_lokasi')->references('id_lokasi')->on('tb_lokasi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asset');
    }
};
