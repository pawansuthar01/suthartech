<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyInternalFormToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = (string) config('services.suthartech_content_token');
        abort_if($token === '' || ! hash_equals($token, (string) $request->bearerToken()), 401);

        return $next($request);
    }
}
