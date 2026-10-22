@extends('layouts.app')
@section('title','Kontak')
@section('page','kontak')
@section('content')
@php
    $alamat = 'Jl. Lu. Adisucipto No. 42, Surakarta, Jawa Tengah';
    $q = urlencode('SMK Negeri 5 Surakarta, ' . $alamat);
@endphp
<main class="page">
<header><h1>Kontak</h1></header>
<p class="lead" style="margin-bottom:24px">Ada pertanyaan tentang pengaduan Anda? Hubungi petugas Sarana dan Prasarana sekolah atau tinggalkan komentar di bawah.</p>
<div class="two">
    <section class="card kontak">
        <span class="ico" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
        <h2>Email</h2>
        <p class="sub">Kirim pertanyaan atau informasi tambahan tentang laporan Anda.</p>
        <p><a class="tel" href="mailto:sarpras{{ '@' }}sekolah.sch.id">sarpras{{ '@' }}sekolah.sch.id</a></p>
        <a class="btn" href="mailto:sarpras{{ '@' }}sekolah.sch.id">Kirim email</a>
    </section>
    <section class="card kontak">
        <span class="ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg></span>
        <h2>Petugas</h2>
        <p class="sub">Penanganan sarana dan prasarana sekolah.</p>
        <p><b>Ruang Sarana dan Prasarana</b><br>SMK Negeri 5 Surakarta</p>
        <a class="btn light" style="border-color:var(--line)" href="{{ route('laporan') }}">Cek status laporan</a>
    </section>
</div>

<section class="card kontak lokasi" style="margin-bottom:16px">
    <span class="ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
    <h2>Lokasi</h2>
    <p><b>SMK Negeri 5 Surakarta</b><br>{{ $alamat }}</p>
    <div class="map"><iframe src="https://maps.google.com/maps?q={{ $q }}&amp;output=embed" title="Peta lokasi SMK Negeri 5 Surakarta" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
    <a class="btn light" style="border-color:var(--line)" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&amp;query={{ $q }}">Buka di Google Maps</a>
</section>

@if (auth()->user()->role === 'pelapor')
<section class="card kontak" id="komentar">
    <span class="ico" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"/></svg></span>
    <h2>Kirim komentar</h2>
    <p class="sub">Saran, masukan, atau pertanyaan untuk petugas. Komentar Anda menjadi awal percakapan; balasan admin muncul di ikon pesawat kertas di pojok kanan bawah.</p>
    <form id="kf" method="POST" action="{{ route('komentar.simpan') }}" novalidate>@csrf
        <label for="pesan">Komentar</label>
        <textarea id="pesan" name="pesan" rows="4" maxlength="500" placeholder="Tulis komentar Anda di sini">{{ old('pesan') }}</textarea>
        <div class="err" id="ep" role="alert">@error('pesan'){{ $message }}@enderror</div>
        <div class="kbar"><span class="sub" id="cnt">0 / 500</span><button class="btn" id="ks" disabled>Kirim komentar</button></div>
    </form>
    @if ($riwayat->count())
        <h3 style="margin:22px 0 8px;font-size:15px">Percakapan Anda</h3>
        <div class="items" style="padding:0">
        @foreach ($riwayat as $k)
            <div class="item"><div><small>{{ $k->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</small><p style="white-space:pre-line;overflow-wrap:anywhere">{{ $k->pesan }}</p></div>
                <button type="button" class="btn light" style="border-color:var(--line)" data-chat-open="{{ $k->id }}">Buka chat <i class="nb" data-nb="{{ $k->id }}" @if (! $k->belum_dibaca) hidden @endif>{{ $k->belum_dibaca }}</i></button></div>
        @endforeach
        </div>
    @endif
</section>
@endif

<h2 data-reveal="words" style="margin:28px 0 12px">Pertanyaan umum</h2>
<details open><summary>Bagaimana alur pengaduan diproses?</summary><div class="items"><p>Admin memverifikasi laporan, Kepala Sekolah menyetujui perbaikan, lalu teknisi mengerjakannya. Setelah teknisi menandai selesai, Anda diminta mengonfirmasi bahwa perbaikan benar-benar tuntas.</p></div></details>
<details><summary>Di mana saya melihat status laporan?</summary><div class="items"><p>Buka menu <a href="{{ route('laporan') }}" style="color:var(--red);font-weight:500">Laporan</a>. Laporan dikelompokkan menjadi Menunggu, Diproses, dan Selesai.</p></div></details>
<details><summary>Apa yang perlu saya tulis saat membuat pengaduan?</summary><div class="items"><p>Pilih kategori kerusakan, pilih ruang atau kelas yang rusak, dan jelaskan kerusakannya. Foto bukti bersifat opsional tetapi membantu petugas memahami masalahnya. <a href="{{ route('laporan.buat') }}" style="color:var(--red);font-weight:500">Buat pengaduan</a></p></div></details>
</main>
@endsection
@push('scripts')
<script>
const ps=document.getElementById('pesan'),ks=document.getElementById('ks'),cnt=document.getElementById('cnt'),ep=document.getElementById('ep');
if(ps){const up=()=>{const n=ps.value.trim().length;cnt.textContent=ps.value.length+' / 500';ks.disabled=n<3};
ps.oninput=()=>{ep.textContent='';up()};
document.getElementById('kf').onsubmit=()=>{ks.disabled=true;ks.textContent='Mengirim...'};up()}
</script>
@endpush
