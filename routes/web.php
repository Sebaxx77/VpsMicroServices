<?php

use App\Http\Controllers\Agendamientos\AgendamientoDescargaController;
use App\Http\Controllers\Agendamientos\FormatoDescarga;
use App\Http\Controllers\Auth\ApiController;
use App\Http\Controllers\CorreosNotificables\CorreosNotificables;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Operaciones\OperacionController;
use App\Http\Controllers\ParquesIndustriales\ParqueIndustrialController;
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
Route::post('/forgot-password', [ApiController::class, 'forgotPassword'])->name('password.email');
Route::post('/reset-password', [ApiController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [ApiController::class, 'logout'])->name('logout');

// Rutas para mostrar los formularios Publicos
Route::prefix('agendamiento/formato-descarga')->group(function () {
    // Muestra el formulario público de formato-descarga
    Route::get('/', [FormatoDescarga::class, 'index'])->name('agendamiento.formato-descarga.index');
    // Procesa el envío del formulario de formato-descarga
    Route::post('/', [FormatoDescarga::class, 'enviar'])->name('agendamiento.formato-descarga.enviar');
});

// Rutas para mostrar el dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

 // Rutas para Permisos
Route::prefix('seguridad/permisos')->name('seguridad.permisos.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index');
    Route::get('/crear', [PermissionController::class, 'create'])->name('create');
    Route::post('/', [PermissionController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [PermissionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy');
});

// Rutas para Roles
Route::prefix('seguridad/roles')->name('seguridad.roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/crear', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{id}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
});
Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::get('/', [UsuarioController::class, 'index'])->name('index');
    Route::get('/crear', [UsuarioController::class, 'create'])->name('create');
    Route::post('/', [UsuarioController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [UsuarioController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UsuarioController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsuarioController::class, 'destroy'])->name('destroy');
});
//Rutas para Parques Industriales
Route::prefix('parques-industriales')->name('parques-industriales.')->group(function () {
    Route::get('/', [ParqueIndustrialController::class, 'index'])->name('index');
    Route::get('/crear', [ParqueIndustrialController::class, 'create'])->name('create');
    Route::post('/', [ParqueIndustrialController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [ParqueIndustrialController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ParqueIndustrialController::class, 'update'])->name('update');
    Route::delete('/{id}', [ParqueIndustrialController::class, 'destroy'])->name('destroy');
});
//Rutas para Correos Notificables
Route::prefix('correos-notificables')->name('correos-notificables.')->group(function () {
    Route::get('/', [CorreosNotificables::class, 'index'])->name('index');
    Route::get('/crear', [CorreosNotificables::class, 'create'])->name('create');
    Route::post('/', [CorreosNotificables::class, 'store'])->name('store');
    Route::get('/{id}/editar', [CorreosNotificables::class, 'edit'])->name('edit');
    Route::put('/{id}', [CorreosNotificables::class, 'update'])->name('update');
    Route::delete('/{id}', [CorreosNotificables::class, 'destroy'])->name('destroy');
});
//Rutas para operaciones
Route::prefix('operaciones')->name('operaciones.')->group(function () {
    Route::get('/', [OperacionController::class, 'index'])->name('index');
    Route::get('/crear', [OperacionController::class, 'create'])->name('create');
    Route::post('/', [OperacionController::class, 'store'])->name('store');
    Route::get('/{id}/editar', [OperacionController::class, 'edit'])->name('edit');
    Route::put('/{id}', [OperacionController::class, 'update'])->name('update');
    Route::delete('/{id}', [OperacionController::class, 'destroy'])->name('destroy');
});
// Rutas para Agendamientos (Solicitudes)
Route::prefix('agendamientos')
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
