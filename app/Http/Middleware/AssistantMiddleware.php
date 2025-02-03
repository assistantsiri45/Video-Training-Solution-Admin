<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\AssistantAllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class AssistantMiddleware
{
    use AssistantAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeAllowed = $request->is($this->assistantAllowedUrls());

        if(Auth::user()->role == User::ROLE_ASSISTANT && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
