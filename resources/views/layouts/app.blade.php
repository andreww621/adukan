<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beranda') - Adukan!</title>
    <link rel="icon" href="{{ asset('assets/img/logo-stacked.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <script>document.documentElement.classList.add('js')</script>
</head>
<body data-page="@yield('page')" data-login="{{ route('login') }}">
    @include('partials.header')
    @auth<form id="lo" method="POST" action="{{ route('logout') }}" hidden>@csrf</form>@endauth
    @yield('content')
    @hasSection('nofooter') @else @include('partials.footer') @endif
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @auth
        @if (in_array(auth()->user()->role, ['pelapor', 'admin'], true))
            @hasSection('nochat') @else
            <script>window.CHAT = {role: @json(auth()->user()->role), base: @json(url('/chat')), open: @json(session('buka_chat')), logo: @json(asset('assets/img/logo-stacked.png'))};</script>
            <script src="{{ asset('assets/js/chat.js') }}" defer></script>
            @endif
        @endif
    @endauth
    @if (session('status'))<script>toast(@json(session('status')))</script>@endif
    @stack('scripts')
</body>
</html>
