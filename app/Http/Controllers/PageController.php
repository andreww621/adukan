<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Pengaduan;
use App\Models\Rating;
<<<<<<< HEAD
=======
use App\Models\ResetSandi;
>>>>>>> d2ba4ff (Initial commit adukan vercel)
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

<<<<<<< HEAD
        $data = match ($role) {
            'admin' => [
                'menunggu'    => Pengaduan::with('pelapor')->where('status', 'menunggu')->oldest()->get(),
                'proses'      => Pengaduan::with(['pelapor', 'teknisi'])
                                    ->whereIn('status', ['diverifikasi', 'disetujui', 'dikerjakan', 'menunggu_konfirmasi'])
                                    ->latest()->get(),
=======
        // Filter kategori + urutan tanggal dari query string; berlaku untuk semua daftar laporan di halaman ini.
        $kat = request('kategori');
        $urut = request('urut');
        $filter = ['kategori' => $kat, 'urut' => $urut];

        $data = match ($role) {
            'admin' => [
                'filter'      => $filter,
                'teknisiList' => User::where('role', 'teknisi')->orderBy('jurusan')->orderBy('name')->get(),
                'menunggu'    => Pengaduan::with('pelapor')->where('status', 'menunggu')->tampil($kat, $urut, 'terlama')->get(),
                'proses'      => Pengaduan::with(['pelapor', 'teknisi'])
                                    ->whereIn('status', ['diverifikasi', 'dikerjakan', 'menunggu_konfirmasi'])
                                    ->tampil($kat, $urut)->get(),
                'riwayat'     => Pengaduan::with(['pelapor', 'teknisi'])
                                    ->when(request('status') === 'selesai', fn ($q) => $q->where('status', 'selesai'))
                                    ->when(request('status') === 'proses', fn ($q) => $q->whereIn('status', ['diverifikasi', 'dikerjakan', 'menunggu_konfirmasi']))
                                    ->when(request('status') === 'menunggu', fn ($q) => $q->where('status', 'menunggu'))
                                    ->tampil($kat, $urut)->paginate(10)->withQueryString()->fragment('riwayat'),
                'resets'      => ResetSandi::with('user')->latest()->get(),
>>>>>>> d2ba4ff (Initial commit adukan vercel)
                'ratings'     => Rating::with('user')->latest()->get(),
                'komentar'    => Komentar::with('user')
                                    ->withCount([
                                        'obrolan as belum_dibaca' => fn ($q) => $q->where('dari', 'pelapor')->whereNull('dibaca_at'),
                                        'obrolan as jumlah',
                                    ])
                                    ->orderByDesc('updated_at')->take(100)->get(),
                'teknisis'    => User::where('role', 'teknisi')->orderByDesc('skor')->get(),
<<<<<<< HEAD
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
=======
                'maintenance' => Setting::maintenance(),
            ],
            'teknisi' => [
                'filter' => $filter,
                'tugas'  => Pengaduan::with('pelapor')->where('teknisi_id', $user->id)
                                ->whereIn('status', ['diverifikasi', 'dikerjakan', 'menunggu_konfirmasi'])->tampil($kat, $urut, 'terlama')->get(),
>>>>>>> d2ba4ff (Initial commit adukan vercel)
            ],
        };

        return view("dashboard.$role", $data);
    }
}
