@extends('layouts.app')
@section('title','Atur ulang kata sandi')
@section('page','login')
@section('nofooter','1')
@section('content')
<main class="split"><div></div>
<form method="POST" action="{{ route('sandi.simpan') }}">
@csrf
<img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
<h1>Atur ulang kata sandi</h1>
<p style="color:var(--mut)">Isi kode dari admin (berlaku {{ \App\Models\ResetSandi::MASA_BERLAKU_MENIT }} menit) dan kata sandi barumu.</p>
<label for="identitas">NISN / NIP / Email</label><input id="identitas" name="identitas" value="{{ old('identitas') }}" required autocomplete="username" autocapitalize="none" spellcheck="false">
@error('identitas')<div class="err">{{ $message }}</div>@enderror
<label for="kode">Kode dari admin</label><input id="kode" name="kode" value="{{ old('kode') }}" required autocomplete="off" autocapitalize="characters" spellcheck="false" maxlength="20" style="letter-spacing:.18em;text-transform:uppercase;font-weight:600">
@error('kode')<div class="err">{{ $message }}</div>@enderror
<label for="password">Kata sandi baru</label><input id="password" name="password" type="password" minlength="8" required autocomplete="new-password">
@error('password')<div class="err">{{ $message }}</div>@enderror
<label for="password_confirmation">Ulangi kata sandi baru</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
<p style="margin-top:20px"><button class="btn" style="width:100%">Simpan kata sandi</button></p>
<p style="margin-top:14px;text-align:center"><a href="{{ route('sandi.lupa') }}" style="color:var(--red);font-weight:500">Belum minta kode?</a> &middot; <a href="{{ route('login') }}" style="color:var(--red);font-weight:500">Masuk</a></p>
</form></main>
@endsection
