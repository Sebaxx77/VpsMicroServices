<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Redirect;

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
    public function boot()
    {
        View::composer([
            'partials.sidebar',
            'dashboard.*',
            // Agrega aquí todas las demás vistas que necesiten la info del usuario
        ], function ($view) {
            $token = Session::get('api_token');
            $user = [];
        
            if ($token) {
                try {
                    $responseUser = Http::withToken($token)->get(config('services.api_vps.url') . '/api/auth/me');
                    if ($responseUser->successful()) {
                        $user = $responseUser->json();
                        Session::put('api_user', $user); // Almacenar en sesión
                    }
                } catch (\Exception $e) {
                    // Manejar error
                }
            }
            $view->with('user', $user ?? []);
        });
    }
}