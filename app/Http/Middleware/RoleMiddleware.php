<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, ...$role)
    {
        if (Auth::guard('api')->user() == null || !in_array(Auth::guard('api')->user()["role"], $role)) {
            return ResponseHelper::Forbidden();
        }

        return $next($request);
    }
}
