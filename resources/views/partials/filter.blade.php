{{-- Filter kategori + urutan tanggal. Dipakai di semua daftar laporan. Variabel: $filter (kategori, urut), $adaStatus (opsional, khusus riwayat admin). --}}
@php
    $adaStatus = $adaStatus ?? false;
    $fk = $filter['kategori'] ?? '';
    $fu = $filter['urut'] ?? '';
    $fs = request('status', '');
    $aktifFilter = $fk || $fu || ($adaStatus && $fs);
@endphp
<form method="GET" action="{{ request()->url() }}" class="fbar" aria-label="Filter dan urutan laporan">
    <span class="ftl">Tampilkan</span>
    <label>Kategori
        <select name="kategori" onchange="this.form.submit()">
            <option value="">Semua kategori</option>
            @foreach (\App\Models\Pengaduan::KATEGORI as $k)<option value="{{ $k }}" @selected($fk === $k)>{{ $k }}</option>@endforeach
        </select>
    </label>
    <label>Urutkan tanggal
        <select name="urut" onchange="this.form.submit()">
            <option value="">Bawaan</option>
            <option value="terbaru" @selected($fu === 'terbaru')>Terbaru dulu</option>
            <option value="terlama" @selected($fu === 'terlama')>Terlama dulu</option>
        </select>
    </label>
    @if ($adaStatus)
    <label>Status (riwayat)
        <select name="status" onchange="this.form.submit()">
            <option value="">Semua status</option>
            <option value="menunggu" @selected($fs === 'menunggu')>Menunggu verifikasi</option>
            <option value="proses" @selected($fs === 'proses')>Dalam proses</option>
            <option value="selesai" @selected($fs === 'selesai')>Selesai</option>
        </select>
    </label>
    @endif
    @if ($aktifFilter)<a class="freset" href="{{ request()->url() }}">Reset</a>@endif
    <noscript><button class="btn">Terapkan</button></noscript>
</form>
