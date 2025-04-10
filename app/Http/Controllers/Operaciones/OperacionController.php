<?php

namespace App\Http\Controllers\Operaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class OperacionController extends Controller
{
    protected function apiUrl($endpoint = '/api/operaciones')
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
        $apiUrl = $this->apiUrl();
        $apiToken = $this->apiToken();

        return view('operaciones.index', compact('apiUrl', 'apiToken'));
    }
    public function create()
    {
        return view('operaciones.create');
    }

    public function store(Request $request)
    {
        $token = $this->apiToken();
        try {
            $response = Http::withToken($token)->post($this->apiUrl(), $request->all());
            if ($response->successful()) {
                return redirect()->route('operaciones.index')
                    ->with('success', 'Permiso creado correctamente.');
            } else {
                $errors = $response->json()['errors'] ?? ['Error al crear la operacion.'];
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->route('operaciones.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function edit($id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;
        
        try {
            $response = Http::withToken($token)->get($apiUrl);
            if ($response->successful()) {
                $operacion = $this->arrayToObjectRecursive($response->json());
                return view('operaciones.edit', compact('operacion'));
            } else {
                return redirect()->route('operaciones.index')->with('error', 'Error al obtener la operacion desde la API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('operaciones.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function update(Request $request, $id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;

        try {
            $response = Http::withToken($token)->put($apiUrl, $request->all());
            if ($response->successful()) {
                return redirect()->route('operaciones.index')->with('success', 'Operacion actualizada correctamente.');
            } else {
                $errors = $response->json()['errors'] ?? ['Error al actualizar la operacion.'];
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->route('operaciones.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function destroy($id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;

        try {
            $response = Http::withToken($token)->delete($apiUrl);
            if ($response->successful()) {
                return redirect()->route('operaciones.index')->with('success', 'Operacion eliminada correctamente.');
            } else {
                return redirect()->route('operaciones.index')->with('error', 'Error al eliminar la operacion desde la API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('operaciones.index')->with('error', 'Error de conexión con la API.');
        }
    }
}