<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class Setup
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

        if(!User::all()->count()) {
            if($request->route()->uri() != 'install') {
                return redirect('/install');
            }
        }

        return $next($request);
    }
}
