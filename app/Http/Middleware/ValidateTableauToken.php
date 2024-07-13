<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class ValidateTableauToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('tableau-token');

        if (!$token) {
            return response()->json(['error' => 'Tableau token missing'], 401);
        }

        // Optionally, you could validate the token with Tableau server
//        $response = Http::withHeaders([
//            'X-Tableau-Auth' => $token,
//            'Accept' => 'application/json',
//        ])->get(config('tableau.server') . '/api/3.10/auth/validate');
//
//        if ($response->status() != 200) {
//            return response()->json(['error' => 'Invalid Tableau token'], 401);
//        }

        return $next($request);
    }
}
