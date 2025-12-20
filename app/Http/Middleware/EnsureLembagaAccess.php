<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSekolahAccess
{
    /**
     * Handle an incoming request.
     * 
     * This middleware ensures that users can only access data from their own sekolah.
     * Superuser can access all data (read-only).
     * Guru users can only access their own sekolah data.
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

        // Superuser can access all (read-only mode handled in controller)
        if ($user->isSuperuser()) {
            return $next($request);
        }

        // Get sekolah_id from route parameter or request (backward compatible with sekolah parameter)
        $sekolahId = $request->route('sekolah') 
            ?? $request->route('sekolah')
            ?? $request->route('sekolah_id') 
            ?? $request->route('sekolah_id')
            ?? $request->input('sekolah_id')
            ?? $request->input('sekolah_id');

        if ($sekolahId && !$user->canAccessSekolah((int) $sekolahId)) {
            abort(403, 'Anda tidak memiliki akses ke data sekolah ini.');
        }

        return $next($request);
    }
}
