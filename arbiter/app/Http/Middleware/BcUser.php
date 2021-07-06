<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class BcUser
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
        if(Auth::user()->role->name == 'BC' || Auth::user()->role->name == 'Admin') {
            return $next($request);
        } else {
            return abort(403, 'User not bc.');
        }
    }
}
