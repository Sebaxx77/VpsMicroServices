<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PermissionController extends Controller
{
    protected function apiUrl($endpoint = '/api/permisos')
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
        return view('seguridad.permisos.index', compact('apiUrl', 'apiToken'));
    }

    public function create()
    {
        return view('seguridad.permisos.create');
    }

    public function store(Request $request)
    {
        $token = $this->apiToken();
        try {
            $response = Http::withToken($token)->post($this->apiUrl(), $request->all());
            if ($response->successful()) {
                return redirect()->route('seguridad.permisos.index')
                    ->with('success', 'Permiso creado correctamente.');
            } else {
                $errors = $response->json()['errors'] ?? ['Error al crear el permiso.'];
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->route('seguridad.permisos.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function edit($id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;
        
        try {
            $response = Http::withToken($token)->get($apiUrl);
            if ($response->successful()) {
                $permission = $this->arrayToObjectRecursive($response->json());
                return view('seguridad.permisos.edit', compact('permission'));
            } else {
                return redirect()->route('seguridad.permisos.index')->with('error', 'Error al obtener el permiso desde la API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('seguridad.permisos.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function update(Request $request, $id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;

        try {
            $response = Http::withToken($token)->put($apiUrl, $request->all());
            if ($response->successful()) {
                return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso actualizado correctamente.');
            } else {
                $errors = $response->json()['errors'] ?? ['Error al actualizar el permiso.'];
                return redirect()->back()->withErrors($errors)->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->route('seguridad.permisos.index')->with('error', 'Error de conexión con la API.');
        }
    }

    public function destroy($id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id;

        try {
            $response = Http::withToken($token)->delete($apiUrl);
            if ($response->successful()) {
                return redirect()->route('seguridad.permisos.index')->with('success', 'Permiso eliminado correctamente.');
            } else {
                return redirect()->route('seguridad.permisos.index')->with('error', 'Error al eliminar el permiso desde la API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('seguridad.permisos.index')->with('error', 'Error de conexión con la API.');
        }
    }
}