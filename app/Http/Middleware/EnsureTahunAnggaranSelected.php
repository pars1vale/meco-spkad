<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTahunAnggaranSelected
{
    public function handle(Request $request, Closure $next)
    {
        // Jika sudah login tapi belum pilih tahun anggaran
        if (auth()->check() && ! session()->has('tahun_anggaran')) {
            // Izinkan akses ke route pilih tahun & logout saja
            if (! $request->routeIs('tahun-anggaran.*') && ! $request->routeIs('logout')) {
                return redirect()->route('tahun-anggaran.pilih');
            }
        }

        return $next($request);
    }
}
