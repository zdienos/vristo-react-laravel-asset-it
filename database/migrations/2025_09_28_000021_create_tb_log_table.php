<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_log', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('log_user', 255);
            $table->string('log_tipe', 50);
            $table->string('log_aksi', 100);
            $table->unsignedBigInteger('log_item')->nullable();
            $table->unsignedBigInteger('log_assign_to')->nullable();
            $table->string('log_assign_type', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_log');
    }
};
