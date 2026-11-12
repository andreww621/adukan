<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetSandi extends Model
{
    protected $table = 'reset_sandi';
    protected $guarded = [];
    protected $casts = ['kadaluarsa_at' => 'datetime'];

    const MASA_BERLAKU_MENIT = 30;
    const MAKS_PERCOBAAN     = 5;

    public function user() { return $this->belongsTo(User::class); }

    public function kodeAktif(): bool
    {
        return $this->kode !== null && $this->kadaluarsa_at && $this->kadaluarsa_at->isFuture();
    }

    public function getStatusAttribute(): string
    {
        if ($this->kode === null) return 'menunggu';
        return $this->kodeAktif() ? 'kode dibuat' : 'kedaluwarsa';
    }
}
