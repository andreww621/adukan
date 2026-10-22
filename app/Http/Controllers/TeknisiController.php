<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;

class TeknisiController extends Controller
{
    private function milik(Request $request, Pengaduan $pengaduan, string $status): void
    {
        abort_unless($request->user()->role === 'teknisi' && $pengaduan->teknisi_id === $request->user()->id, 403);
        abort_unless($pengaduan->status === $status, 422);
    }

    public function proses(Request $request, Pengaduan $pengaduan)
    {
        $this->milik($request, $pengaduan, 'disetujui');
        $pengaduan->update(['status' => 'dikerjakan']);

        return back()->with('status', 'Pekerjaan dimulai');
    }

    public function selesai(Request $request, Pengaduan $pengaduan)
    {
        $this->milik($request, $pengaduan, 'dikerjakan');
        $pengaduan->update(['status' => 'menunggu_konfirmasi']);

        return back()->with('status', 'Dikirim ke pelapor untuk dikonfirmasi');
    }
}
