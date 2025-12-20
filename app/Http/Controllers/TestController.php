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
                'sekolah_id' => $user->sekolah_id,
            ],
            'spatie_roles' => $user->getRoleNames(),
            'role_checks' => [
                'isSuperuser' => $user->isSuperuser(),
                'isPengurusBesar' => $user->isPengurusBesar(),
                'isKomisariatWilayah' => $user->isKomisariatWilayah(),
                'isGuru' => $user->isGuru(),
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
            'user' => auth()->user()->only(['name', 'email', 'sekolah_id'])
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