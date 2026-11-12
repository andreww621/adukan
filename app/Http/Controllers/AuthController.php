<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister() { return view('register'); }
    public function showLogin()    { return view('login'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Akun baru selalu berperan "pelapor" (default kolom role).
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
            'role'     => ['required', 'in:pelapor,admin,kepsek,teknisi'],
        ]);

        // Peran yang dipilih harus cocok dengan peran akun di database.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email, kata sandi, atau peran yang dipilih tidak cocok.'])
                ->onlyInput('email', 'role');
        }

        $request->session()->regenerate();

        return redirect()->intended($this->homeFor(Auth::user()));
=======
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tidak ada pendaftaran: akun siswa, guru, dan petugas dibuat oleh sekolah.
    public function showLogin() { return view('login'); }

    public function login(Request $request)
    {
        $data = $request->validate([
            'identitas' => ['required', 'string', 'max:150'],
            'password'  => ['required', 'string', 'max:200'],
        ]);

        // Dibatasi per akun + alamat IP (bukan IP saja, karena satu kelas sering memakai WiFi yang sama).
        $kunci = 'login:' . Str::lower($data['identitas']) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($kunci, 8)) {
            return back()
                ->withErrors(['identitas' => 'Terlalu banyak percobaan. Coba lagi dalam ' . RateLimiter::availableIn($kunci) . ' detik.'])
                ->onlyInput('identitas');
        }

        $user = User::kandidatLogin($data['identitas'])->first(fn (User $u) => $u->cekSandi($data['password']));

        if (! $user) {
            RateLimiter::hit($kunci, 60);

            return back()
                ->withErrors(['identitas' => 'NISN/NIP/email atau kata sandi tidak cocok.'])
                ->onlyInput('identitas');
        }

        RateLimiter::clear($kunci);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->sandi_diganti_at === null) {
            $request->session()->flash('status', 'Kamu masih memakai kata sandi awal. Ganti di halaman Profil.');
        }

        return redirect()->intended($this->homeFor($user));
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

<<<<<<< HEAD
        return redirect()->route('register');
=======
        return redirect()->route('login');
>>>>>>> d2ba4ff (Initial commit adukan vercel)
    }

    private function homeFor(User $user): string
    {
        return $user->role === 'pelapor' ? route('home') : route('dashboard', $user->role);
    }
}
