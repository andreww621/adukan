@extends('layouts.app')
@section('title','Adukan!')
@section('page','home')
@section('content')
<section class="hero"><img src="{{ asset('assets/img/hero.jpg') }}" alt="">
<div class="hero-txt"><h1>Jangan didiamkan. Adukan!</h1>
<p class="rot" data-rot><span class="sr">Lampu mati. Keran bocor. Atap bocor.</span><span class="rot-box" aria-hidden="true"><b class="rot-cat">Listrik</b><span class="rot-word">Lampu mati.</span></span></p>
<p>Laporkan kerusakan sarana sekolah dengan foto bukti, lalu pantau perbaikannya sampai selesai.</p><a class="btn" href="{{ route('laporan.buat') }}">Buat pengaduan</a><a class="btn ghost" href="#alur">Lihat alurnya</a></div><span class="cue">Gulir ke bawah</span></section>
<section id="alur"><div class="pin"><div id="scene" aria-hidden="true"></div><div><h2 data-reveal="words" style="font-size:clamp(22px,3vw,30px);margin-bottom:16px">Dari laporan sampai selesai</h2><div class="steps">
<div class="step s-wait on"><span class="badge">Menunggu</span><h3>Laporkan kerusakan</h3><p>Pilih kategori, tulis lokasi, dan unggah foto. Admin langsung menerima laporan Anda.</p></div>
<div class="step s-proc"><span class="badge">Diproses</span><h3>Diverifikasi dan diperbaiki</h3><p>Admin memverifikasi, Kepala Sekolah menyetujui perbaikan, lalu teknisi mengerjakannya.</p></div>
<div class="step s-done"><span class="badge">Selesai</span><h3>Pantau sampai tuntas</h3><p>Status berubah otomatis dan Anda mendapat notifikasi saat perbaikan selesai.</p></div></div></div></div></section>
<section class="statband"><div class="wrap"><h2 class="big" data-reveal="words">Adukan! dalam angka</h2>
<p class="sr">{{ number_format($stat['masuk'], 0, ',', '.') }} laporan masuk, {{ number_format($stat['selesai'], 0, ',', '.') }} selesai.</p>
<div class="nums" aria-hidden="true">
<div class="num"><b data-count="{{ $stat['masuk'] }}">{{ number_format($stat['masuk'], 0, ',', '.') }}</b><span>laporan masuk</span></div>
<div class="num ok"><b data-count="{{ $stat['selesai'] }}">{{ number_format($stat['selesai'], 0, ',', '.') }}</b><span>laporan selesai</span></div></div></div></section>
<section class="sec"><div class="wrap" style="text-align:center"><h2 class="big" data-reveal="words">Apa saja yang bisa dilaporkan</h2><p class="lead" style="margin:0 auto">Tiga kategori kerusakan sarana yang paling sering terjadi di sekolah.</p>
<div class="circles">
<div class="circ"><div class="dot"><svg viewBox="0 0 24 24"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div><h3>Listrik</h3><p>Lampu mati, stopkontak rusak, kabel terkelupas.</p></div>
<div class="circ"><div class="dot"><svg viewBox="0 0 24 24"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5S12.5 5 12 2c-.5 3-2 4.9-4 6.5S5 13 5 15a7 7 0 0 0 7 7z"/></svg></div><h3>Sanitasi</h3><p>Keran bocor, toilet tersumbat, saluran air macet.</p></div>
<div class="circ"><div class="dot"><svg viewBox="0 0 24 24"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6"/></svg></div><h3>Bangunan</h3><p>Atap bocor, pintu atau jendela rusak, dinding retak.</p></div></div></div></section>
<section class="cta"><h2 data-reveal="words">Lihat sesuatu yang rusak?</h2><p>Membuat laporan hanya butuh satu menit.</p><a class="btn light" href="{{ route('laporan.buat') }}">Buat pengaduan</a></section>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="{{ asset('assets/js/scene3d.js') }}"></script>
@endpush
