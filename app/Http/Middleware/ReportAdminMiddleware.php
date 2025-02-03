<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\AllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class ReportAdminMiddleware
{
    use AllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeAllowed = $request->is($this->allowedUrls());

        if(Auth::user()->role == User::ROLE_REPORT_ADMIN && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }
        return $next($request);
    }
}
