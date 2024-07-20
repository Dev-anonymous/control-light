<?php

namespace App\Http\Middleware\app;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!in_array(auth()->user()->user_role, ['admin', 'gerant'])) {
            if (request()->wantsJson()) {
                return response(["message" => "Vous n'etes pas autorisé à acceder à cette ressource."], 401);
            }
            abort(401);
        };
        return $next($request);
    }
}
