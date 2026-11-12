<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();     // pelapor
            $t->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('kategori', 20);
            $t->string('lokasi', 150);
            $t->text('deskripsi');
            $t->string('foto')->nullable();
            // menunggu > diverifikasi > disetujui > dikerjakan > menunggu_konfirmasi > selesai
            $t->string('status', 25)->default('menunggu');
            $t->unsignedBigInteger('dana')->nullable();
            $t->string('norek', 20)->nullable();
            $t->timestamp('dibayar_at')->nullable();
            $t->timestamps();
        });

        Schema::create('ratings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $t->unsignedTinyInteger('nilai');
            $t->text('komentar')->nullable();
            $t->timestamps();
        });

        Schema::create('settings', function (Blueprint $t) {
            $t->string('key')->primary();
            $t->text('value')->nullable();
        });

        Schema::table('users', function (Blueprint $t) {
            $t->integer('skor')->default(100);   // skor teknisi (maks 150)
            $t->integer('bonus')->default(0);    // kelebihan di atas 150, tampil sebagai +N
            $t->string('pin')->nullable();       // hash PIN pembayaran kepala sekolah
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn(['skor', 'bonus', 'pin']));
        Schema::dropIfExists('settings');
        Schema::dropIfExists('ratings');
        Schema::dropIfExists('pengaduan');
    }
};
