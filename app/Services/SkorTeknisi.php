<?php

namespace App\Services;

use App\Models\User;

/**
 * Sistem poin teknisi. Ubah konstanta di bawah kalau aturannya berubah.
 */
class SkorTeknisi
{
    const AWAL    = 100;  // skor awal (default kolom users.skor)
    const MAKS    = 150;  // batas skor
    const HADIAH  = 5;    // pekerjaan terkonfirmasi selesai
    const HUKUMAN = 10;   // menandai selesai padahal belum

    public static function hadiah(User $teknisi): void
    {
        $teknisi->skor += self::HADIAH;

        // Kelebihan di atas 150 ditampung sebagai bonus (+N).
        if ($teknisi->skor > self::MAKS) {
            $teknisi->bonus += $teknisi->skor - self::MAKS;
            $teknisi->skor = self::MAKS;
        }

        $teknisi->save();
    }

    public static function hukuman(User $teknisi): void
    {
        // Bonus dipotong lebih dulu, sisanya baru mengurangi skor.
        $sisa = self::HUKUMAN;
        $potongBonus = min($teknisi->bonus, $sisa);
        $teknisi->bonus -= $potongBonus;
        $teknisi->skor = max(0, $teknisi->skor - ($sisa - $potongBonus));

        $teknisi->save();
    }
}
