<?php

namespace App\Http\Middleware;

use Closure;

class APITokenMiddleware
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
        $loginToken = \App\LoginToken::whereToken($request->token)->first();

        if($request->token !== null){
            if($loginToken){
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized User'], 401);
    }
}
