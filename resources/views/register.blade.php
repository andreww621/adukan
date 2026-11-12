@extends('layouts.app')
@section('title','Daftar')
@section('page','register')
@section('nofooter','1')
@section('content')
<main class="split"><div></div>
<form method="POST" action="{{ route('register.store') }}">
@csrf
<img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
<h1>Buat akun</h1>
<p style="color:var(--mut)">Daftar sebagai pelapor untuk mengadukan kerusakan sarana sekolah.</p>
<label for="name">Nama lengkap</label><input id="name" name="name" value="{{ old('name') }}" required autocomplete="name">
@error('name')<div class="err">{{ $message }}</div>@enderror
<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
@error('email')<div class="err">{{ $message }}</div>@enderror
<label for="password">Kata sandi</label><input id="password" name="password" type="password" minlength="8" required autocomplete="new-password">
@error('password')<div class="err">{{ $message }}</div>@enderror
<label for="password_confirmation">Ulangi kata sandi</label><input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
<p style="margin-top:20px"><button class="btn" style="width:100%">Daftar</button></p>
<p style="margin-top:14px;text-align:center">Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--red);font-weight:500">Masuk</a></p>
</form></main>
@endsection
