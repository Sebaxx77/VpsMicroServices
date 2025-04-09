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
     * Mapea un array de items a una colección de objetos.
     */
    protected function arrayToObjectRecursive($data)
    {
        if (is_array($data)) {
            return (object) array_map([$this, 'arrayToObjectRecursive'], $data);
        }
        return $data;
    }
    /**
 * Mapea un array de items a una colección de objetos (recursivo).
 */
    protected function mapToCollection($items)
    {
        return collect($items)->map(function ($item) {
            return $this->arrayToObjectRecursive($item);
        });
    }

    /**
     * Reconstruye un paginador a partir de la respuesta de la API.
     *
     * Se usa para que la vista trabaje con el paginador
     * y puedas seguir llamando a $usuarios->links() sin inconveniente.
     */
    protected function buildPaginator(array $paginatedData)
    {
        // Se asume que la API devuelve una estructura similar a la del paginador de Laravel:
        // ['data' => [...], 'total' => int, 'per_page' => int, 'current_page' => int, ...]
        $items = $this->mapToCollection($paginatedData['data']);
        return new LengthAwarePaginator(
            $items,
            $paginatedData['total'],
            $paginatedData['per_page'],
            $paginatedData['current_page'],
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Muestra la lista de usuarios junto con sus roles, permisos y operaciones,
     * consumiendo la API protegida con Sanctum.
     */
    public function index(Request $request)
    {
        $token = $this->apiToken();
        
        // Captura 'search' y 'page'
        $params = $request->only(['search', 'page']);
        
        // Si 'search' está vacío, lo removemos para que la API devuelva todos los registros.
        if(empty($params['search'])) {
            unset($params['search']);
        }

        $response = Http::withToken($token)->get($this->apiUrl(), $params);

        if ($response->successful()) {
            $data = $response->json();
            // Reconstrucción del paginador
            $usuarios = isset($data['usuarios']['data'])
                ? $this->buildPaginator($data['usuarios'])
                : collect([]);
                
            // Convertir roles, permisos y operaciones a colecciones de objetos
            $roles       = isset($data['roles']) ? $this->mapToCollection($data['roles']) : collect();
            $permissions = isset($data['permissions']) ? $this->mapToCollection($data['permissions']) : collect();
            $operaciones = isset($data['operaciones']) ? $this->mapToCollection($data['operaciones']) : collect();
        } else {
            $usuarios = collect();
            $roles = collect();
            $permissions = collect();
            $operaciones = collect();
        }

        return view('usuarios.index', compact('usuarios', 'roles', 'permissions', 'operaciones'));
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

        // Obtener datos del usuario mediante el endpoint show
        $responseUser = Http::withToken($token)->get($this->apiUrl("/{$id}"));
        // Obtener metadatos: roles, permisos y operaciones
        $responseMeta = Http::withToken($token)->get($this->apiUrl());
        
        if ($responseUser->successful() && $responseMeta->successful()) {
            $usuarioRaw = $responseUser->json()['usuario'] ?? null;
            if (!$usuarioRaw) {
                return redirect()->route('usuarios.index')->withErrors('Usuario no encontrado.');
            }
            $usuario = $this->arrayToObjectRecursive($usuarioRaw);
            
            $meta        = $responseMeta->json();
            $roles       = isset($meta['roles']) ? $this->mapToCollection($meta['roles']) : collect();
            $permissions = isset($meta['permissions']) ? $this->mapToCollection($meta['permissions']) : collect();
            $operaciones = isset($meta['operaciones']) ? $this->mapToCollection($meta['operaciones']) : collect();
        } else {
            return redirect()->route('usuarios.index')->withErrors('Error al obtener datos.');
        }

        return view('usuarios.edit', compact('usuario', 'roles', 'permissions', 'operaciones'));
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

        $response = Http::withToken($token)
            ->delete($this->apiUrl("/{$id}"));

        if ($response->successful()) {
            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } else {
            return redirect()->back()->withErrors('Error al eliminar el usuario.');
        }
    }
}
