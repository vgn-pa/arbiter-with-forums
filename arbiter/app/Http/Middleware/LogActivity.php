<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use App\Activity;

class LogActivity
{
    private $urls = [
        'attacks/'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $record = false;
        $path = $request->path();

        foreach($this->urls as $url) {
            if(strstr($path, $url)) {
                $record = true;
            }
        }

        if($record) {
            $log = [
                'user_id' => Auth::user()->id,
                'url' => str_replace('api/v1', '', $path),
                'method' => $request->method(),
                'user_agent' => $request->server('HTTP_USER_AGENT'),
                'ip_address' => $request->ip(),
                'location' => ""
            ];
    
            Activity::create($log);
        }

        return $next($request);
    }
}
