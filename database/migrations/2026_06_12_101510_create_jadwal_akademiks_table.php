<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruangan_id')->constrained('ruangans')->onDelete('cascade');
            $table->enum('hari', [
                'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'
            ]);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('mata_kuliah');
            $table->string('dosen');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_akademiks');
    }
};
