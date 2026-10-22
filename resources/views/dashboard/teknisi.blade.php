@extends('layouts.app')
@section('title','Dashboard Teknisi')
@section('page','dash')
@section('content')
@php $u = auth()->user(); @endphp
<main class="page" style="max-width:720px">
<header><h1>Dashboard Teknisi</h1></header>
<section class="card skor"><p class="sub">Skor kinerja</p>
<b>{{ $u->skor }}</b><span style="color:var(--mut)"> / {{ \App\Services\SkorTeknisi::MAKS }}</span>
@if ($u->bonus > 0)<div class="plus">+{{ $u->bonus }}</div>@endif
<p class="sub">Pekerjaan terkonfirmasi selesai: +{{ \App\Services\SkorTeknisi::HADIAH }} poin. Menandai selesai padahal belum selesai: -{{ \App\Services\SkorTeknisi::HUKUMAN }} poin.</p></section>
<section class="card"><h2>Permintaan dari Kepala Sekolah</h2>
<p class="sub">Tekan Proses saat mulai bekerja, lalu Selesai setelah perbaikan benar-benar tuntas.</p>
<div class="items" style="padding:0;margin-top:12px">
@forelse ($tugas as $p)
    <div class="item"><div class="thumb">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->kategori[0] }}@endif</div>
        <div><span class="badge {{ $p->warna }}">{{ $p->label }}</span><b style="display:block">{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->deskripsi }}</small><small>Dana diterima {{ $p->dana_rp }}</small>@include('partials.video-btn', ['p' => $p])</div>
        @if ($p->status === 'disetujui')
            <form method="POST" action="{{ route('teknisi.proses', $p) }}">@csrf<button class="btn">Proses</button></form>
        @elseif ($p->status === 'dikerjakan')
            <form method="POST" action="{{ route('teknisi.selesai', $p) }}">@csrf<button class="btn">Selesai</button></form>
        @endif</div>
@empty
    <p class="sub">Belum ada permintaan baru.</p>
@endforelse
</div></section>
</main>
@endsection
