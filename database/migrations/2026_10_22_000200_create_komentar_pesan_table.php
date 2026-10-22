<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Setiap komentar adalah satu percakapan (thread); pesan-pesannya disimpan di sini.
        Schema::create('komentar_pesan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komentar_id')->constrained('komentar')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // pengirim
            $table->string('dari', 10);                 // pelapor | admin
            $table->text('isi');
            $table->timestamp('dibaca_at')->nullable(); // dibaca oleh pihak lawan
            $table->timestamps();
            $table->index(['komentar_id', 'id']);
        });

        // Komentar lama (sebelum fitur chat) menjadi pesan pertama percakapannya.
        foreach (DB::table('komentar')->get() as $k) {
            DB::table('komentar_pesan')->insert([
                'komentar_id' => $k->id,
                'user_id'     => $k->user_id,
                'dari'        => 'pelapor',
                'isi'         => $k->pesan,
                'created_at'  => $k->created_at,
                'updated_at'  => $k->created_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_pesan');
    }
};
