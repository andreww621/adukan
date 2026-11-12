<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
<<<<<<< HEAD

class ProfilController extends Controller
{
    public function foto(Request $request)
    {
        $request->validate(
            ['foto' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120']],
            [
                'foto.required' => 'Pilih foto terlebih dahulu.',
                'foto.image'    => 'File harus berupa gambar.',
                'foto.mimes'    => 'Format foto harus JPG, PNG, atau WEBP.',
                'foto.max'      => 'Ukuran foto maksimal 5 MB.',
                'foto.uploaded' => 'Foto gagal diunggah. Coba foto dengan ukuran maksimal 5 MB.',
            ]
        );

        $user = $request->user();
        $lama = $user->foto;

        $user->forceFill(['foto' => $request->file('foto')->store('profil', 'public')])->save();

        if ($lama) {
            Storage::disk('public')->delete($lama);
        }

        return back()->with('status', 'Foto profil diperbarui');
    }

    public function hapus(Request $request)
    {
=======
use Illuminate\Support\Str;

class ProfilController extends Controller
{
    public function hapus(Request $request)
    {
        $user = $request->user();

        if ($user->foto && file_exists(public_path($user->foto))) {
            @unlink(public_path($user->foto));
            $user->forceFill(['foto' => null])->save();
        }

        return back()->with('status', 'Foto profil dihapus');
    }

    public function hapus(Request $request)
    {   
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        $user = $request->user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->forceFill(['foto' => null])->save();
        }

        return back()->with('status', 'Foto profil dihapus');
    }
<<<<<<< HEAD
=======

    // Ganti kata sandi dari halaman profil (hanya untuk yang sudah masuk).
    public function sandi(Request $request)
    {
        $data = $request->validateWithBag('sandi', [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'max:100', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Isi kata sandi saat ini.',
            'password.required'         => 'Isi kata sandi baru.',
            'password.min'              => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'        => 'Pengulangan kata sandi baru tidak sama.',
            'password.different'        => 'Kata sandi baru harus berbeda dari yang lama.',
        ]);

        $user = $request->user();

        if (! $user->cekSandi($data['current_password'])) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.'], 'sandi');
        }

        $user->forceFill(['password' => $data['password'], 'sandi_diganti_at' => now()]);
        $user->setRememberToken(Str::random(60));   // perangkat lain yang "ingat saya" harus masuk ulang
        $user->save();

        return back()->with('status', 'Kata sandi berhasil diganti');
    }
>>>>>>> d2ba4ff (Initial commit adukan vercel)
}
