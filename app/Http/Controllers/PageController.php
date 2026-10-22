<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Pengaduan;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\User;

class PageController extends Controller
{
    public function home()
    {
        $user = auth()->user();

        if ($user->role !== 'pelapor') {
            return redirect()->route('dashboard', $user->role);
        }

        return view('home', ['stat' => [
            'masuk'   => Pengaduan::count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
        ]]);
    }

    public function buat()   { return view('buat-laporan'); }

    public function kontak()
    {
        $user = auth()->user();

        return view('kontak', [
            'riwayat' => $user->role === 'pelapor'
                ? Komentar::where('user_id', $user->id)
                    ->withCount(['obrolan as belum_dibaca' => fn ($q) => $q->where('dari', 'admin')->whereNull('dibaca_at')])
                    ->orderByDesc('updated_at')->take(5)->get()
                : collect(),
        ]);
    }

    public function profil()
    {
        return view('profil', ['rating' => Rating::where('user_id', auth()->id())->first()]);
    }

    public function dashboard(string $role)
    {
        $user = auth()->user();
        abort_unless($user->role === $role, 403);

        $data = match ($role) {
            'admin' => [
                'menunggu'    => Pengaduan::with('pelapor')->where('status', 'menunggu')->oldest()->get(),
                'proses'      => Pengaduan::with(['pelapor', 'teknisi'])
                                    ->whereIn('status', ['diverifikasi', 'disetujui', 'dikerjakan', 'menunggu_konfirmasi'])
                                    ->latest()->get(),
                'ratings'     => Rating::with('user')->latest()->get(),
                'komentar'    => Komentar::with('user')
                                    ->withCount([
                                        'obrolan as belum_dibaca' => fn ($q) => $q->where('dari', 'pelapor')->whereNull('dibaca_at'),
                                        'obrolan as jumlah',
                                    ])
                                    ->orderByDesc('updated_at')->take(100)->get(),
                'teknisis'    => User::where('role', 'teknisi')->orderByDesc('skor')->get(),
                'aktif'       => Setting::anggaranAktif(),
                'sisa'        => Setting::sisaAnggaran(),
                'maintenance' => Setting::maintenance(),
            ],
            'kepsek' => [
                'antrean' => Pengaduan::with('pelapor')->where('status', 'diverifikasi')->oldest()->get(),
                'riwayat' => Pengaduan::with('teknisi')->whereNotNull('dibayar_at')->latest('dibayar_at')->take(5)->get(),
                'aktif'   => Setting::anggaranAktif(),
                'sisa'    => Setting::sisaAnggaran(),
            ],
            'teknisi' => [
                'tugas' => Pengaduan::with('pelapor')->where('teknisi_id', $user->id)
                                ->whereIn('status', ['disetujui', 'dikerjakan', 'menunggu_konfirmasi'])->oldest()->get(),
            ],
        };

        return view("dashboard.$role", $data);
    }
}
