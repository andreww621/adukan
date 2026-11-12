@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page','dash')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<<<<<<< HEAD
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
=======
<main class="page" style="max-width:1180px">
<header><h1>Dashboard Admin</h1></header>
@include('partials.filter', ['filter' => $filter, 'adaStatus' => true])
<div class="two wl">
    <section class="card roomy"><h2>Perlu diverifikasi</h2><p class="sub">Isi perkiraan dana, lalu verifikasi agar diteruskan ke Kepala Sekolah.</p>
        <div class="items" style="padding:0;margin-top:14px">
        @forelse ($menunggu as $p)
    <div class="item rich"><div class="thumb big">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->inisial }}@endif</div>
        <div class="body">
            <div class="meta"><b>{{ $p->kategori }} {{ $p->lokasi }}</b><span class="when">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</span></div>
            <p class="desk">{{ $p->deskripsi }}</p>
            <small>Pelapor: {{ $p->pelapor->name }}</small>
            @include('partials.video-btn', ['p' => $p])
            
            <!-- Form verifikasi baru: Input dana dihapus, diganti pilih teknisi -->
            <form method="POST" action="{{ route('pengaduan.verifikasi', $p) }}" style="margin-top: 10px; display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                @csrf
                <select name="teknisi_id" required aria-label="Pilih Teknisi" style="flex: 1; min-width: 160px; padding: 6px 10px; border-radius: 6px; border: 1px solid var(--line);">
                    <option value="">-- Pilih Teknisi --</option>
                    @foreach ($teknisis as $t)
                        <option value="{{ $t->id }}">{{ $t->name }} @if($t->jurusan) ({{ $t->jurusan }}) @endif</option>
                    @endforeach
                </select>
                <button class="btn">Verifikasi</button>
            </form>
        </div></div>
@empty <p class="sub">Tidak ada laporan baru.</p>
@endforelse
        </div></section>
    <section class="card compact"><h2>Dalam proses</h2><p class="sub">Laporan yang sudah diverifikasi sampai menunggu konfirmasi selesai.</p>
        <div class="items" style="padding:0;margin-top:12px">
        @forelse ($proses as $p)
            <div class="item rich"><div class="thumb">{{ $p->inisial }}</div>
                <div class="body"><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><p class="desk">{{ $p->deskripsi }}</p><small>{{ $p->pelapor->name }} dana {{ $p->dana_rp }}@if ($p->teknisi_label) - {{ $p->teknisi_label }}@endif</small>@include('partials.video-btn', ['p' => $p])
                    <span class="badge {{ $p->warna }}" style="margin-top:6px">{{ $p->label }}</span>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
                    @if ($p->status === 'menunggu_konfirmasi')
                        <div class="ask"><form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="selesai"><button class="btn">Sudah selesai</button></form>
                        <form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="belum"><button class="btn light" style="border-color:var(--line)">Belum selesai</button></form></div>
                    @endif</div></div>
        @empty <p class="sub">Belum ada laporan dalam proses.</p>
        @endforelse
        </div></section>
</div>
<<<<<<< HEAD
<div class="two">
    <section class="card"><h2>Anggaran perbaikan</h2>
        <p class="sub">Sisa anggaran saat ini: <b>{{ $rp($sisa) }}</b> ({{ $aktif ? 'tersedia' : 'tidak tersedia' }}). Kepala Sekolah hanya bisa mengirim dana saat anggaran tersedia dan cukup.</p>
        <form method="POST" action="{{ route('admin.anggaran') }}">@csrf
        <label for="ag">Status anggaran</label><select id="ag" name="aktif"><option value="1" @selected($aktif)>Tersedia</option><option value="0" @selected(! $aktif)>Tidak tersedia</option></select>
        <label for="tt">Total anggaran (Rp)</label><input id="tt" name="total" type="number" min="0" value="{{ $sisa }}" required>
        @error('total')<div class="err">{{ $message }}</div>@enderror
        <p style="margin-top:14px"><button class="btn">Simpan anggaran</button></p></form></section>
    <section class="card"><h2 style="margin-bottom:14px">Menu admin</h2>
=======

<!-- Bagian Anggaran Perbaikan sudah dihapus, menyisakan Menu Admin saja atau dibuat full width / disesuaikan -->
<div class="two">
    <section class="card" style="grid-column: 1 / -1;"><h2 style="margin-bottom:14px">Menu admin</h2>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        <a class="btn pill" href="{{ route('admin.ekspor') }}">Ekspor laporan Excel</a>
        <form method="POST" action="{{ route('admin.maintenance') }}" style="margin:0">@csrf<button class="btn pill">Maintenance: {{ $maintenance ? 'aktif' : 'nonaktif' }}</button></form>
        <p class="sub" style="margin:-4px 0 10px">Saat aktif, pelapor tidak bisa memakai website. Petugas tetap bisa masuk.</p>
        <button class="btn pill" type="button" onclick="ak.showModal()">Tambah akun Kepsek / Teknisi</button></section>
</div>
<<<<<<< HEAD
<div class="two">
    <section class="card"><h2>Rating dari pelapor</h2>
=======

<section class="card roomy" id="sandi" style="margin-bottom:16px"><h2>Permintaan lupa kata sandi @if ($resets->count())<i class="nb">{{ $resets->count() }}</i>@endif</h2>
    <p class="sub">Pastikan identitas pemohon benar (bertemu langsung atau lewat kanal yang kamu percaya) sebelum membuat kode. Kode berlaku {{ \App\Models\ResetSandi::MASA_BERLAKU_MENIT }} menit dan hanya sekali pakai.</p>
    <div class="items" style="padding:0;margin-top:12px">
    @forelse ($resets as $r)
        <div class="item"><div class="thumb">{{ mb_strtoupper(mb_substr($r->user->name, 0, 1)) }}</div>
            <div><b>{{ $r->user->name }}</b> <span class="tag">{{ $r->user->role }}</span><small>{{ $r->user->login_id }}@if ($r->user->info) ({{ $r->user->info }})@endif - diminta {{ $r->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small><small>Status: {{ $r->status }}</small></div>
            <div class="ctl" style="display:flex;gap:8px;flex-wrap:wrap">
                <form method="POST" action="{{ route('admin.sandi.kode', $r) }}">@csrf<button class="btn">{{ $r->kodeAktif() ? 'Buat kode baru' : 'Buat kode' }}</button></form>
                <form method="POST" action="{{ route('admin.sandi.tolak', $r) }}">@csrf @method('DELETE')<button class="btn light" style="border-color:var(--line)">Hapus</button></form>
            </div></div>
    @empty <p class="sub">Tidak ada permintaan.</p>
    @endforelse
    </div></section>
<div class="two wr">
    <section class="card compact"><h2>Teknisi</h2><p class="sub">Atur jurusan tiap teknisi dan hapus akun yang tidak dipakai. Skor maksimal 150, kelebihan poin tampil sebagai +N.</p>
        <div class="tlist">
        @forelse ($teknisis as $t)
            <div class="trow">
                <div class="tname"><b>{{ $t->name }}</b><small>{{ $t->login_id }}</small></div>
                <span class="tskor">{{ $t->skor }}@if ($t->bonus > 0) <span style="color:var(--ok)">+{{ $t->bonus }}</span>@endif</span>
                <form method="POST" action="{{ route('admin.teknisi.jurusan', $t) }}" class="tjur">@csrf @method('PATCH')
                    <select name="jurusan" aria-label="Jurusan {{ $t->name }}" onchange="this.form.requestSubmit()">
                        @unless ($t->jurusan)<option value="">Belum diatur</option>@endunless
                        @foreach (config('jurusan') as $j)<option @selected($t->jurusan === $j)>{{ $j }}</option>@endforeach
                    </select></form>
                <button type="button" class="btn light del" data-hapus="{{ route('admin.teknisi.hapus', $t) }}" data-nama="{{ $t->name }}">Hapus</button>
            </div>
        @empty <p class="sub" style="margin-top:10px">Belum ada akun teknisi.</p>
        @endforelse
        </div></section>
    <section class="card compact"><h2>Rating dari pelapor</h2>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        <p class="sub">@if ($ratings->count()) Rata-rata {{ number_format($ratings->avg('nilai'), 1, ',', '') }} dari 5 ({{ $ratings->count() }} penilaian) @else Belum ada penilaian. @endif</p>
        <div style="margin-top:8px">
        @foreach ($ratings as $r)
            <div class="rate" style="flex-wrap:wrap"><span>{{ $r->user->name }}</span><span aria-label="{{ $r->nilai }} dari 5">{{ str_repeat('★', $r->nilai) }}{{ str_repeat('☆', 5 - $r->nilai) }}</span>@if ($r->komentar)<small style="width:100%;color:var(--mut)">{{ $r->komentar }}</small>@endif</div>
        @endforeach
        </div></section>
<<<<<<< HEAD
    <section class="card"><h2>Skor teknisi</h2><p class="sub">Maksimal 150. Kelebihan poin tampil sebagai +N.</p>
        <div style="margin-top:8px">
        @forelse ($teknisis as $t)
            <div class="rate"><span>{{ $t->name }}</span><span style="color:var(--ink);font-weight:600">{{ $t->skor }}@if ($t->bonus > 0) <span style="color:var(--ok)">+{{ $t->bonus }}</span>@endif</span></div>
        @empty <p class="sub">Belum ada akun teknisi.</p>
        @endforelse
        </div></section>
</div>
=======
</div>
<section class="card roomy" id="riwayat" style="margin-bottom:16px"><h2>Riwayat laporan</h2>
    <p class="sub">Semua laporan yang pernah masuk ({{ $riwayat->total() }} laporan sesuai filter di atas), termasuk yang sudah selesai.</p>
    <div class="items" style="padding:0;margin-top:14px">
    @forelse ($riwayat as $p)
        <div class="item rich"><div class="thumb">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->inisial }}@endif</div>
            <div class="body">
                <div class="meta"><b>{{ $p->kategori }} {{ $p->lokasi }}</b><span class="when">{{ $p->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</span></div>
                <p class="desk">{{ $p->deskripsi }}</p>
                <small>Pelapor: {{ $p->pelapor->name ?? '-' }}@if ($p->dana !== null) - dana {{ $p->dana_rp }}@endif @if ($p->teknisi_label) - teknisi {{ $p->teknisi_label }}@endif</small>
                @include('partials.video-btn', ['p' => $p])
                <span class="badge {{ $p->warna }}" style="margin-top:6px">{{ $p->label }}</span>
            </div></div>
    @empty <p class="sub">Belum ada laporan yang cocok dengan filter.</p>
    @endforelse
    </div>
    @if ($riwayat->hasPages())
    <nav class="pager" aria-label="Halaman riwayat">
        @if ($riwayat->onFirstPage())<span class="btn light off">&lsaquo; Sebelumnya</span>@else<a class="btn light" href="{{ $riwayat->previousPageUrl() }}">&lsaquo; Sebelumnya</a>@endif
        <span class="sub">Halaman {{ $riwayat->currentPage() }} dari {{ $riwayat->lastPage() }}</span>
        @if ($riwayat->hasMorePages())<a class="btn light" href="{{ $riwayat->nextPageUrl() }}">Berikutnya &rsaquo;</a>@else<span class="btn light off">Berikutnya &rsaquo;</span>@endif
    </nav>
    @endif
</section>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
<section class="card" style="margin-bottom:16px"><h2>Komentar dari pelapor <i class="nb" data-nb-total data-nb-wrap @if ($komentar->sum('belum_dibaca') === 0) hidden @endif>{{ $komentar->sum('belum_dibaca') }}</i></h2>
    <p class="sub">Komentar dari halaman Kontak. Buka chat untuk membalas; pelapor akan menerima notifikasi. @if ($komentar->count()) {{ $komentar->count() }} percakapan terbaru. @else Belum ada komentar. @endif</p>
    <div class="items" style="padding:0;margin-top:12px">
    @foreach ($komentar as $k)
        <div class="item kom @if ($k->belum_dibaca) has-new @endif" data-kid="{{ $k->id }}"><div class="thumb">@if ($k->user->foto_url)<img src="{{ $k->user->foto_url }}" alt="">@else{{ mb_strtoupper(mb_substr($k->user->name, 0, 1)) }}@endif</div>
<<<<<<< HEAD
            <div><b>{{ $k->user->name }}</b> <small style="display:inline">{{ $k->user->email }} - {{ $k->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
=======
            <div><b>{{ $k->user->name }}</b> <small style="display:inline">{{ $k->user->login_id }} {{ $k->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
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
<<<<<<< HEAD
<label for="rl">Peran</label><select id="rl" name="role"><option value="kepsek">Kepala Sekolah</option><option value="teknisi">Teknisi</option></select>
<p style="margin-top:18px;display:flex;gap:10px;justify-content:center"><button class="btn">Simpan akun</button><button type="button" class="btn light" style="border-color:var(--line)" onclick="ak.close()">Batal</button></p></form></dialog>
@endsection
@push('scripts')
<script>@if ($errors->akun->any()) ak.showModal(); @endif</script>
@endpush
=======
<label for="rl">Peran</label><select id="rl" name="role"><option value="kepsek" @selected(old('role') === 'kepsek')>Kepala Sekolah</option><option value="teknisi" @selected(old('role') === 'teknisi')>Teknisi</option></select>
<div id="jr-wrap" hidden><label for="jr">Jurusan teknisi</label><select id="jr" name="jurusan"><option value="">Pilih jurusan</option>@foreach (config('jurusan') as $j)<option @selected(old('jurusan') === $j)>{{ $j }}</option>@endforeach</select></div>
<p style="margin-top:18px;display:flex;gap:10px;justify-content:center"><button class="btn">Simpan akun</button><button type="button" class="btn light" style="border-color:var(--line)" onclick="ak.close()">Batal</button></p></form></dialog>
<dialog id="hdlg"><h2>Hapus teknisi?</h2><p id="hnama">Akun ini akan dihapus. Riwayat laporannya tetap tersimpan dengan namanya.</p>
<form method="POST" id="hform" action="" style="margin:0">@csrf @method('DELETE')<button class="btn">Ya, hapus</button> <button type="button" class="btn light" style="border-color:var(--line)" onclick="hdlg.close()">Batal</button></form></dialog>
@if (session('kode_sandi'))
<dialog id="kdlg"><h2>Kode untuk {{ session('kode_sandi')['nama'] }}</h2><p>{{ session('kode_sandi')['akun'] }}<br>Sampaikan langsung kepada yang bersangkutan. Kode ini hanya ditampilkan sekali dan berlaku {{ \App\Models\ResetSandi::MASA_BERLAKU_MENIT }} menit.</p>
<div class="kode" id="kode-teks">{{ session('kode_sandi')['kode'] }}</div>
<p style="margin:16px 0 0"><button type="button" class="btn" id="kcopy">Salin kode</button> <button type="button" class="btn light" style="border-color:var(--line)" onclick="kdlg.close()">Tutup</button></p></dialog>
@endif
@endsection
@push('scripts')
<script>
const rl=document.getElementById('rl'),jw=document.getElementById('jr-wrap'),jr=document.getElementById('jr');
const sj=()=>{const t=rl.value==='teknisi';jw.hidden=!t;jr.required=t};rl.onchange=sj;sj();
@if ($errors->akun->any()) ak.showModal(); @endif
document.querySelectorAll('[data-hapus]').forEach(b=>b.onclick=()=>{hform.action=b.dataset.hapus;hnama.textContent='Akun teknisi '+b.dataset.nama+' akan dihapus. Riwayat laporannya tetap tersimpan dengan namanya.';hdlg.showModal()});
@if (session('kode_sandi'))
kdlg.showModal();
kcopy.onclick=()=>{const t=document.getElementById('kode-teks').textContent.trim();(navigator.clipboard?navigator.clipboard.writeText(t):Promise.reject()).then(()=>toast('Kode disalin')).catch(()=>{const r=document.createRange();r.selectNodeContents(document.getElementById('kode-teks'));const s=getSelection();s.removeAllRanges();s.addRange(r);toast('Pilih lalu salin kode secara manual')})};
@endif
</script>
@endpush
>>>>>>> d2ba4ff (Initial commit adukan vercel)
