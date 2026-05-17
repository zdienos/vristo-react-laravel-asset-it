<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_asset_log', function (Blueprint $table) {
            $table->id('id_asset_log');
            $table->unsignedBigInteger('id_asset');
            $table->unsignedBigInteger('id_status');
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->string('assign_type', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_asset')->references('id_asset')->on('tb_asset')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('tb_status')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_asset_log');
    }
};
