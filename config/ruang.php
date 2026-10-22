<?php

// Daftar ruang SMK Negeri 5 Surakarta (sumber: Denah Ruang TA 2025/2026).
// Dipakai untuk dropdown Lokasi di form pengaduan dan validasinya.
$rg = fn (string $lantai, array $nomor) => array_map(fn ($n) => "Rg $lantai.$n", $nomor);
$seq = fn (int $a, int $b) => array_map(fn ($n) => str_pad((string) $n, 2, '0', STR_PAD_LEFT), range($a, $b));

return [
    'Ruang kelas - Lantai 1' => array_merge(
        $rg('1', $seq(1, 12)),
        $rg('1', ['18', '19', '20', '21'])
    ),
    'Ruang kelas - Lantai 2' => array_merge(
        $rg('2', $seq(1, 18)),
        $rg('2', ['20', '21', '22', '23', '24', '25'])
    ),
    'Laboratorium' => [
        'Lab. RPL 1', 'Lab. RPL 2', 'Lab. RPL 3', 'Lab. RPL 4',
        'Lab. B. Ingrs 1', 'Lab. B. Ingrs 2', 'Lab. B. Ingrs 3',
        'Lab. ASM 1 (R.1.13)', 'Lab. ASM 2 (R.1.14)', 'Lab. ASM 3 (R.1.15)',
        'T. ASM 1 (R.1.16)', 'T. ASM 2 (R.2.19)',
        'Lab. Fisika', 'Lab. MM', 'Lab. KKP 1', 'Lab. KKPI 2',
        'Lab. Komp. DPIB Lt.2', 'R. Gbr. DPIB Lt.1', 'R. TEFA',
    ],
    'Bengkel & RPS' => [
        'RPS TKR', 'RPS Teknik Pemesinan', 'RPS Teknik Otomotif', 'RPS Elektronika', 'RPS TKP',
        'RPS TSM Lt.1', 'RPS TSM 1', 'RPS TSM 2', 'RPS TSM 3',
        'Bengkel Otomasi', 'Bengkel SKE', 'Bengkel PSA', 'Bengkel SKT', 'Bengkel SKEE',
        'Bkl Elektro 1', 'Bkl Elektro 2', 'Bkl Elektro 3', 'Bkl Elektro 4',
        'Bkl Listrik 2', 'Bkl Listrik 3 (IML)', 'Bkl Listrik 5', 'Bkl Listrik 6 (ITL)',
        'Bkl DLE', 'L4 Listrik', 'Doorlop',
    ],
    'Kantor & ruang guru' => [
        'Ruang KS', 'Ruang TU', 'WKS 1', 'WKS 3', 'WKS 4', 'WKS SDM', 'WKS WMM', 'Ruang Waka 2',
        'R. Guru', 'Ruang Guru NK', 'Ruang Guru Listrik', 'R. Guru PPLG',
        'R. BP/BK', 'R. Dapodik', 'R. Seni Rupa', 'LSP P1', 'R. Meeting', 'R. Lobby',
    ],
    'Fasilitas umum' => [
        'Ruang Aula', 'Ruang Perpustakaan', 'Masjid', 'R. Serbaguna/Indoor', 'UKS', 'R. OSIS',
        'Showroom', 'R. Satpam', 'Gudang',
        'WC (dekat Ruang Aula)', 'WC (dekat Rg 1.01)', 'WC (dekat Lab. RPL 4)', 'WC (dekat Lab. MM)',
    ],
];
