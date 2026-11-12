@guest
<header class="top">
<<<<<<< HEAD
    <a href="{{ route('register') }}"><img src="{{ asset('assets/img/logo-horizontal.png') }}" alt="Adukan!"></a>
    <nav><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'on' : '' }}">Masuk</a><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'on' : '' }}">Daftar</a></nav>
=======
    <a href="{{ route('login') }}"><img src="{{ asset('assets/img/logo-horizontal.png') }}" alt="Adukan!"></a>
    <nav><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'on' : '' }}">Masuk</a></nav>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
</header>
@else
@php $role = auth()->user()->role; $initial = strtoupper(mb_substr(auth()->user()->name, 0, 1)); $fotoUrl = auth()->user()->foto_url; @endphp
@if ($role !== 'pelapor')
<header class="top">
    <a href="{{ route('dashboard', $role) }}"><img src="{{ asset('assets/img/logo-horizontal.png') }}" alt="Adukan! - dashboard"></a>
    <nav><a href="{{ route('dashboard', $role) }}" class="on">Dashboard</a></nav>
    <div class="ava">
        <button aria-label="Menu akun" aria-expanded="false">@if ($fotoUrl)<img src="{{ $fotoUrl }}" alt="">@else{{ $initial }}@endif</button>
<<<<<<< HEAD
        <div class="menu"><button class="out" data-logout>Keluar</button></div>
=======
        <div class="menu"><a href="{{ route('profil') }}">Profil saya</a><button class="out" data-logout>Keluar</button></div>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    </div>
</header>
@else
@php
    $nav = [
        ['home', 'Beranda', route('home')],
        ['laporan*', 'Laporan', route('laporan')],
        ['profil', 'Profil', route('profil')],
        ['kontak', 'Kontak', route('kontak')],
    ];
@endphp
<header class="top">
    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-horizontal.png') }}" alt="Adukan! - beranda"></a>
    <nav>
        @foreach ($nav as [$pattern, $label, $url])
            <a href="{{ $url }}" class="{{ $pattern && request()->routeIs($pattern) ? 'on' : '' }}">{{ $label }}</a>
        @endforeach
    </nav>
    <div class="ava">
        <button aria-label="Menu akun" aria-expanded="false">@if ($fotoUrl)<img src="{{ $fotoUrl }}" alt="">@else{{ $initial }}@endif</button>
        <div class="menu">
            @foreach ($nav as [, $label, $url])
                <a class="m" href="{{ $url }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('profil') }}">Profil saya</a>
            <button class="out" data-logout>Keluar</button>
        </div>
    </div>
</header>
@endif
@endguest
