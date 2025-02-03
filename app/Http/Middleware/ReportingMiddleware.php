<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ReportingAllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class ReportingMiddleware
{
    use ReportingAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $routeAllowed = $request->is($this->reportingAllowedUrls());

        if(Auth::user()->role == User::ROLE_REPORTING && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
