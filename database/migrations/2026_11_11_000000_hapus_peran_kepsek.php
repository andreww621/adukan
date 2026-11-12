<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Peran Kepala Sekolah dihapus. Setelah diverifikasi, laporan langsung ke teknisi yang dipilih admin.
return new class extends Migration
{
    public function up(): void
    {
        // Laporan yang masih menunggu keputusan kepsek (belum ada teknisi) kembali ke admin untuk dipilihkan teknisi.
        DB::table('pengaduan')->where('status', 'diverifikasi')->whereNull('teknisi_id')->update(['status' => 'menunggu']);
        // Yang sudah "disetujui" tinggal menunggu teknisi memulai pekerjaan.
        DB::table('pengaduan')->where('status', 'disetujui')->update(['status' => 'diverifikasi']);

        DB::table('users')->where('role', 'kepsek')->delete();
        DB::table('settings')->whereIn('key', ['anggaran_aktif', 'anggaran_total'])->delete();

        foreach (['dana', 'norek', 'dibayar_at'] as $kolom) {
            if (Schema::hasColumn('pengaduan', $kolom)) {
                Schema::table('pengaduan', fn (Blueprint $t) => $t->dropColumn($kolom));
            }
        }
        if (Schema::hasColumn('users', 'pin')) {
            Schema::table('users', fn (Blueprint $t) => $t->dropColumn('pin'));
        }
    }

    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $t) {
            $t->unsignedBigInteger('dana')->nullable();
            $t->string('norek', 20)->nullable();
            $t->timestamp('dibayar_at')->nullable();
        });
        Schema::table('users', fn (Blueprint $t) => $t->string('pin')->nullable());
    }
};
