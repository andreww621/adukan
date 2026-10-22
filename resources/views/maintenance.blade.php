@extends('layouts.app')
@section('title','Maintenance')
@section('page','maint')
@section('nofooter','1')
@section('nochat','1')
@section('content')
<main class="maint">
    <img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
    <h1>Sedang dalam perawatan</h1>
    <p>Adukan! sedang diperbarui oleh petugas. Silakan coba lagi beberapa saat lagi.</p>
    <button class="btn" data-logout>Keluar</button>
</main>
@endsection
