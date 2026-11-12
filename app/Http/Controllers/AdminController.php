<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
<<<<<<< HEAD
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
=======
use App\Models\ResetSandi;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
>>>>>>> d2ba4ff (Initial commit adukan vercel)

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
<<<<<<< HEAD
            'role'     => ['required', 'in:kepsek,teknisi'],
=======
            'role'     => ['required', 'in:teknisi'],
            'jurusan'  => ['nullable', 'required_if:role,teknisi', Rule::in(config('jurusan'))],
        ], [
            'jurusan.required_if' => 'Pilih jurusan teknisi.',
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        ]);

        User::forceCreate([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
<<<<<<< HEAD
        ]);

        return redirect()->route('dashboard', 'admin')
            ->with('status', 'Akun ' . ($data['role'] === 'kepsek' ? 'Kepala Sekolah' : 'Teknisi') . ' berhasil dibuat');
    }

=======
            'jurusan'  => $data['role'] === 'teknisi' ? $data['jurusan'] : null,
        ]);

        return redirect()->route('dashboard', 'admin')
            ->with('status', 'Akun Teknisi berhasil dibuat');
    }

    public function jurusan(Request $request, User $user)
    {
        $this->admin($request);
        abort_unless($user->role === 'teknisi', 404);

        $data = $request->validate(['jurusan' => ['required', Rule::in(config('jurusan'))]]);
        $user->forceFill(['jurusan' => $data['jurusan']])->save();

        return back()->with('status', 'Jurusan ' . $user->name . ' diperbarui');
    }

    public function hapusTeknisi(Request $request, User $user)
    {
        $this->admin($request);
        abort_unless($user->role === 'teknisi', 404);

        // Teknisi yang masih memegang pekerjaan tidak boleh dihapus, supaya laporannya tidak menggantung.
        $aktif = Pengaduan::where('teknisi_id', $user->id)
            ->whereIn('status', ['diverifikasi', 'dikerjakan', 'menunggu_konfirmasi'])->count();
        if ($aktif > 0) {
            return back()->with('galat', $user->name . ' masih memegang ' . $aktif . ' pekerjaan aktif. Hapus setelah pekerjaannya selesai.');
        }

        DB::transaction(function () use ($user) {
            // Simpan namanya di riwayat laporan sebelum akun dihapus (kolom teknisi_id otomatis menjadi kosong).
            Pengaduan::where('teknisi_id', $user->id)->update(['teknisi_nama' => $user->name]);
            $user->delete();
        });

        return back()->with('status', 'Akun teknisi ' . $user->name . ' dihapus');
    }

    // Admin menyetujui permintaan lupa kata sandi dengan membuat kode sekali pakai.
    public function kodeSandi(Request $request, ResetSandi $reset)
    {
        $this->admin($request);

        $abjad = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';   // tanpa karakter yang mirip (I, O, 0, 1)
        $kode = '';
        for ($i = 0; $i < 8; $i++) {
            $kode .= $abjad[random_int(0, strlen($abjad) - 1)];
        }

        $reset->update([
            'kode'          => Hash::make($kode),
            'kadaluarsa_at' => now()->addMinutes(ResetSandi::MASA_BERLAKU_MENIT),
            'percobaan'     => 0,
        ]);

        return back()->with('kode_sandi', ['nama' => $reset->user->name, 'email' => $reset->user->email, 'kode' => $kode]);
    }

    public function tolakSandi(Request $request, ResetSandi $reset)
    {
        $this->admin($request);
        $reset->delete();

        return back()->with('status', 'Permintaan dihapus');
    }

    // Admin memverifikasi laporan sekaligus menugaskan teknisi. Setelah itu teknisi yang mengubah statusnya.
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    public function verifikasi(Request $request, Pengaduan $pengaduan)
    {
        $this->admin($request);
        abort_unless($pengaduan->status === 'menunggu', 422);

<<<<<<< HEAD
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
=======
        $data = $request->validate(['teknisi_id' => ['required', 'integer']], [
            'teknisi_id.required' => 'Pilih teknisi untuk laporan ini.',
        ]);
        $teknisi = User::where('id', $data['teknisi_id'])->where('role', 'teknisi')->firstOrFail();

        $pengaduan->update([
            'status'       => 'diverifikasi',
            'teknisi_id'   => $teknisi->id,
            'teknisi_nama' => $teknisi->name,
        ]);

        return back()->with('status', 'Laporan diverifikasi dan diteruskan ke ' . $teknisi->name);
>>>>>>> d2ba4ff (Initial commit adukan vercel)
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
<<<<<<< HEAD
            fputcsv($out, ['ID', 'Tanggal', 'Pelapor', 'Kategori', 'Lokasi', 'Deskripsi', 'Status', 'Dana (Rp)', 'Teknisi', 'Rekening', 'Tanggal dibayar'], ';');
=======
            fputcsv($out, ['ID', 'Tanggal', 'Pelapor', 'Kategori', 'Lokasi', 'Deskripsi', 'Status', 'Teknisi'], ';');
>>>>>>> d2ba4ff (Initial commit adukan vercel)

            Pengaduan::with(['pelapor', 'teknisi'])->orderBy('id')->each(function (Pengaduan $p) use ($out) {
                fputcsv($out, [
                    $p->id,
                    $p->created_at->format('Y-m-d H:i'),
                    $p->pelapor->name ?? '-',
                    $p->kategori,
                    $p->lokasi,
                    $p->deskripsi,
                    $p->label,
<<<<<<< HEAD
                    $p->dana,
                    $p->teknisi->name ?? '-',
                    $p->norek ? '****' . substr($p->norek, -4) : '-',
                    $p->dibayar_at?->format('Y-m-d H:i'),
=======
                    $p->teknisi_label ?? '-',
>>>>>>> d2ba4ff (Initial commit adukan vercel)
                ], ';');
            });

            fclose($out);
        }, 'laporan-adukan-' . date('Ymd') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
