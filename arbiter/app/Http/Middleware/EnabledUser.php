<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EnabledUser
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
        if(!Auth::user()) {
            return redirect('/login');
        }

        if(Auth::user()->is_enabled) {
            return $next($request);
        } else {
            return abort(403, 'User not enabled.');
        }
    }
}
