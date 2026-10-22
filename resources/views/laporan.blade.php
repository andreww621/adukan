@extends('layouts.app')
@section('title','Laporan saya')
@section('page','laporan')
@section('content')
<main class="page"><header><h1>Laporan saya</h1><a class="btn" href="{{ route('laporan.buat') }}">Buat pengaduan</a></header>
@php
    $grup = ['Menunggu' => [['menunggu', 'diverifikasi', 'disetujui'], 's-wait'], 'Diproses' => [['dikerjakan', 'menunggu_konfirmasi'], 's-proc'], 'Selesai' => [['selesai'], 's-done']];
@endphp
@foreach ($grup as $nama => [$status, $kelas])
    @php $list = $items->whereIn('status', $status); @endphp
    <details @if ($loop->first || $list->contains('status', 'menunggu_konfirmasi')) open @endif>
        <summary><span class="badge {{ $kelas }}" style="margin:0">{{ $nama }}</span>{{ $list->count() }} laporan</summary>
        <div class="items">
        @forelse ($list as $p)
            <div class="item">
                <div class="thumb">@if ($p->foto_url)<img src="{{ $p->foto_url }}" alt="Foto laporan">@else{{ $p->kategori[0] }}@endif</div>
                <div>
                    <b>{{ $p->kategori }} - {{ $p->lokasi }}</b>
                    <small>{{ $p->deskripsi }}</small>
                    @include('partials.video-btn', ['p' => $p])
                    <small>{{ $p->label }}@if ($p->teknisi) - teknisi {{ $p->teknisi->name }}@endif</small>
                    @if ($p->status === 'menunggu_konfirmasi')
                        <div class="ask"><span>Teknisi menandai pekerjaan ini selesai. Benarkah?</span>
                            <form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="selesai"><button class="btn">Sudah selesai</button></form>
                            <form method="POST" action="{{ route('pengaduan.konfirmasi', $p) }}">@csrf<input type="hidden" name="hasil" value="belum"><button class="btn light" style="border-color:var(--line)">Belum selesai</button></form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="sub">Belum ada laporan di sini.</p>
        @endforelse
        </div>
    </details>
@endforeach
</main>
@endsection
