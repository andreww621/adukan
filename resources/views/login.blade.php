@extends('layouts.app')
@section('title','Masuk')
@section('page','login')
@section('nofooter','1')
@section('content')
<main class="split"><div></div>
<form method="POST" action="{{ route('login.store') }}">
@csrf
<img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
<h1>Masuk</h1>
<label for="role">Masuk sebagai</label>
<select id="role" name="role">
@foreach (['pelapor' => 'Pelapor', 'admin' => 'Admin / Petugas', 'kepsek' => 'Kepala Sekolah', 'teknisi' => 'Teknisi'] as $value => $label)
    <option value="{{ $value }}" @selected(old('role', 'pelapor') === $value)>{{ $label }}</option>
@endforeach
</select>
<label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">
@error('email')<div class="err">{{ $message }}</div>@enderror
<label for="password">Kata sandi</label><input id="password" name="password" type="password" required autocomplete="current-password">
<label style="display:flex;gap:8px;align-items:center;font-weight:400"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label>
<p style="margin-top:20px"><button class="btn" style="width:100%">Masuk</button></p>
<p style="margin-top:14px;text-align:center">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--red);font-weight:500">Daftar</a></p>
</form></main>
@endsection
