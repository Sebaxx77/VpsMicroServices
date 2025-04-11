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
    protected function apiUrl($endpoint = '')
    {
        $base = '/api/usuarios';
        return rtrim(config('services.api_vps.url'), '/') . $base . $endpoint;
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
        $apiToken = $this->apiToken();
        $apiUrl = $this->apiUrl('/create-data');
        $submitUrl = $this->apiUrl();

        return view('usuarios.create', compact('apiUrl', 'apiToken', 'submitUrl'));
    }

    /**
     * Muestra el formulario para editar un usuario existente.
     * Se consume la API para obtener los datos del usuario y los metadatos necesarios.
     */
    public function edit($id)
    {
        $apiToken = $this->apiToken();
        $apiUrl = $this->apiUrl("/edit-data/{$id}"); // para obtener los datos del usuario
        $submitUrl = $this->apiUrl("/$id"); // para hacer PUT a /usuarios/{id}

        return view('usuarios.create', compact('apiUrl', 'apiToken', 'submitUrl', 'id'));
    }
}
