<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        Auth::provider('users', function ($app, array $config) {
            return new \Illuminate\Auth\EloquentUserProvider($app['hash'], \App\Models\User::class);
        });

        // タイムアウト後のリダイレクト設定を追加
        Auth::viaRequest('session', function (Request $request) {
            if ($request->user()) {
                return $request->user();
            }

            return redirect(config('auth.timeout_redirect', '/'));
        });


    }
}
