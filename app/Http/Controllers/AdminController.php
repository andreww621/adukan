<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function admin(Request $request): void
    {
        abort_unless($request->user()->role === 'admin', 403);
    }

    public function storeAkun(Request $request)
    {
        $this->admin($request);

        $data = $request->validateWithBag('akun', [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:kepsek,teknisi'],
        ]);

        User::forceCreate([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        return redirect()->route('dashboard', 'admin')
            ->with('status', 'Akun ' . ($data['role'] === 'kepsek' ? 'Kepala Sekolah' : 'Teknisi') . ' berhasil dibuat');
    }

    public function verifikasi(Request $request, Pengaduan $pengaduan)
    {
        $this->admin($request);
        abort_unless($pengaduan->status === 'menunggu', 422);

        $data = $request->validate(['dana' => ['required', 'integer', 'min:0', 'max:1000000000']]);
        $pengaduan->update(['status' => 'diverifikasi', 'dana' => $data['dana']]);

        return back()->with('status', 'Laporan diverifikasi dan diteruskan ke Kepala Sekolah');
    }

    public function anggaran(Request $request)
    {
        $this->admin($request);

        $data = $request->validate([
            'aktif' => ['required', 'in:0,1'],
            'total' => ['required', 'integer', 'min:0', 'max:100000000000'],
        ]);

        Setting::simpan('anggaran_aktif', $data['aktif']);
        Setting::simpan('anggaran_total', $data['total']);

        return back()->with('status', 'Anggaran disimpan');
    }

    public function maintenance(Request $request)
    {
        $this->admin($request);

        $aktif = ! Setting::maintenance();
        Setting::simpan('maintenance', $aktif ? '1' : '0');

        return back()->with('status', 'Mode maintenance ' . ($aktif ? 'diaktifkan' : 'dimatikan'));
    }

    // CSV dengan pemisah titik koma dan BOM UTF-8 supaya langsung rapi di Excel (pengaturan Indonesia).
    public function ekspor(Request $request)
    {
        $this->admin($request);

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF" . "sep=;\n");
            fputcsv($out, ['ID', 'Tanggal', 'Pelapor', 'Kategori', 'Lokasi', 'Deskripsi', 'Status', 'Dana (Rp)', 'Teknisi', 'Rekening', 'Tanggal dibayar'], ';');

            Pengaduan::with(['pelapor', 'teknisi'])->orderBy('id')->each(function (Pengaduan $p) use ($out) {
                fputcsv($out, [
                    $p->id,
                    $p->created_at->format('Y-m-d H:i'),
                    $p->pelapor->name ?? '-',
                    $p->kategori,
                    $p->lokasi,
                    $p->deskripsi,
                    $p->label,
                    $p->dana,
                    $p->teknisi->name ?? '-',
                    $p->norek ? '****' . substr($p->norek, -4) : '-',
                    $p->dibayar_at?->format('Y-m-d H:i'),
                ], ';');
            });

            fclose($out);
        }, 'laporan-adukan-' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
