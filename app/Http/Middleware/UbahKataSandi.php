<?php

namespace App\Http\Middleware;

use Closure;

class UbahKataSandi
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->password_reset) {
            if ($request->route()->getName() !== 'ubah_kata_sandi') {
                return redirect()->route('ubah_kata_sandi');
            }
        }

        return $next($request);
    }
}