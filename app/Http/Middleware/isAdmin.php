<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class isAdmin
{

// http://www.robert-askam.co.uk/posts/post/creating-admin-middleware-in-laravel-53

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if ( Auth::check() && Auth::user()->isAdmin() )  {
            return $next($request);
        }
        return redirect('home');
    }
}
