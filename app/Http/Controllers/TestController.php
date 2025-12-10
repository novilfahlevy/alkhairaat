<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Test role access
     */
    public function testRoles(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'legacy_role' => $user->role,
                'lembaga_id' => $user->lembaga_id,
            ],
            'spatie_roles' => $user->getRoleNames(),
            'spatie_permissions' => $user->getPermissionNames(),
            'role_checks' => [
                'isSuperAdmin' => $user->isSuperAdmin(),
                'isWilayah' => $user->isWilayah(),
                'isSekolah' => $user->isSekolah(),
            ],
            'permission_checks' => [
                'can_manage_users' => $user->can('manage_users'),
                'can_manage_lembaga' => $user->can('manage_lembaga'),
                'can_manage_santri' => $user->can('manage_santri'),
                'can_manage_alumni' => $user->can('manage_alumni'),
                'can_view_reports' => $user->can('view_reports'),
                'can_export_data' => $user->can('export_data'),
            ],
        ]);
    }

    /**
     * Test super admin only access
     */
    public function superAdminOnly()
    {
        return response()->json([
            'message' => 'This endpoint is only accessible by super admin users',
            'user' => auth()->user()->only(['name', 'email'])
        ]);
    }

    /**
     * Test sekolah only access
     */
    public function sekolahOnly()
    {
        return response()->json([
            'message' => 'This endpoint is only accessible by sekolah users',
            'user' => auth()->user()->only(['name', 'email', 'lembaga_id'])
        ]);
    }

    /**
     * Test permission-based access
     */
    public function manageSantri()
    {
        return response()->json([
            'message' => 'You have permission to manage santri',
            'user' => auth()->user()->only(['name', 'email'])
        ]);
    }
}