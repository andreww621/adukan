@extends('layouts.app')
@section('title','Dashboard Kepala Sekolah')
@section('page','dash')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<main class="page" style="max-width:720px">
<header><h1>Dashboard Kepala Sekolah</h1></header>
@unless ($aktif)<div class="card" style="border-color:var(--err);margin-bottom:16px">Anggaran belum tersedia, jadi dana belum bisa dikirim. Hubungi admin.</div>@endunless
<section class="card" style="margin-bottom:16px"><h2>Menunggu persetujuan</h2>
<p class="sub">Sisa anggaran: <b>{{ $rp($sisa) }}</b>. Setujui laporan untuk meneruskannya ke teknisi beserta dananya.</p>
<div class="items" style="padding:0;margin-top:12px">
@forelse ($antrean as $p)
    <div class="item"><div class="thumb">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->kategori[0] }}@endif</div><div><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->pelapor->name }}: {{ $p->deskripsi }}</small><small>Perkiraan dana {{ $p->dana_rp }}</small>@include('partials.video-btn', ['p' => $p])</div><a class="btn" href="{{ route('kepsek.payment', $p) }}">Setujui dan kirim dana</a></div>
@empty
    <p class="sub">Belum ada laporan yang perlu disetujui.</p>
@endforelse
</div></section>
<section class="card"><h2>Riwayat pembayaran</h2>
<div class="items" style="padding:0;margin-top:12px">
@forelse ($riwayat as $p)
    <div class="item"><div class="thumb">{{ $p->kategori[0] }}</div><div><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->dana_rp }} ke {{ $p->teknisi->name ?? '-' }} pada {{ $p->dibayar_at->format('d/m/Y H:i') }}</small></div><span class="badge {{ $p->warna }}" style="margin:0 0 0 auto">{{ $p->label }}</span></div>
@empty
    <p class="sub">Belum ada pembayaran.</p>
@endforelse
</div></section>
</main>
@endsection
