<?php

namespace App\Http\Middleware;

use App\Enums\Roles\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(! $request->user()->hasRole([Role::Superadmin->value, Role::Admin->value]), 404);

        return $next($request);
    }
}
