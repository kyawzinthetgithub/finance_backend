<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FrondendApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiToken = config('api.frontend_api_token');
        if ($request->header('x-api-token') != $apiToken) {
            return response(['error' => 'Unauthorized'],401);
        }
        return $next($request);
    }
}
