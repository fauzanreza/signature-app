<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
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
        $response = $next($request);

        // Prevent the browser from sniffing the content type
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Prevent the page from being displayed in a frame (Clickjacking protection)
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Enable the browser's XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Force the use of HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Control how much referrer information should be included with requests
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');

        // Content Security Policy
        // Note: This is a basic CSP. You might need to adjust it based on your app's needs.
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.jsdelivr.net https://code.jquery.com https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data:; connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; worker-src 'self' blob: https://cdnjs.cloudflare.com;");

        return $response;
    }
}
