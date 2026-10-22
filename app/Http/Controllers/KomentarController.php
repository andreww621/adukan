<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KomentarController extends Controller
{
    // Komentar dari halaman Kontak. Hanya pelapor yang boleh mengirim; admin membacanya di dashboard.
    public function store(Request $request)
    {
        abort_unless($request->user()->role === 'pelapor', 403);

        $data = $request->validate(
            ['pesan' => ['required', 'string', 'min:3', 'max:500']],
            [
                'pesan.required' => 'Komentar wajib diisi.',
                'pesan.min'      => 'Komentar terlalu singkat.',
                'pesan.max'      => 'Komentar maksimal 500 karakter.',
            ]
        );

        $pesan = trim($data['pesan']);

        // Komentar = awal percakapan: pesan pertamanya disimpan juga sebagai pesan chat.
        $komentar = DB::transaction(function () use ($request, $pesan) {
            $k = Komentar::create(['user_id' => $request->user()->id, 'pesan' => $pesan]);
            $k->obrolan()->create(['user_id' => $request->user()->id, 'dari' => 'pelapor', 'isi' => $pesan]);

            return $k;
        });

        return redirect()->route('kontak')->with('status', 'Komentar terkirim')->with('buka_chat', $komentar->id);
    }
}
