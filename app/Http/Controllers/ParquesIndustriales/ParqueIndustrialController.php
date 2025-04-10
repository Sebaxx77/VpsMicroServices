<?php

namespace App\Http\Controllers\ParquesIndustriales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParqueIndustrialController extends Controller
{
    protected function apiUrl($endpoint = '/api/parques-industriales')
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
        return view('parques-industriales.index', compact('apiUrl', 'apiToken'));
    }
    
}