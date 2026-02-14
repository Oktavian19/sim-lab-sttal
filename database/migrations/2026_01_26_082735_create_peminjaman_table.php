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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_peminjam')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_lab')->nullable()->constrained('laboratorium');

            $table->dateTime('start_time');
            $table->dateTime('end_time'); 

            $table->string('kegiatan', 255);
            $table->integer('jumlah_peserta');

            $table->enum('status_pengajuan', ['pending', 'disetujui', 'ditolak', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->index(['id_lab', 'start_time', 'end_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
