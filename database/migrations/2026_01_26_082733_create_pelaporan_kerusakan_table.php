<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelaporan_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alat');
            $table->unsignedBigInteger('id_pelapor');

            $table->dateTime('tanggal_lapor')->useCurrent();
            $table->text('deskripsi_kerusakan');
            $table->string('foto_bukti', 255);

            $table->enum('status_tindak_lanjut', ['menunggu', 'sedang_diperbaiki', 'selesai', 'afkir'])->default('menunggu');
            $table->text('keterangan_perbaikan')->nullable();
            $table->timestamps();

            $table->foreign('id_alat')->references('id')->on('alat')->onDelete('cascade');
            $table->foreign('id_pelapor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporan_kerusakan');
    }
};
