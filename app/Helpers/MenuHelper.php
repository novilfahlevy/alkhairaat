<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MenuHelper
{
    /**
     * Filter menu items based on current user's role
     */
    public static function filterItemsByRole(array $items): array
    {
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $userRole = null;
        if ($user->isSuperuser()) {
            $userRole = User::ROLE_SUPERUSER;
        } elseif ($user->isPengurusBesar()) {
            $userRole = User::ROLE_PENGURUS_BESAR;
        } elseif ($user->isKomisariatDaerah()) {
            $userRole = User::ROLE_KOMISARIAT_DAERAH;
        } elseif ($user->isKomisariatWilayah()) {
            $userRole = User::ROLE_KOMISARIAT_WILAYAH;
        } elseif ($user->isSekolah()) {
            $userRole = User::ROLE_SEKOLAH;
        }

        return array_filter($items, function ($item) use ($userRole) {
            return isset($item['roles']) && in_array($userRole, $item['roles']);
        });
    }

    public static function getMenuGroups()
    {
        return [
            [
                'title' => 'Menu Utama',
                'items' => self::filterItemsByRole([
                    [
                        'icon' => 'dashboard',
                        'name' => 'Dasbor',
                        'path' => '/',
                        'roles' => [User::ROLE_SUPERUSER, User::ROLE_PENGURUS_BESAR, User::ROLE_KOMISARIAT_WILAYAH, User::ROLE_SEKOLAH],
                    ],
                    [
                        'icon' => 'sekolah',
                        'name' => 'Sekolah',
                        'roles' => [User::ROLE_SUPERUSER, User::ROLE_PENGURUS_BESAR, User::ROLE_KOMISARIAT_DAERAH, User::ROLE_KOMISARIAT_WILAYAH, User::ROLE_SEKOLAH],
                        'path' => '/sekolah'
                    ],
                    [
                        'icon' => 'students',
                        'name' => 'Murid',
                        'roles' => [User::ROLE_SUPERUSER, User::ROLE_KOMISARIAT_WILAYAH, User::ROLE_SEKOLAH],
                        'path' => '/murid',
                    ],
                    [
                        'icon' => 'graduates',
                        'name' => 'Alumni',
                        'roles' => [User::ROLE_SUPERUSER, User::ROLE_KOMISARIAT_WILAYAH, User::ROLE_SEKOLAH],
                        'path' => '/alumni'
                    ],
                ])
            ],
            [
                'title' => 'Data Wilayah',
                'items' => self::filterItemsByRole([
                    [
                        'icon' => 'kabupaten',
                        'name' => 'Kabupaten',
                        'roles' => [User::ROLE_SUPERUSER],
                        'path' => '/kabupaten'
                    ],
                    [
                        'icon' => 'provinsi',
                        'name' => 'Provinsi',
                        'roles' => [User::ROLE_SUPERUSER],
                        'path' => '/provinsi'
                    ],
                ])
            ],
            [
                'title' => 'Menu Lainnya',
                'items' => self::filterItemsByRole([
                    [
                        'icon' => 'users',
                        'name' => 'Manajemen User',
                        'roles' => [User::ROLE_SUPERUSER],
                        'path' => '/user'
                    ],
                    // Manajemen Komwil (oleh Pengurus Besar)
                    [
                        'icon' => 'users',
                        'name' => 'Manajemen Komwil',
                        'roles' => [User::ROLE_PENGURUS_BESAR],
                        'path' => '/manajemen/komwil',
                    ],
                    // Manajemen Komda (oleh Komwil)
                    [
                        'icon' => 'users',
                        'name' => 'Manajemen Komda',
                        'roles' => [User::ROLE_KOMISARIAT_WILAYAH],
                        'path' => '/manajemen/komda',
                    ],
                    // Manajemen Akun Sekolah (oleh Komda)
                    [
                        'icon' => 'users',
                        'name' => 'Manajemen Akun Sekolah',
                        'roles' => [User::ROLE_KOMISARIAT_DAERAH],
                        'path' => '/manajemen/akun-sekolah',
                    ],
                ])
            ]
        ];
    }

    public static function isActive($path)
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg($iconName)
    {
        $icons = [
            'dashboard' => '<i class="fas fa-tachometer-alt"></i>',
            'sekolah' => '<i class="fas fa-building-columns"></i>',
            'students' => '<i class="fas fa-users-line"></i>',
            'graduates' => '<i class="fas fa-graduation-cap"></i>',
            'kabupaten' => '<i class="fas fa-city"></i>',
            'provinsi' => '<i class="fas fa-flag-usa"></i>',
            'users' => '<i class="fas fa-users"></i>',
        ];

        return $icons[$iconName] ?? '<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/></svg>';
    }
}
