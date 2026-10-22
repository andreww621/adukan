<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomentarPesan extends Model
{
    protected $table = 'komentar_pesan';
    protected $guarded = [];
    protected $casts = ['dibaca_at' => 'datetime'];

    public function komentar() { return $this->belongsTo(Komentar::class); }
}
