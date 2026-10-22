@if (auth()->check() && auth()->user()->role !== 'pelapor')
<footer>
    <div class="fgrid compact">
        <img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!" style="width:80px">
        <p>Adukan! Sistem Pengaduan Sarana Sekolah</p>
    </div>
    <p class="copy">&copy; {{ date('Y') }} Adukan!</p>
</footer>
@else
<footer>
    <div class="fgrid">
        <img src="{{ asset('assets/img/logo-stacked.png') }}" alt="Adukan!">
        <div>
            <h3>Halaman</h3>
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('laporan') }}">Laporan saya</a>
            <a href="{{ route('laporan.buat') }}">Buat pengaduan</a>
            <a href="{{ route('profil') }}">Profil</a>
            <a href="{{ route('kontak') }}">Kontak</a>
        </div>
        <div>
            <h3>Kontak</h3>
            <p>Ruang Sarana dan Prasarana</p>
            <a href="mailto:sarpras{{ '@' }}sekolah.sch.id">sarpras{{ '@' }}sekolah.sch.id</a>
        </div>
    </div>
    <p class="copy">&copy; {{ date('Y') }} Adukan! Sistem Pengaduan Sarana Sekolah.</p>
</footer>
@endif
