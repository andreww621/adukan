<?php

namespace App\Http\Controllers;

use App\Models\ResetSandi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Lupa kata sandi tanpa email (hosting gratis tidak bisa mengirim surel):
 * 1) pengguna mengirim permintaan dengan NISN/NIP-nya (atau email untuk akun petugas),
 * 2) admin memeriksa identitas lalu membuat kode sekali pakai (berlaku 30 menit) dan menyampaikannya langsung,
 * 3) pengguna memasukkan NISN/NIP + kode + kata sandi baru.
 */
class SandiController extends Controller
{
    public function lupa() { return view('sandi.lupa'); }

    public function kirim(Request $request)
    {
        $data = $request->validate(['identitas' => ['required', 'string', 'max:150']]);

        $user = $this->akun($data['identitas']);
        if ($user) {
            $reset = ResetSandi::firstOrNew(['user_id' => $user->id]);
            // Kode yang masih berlaku tidak ditimpa, supaya permintaan ulang orang lain tidak membatalkannya.
            if (! $reset->exists || ! $reset->kodeAktif()) {
                $reset->fill(['kode' => null, 'kadaluarsa_at' => null, 'percobaan' => 0])->save();
            }
        }

        // Jawaban sama untuk akun terdaftar maupun tidak, agar daftar akun tidak bisa ditebak.
        return redirect()->route('sandi.atur')->withInput(['identitas' => $data['identitas']])
            ->with('status', 'Permintaan dikirim. Hubungi admin sekolah untuk mendapatkan kode, lalu isi di halaman ini.');
    }

    public function atur() { return view('sandi.atur'); }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'identitas' => ['required', 'string', 'max:150'],
            'kode'     => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $gagal = fn () => back()->withInput($request->only('identitas'))
            ->withErrors(['kode' => 'Kode salah, sudah kedaluwarsa, atau permintaan belum disetujui admin.']);

        $user  = $this->akun($data['identitas']);
        $reset = $user ? ResetSandi::where('user_id', $user->id)->first() : null;

        if (! $reset || ! $reset->kodeAktif() || $reset->percobaan >= ResetSandi::MAKS_PERCOBAAN) {
            return $gagal();
        }
        if (! Hash::check(Str::upper(trim($data['kode'])), $reset->kode)) {
            $reset->increment('percobaan');
            return $gagal();
        }

        DB::transaction(function () use ($user, $reset, $data) {
            $user->password = $data['password'];   // di-hash otomatis oleh cast "hashed"
            $user->sandi_diganti_at = now();
            $user->setRememberToken(Str::random(60));
            $user->save();
            $reset->delete();
        });

        return redirect()->route('login')->with('status', 'Kata sandi berhasil diganti. Silakan masuk.');
    }

    // Hanya dipakai bila isiannya menunjuk tepat satu akun (nama kembar harus memakai NISN/NIP).
    private function akun(string $isian): ?User
    {
        $cocok = User::kandidatLogin($isian);

        return $cocok->count() === 1 ? $cocok->first() : null;
    }
}
