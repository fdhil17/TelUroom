<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->date('tanggal_reservasi');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->text('keperluan');
            $table->enum('status', [
                'menunggu_ssc',
                'menunggu_logistik',
                'disetujui',
                'ditolak_ssc',
                'ditolak_logistik'
            ])->default('menunggu_ssc');
            $table->text('catatan_ssc')->nullable();
            $table->text('catatan_logistik')->nullable();
            $table->foreignId('approved_by_ssc')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by_logistik')->nullable()->constrained('users')->onDelete('set null');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
