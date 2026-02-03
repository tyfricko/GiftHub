<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * If the authenticated user's email is not verified, store the intended URL
     * and redirect them to the verification notice page with a friendly message.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && ! $user->hasVerifiedEmail()) {
            // Preserve the intended URL so we can redirect after verification
            $request->session()->put('url.intended', url()->full());

            return redirect()->route('home')
                ->with('warning', 'Prosimo, potrdite svoj e-poštni naslov, da lahko nadaljujete z ustvarjanjem seznamov želja ali dogodkov.');
        }

        return $next($request);
    }
}
