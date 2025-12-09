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
     * This middleware prevents super admin from modifying lembaga data.
     * Super admin can only view data, not add/edit/delete.
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

        // Block modification requests for super admin
        if ($user->isSuperAdmin() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            abort(403, 'Super Admin tidak dapat menambah, mengubah, atau menghapus data lembaga.');
        }

        return $next($request);
    }
}
