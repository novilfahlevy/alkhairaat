<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLembagaAccess
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that users can only access data from their own lembaga.
     * Super admin can access all data (read-only).
     * Sekolah users can only access their own lembaga data.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Super admin can access all (read-only mode handled in controller)
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get lembaga_id from route parameter or request
        $lembagaId = $request->route('lembaga') 
            ?? $request->route('lembaga_id') 
            ?? $request->input('lembaga_id');

        if ($lembagaId && !$user->canAccessLembaga((int) $lembagaId)) {
            abort(403, 'Anda tidak memiliki akses ke data lembaga ini.');
        }

        return $next($request);
    }
}
