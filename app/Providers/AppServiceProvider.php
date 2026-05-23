<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
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
        if ($this->app->environment('local') && Schema::hasTable('users')) {
            if (! User::where('role', 'admin')->exists()) {
                User::updateOrCreate(
                    ['email' => 'admin@example.com'],
                    [
                        'name' => 'Administrator',
                        'username' => 'admin',
                        'email' => 'admin@example.com',
                        'password' => Hash::make('admin123'),
                        'role' => 'admin',
                    ]
                );
            }
        }
    }
}
