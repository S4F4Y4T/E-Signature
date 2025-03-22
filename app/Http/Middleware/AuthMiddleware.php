<?php

namespace App\Http\Middleware;

use App\Http\Requests\ValidateSigner;
use App\Models\Signer;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(ValidateSigner $request, $shortUrl, Closure $next): Response
    {
        $signer = Signer::query()->where('otp', $request->otp)->where('short_url', $shortUrl)->first();

        if(!$signer){
            return response()->json(['message' => 'Invalid signer.'], 404);
        }

        return $next($request);
    }
}
