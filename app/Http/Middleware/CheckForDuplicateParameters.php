<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckForDuplicateParameters
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
        // Get the raw query string
        $queryString = $request->server('QUERY_STRING');

        if (!empty($queryString)) {
            // Split parameters by '&'
            $params = explode('&', $queryString);
            $keys = [];

            foreach ($params as $param) {
                // Skip empty segments
                if (empty($param)) {
                    continue;
                }

                // Split key and value (limit to 2 parts)
                $parts = explode('=', $param, 2);
                // Decode the key (e.g., 'foo%5B%5D' becomes 'foo[]')
                $key = urldecode($parts[0]);

                // Allow array parameters (ending in []) to have duplicates (PHP standard behavior)
                // Using substr for PHP 7.3 compatibility
                if (substr($key, -2) === '[]') {
                    continue;
                }

                // If check for duplicate keys
                if (isset($keys[$key])) {
                    if ($request->wantsJson()) {
                        return response()->json(['message' => 'Duplicate parameters detected.'], 400);
                    }
                    abort(400, 'Bad Request: Duplicate parameters detected.');
                }

                $keys[$key] = true;
            }
        }

        return $next($request);
    }
}
