<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http; // Asegúrate de importar la clase Http

class CheckApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = Session::get('api_token');

        if (!$apiToken) {
            return redirect('/login')->withErrors(['auth' => 'No estás autenticado.']);
        }

        // Opcionalmente, puedes validar el token con la API en cada request
        // Asegúrate de tener configurada la URL base de tu API en config/services.php
        // Ejemplo de configuración en config/services.php:
        // 'api_vps' => [
        //     'url' => env('API_VPS_URL', 'http://tu-api.com'),
        // ],
        //
        // $apiBaseUrl = config('services.api_vps.url');
        // if ($apiBaseUrl) {
        //     $response = Http::withHeaders(['Authorization' => 'Bearer ' . $apiToken])
        //                     ->get($apiBaseUrl . '/api/auth/check'); // Reemplaza '/api/auth/check' con la ruta de validación de tu API
        //
        //     if (!$response->successful()) {
        //         Session::forget('api_token');
        //         Session::forget('user');
        //         return redirect('/login')->withErrors(['auth' => 'Tu sesión ha expirado o es inválida.']);
        //     }
        // }

        return $next($request);
    }
}