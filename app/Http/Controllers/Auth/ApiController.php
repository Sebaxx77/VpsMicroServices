<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;

class ApiController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response = Http::post(config('services.api_vps.url') . '/api/auth/login', $credentials);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'] ?? null;

                if ($token && isset($data['user'])) {
                    session([
                        'user' => $data['user'],
                        'api_token' => $token,
                    ]);

                    return redirect()->route('dashboard');
                } else {
                    return back()->withErrors(['email' => 'La API no devolvió un token de acceso o datos de usuario.']);
                }
            } else {
                $errorMessage = $response->json('message') ?? 'Error al intentar iniciar sesión en la API.';
                return back()->withErrors(['email' => $errorMessage])->withInput();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'No se pudo conectar con la API.'])->withInput();
        }
    }

    public function logout()
    {
        // Obtenemos el token de la sesión
        $token = session('api_token');
    
        // Si no hay token, ya está desconectado, redirigimos al login
        if (!$token) {
            return redirect()->route('login')->with('message', 'Ya estás desconectado.');
        }
    
        // Enviar la solicitud para revocar el token en el backend
        try {
            // Realizamos la solicitud POST al backend para cerrar sesión
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->post(config('services.api_vps.url') . '/api/auth/logout');
    
            // Si la respuesta es exitosa, eliminamos el token de la sesión
            if ($response->successful()) {
                session()->forget('api_token');  // Eliminar el token de la sesión
                session()->forget('user');       // Opcional: también puedes eliminar los datos del usuario si ya no los necesitas
    
                // Redirigir al login después de hacer logout
                return redirect()->route('login')->with('message', 'Sesión cerrada exitosamente.');
            } else {
                return redirect()->route('login')->withErrors(['auth' => 'Error al cerrar sesión.']);
            }
        } catch (\Exception $e) {
            // En caso de error al conectar con la API
            return redirect()->route('login')->withErrors(['auth' => 'Error al conectar con la API.']);
        }
    }
    

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        try {
            $response = Http::post(config('services.api_vps.url') . '/api/auth/forgot-password', ['email' => $request->email]);

            if ($response->successful()) {
                return back()->with('status', $response->json('message') ?? 'Se ha enviado un enlace para restablecer la contraseña a su correo electrónico.');
            } else {
                return back()->withErrors(['email' => $response->json('message') ?? 'No se pudo solicitar el restablecimiento de la contraseña.']);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors(['email' => 'No se pudo conectar con la API para solicitar el restablecimiento de la contraseña.']);
        }
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = Http::post(config('services.api_vps.url') . '/api/auth/reset-password', $request->only('token', 'email', 'password', 'password_confirmation'));

            if ($response->successful()) {
                return redirect('/login')->with('status', $response->json('message') ?? 'Su contraseña ha sido restablecida exitosamente. Por favor, inicie sesión.');
            } else {
                return back()->withErrors(['email' => $response->json('message') ?? 'No se pudo restablecer la contraseña.'])->withInput();
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors(['email' => 'No se pudo conectar con la API para restablecer la contraseña.'])->withInput();
        }
    }
}