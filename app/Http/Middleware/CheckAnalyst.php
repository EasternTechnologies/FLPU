<?php

namespace App\Http\Middleware;
use Closure;

class CheckAnalyst
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !$request->user()->isanalyst()) {

            return redirect()->to('/')->with('error','У вас нет прав');
            die;
        }
        return $next($request);
    }
}
