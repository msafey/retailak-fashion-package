<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckRole
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

        if(!Auth::guard('admin_user')->user()->hasRole('super')){
            return \Response::view('errors.401', array(), 401);
        }


        return $next($request); 

    }
}
