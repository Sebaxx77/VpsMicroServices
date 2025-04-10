<?php

namespace App\Http\Controllers\CorreosNotificables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CorreosNotificables extends Controller
{
    protected function apiUrl($endpoint = '/api/correos-notificables')
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
        return view('correosnotificables.index', compact('apiUrl', 'apiToken'));
    }
    
}