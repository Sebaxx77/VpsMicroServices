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

    /**
     * Mapea un array de items a una colección de objetos (recursivo).
     */
    protected function arrayToObjectRecursive($data)
    {
        if (is_array($data)) {
            return (object) array_map([$this, 'arrayToObjectRecursive'], $data);
        }
        return $data;
    }
    /**
     * Mapea un array de items a una colección de objetos.
     */
    protected function mapToCollection($items)
    {
        return collect($items)->map(function ($item) {
            return $this->arrayToObjectRecursive($item);
        });
    }

    public function index()
    {
        $token = $this->apiToken();
        try {
            $response = Http::withToken($token)->get($this->apiUrl());
            if ($response->successful()) {
                $permissions = $this->mapToCollection($response->json());
                return view('seguridad.permisos.index', compact('permissions'));
            } else {
                return redirect()->route('seguridad.permisos.index')->with('error', 'Error al obtener los permisos desde la API.');
            }
        } catch (\Exception $e) {
            return redirect()->route('seguridad.permisos.index')->with('error', 'Error de conexión con la API.');
        }
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