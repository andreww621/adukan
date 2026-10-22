<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';
    protected $guarded = [];
    protected $casts = ['dibayar_at' => 'datetime'];

    const LABEL = [
        'menunggu'            => 'Menunggu verifikasi',
        'diverifikasi'        => 'Diverifikasi admin',
        'disetujui'           => 'Disetujui Kepsek',
        'dikerjakan'          => 'Sedang dikerjakan',
        'menunggu_konfirmasi' => 'Menunggu konfirmasi selesai',
        'selesai'             => 'Selesai',
    ];

    public function pelapor() { return $this->belongsTo(User::class, 'user_id'); }
    public function teknisi() { return $this->belongsTo(User::class, 'teknisi_id'); }

    public function getLabelAttribute(): string { return self::LABEL[$this->status] ?? $this->status; }
    public function getWarnaAttribute(): string { return $this->status === 'selesai' ? 's-done' : ($this->status === 'menunggu' ? 's-wait' : 's-proc'); }
    public function getFotoUrlAttribute(): ?string { return $this->foto ? asset('storage/' . $this->foto) : null; }
    public function getVideoUrlAttribute(): ?string { return $this->video ? asset('storage/' . $this->video) : null; }
    public function getDanaRpAttribute(): string { return $this->dana === null ? '-' : 'Rp ' . number_format($this->dana, 0, ',', '.'); }
}
