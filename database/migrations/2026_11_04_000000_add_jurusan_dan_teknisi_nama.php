<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('jurusan', 60)->nullable()->after('role');   // keahlian/jurusan teknisi
        });

        Schema::table('pengaduan', function (Blueprint $t) {
            $t->string('teknisi_nama', 100)->nullable();            // salinan nama teknisi, supaya riwayat tetap utuh walau akun teknisi dihapus
        });
    }

    public function down(): void
    {
        Schema::table('pengaduan', fn (Blueprint $t) => $t->dropColumn('teknisi_nama'));
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('jurusan'));
    }
};
