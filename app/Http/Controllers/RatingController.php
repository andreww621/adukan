<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        abort_unless($request->user()->role === 'pelapor', 403);

        $data = $request->validate([
            'nilai'    => ['required', 'integer', 'between:1,5'],
            'komentar' => ['nullable', 'string', 'max:300'],
        ]);

        Rating::updateOrCreate(['user_id' => $request->user()->id], $data);

        return back()->with('status', 'Nilai Anda tersimpan');
    }
}
