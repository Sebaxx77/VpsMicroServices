<div id="sidebar"
    class="fixed top-0 left-0 bg-gray-800 text-white w-auto max-w-xs h-screen p-4 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50">

    <div class="mb-4">
        <input id="sidebarSearch" type="text" placeholder="Buscar..."
            class="w-full p-2 rounded bg-gray-700 text-white placeholder-gray-400">
    </div>

    <ul id="sidebarList" class="text-sm md:text-base">
        @empty(session('api_token'))
            <h2 class="mb-2">Menú de Opciones</h2>
            <li>
                <a href="{{ route('bienvenido') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Inicio</a>
            </li>
            <li>
                <a href="{{ route('login') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Iniciar Sesión</a>
            </li>
            <hr class="my-2 border-gray-600">
            <h2 class="mb-2">Agendamientos</h2>
            <li>
                <a href="{{ route('login') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Agendar Visita</a>
            </li>
            <li>
                <a href="{{ route('agendamiento.formato-descarga.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Agendar Descarga</a>
            </li>
        @else
            <h2 class="mb-2">Menú de Opciones</h2>
            <li>
                <a href="{{ route('dashboard') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Panel de Control</a>
            </li>
            <li>
                <a href="{{ route('profile.manage') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Perfil</a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Cerrar Sesión</button>
                </form>
            </li>
            <hr class="my-2 border-gray-600">

            @if(isset($user['role']) && $user['role'] === 'Administrador')
                <h2 class="mb-2">Opciones Administrador</h2>
                <li>
                    <a href="{{ route('usuarios.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Usuarios</a>
                </li>
                <li>
                    <a href="{{ route('parques-industriales.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Parques Industriales</a>
                </li>
                <li>
                    <a href="{{ route('correos-notificables.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Correos Notificables</a>
                </li>
                <li>
                    <a href="{{ route('operaciones.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Operaciones</a>
                </li>
                <li>
                    <a href="{{ route('seguridad.roles.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Roles</a>
                </li>
                <li>
                    <a href="{{ route('seguridad.permisos.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Permisos</a>
                </li>
            @endif

            @if(isset($user['role']) && $user['role'] === 'Autorizador Agendamientos')
                <h2 class="mb-2">Opciones Autorizador</h2>
                <li>
                    <a href="{{ route('solicitudes.gestion') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Gestión Solicitudes</a>
                </li>
                <li>
                    <a href="{{ route('solicitudes.pendientes') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Solicitudes Pendientes</a>
                </li>
            @endif

            @if(isset($user['role']) && $user['role'] === 'Supervisor Agendamientos')
                <h2 class="mb-2">Opciones Supervisor</h2>
                <li>
                    <a href="{{ route('agendamientos.descarga.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Agendamientos Descarga</a>
                </li>
                <li>
                    <a href="{{ route('agendamientos.visita.index') }}" class="block p-2 text-blue-400 hover:text-white hover:bg-blue-600 transition-colors">Agendamientos Visita</a>
                </li>
            @endif
        @endempty
    </ul>
</div>