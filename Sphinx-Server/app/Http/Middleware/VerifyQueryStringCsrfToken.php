<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
use Closure;
use Illuminate\Session\TokenMismatchException;

/**
 * Verify a CSRF token included in the GET path.
 *
 * @package App\Http\Middleware
 */
class VerifyQueryStringCsrfToken extends BaseVerifier
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if (!$this->tokensMatch($request)) {
            throw new TokenMismatchException();
        }

        return $next($request);
    }
}
