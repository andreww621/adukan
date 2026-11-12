<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\KepsekController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RatingController;
<<<<<<< HEAD
=======
use App\Http\Controllers\SandiController;
>>>>>>> d2ba4ff (Initial commit adukan vercel)
use App\Http\Controllers\TeknisiController;
use App\Http\Middleware\Maintenance;
use Illuminate\Support\Facades\Route;

// Hanya untuk tamu (belum login).
Route::middleware('guest')->group(function () {
<<<<<<< HEAD
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.store');
});

// Semua halaman lain wajib login. Tamu otomatis diarahkan ke /daftar.
=======
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.store');

    // Lupa kata sandi (kode dari admin)
    Route::get('/lupa-sandi', [SandiController::class, 'lupa'])->name('sandi.lupa');
    Route::post('/lupa-sandi', [SandiController::class, 'kirim'])->middleware('throttle:5,1')->name('sandi.kirim');
    Route::get('/atur-ulang-sandi', [SandiController::class, 'atur'])->name('sandi.atur');
    Route::post('/atur-ulang-sandi', [SandiController::class, 'simpan'])->middleware('throttle:10,1')->name('sandi.simpan');
});

// Semua halaman lain wajib login. Tamu otomatis diarahkan ke /masuk.
>>>>>>> d2ba4ff (Initial commit adukan vercel)
Route::middleware(['auth', Maintenance::class])->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [PageController::class, 'home'])->name('home');

    // Pelapor
    Route::get('/laporan', [PengaduanController::class, 'index'])->name('laporan');
    Route::get('/laporan/buat', [PageController::class, 'buat'])->name('laporan.buat');
    Route::post('/laporan', [PengaduanController::class, 'store'])->name('pengaduan.simpan');
    Route::post('/pengaduan/{pengaduan}/konfirmasi', [PengaduanController::class, 'konfirmasi'])->name('pengaduan.konfirmasi');
    Route::get('/profil', [PageController::class, 'profil'])->name('profil');
    Route::post('/profil/foto', [ProfilController::class, 'foto'])->name('profil.foto');
    Route::delete('/profil/foto', [ProfilController::class, 'hapus'])->name('profil.foto.hapus');
<<<<<<< HEAD
=======
    Route::put('/profil/sandi', [ProfilController::class, 'sandi'])->middleware('throttle:10,1')->name('profil.sandi');
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    Route::get('/kontak', [PageController::class, 'kontak'])->name('kontak');
    Route::post('/kontak/komentar', [KomentarController::class, 'store'])->name('komentar.simpan');
    // Chat pelapor <-> admin (JSON untuk chat.js)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{komentar}', [ChatController::class, 'show'])->whereNumber('komentar')->name('chat.show');
    Route::post('/chat/{komentar}', [ChatController::class, 'kirim'])->whereNumber('komentar')->middleware('throttle:40,1')->name('chat.kirim');
    Route::post('/profil/rating', [RatingController::class, 'store'])->name('rating.simpan');

    // Dashboard per peran
    Route::get('/dashboard/{role}', [PageController::class, 'dashboard'])
        ->whereIn('role', ['admin', 'kepsek', 'teknisi'])->name('dashboard');

    // Admin
    Route::post('/dashboard/admin/akun', [AdminController::class, 'storeAkun'])->name('admin.akun');
<<<<<<< HEAD
=======
    Route::patch('/dashboard/admin/teknisi/{user}/jurusan', [AdminController::class, 'jurusan'])->whereNumber('user')->name('admin.teknisi.jurusan');
    Route::delete('/dashboard/admin/teknisi/{user}', [AdminController::class, 'hapusTeknisi'])->whereNumber('user')->name('admin.teknisi.hapus');
    Route::post('/dashboard/admin/sandi/{reset}/kode', [AdminController::class, 'kodeSandi'])->whereNumber('reset')->name('admin.sandi.kode');
    Route::delete('/dashboard/admin/sandi/{reset}', [AdminController::class, 'tolakSandi'])->whereNumber('reset')->name('admin.sandi.tolak');
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    Route::post('/dashboard/admin/anggaran', [AdminController::class, 'anggaran'])->name('admin.anggaran');
    Route::post('/dashboard/admin/maintenance', [AdminController::class, 'maintenance'])->name('admin.maintenance');
    Route::get('/dashboard/admin/ekspor', [AdminController::class, 'ekspor'])->name('admin.ekspor');
    Route::post('/pengaduan/{pengaduan}/verifikasi', [AdminController::class, 'verifikasi'])->name('pengaduan.verifikasi');

    // Kepala sekolah
    Route::get('/dashboard/kepsek/pembayaran/{pengaduan}', [KepsekController::class, 'form'])->name('kepsek.payment');
    Route::post('/dashboard/kepsek/pembayaran/{pengaduan}', [KepsekController::class, 'bayar'])->name('kepsek.bayar');

    // Teknisi
    Route::post('/pengaduan/{pengaduan}/proses', [TeknisiController::class, 'proses'])->name('teknisi.proses');
    Route::post('/pengaduan/{pengaduan}/selesai', [TeknisiController::class, 'selesai'])->name('teknisi.selesai');
});
