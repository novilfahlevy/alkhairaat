<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventSuperAdminModification
{
    /**
     * Handle an incoming request.
     * 
     * This middleware prevents superuser from modifying sekolah data.
     * Superuser can only view data, not add/edit/delete.
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

        // Block modification requests for superuser
        if ($user->isSuperuser() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            abort(403, 'Superuser tidak dapat menambah, mengubah, atau menghapus data sekolah.');
        }

        return $next($request);
    }
}
