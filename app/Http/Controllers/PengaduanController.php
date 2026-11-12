<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Services\SkorTeknisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengaduanController extends Controller
{
<<<<<<< HEAD
    public function index()
    {
        $items = Pengaduan::with('teknisi')->where('user_id', auth()->id())->latest()->get();

        return view('laporan', compact('items'));
=======
    public function index(Request $request)
    {
        $items = Pengaduan::with('teknisi')->where('user_id', auth()->id())
            ->tampil($request->query('kategori'), $request->query('urut'))->get();

        return view('laporan', ['items' => $items, 'filter' => $request->only('kategori', 'urut')]);
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->role === 'pelapor', 403);

        $data = $request->validate([
<<<<<<< HEAD
            'kategori'  => ['required', 'in:Listrik,Sanitasi,Bangunan'],
            'lokasi'    => ['required', 'string', Rule::in(collect(config('ruang'))->flatten()->all())],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'foto'      => ['nullable', 'image', 'max:5120'],
            'video'     => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska', 'max:20480'],
        ], [
=======
            'kategori'  => ['required', Rule::in(Pengaduan::KATEGORI)],
            'lokasi'    => ['required', 'string', Rule::in(collect(config('ruang'))->flatten()->all())],
            'deskripsi' => ['required', 'string', 'max:1000'],
            'foto'      => ['required', 'image', 'max:5120'],
            'video'     => ['nullable', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime,video/x-matroska', 'max:20480'],
        ], [
            'foto.required'   => 'Foto wajib dilampirkan sebagai bukti kerusakan.',
            'foto.image'      => 'File foto harus berupa gambar (JPG, PNG, dll).',
            'foto.max'        => 'Ukuran foto maksimal 5 MB.',
>>>>>>> d2ba4ff (Initial commit adukan vercel)
            'video.mimetypes' => 'Video harus berformat MP4, WEBM, atau MOV.',
            'video.max'       => 'Ukuran video maksimal 20 MB.',
            'video.uploaded'  => 'Video gagal diunggah. Ukuran maksimal 20 MB (cek juga upload_max_filesize dan post_max_size di php.ini).',
        ]);

        Pengaduan::create([
            'user_id'   => $request->user()->id,
            'kategori'  => $data['kategori'],
            'lokasi'    => $data['lokasi'],
            'deskripsi' => $data['deskripsi'],
<<<<<<< HEAD
            'foto'      => $request->file('foto')?->store('pengaduan', 'public'),
=======
            'foto'      => $request->file('foto')->store('pengaduan', 'public'),
>>>>>>> d2ba4ff (Initial commit adukan vercel)
            'video'     => $request->file('video')?->store('pengaduan/video', 'public'),
        ]);

        return redirect()->route('laporan')->with('status', 'Pengaduan terkirim');
    }

    // Pelapor (pemilik laporan) atau admin memastikan apakah pekerjaan benar-benar selesai.
    public function konfirmasi(Request $request, Pengaduan $pengaduan)
    {
        $user = $request->user();
        abort_unless($user->role === 'admin' || $pengaduan->user_id === $user->id, 403);
        abort_unless($pengaduan->status === 'menunggu_konfirmasi', 422);

        $hasil = $request->validate(['hasil' => ['required', 'in:selesai,belum']])['hasil'];
        $teknisi = $pengaduan->teknisi;

        if ($hasil === 'selesai') {
            $pengaduan->update(['status' => 'selesai']);
            if ($teknisi) {
                SkorTeknisi::hadiah($teknisi);
            }
            $pesan = 'Pekerjaan ditandai selesai';
        } else {
            $pengaduan->update(['status' => 'dikerjakan']);
            if ($teknisi) {
                SkorTeknisi::hukuman($teknisi);
            }
            $pesan = 'Dikembalikan ke teknisi, skor teknisi dikurangi ' . SkorTeknisi::HUKUMAN;
        }

        return back()->with('status', $pesan);
    }
}
