<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // jika usertype nya adalah admin
        if (Auth::user()->utype === 'ADM')
        {
            // maka akan dialihkan disini
            return $next($request);
        }
        // jika bukan admin maka akan dialihkan disini
        else{
            // If you would like to remove all data from the session, you may use the flush method:
            session()->flush();
            return redirect()->route('login');
        }
        
    }
}
