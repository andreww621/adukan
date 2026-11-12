<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class Maintenance
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Saat maintenance aktif, hanya pelapor yang diblokir. Petugas tetap bisa masuk.
        if ($user && $user->role === 'pelapor' && Setting::maintenance() && ! $request->routeIs('logout')) {
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
