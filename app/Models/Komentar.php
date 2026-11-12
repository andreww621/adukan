<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = 'komentar';
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }

    // Semua pesan dalam percakapan ini (pesan pertama = komentar pelapor).
    public function obrolan() { return $this->hasMany(KomentarPesan::class, 'komentar_id'); }

    public function pesanTerakhir() { return $this->hasOne(KomentarPesan::class, 'komentar_id')->latestOfMany(); }
}
