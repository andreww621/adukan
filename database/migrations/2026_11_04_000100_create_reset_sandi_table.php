<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reset_sandi', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $t->string('kode')->nullable();                 // hash kode sekali pakai, diisi saat admin menyetujui
            $t->timestamp('kadaluarsa_at')->nullable();
            $t->unsignedTinyInteger('percobaan')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reset_sandi');
    }
};
