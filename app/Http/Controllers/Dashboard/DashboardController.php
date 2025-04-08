<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $token = session('api_token');

        if (!$token) {
            return redirect()->route('login')->withErrors(['email' => 'Sesión inválida.']);
        }

        $response = Http::withToken($token)->get(config('services.api_vps.url') . '/api/dashboard');

        if (!$response->successful()) {
            return redirect()->route('login')->withErrors(['email' => 'No se pudo obtener la información del dashboard.']);
        }

        $dashboardData = $response->json();

        switch ($dashboardData['rol']) {
            case 'Administrador':
                return view('dashboard.administrador', $dashboardData);
            case 'Supervisor Agendamientos':
                return view('dashboard.supervisor', $dashboardData); // Pasar $dashboardData completo
            case 'Autorizador Agendamientos':
            default:
                return redirect('/')->with('error', $dashboardData['message'] ?? 'Acceso Denegado.');
        }
    }
}