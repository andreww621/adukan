<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('username', 80)->nullable()->unique()->after('name');   // siswa: NISN, guru/staf: NIP
            $t->string('info', 100)->nullable()->after('role');                // siswa: rombel (XII DPIB A), guru: mata pelajaran
            $t->timestamp('sandi_diganti_at')->nullable();                     // kosong = masih memakai kata sandi awal
        });

        // Email tidak lagi wajib (siswa dan guru masuk dengan NISN/NIP).
        Schema::table('users', function (Blueprint $t) {
            $t->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['username']);
            $t->dropColumn(['username', 'info', 'sandi_diganti_at']);
        });
    }
};
