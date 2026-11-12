@extends('layouts.app')
@section('title','Lupa kata sandi')
@section('page','login')
@section('nofooter','1')
@section('content')
<main class="split"><div></div>
<form method="POST" action="{{ route('sandi.kirim') }}">
@csrf
<img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
<h1>Lupa kata sandi</h1>
<p style="color:var(--mut)">Masukkan NISN atau NIP akunmu (akun petugas memakai email). Permintaan akan diteruskan ke admin, yang akan memberi kode sekali pakai setelah memastikan identitasmu.</p>
<label for="identitas">NISN / NIP / Email</label><input id="identitas" name="identitas" value="{{ old('identitas') }}" required autocomplete="username" autocapitalize="none" spellcheck="false">
@error('identitas')<div class="err">{{ $message }}</div>@enderror
<p style="margin-top:20px"><button class="btn" style="width:100%">Kirim permintaan</button></p>
<p style="margin-top:14px;text-align:center">Sudah punya kode? <a href="{{ route('sandi.atur') }}" style="color:var(--red);font-weight:500">Atur ulang sandi</a></p>
<p style="margin-top:6px;text-align:center"><a href="{{ route('login') }}" style="color:var(--red);font-weight:500">Kembali ke halaman masuk</a></p>
</form></main>
@endsection
