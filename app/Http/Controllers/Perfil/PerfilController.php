<?php

namespace App\Http\Controllers\Perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PerfilController extends Controller
{
    
    protected function apiUrl($endpoint = '/api/perfil')
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
    public function profileManage()
    {
        $apiUrl = $this->apiUrl();
        $apiToken = $this->apiToken();
        return view('profile.manage', compact('apiUrl', 'apiToken'));
    }
}