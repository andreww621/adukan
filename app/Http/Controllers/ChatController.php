<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\KomentarPesan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Chat antara pelapor dan admin. Percakapan dimulai dari komentar pelapor (halaman Kontak);
// admin membalas dari dashboard. Semua respons berupa JSON untuk assets/js/chat.js.
class ChatController extends Controller
{
    private function peran(Request $request): string
    {
        $role = $request->user()->role;
        abort_unless(in_array($role, ['admin', 'pelapor'], true), 403);

        return $role;
    }

    // Admin boleh membuka semua percakapan; pelapor hanya miliknya sendiri.
    private function izin(Request $request, Komentar $komentar): string
    {
        $peran = $this->peran($request);
        abort_unless($peran === 'admin' || $komentar->user_id === $request->user()->id, 403);

        return $peran;
    }

    private static function lawan(string $peran): string
    {
        return $peran === 'admin' ? 'pelapor' : 'admin';
    }

    private function pesanJson(KomentarPesan $m, string $peran): array
    {
        return [
            'id'    => $m->id,
            'dari'  => $m->dari,
            'mine'  => $m->dari === $peran,
            'isi'   => $m->isi,
            'waktu' => $m->created_at->toIso8601String(),
        ];
    }

    // Daftar percakapan + jumlah pesan belum dibaca (dipakai untuk ikon notifikasi dan badge).
    public function index(Request $request)
    {
        $peran = $this->peran($request);
        $lawan = self::lawan($peran);
        $user  = $request->user();

        $threads = Komentar::with(['user', 'pesanTerakhir'])
            ->withCount(['obrolan as belum_dibaca' => fn ($q) => $q->where('dari', $lawan)->whereNull('dibaca_at')])
            ->when($peran === 'pelapor', fn ($q) => $q->where('user_id', $user->id))
            ->orderByDesc('updated_at')->orderByDesc('id')
            ->limit(100)
            ->get();

        $total = KomentarPesan::where('dari', $lawan)->whereNull('dibaca_at')
            ->when($peran === 'pelapor', fn ($q) => $q->whereHas('komentar', fn ($k) => $k->where('user_id', $user->id)))
            ->count();

        return response()->json([
            'total'   => $total,
            'threads' => $threads->map(fn (Komentar $k) => [
                'id'           => $k->id,
                'judul'        => Str::limit($k->pesan, 40),
                'nama'         => $k->user->name,
                'foto'         => $k->user->foto_url,
                'belum_dibaca' => (int) $k->belum_dibaca,
                'terakhir'     => $k->pesanTerakhir ? [
                    'isi'   => Str::limit($k->pesanTerakhir->isi, 60),
                    'dari'  => $k->pesanTerakhir->dari,
                    'waktu' => $k->pesanTerakhir->created_at->toIso8601String(),
                ] : null,
            ])->values(),
        ]);
    }

    // Isi percakapan. Membuka percakapan menandai pesan dari lawan sebagai sudah dibaca.
    // ?after=<id> hanya mengambil pesan yang lebih baru (untuk polling).
    public function show(Request $request, Komentar $komentar)
    {
        $peran = $this->izin($request, $komentar);

        $komentar->obrolan()->where('dari', self::lawan($peran))->whereNull('dibaca_at')->update(['dibaca_at' => now()]);

        $pesan = $komentar->obrolan()
            ->when($request->filled('after'), fn ($q) => $q->where('id', '>', (int) $request->query('after')))
            ->orderBy('id')
            ->get();

        return response()->json(['pesan' => $pesan->map(fn (KomentarPesan $m) => $this->pesanJson($m, $peran))->values()]);
    }

    public function kirim(Request $request, Komentar $komentar)
    {
        $peran = $this->izin($request, $komentar);

        $data = $request->validate(
            ['isi' => ['required', 'string', 'max:1000']],
            ['isi.required' => 'Pesan tidak boleh kosong.', 'isi.max' => 'Pesan maksimal 1000 karakter.']
        );

        $pesan = $komentar->obrolan()->create([
            'user_id' => $request->user()->id,
            'dari'    => $peran,
            'isi'     => $data['isi'],
        ]);
        $komentar->touch(); // urutkan percakapan berdasarkan aktivitas terakhir

        return response()->json($this->pesanJson($pesan, $peran), 201);
    }
}
