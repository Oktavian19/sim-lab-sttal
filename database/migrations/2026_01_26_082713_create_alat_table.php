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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat', 100);
            $table->string('merk', 100)->nullable();
            $table->integer('tahun_pengadaan')->nullable();
            $table->foreignId('lokasi')->nullable()->constrained('laboratorium')->nullOnDelete();
            $table->enum('kondisi', ['baik', 'rusak', 'maintenance']);
            $table->string('foto_alat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
