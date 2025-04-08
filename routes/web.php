<?php

use App\Http\Controllers\Agendamientos\AgendamientoDescargaController;
use App\Http\Controllers\Agendamientos\FormatoDescarga;
use App\Http\Controllers\Auth\ApiController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Operaciones\OperacionController;
use App\Http\Controllers\Seguridad\PermissionController;
use App\Http\Controllers\Seguridad\RoleController;
use App\Http\Controllers\Usuarios\UsuarioController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes (UI)
|--------------------------------------------------------------------------
|
| Aquí definimos las rutas web para la interfaz de usuario (UI). Estas rutas
| mostrarán las vistas y enviarán las peticiones al API del monolito.
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('bienvenido'); // Se le asigna un nombre a la ruta raiz de la app 

// Rutas para mostrar los formularios de autenticación
Route::get('/login', [ApiController::class, 'showLoginForm'])->name('login');
Route::get('/forgot-password', [ApiController::class, 'showForgotPasswordForm'])->name('password.request');
Route::get('/reset-password/{token}', [ApiController::class, 'showResetPasswordForm'])->name('password.reset');

// Rutas para enviar las peticiones de autenticación al API
Route::post('/login', [ApiController::class, 'login']);
Route::post('/logout', [ApiController::class, 'logout'])->middleware('auth')->name('logout'); // Requiere autenticación para cerrar sesión
Route::post('/forgot-password', [ApiController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [ApiController::class, 'resetPassword'])->name('password.update');


Route::middleware(['api.auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Aquí puedes definir otras rutas que requieran autenticación por API
    // Route::get('/perfil', [ProfileController::class, 'index'])->name('perfil');
});

Route::prefix('agendamiento/formato-descarga')->group(function () {
    // Muestra el formulario público de formato-descarga
    Route::get('/', [FormatoDescarga::class, 'index'])->name('agendamiento.formato-descarga.index');

    // Procesa el envío del formulario de formato-descarga
    Route::post('/', [FormatoDescarga::class, 'enviar'])->name('agendamiento.formato-descarga.enviar');
}); //Rutas para el formato de agendamiento de descarga, tanto para mostrar el formato como para enviarlo a la API del Microservicio.

 // Rutas para Permisos
Route::middleware(['permission:Administrar Permisos'])->prefix('seguridad/permisos')->name('seguridad.permisos.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::get('/crear', [PermissionController::class, 'create'])->name('create');
    Route::post('/', [PermissionController::class, 'store'])->name('store');
    Route::get('/{permission}/editar', [PermissionController::class, 'edit'])->name('edit');
    Route::put('/{permission}', [PermissionController::class, 'update'])->name('update');
    Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
});

// Rutas para Roles
Route::middleware(['permission:Administrar Roles'])->prefix('seguridad/roles')->name('seguridad.roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/crear', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}/editar', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});
// Rutas para Usuarios
Route::middleware(['permission:Administrar Usuarios'])->prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('index');
    Route::get('/crear', [UsuarioController::class, 'create'])->name('create');
    Route::post('/', [UsuarioController::class, 'store'])->name('store');
    Route::get('/{usuario}/editar', [UsuarioController::class, 'edit'])->name('edit');
    Route::put('/{usuario}', [UsuarioController::class, 'update'])->name('update');
    Route::delete('/{usuario}', [UsuarioController::class, 'destroy'])->name('destroy');
});
//Rutas para operaciones
Route::middleware(['permission:Administrar Operaciones'])->prefix('operaciones')->name('operaciones.')->group(function () {
    Route::get('/', [OperacionController::class, 'index'])->name('index');
    Route::get('/crear', [OperacionController::class, 'create'])->name('create');
    Route::post('/', [OperacionController::class, 'store'])->name('store');
    Route::get('/{operacion}/editar', [OperacionController::class, 'edit'])->name('edit');
    Route::put('/{operacion}', [OperacionController::class, 'update'])->name('update');
    Route::delete('/{operacion}', [OperacionController::class, 'destroy'])->name('destroy');
});
// Rutas para Agendamientos (Solicitudes)
Route::middleware(['permission:Administrar Agendamientos'])->prefix('agendamientos')
->name('solicitudes.')->group(function () {

    Route::get('/gestion', [AgendamientoDescargaController::class, 'index'])->name('gestion');
    // Grupo para todo lo relacionado a solicitudes PENDIENTES
    Route::prefix('pendientes')->group(function () {
    // Listar pendientes
    Route::get('/', [AgendamientoDescargaController::class, 'pendientes'])->name('pendientes');
    // Editar (formulario)
    Route::put('/{id}', [AgendamientoDescargaController::class, 'update'])->name('update');
        });
});
