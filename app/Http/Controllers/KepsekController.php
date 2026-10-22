<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KepsekController extends Controller
{
    public function form(Request $request, Pengaduan $pengaduan)
    {
        abort_unless($request->user()->role === 'kepsek', 403);
        abort_unless($pengaduan->status === 'diverifikasi', 404);

        return view('dashboard.kepsek-pembayaran', [
            'p'        => $pengaduan,
            'teknisis' => User::where('role', 'teknisi')->orderBy('name')->get(),
            'aktif'    => Setting::anggaranAktif(),
            'sisa'     => Setting::sisaAnggaran(),
            'punyaPin' => (bool) $request->user()->pin,
        ]);
    }

    public function bayar(Request $request, Pengaduan $pengaduan)
    {
        $kepsek = $request->user();
        abort_unless($kepsek->role === 'kepsek', 403);
        abort_unless($pengaduan->status === 'diverifikasi', 422);

        $data = $request->validate([
            'teknisi_id' => ['required', 'integer'],
            'norek'      => ['required', 'digits_between:8,20'],
            'pin'        => ['required', 'digits:6'],
        ]);

        $teknisi = User::where('id', $data['teknisi_id'])->where('role', 'teknisi')->firstOrFail();

        if (! Setting::anggaranAktif()) {
            return back()->withErrors(['anggaran' => 'Anggaran belum tersedia. Minta admin mengaktifkannya.'])->withInput();
        }
        if ($pengaduan->dana > Setting::sisaAnggaran()) {
            return back()->withErrors(['anggaran' => 'Sisa anggaran tidak cukup untuk dana ini.'])->withInput();
        }

        // Pembayaran pertama membuat PIN; selanjutnya PIN diperiksa.
        if ($kepsek->pin) {
            if (! Hash::check($data['pin'], $kepsek->pin)) {
                return back()->withErrors(['pin' => 'PIN salah.'])->withInput($request->except('pin'));
            }
        } else {
            $kepsek->pin = Hash::make($data['pin']);
            $kepsek->save();
        }

        DB::transaction(function () use ($pengaduan, $teknisi, $data) {
            Setting::simpan('anggaran_total', Setting::sisaAnggaran() - $pengaduan->dana);
            $pengaduan->update([
                'status'     => 'disetujui',
                'teknisi_id' => $teknisi->id,
                'norek'      => $data['norek'],
                'dibayar_at' => now(),
            ]);
        });

        return redirect()->route('dashboard', 'kepsek')->with('status', 'Dana terkirim ke ' . $teknisi->name);
    }
}
