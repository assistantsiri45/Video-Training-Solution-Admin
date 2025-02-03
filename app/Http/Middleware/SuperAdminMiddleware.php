<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App\Traits\SuperAdminNotAllowedUrls;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    use SuperAdminNotAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeNotAllowed = $request->is($this->superAdminNotAllowedUrls());

        if(Auth::user()->role == User::ROLE_ADMIN && $routeNotAllowed) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
