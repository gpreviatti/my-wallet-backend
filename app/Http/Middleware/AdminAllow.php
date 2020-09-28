<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAllow
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->id == 1 || auth()->user()->email == 'admin@admin.com') {
            return $next($request);
        }
        return response()->json(['message' => 'Not allowed']);
    }
}
