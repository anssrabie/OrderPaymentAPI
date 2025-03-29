<?php

namespace App\Http\Middleware;

use App\Traits\ApiTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    use ApiTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return $this->errorMessage('Unauthorized. Invalid API Key.',Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
