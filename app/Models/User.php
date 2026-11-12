<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

// Tambahkan 'foto' ke dalam fillable di sini
#[Fillable(['name', 'email', 'password', 'foto', 'role', 'skor', 'bonus', 'pin'])]
=======
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

#[Fillable(['name', 'email', 'password'])]
>>>>>>> d2ba4ff (Initial commit adukan vercel)
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

<<<<<<< HEAD
    /**
     * Accessor untuk foto_url (menggunakan format modern Laravel Attribute)
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto ? asset('storage/' . $this->foto) : null,
        );
=======
    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    // Pengenal yang ditampilkan: NISN/NIP, atau email untuk akun petugas.
    public function getLoginIdAttribute(): string
    {
        return $this->username ?: (string) $this->email;
    }

    /**
     * Bentuk baku kata sandi awal: huruf kecil, tanpa spasi/tanda baca, nol di depan NISN dibuang.
     * "0086944390_Amalia Umi Hasanun" dan "86944390_amalia umi hasanun" menjadi sama.
     */
    public static function kanon(string $s): string
    {
        $s = preg_replace('/[^\p{L}\p{N}_]/u', '', mb_strtolower($s)) ?? '';

        return preg_replace('/^0+(?=\d)/', '', $s) ?? $s;
    }

    // Kata sandi awal = NISN_nama (guru: NIP_nama). Yang disimpan adalah bentuk bakunya.
    public static function sandiAwal(string $dasar, string $nama): string
    {
        return self::kanon($dasar . '_' . $nama);
    }

    /**
     * Selama kata sandi belum pernah diganti, huruf besar/kecil, spasi, dan tanda baca tidak dipermasalahkan.
     * Setelah diganti, kata sandi harus persis sama.
     */
    public function cekSandi(string $sandi): bool
    {
        if (Hash::check($sandi, $this->password)) {
            return true;
        }

        return $this->sandi_diganti_at === null && Hash::check(self::kanon($sandi), $this->password);
    }

    /**
     * Cari akun dari isian halaman masuk: email, NISN/NIP (nol di depan NISN boleh dihilangkan), atau nama lengkap.
     * Nama bisa kembar, jadi yang dikembalikan daftar; yang kata sandinya cocok dipilih oleh pemanggil.
     */
    public static function kandidatLogin(string $isian): Collection
    {
        $isian = trim((string) preg_replace('/\s+/', ' ', $isian));
        $kecil = mb_strtolower($isian);

        if (str_contains($isian, '@')) {
            return static::whereRaw('LOWER(email) = ?', [$kecil])->take(5)->get();
        }

        $angka = preg_replace('/[\s.\-]/', '', $isian);
        if ($angka !== '' && ctype_digit($angka)) {
            $username = strlen($angka) <= 10 ? str_pad($angka, 10, '0', STR_PAD_LEFT) : $angka;

            return static::where('username', $username)->take(5)->get();
        }

        return static::where('username', $kecil)
            ->orWhereRaw('LOWER(name) = ?', [$kecil])
            ->take(8)->get();
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
<<<<<<< HEAD
            'password' => 'hashed',
        ];
    }
}
=======
            'sandi_diganti_at'  => 'datetime',
            'password' => 'hashed',
        ];
    }
}
>>>>>>> d2ba4ff (Initial commit adukan vercel)
