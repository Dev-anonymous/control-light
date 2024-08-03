<?php

namespace App\Http\Middleware\app;

use App\Models\Compte;
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

        $role = auth()->user()->user_role;
        if (in_array($role, ['caissier', 'admin', 'client'])) {
            templatepath();
            foreach (Compte::all() as $el) {
                $v =  getConfig('prefixe_facture');
                if (!$v) {
                    setConfig('prefixe_facture', 'FAC');
                }
                $v =  getConfig('prefixe_be');
                if (!$v) {
                    setConfig('prefixe_be', 'BE');
                }
                $v =  getConfig('prefixe_bs');
                if (!$v) {
                    setConfig('prefixe_bs', 'BS');
                }
            }

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
