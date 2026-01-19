<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share user, roles, dan permissions ke semua view
        View::composer('*', function ($view) {
            if (Session::has('user')) {
                $user = Session::get('user');
                $roles = $user['roles'] ?? [];
                $permissions = $user['permissions'] ?? [];

                $view->with([
                    'user' => $user,
                    'role' => $roles,
                    'permission' => $permissions,
                ]);
            } else {
                $view->with([
                    'user' => null,
                    'role' => [],
                    'permission' => [],
                ]);
            }
        });

        // 🔸 Custom Blade directive untuk permission
        Blade::if('canApi', function ($permission) {
            $permissions = Session::get('user.permission', []);
            return in_array($permission, $permissions);
        });

        // 🔸 canAnyApi: true jika salah satu permission ada
        Blade::if('canAnyApi', function (...$permissions) {
            $userPermissions = Session::get('user.permission', []);

            foreach ($permissions as $p) {
                if (in_array($p, $userPermissions)) {
                    return true;
                }
            }

            return false;
        });

        // 🔸 Custom Blade directive untuk role
        Blade::if('hasRole', function ($role) {
            $roles = Session::get('user.role', []);
            return in_array($role, $roles);
        });

        // 🔸 Custom Blade directive kebalikannya (jika tidak punya permission)
        Blade::if('cannotApi', function ($permission) {
            $permissions = Session::get('user.permission', []);
            return !in_array($permission, $permissions);
        });

        // 🔸 Custom directive untuk section pengguna yang bypass admin
        Blade::if('canAnyApiPengguna', function (...$permissions) {
            $roles = Session::get('user.role', []);

            // Jika user memiliki role admin, bypass semua permission check
            if (in_array('admin', $roles)) {
                return true;
            }

            $userPermissions = Session::get('user.permission', []);

            foreach ($permissions as $p) {
                if (in_array($p, $userPermissions)) {
                    return true;
                }
            }

            return false;
        });

        // 🔸 Custom directive untuk single permission di section pengguna yang bypass admin
        Blade::if('canApiPengguna', function ($permission) {
            $roles = Session::get('user.role', []);

            // Jika user memiliki role admin, bypass semua permission check
            if (in_array('admin', $roles)) {
                return true;
            }

            $permissions = Session::get('user.permission', []);
            return in_array($permission, $permissions);
        });
    }
}
