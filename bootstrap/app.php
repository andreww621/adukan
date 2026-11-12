<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
<<<<<<< HEAD
       $middleware->redirectGuestsTo(fn () => route('register')); // tamu belum login -> /daftar
=======
       $middleware->trustProxies(at: '*');                         // percaya proxy ngrok (https)
       $middleware->redirectGuestsTo(fn () => route('login'));    // tamu belum login -> /masuk
>>>>>>> d2ba4ff (Initial commit adukan vercel)
       $middleware->redirectUsersTo(fn () => route('home'));      // sudah login buka /masuk -> beranda
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
<<<<<<< HEAD
    })->create();
=======
    })->create();
>>>>>>> d2ba4ff (Initial commit adukan vercel)
