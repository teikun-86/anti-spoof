<?php

namespace Teikun86\AntiSpoof\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Teikun86\AntiSpoof\Actions\DetectSpoofing;

class AntiSpoofMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DetectSpoofing::run($request);
        return $next($request);
    }
}
