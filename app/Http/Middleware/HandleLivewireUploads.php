<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleLivewireUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For Livewire upload requests, ensure proper session handling
        if ($request->is('livewire/upload-file')) {
            // Start session if not already started
            if (!$request->hasSession()) {
                $request->setLaravelSession(app('session')->driver());
            }
        }

        return $next($request);
    }
}
