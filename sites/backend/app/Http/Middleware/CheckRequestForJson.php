<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CheckRequestForJson
 * @package App\Http\Middleware
 */
class CheckRequestForJson
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        if ($request->wantsJson()) {
            return $next($request);
        }

        throw new Exception('I only answer to JSON requests bitch.');
    }
}
