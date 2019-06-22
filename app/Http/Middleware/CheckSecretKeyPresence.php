<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

/**
 * This class checks if the secret key is set in the cookie of the browser.
 * If it’s not set, it displays the page asking the user to set the secret key.
 */
class CheckSecretKeyPresence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Cookie::has('trump_is_a_dick') == false) {
            return redirect('/secret');
        }

        return $next($request);
    }
}
