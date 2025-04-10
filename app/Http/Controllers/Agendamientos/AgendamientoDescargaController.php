<?php

namespace App\Http\Controllers\Agendamientos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AgendamientoDescargaController extends Controller
{
    protected function apiUrl($endpoint = '/api/agendamientos/formato-descarga/otros')
    {
        return config('services.api_vps.url') . $endpoint;
    }

    /**
     * Obtiene el token API almacenado en sesión.
     */
    protected function apiToken()
    {
        return session('api_token');
    }
    public function index()
    {
        $apiUrl = $this->apiUrl(); // o la ruta que estés usando
        $apiToken = $this->apiToken();
        return view('solicitudes.formato-descarga.index', compact('apiUrl', 'apiToken'));
    }

    /**
     * Muestra solo las solicitudes pendientes obtenidas desde el microservicio.
     */
    public function pendientes()
    {
        $apiUrl = config('services.microservice.url') . '/api/agendamientos/formato-descarga/pendientes';
        $response = Http::get($apiUrl);

        // El endpoint ya retorna solo pendientes
        $pendientes = $response->successful() 
            ? $response->json()['agendamientos'] ?? [] 
            : [];

        return view('solicitudes.formato-descarga.pendientes', compact('pendientes'));
    }

    /**
     * Actualiza una solicitud (para aprobar o rechazar) y envía la información actualizada al microservicio.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all(); // Obtener todos los datos del request

        $apiUrl = config('services.microservice.url') . '/api/agendamientos/formato-descarga/' . $id;
        $microResponse = Http::put($apiUrl, $data);
    
        if ($microResponse->successful()) {
            return redirect()->route('solicitudes.pendientes')->with('success', '.Actualización realizada con exito.');
        } else {
            return back()->withErrors('Error al sincronizar la actualización con el microservicio.');
        }
    }    
}