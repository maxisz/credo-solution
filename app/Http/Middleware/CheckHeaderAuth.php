<?php

namespace App\Http\Middleware;

use Closure;

class CheckHeaderAuth
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
        $expectedHeaders = [
            'X-CG-AUTH' => "YjJkYzdlYWYxM2QwNDkxZGI4YTI0YjcwNzRiOWYxNmNyb290LmFkbWluQG11bHRpbGluay1zeXN0ZW1zLmNvbQ==",
            // 'X-CG-API-VERSION' => '1.0.1',
            // Add more headers and their expected values as needed
        ];

        foreach ($expectedHeaders as $headerName => $expectedValue) {
            $headerValue = $request->header($headerName);

            // if ($headerValue !== $expectedValue) {
            //     // return response()->json(['error' => 'Unauthorized'], 401);
            //     return $next($request);
            // }
        }

        return $next($request);


    }
}