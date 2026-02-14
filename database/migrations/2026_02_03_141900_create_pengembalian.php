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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjaman')->onDelete('cascade');
            $table->unsignedBigInteger('id_petugas');

            $table->dateTime('tanggal_kembali_realisasi');
            $table->enum('status_pengembalian', ['tepat_waktu', 'terlambat']);
            $table->string('denda_atau_sanksi', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_petugas')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
