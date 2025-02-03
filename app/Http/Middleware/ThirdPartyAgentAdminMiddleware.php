<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ThirdPartyAgentAllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class ThirdPartyAgentAdminMiddleware
{
    use ThirdPartyAgentAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeAllowed = $request->is($this->thirdPartyAgentAllowedUrls());

        if(Auth::user()->role == User::ROLE_THIRD_PARTY_AGENT && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
