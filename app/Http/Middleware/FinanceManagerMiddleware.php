<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\FinanceManagerAllowedUrls;
use Closure;
use Illuminate\Support\Facades\Auth;

class FinanceManagerMiddleware
{
    use FinanceManagerAllowedUrls;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $routeAllowed = $request->is($this->financeManagerAllowedUrls());

        if(Auth::user()->role == User::ROLE_FINANCE_MANAGER && ! $routeAllowed){
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
