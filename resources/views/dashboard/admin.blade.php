@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page','dash')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<main class="page" style="max-width:1040px">
<header><h1>Dashboard Admin</h1></header>
<div class="two">
    <section class="card"><h2>Perlu diverifikasi</h2><p class="sub">Isi perkiraan dana, lalu verifikasi agar diteruskan ke Kepala Sekolah.</p>
        <div class="items" style="padding:0;margin-top:12px">
        @forelse ($menunggu as $p)
            <div class="item"><div class="thumb">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->kategori[0] }}@endif</div>
                <div><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->pelapor->name }}: {{ $p->deskripsi }}</small>@include('partials.video-btn', ['p' => $p])</div>
                <form method="POST" action="{{ route('pengaduan.verifikasi', $p) }}">@csrf<input name="dana" type="number" min="0" required placeholder="Dana (Rp)" aria-label="Perkiraan dana" style="width:130px"><button class="btn">Verifikasi</button></form></div>
        @empty <p class="sub">Tidak ada laporan baru.</p>
        @endforelse
        </div></section>
    <section class="card"><h2>Dalam proses</h2><p class="sub">Laporan yang sudah diverifikasi sampai menunggu konfirmasi selesai.</p>
        <div class="items" style="padding:0;margin-top:12px">
        @forelse ($proses as $p)
            <div class="item"><div class="thumb">{{ $p->kategori[0] }}</div>
                <div><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->pelapor->name }} - dana {{ $p->dana_rp }}@if ($p->teknisi) - {{ $p->teknisi->name }}@endif</small>@include('partials.video-btn', ['p' => $p])
                    <span class="badge {{ $p->warna }}" style="margin-top:4px">{{ $p->label }}</span>
                    @if ($p->status === 'menunggu_konfirmasi')
                        <div class="ask"><form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="selesai"><button class="btn">Sudah selesai</button></form>
                        <form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="belum"><button class="btn light" style="border-color:var(--line)">Belum selesai</button></form></div>
                    @endif</div></div>
        @empty <p class="sub">Belum ada laporan dalam proses.</p>
        @endforelse
        </div></section>
</div>
<div class="two">
    <section class="card"><h2>Anggaran perbaikan</h2>
        <p class="sub">Sisa anggaran saat ini: <b>{{ $rp($sisa) }}</b> ({{ $aktif ? 'tersedia' : 'tidak tersedia' }}). Kepala Sekolah hanya bisa mengirim dana saat anggaran tersedia dan cukup.</p>
        <form method="POST" action="{{ route('admin.anggaran') }}">@csrf
        <label for="ag">Status anggaran</label><select id="ag" name="aktif"><option value="1" @selected($aktif)>Tersedia</option><option value="0" @selected(! $aktif)>Tidak tersedia</option></select>
        <label for="tt">Total anggaran (Rp)</label><input id="tt" name="total" type="number" min="0" value="{{ $sisa }}" required>
        @error('total')<div class="err">{{ $message }}</div>@enderror
        <p style="margin-top:14px"><button class="btn">Simpan anggaran</button></p></form></section>
    <section class="card"><h2 style="margin-bottom:14px">Menu admin</h2>
        <a class="btn pill" href="{{ route('admin.ekspor') }}">Ekspor laporan Excel</a>
        <form method="POST" action="{{ route('admin.maintenance') }}" style="margin:0">@csrf<button class="btn pill">Maintenance: {{ $maintenance ? 'aktif' : 'nonaktif' }}</button></form>
        <p class="sub" style="margin:-4px 0 10px">Saat aktif, pelapor tidak bisa memakai website. Petugas tetap bisa masuk.</p>
        <button class="btn pill" type="button" onclick="ak.showModal()">Tambah akun Kepsek / Teknisi</button></section>
</div>
<div class="two">
    <section class="card"><h2>Rating dari pelapor</h2>
        <p class="sub">@if ($ratings->count()) Rata-rata {{ number_format($ratings->avg('nilai'), 1, ',', '') }} dari 5 ({{ $ratings->count() }} penilaian) @else Belum ada penilaian. @endif</p>
        <div style="margin-top:8px">
        @foreach ($ratings as $r)
            <div class="rate" style="flex-wrap:wrap"><span>{{ $r->user->name }}</span><span aria-label="{{ $r->nilai }} dari 5">{{ str_repeat('★', $r->nilai) }}{{ str_repeat('☆', 5 - $r->nilai) }}</span>@if ($r->komentar)<small style="width:100%;color:var(--mut)">{{ $r->komentar }}</small>@endif</div>
        @endforeach
        </div></section>
    <section class="card"><h2>Skor teknisi</h2><p class="sub">Maksimal 150. Kelebihan poin tampil sebagai +N.</p>
        <div style="margin-top:8px">
        @forelse ($teknisis as $t)
            <div class="rate"><span>{{ $t->name }}</span><span style="color:var(--ink);font-weight:600">{{ $t->skor }}@if ($t->bonus > 0) <span style="color:var(--ok)">+{{ $t->bonus }}</span>@endif</span></div>
        @empty <p class="sub">Belum ada akun teknisi.</p>
        @endforelse
        </div></section>
</div>
<section class="card" style="margin-bottom:16px"><h2>Komentar dari pelapor <i class="nb" data-nb-total data-nb-wrap @if ($komentar->sum('belum_dibaca') === 0) hidden @endif>{{ $komentar->sum('belum_dibaca') }}</i></h2>
    <p class="sub">Komentar dari halaman Kontak. Buka chat untuk membalas; pelapor akan menerima notifikasi. @if ($komentar->count()) {{ $komentar->count() }} percakapan terbaru. @else Belum ada komentar. @endif</p>
    <div class="items" style="padding:0;margin-top:12px">
    @foreach ($komentar as $k)
        <div class="item kom @if ($k->belum_dibaca) has-new @endif" data-kid="{{ $k->id }}"><div class="thumb">@if ($k->user->foto_url)<img src="{{ $k->user->foto_url }}" alt="">@else{{ mb_strtoupper(mb_substr($k->user->name, 0, 1)) }}@endif</div>
            <div><b>{{ $k->user->name }}</b> <small style="display:inline">{{ $k->user->email }} - {{ $k->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
                <p style="white-space:pre-line;margin-top:4px">{{ $k->pesan }}</p>
                <small>{{ $k->jumlah }} pesan</small></div>
            <button type="button" class="btn" data-chat-open="{{ $k->id }}">Buka chat <i class="nb" data-nb="{{ $k->id }}" @if (! $k->belum_dibaca) hidden @endif>{{ $k->belum_dibaca }}</i></button></div>
    @endforeach
    </div></section>
</main>
<dialog id="ak"><h2>Tambah akun</h2>
<form method="POST" action="{{ route('admin.akun') }}" style="text-align:left">@csrf
@if ($errors->akun->any())<div class="err">{{ $errors->akun->first() }}</div>@endif
<label for="n">Nama</label><input id="n" name="name" value="{{ old('name') }}" required>
<label for="e">Email</label><input id="e" name="email" type="email" value="{{ old('email') }}" required>
<label for="pw">Kata sandi sementara</label><input id="pw" name="password" type="password" minlength="8" required autocomplete="new-password">
<label for="rl">Peran</label><select id="rl" name="role"><option value="kepsek">Kepala Sekolah</option><option value="teknisi">Teknisi</option></select>
<p style="margin-top:18px;display:flex;gap:10px;justify-content:center"><button class="btn">Simpan akun</button><button type="button" class="btn light" style="border-color:var(--line)" onclick="ak.close()">Batal</button></p></form></dialog>
@endsection
@push('scripts')
<script>@if ($errors->akun->any()) ak.showModal(); @endif</script>
@endpush
