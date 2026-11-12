<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $guarded = [];
<<<<<<< HEAD
    protected $casts = ['dibayar_at' => 'datetime'];
=======

    const KATEGORI = ['Listrik', 'Sanitasi', 'Bangunan', 'Lain-lain'];
>>>>>>> d2ba4ff (Initial commit adukan vercel)

    const LABEL = [
        'menunggu'            => 'Menunggu verifikasi',
        'diverifikasi'        => 'Diverifikasi admin',
<<<<<<< HEAD
        'disetujui'           => 'Disetujui Kepsek',
=======
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        'dikerjakan'          => 'Sedang dikerjakan',
        'menunggu_konfirmasi' => 'Menunggu konfirmasi selesai',
        'selesai'             => 'Selesai',
    ];

    public function pelapor() { return $this->belongsTo(User::class, 'user_id'); }
    public function teknisi() { return $this->belongsTo(User::class, 'teknisi_id'); }

<<<<<<< HEAD
=======
    // Pengurutan + filter kategori yang dipakai semua daftar laporan. $urut: 'terbaru' | 'terlama' (kosong = pakai $default).
    public function scopeTampil($q, ?string $kategori, ?string $urut, string $default = 'terbaru', string $kolom = 'created_at')
    {
        if (in_array($kategori, self::KATEGORI, true)) {
            $q->where('kategori', $kategori);
        }
        $urut = in_array($urut, ['terbaru', 'terlama'], true) ? $urut : $default;

        return $urut === 'terlama' ? $q->orderBy($kolom)->orderBy('id') : $q->orderByDesc($kolom)->orderByDesc('id');
    }

    public function getInisialAttribute(): string { return $this->kategori === 'Lain-lain' ? '+' : mb_substr($this->kategori, 0, 1); }
    public function getTeknisiLabelAttribute(): ?string { return $this->teknisi->name ?? $this->teknisi_nama; }
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    public function getLabelAttribute(): string { return self::LABEL[$this->status] ?? $this->status; }
    public function getWarnaAttribute(): string { return $this->status === 'selesai' ? 's-done' : ($this->status === 'menunggu' ? 's-wait' : 's-proc'); }
    public function getFotoUrlAttribute(): ?string { return $this->foto ? asset('storage/' . $this->foto) : null; }
    public function getVideoUrlAttribute(): ?string { return $this->video ? asset('storage/' . $this->video) : null; }
<<<<<<< HEAD
    public function getDanaRpAttribute(): string { return $this->dana === null ? '-' : 'Rp ' . number_format($this->dana, 0, ',', '.'); }
=======
>>>>>>> d2ba4ff (Initial commit adukan vercel)
}
