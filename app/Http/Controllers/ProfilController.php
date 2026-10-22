<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $user = $request->user();

        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
            $user->forceFill(['foto' => null])->save();
        }

        return back()->with('status', 'Foto profil dihapus');
    }
}
