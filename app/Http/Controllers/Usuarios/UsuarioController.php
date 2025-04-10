<?php

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class UsuarioController extends Controller
{
    /**
     * Obtiene la URL base de la API desde la configuración.
     */
    protected function apiUrl($endpoint = '/api/usuarios')
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
     * Muestra la lista de usuarios junto con sus roles, permisos y operaciones,
     * consumiendo la API protegida con Sanctum.
     */
    public function index()
    {
        $apiUrl = $this->apiUrl(); // o la ruta que estés usando
        $apiToken = $this->apiToken();
        return view('usuarios.index', compact('apiUrl', 'apiToken'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * Se hace una petición a la API para obtener roles y operaciones.
     */
    public function create()
    {
        $token = $this->apiToken();

        $response = Http::withToken($token)->get($this->apiUrl());
        if ($response->successful()) {
            $data = $response->json();
            $roles       = isset($data['roles']) ? $this->mapToCollection($data['roles']) : collect();
            $operaciones = isset($data['operaciones']) ? $this->mapToCollection($data['operaciones']) : collect();
        } else {
            $roles = collect();
            $operaciones = collect();
        }

        return view('usuarios.create', compact('roles', 'operaciones'));
    }

    /**
     * Almacena el nuevo usuario mediante un POST a la API.
     */
    public function store(Request $request)
    {
        $token = $this->apiToken();

        // Validación temprana en la UI
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'password'     => 'required|string|min:6|confirmed',
            'role'         => 'required|string',
            'operacion_id' => 'required|integer',
        ]);

        $response = Http::withToken($token)->post($this->apiUrl(), $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario creado exitosamente.');
        } else {
            return redirect()->back()->withErrors('Error al crear el usuario.');
        }
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     * Se consume la API para obtener los datos del usuario y los metadatos necesarios.
     */
    public function edit($id)
    {
        $token = $this->apiToken();
        $apiUrl = $this->apiUrl() . '/' . $id; // Concatenamos el / y el $id a la URL base

    try {
        // Obtener los datos del usuario específico, roles y operaciones desde la API
        $userResponse = Http::withToken($token)->get($apiUrl);

            if ($userResponse->successful()) {
                $data = $userResponse->json();
                $usuario = $this->mapToCollection([$data['usuario']])->first(); // Convertimos el array del usuario a una Collection y obtenemos el primer (y único) elemento
                $roles = $this->mapToCollection($data['roles'] ?? []);
                $operaciones = $this->mapToCollection($data['operaciones'] ?? []);

                return view('usuarios.edit', compact('usuario', 'roles', 'operaciones'));
            } else {
                // Manejar el error si no se encuentra el usuario
                return redirect()->route('usuarios.index')->with('error', 'No se encontraron los datos del usuario para editar.');
            }

        } catch (\Exception $e) {
            // Manejar errores de conexión u otros errores
            return redirect()->route('usuarios.index')->with('error', 'Ocurrió un error al cargar la página de edición.');
        }
    }

    /**
     * Actualiza un usuario mediante una petición PUT a la API.
     */
    public function update(Request $request, $id)
    {
        $token = $this->apiToken();
        
        // Validación de datos
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'operacion_id' => 'required|integer',
            'role'         => 'sometimes|required|string',
            // Puedes agregar validación adicional según necesidades.
        ]);

        $response = Http::withToken($token)
            ->put($this->apiUrl("/{$id}"), $data);

        if ($response->successful()) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario actualizado exitosamente.');
        } else {
            return redirect()->back()->withErrors('Error al actualizar el usuario.');
        }
    }

    /**
     * Elimina un usuario mediante una petición DELETE a la API.
     */
    public function destroy($id)
    {
        $token = $this->apiToken();

        $response = Http::withToken($token)->delete($this->apiUrl("/{$id}"));

        if ($response->successful()) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } else {
            return redirect()->back()->withErrors('Error al eliminar el usuario.');
        }
    }
}
