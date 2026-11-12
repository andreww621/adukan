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
<<<<<<< HEAD
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
=======
<p style="color:var(--mut)">Input NISN / NIP dibawah</p>
<label for="identitas">NISN / NIP</label><input id="identitas" name="identitas" value="{{ old('identitas') }}" required autocomplete="username" autocapitalize="none" spellcheck="false">
@error('identitas')<div class="err">{{ $message }}</div>@enderror
<label for="password">Kata sandi</label><input id="password" name="password" type="password" required autocomplete="current-password">
<p class="sub" style="margin-top:6px">Kata sandi awal: <b>NISN_nama</b> (guru dan staf: <b>NIP_nama</b>), misalnya <b>0012345678_Budi Santoso</b>.</p>
<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-top:10px"><label style="display:flex;gap:8px;align-items:center;font-weight:400;margin:0"><input type="checkbox" name="remember" style="width:auto"> Ingat saya</label><a href="{{ route('sandi.lupa') }}" style="color:var(--red);font-weight:500;font-size:14px">Lupa kata sandi?</a></div>
<p style="margin-top:20px"><button class="btn" style="width:100%">Masuk</button></p>
<p class="sub" style="margin-top:14px;text-align:center">Belum bisa masuk? Hubungi admin sekolah.</p>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
</form></main>
@endsection
