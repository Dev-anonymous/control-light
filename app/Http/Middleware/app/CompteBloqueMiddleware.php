<?php

namespace App\Http\Middleware\app;

use Closure;
use Illuminate\Http\Request;

class CompteBloqueMiddleware
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
        if (auth()->user()->user_role == 'caissier') {
            if (auth()->user()->actif == 0) {
                if (request()->wantsJson()) {
                    return response(["message" => "Compte bloqué."], 401);
                }
                return redirect(route('compte-bloque.web'));
            }
        }
        return $next($request);
    }
}
