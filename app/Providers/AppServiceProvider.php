<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
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

            
        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
     // ストレージのURL設定を直接ここに追加
     if (config('app.url')) {
        $url = config('app.url') . '/storage';
        config(['filesystems.disks.public.url' => $url]);
    }
     
    }
}
