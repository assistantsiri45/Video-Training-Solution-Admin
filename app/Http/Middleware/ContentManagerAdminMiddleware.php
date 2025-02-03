<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ContentManagerAllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class ContentManagerAdminMiddleware
{
    use ContentManagerAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeAllowed = $request->is($this->contentManagerAllowedUrls());

        if(Auth::user()->role == User::ROLE_CONTENT_MANAGER && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
