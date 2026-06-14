<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_ruangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')->constrained('reservasis')->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('keperluan');
            $table->string('nim')->nullable();
            $table->string('prodi')->nullable();
            $table->enum('status', ['disetujui', 'ditolak_ssc', 'ditolak_logistik']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_ruangans');
    }
};
