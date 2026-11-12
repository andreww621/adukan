<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Impor akun siswa/guru dari CSV dengan kolom: nama,username,role,info,dasar
 *   username = NISN (siswa) atau NIP (guru), dasar = bagian depan kata sandi awal (NISN/NIP).
 * Kata sandi awal = dasar_nama. Akun yang username-nya sudah ada dilewati (kata sandinya tidak disentuh).
 *   php artisan akun:impor akun-awal.csv
 */
class ImporAkun extends Command
{
    protected $signature = 'akun:impor {file : Path file CSV}';
    protected $description = 'Impor akun siswa dan guru dari CSV (kata sandi awal: NISN_nama / NIP_nama)';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (! is_file($path) || ! ($fh = fopen($path, 'r'))) {
            $this->error("File tidak ditemukan: $path");
            return self::FAILURE;
        }

        $head = fgetcsv($fh);
        if ($head) {
            $head[0] = ltrim($head[0], "\xEF\xBB\xBF");   // buang BOM bila ada
        }

        $baru = $lewat = 0;
        while (($baris = fgetcsv($fh)) !== false) {
            if (count($baris) < count($head)) {
                continue;
            }
            $r = array_combine($head, array_slice($baris, 0, count($head)));

            if (User::where('username', $r['username'])->exists()) {
                $lewat++;
                continue;
            }

            User::forceCreate([
                'name'     => $r['nama'],
                'username' => $r['username'],
                'role'     => $r['role'] ?: 'pelapor',
                'info'     => $r['info'] ?: null,
                'password' => User::sandiAwal($r['dasar'], $r['nama']),   // di-hash otomatis oleh cast "hashed"
            ]);
            $baru++;
        }
        fclose($fh);

        $this->info("Selesai: $baru akun dibuat, $lewat dilewati (sudah ada).");

        return self::SUCCESS;
    }
}
