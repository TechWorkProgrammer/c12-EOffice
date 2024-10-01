<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckJsonRequest
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->expectsJson()) {
            abort(404);
        }
        return $next($request);
    }
}
