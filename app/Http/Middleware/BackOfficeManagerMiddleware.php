<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use App\Traits\BackOfficeManagerNotAllowedUrls;
use Illuminate\Support\Facades\Auth;

class BackOfficeManagerMiddleware
{
    use BackOfficeManagerNotAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeNotAllowed = $request->is($this->BackOfficeManagerNotAllowedUrls());

        if(Auth::user()->role == User::ROLE_BACKOFFICE_MANAGER && $routeNotAllowed) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
