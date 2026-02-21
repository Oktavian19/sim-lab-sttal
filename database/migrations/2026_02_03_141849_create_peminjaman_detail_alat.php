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
        Schema::create('peminjaman_detail_alat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah');
            $table->string('kondisi_saat_pinjam', 50)->nullable();
            $table->string('kondisi_saat_kembali', 50)->nullable();
            $table->timestamps();

            $table->foreign('id_alat')->references('id')->on('alat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_detail_alat');
    }
};
