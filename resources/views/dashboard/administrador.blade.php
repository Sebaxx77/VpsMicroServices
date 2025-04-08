<x-app-layout>
    <div id="mainContent" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Panel de Control Administrativo</h2>

        <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Bienvenido, {{ $user['name'] }}</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Permisos</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total_permisos ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <span class="text-blue-600 text-xl">🔑</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Roles</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total_roles ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <span class="text-purple-600 text-xl">🛡️</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Usuarios Activos</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total_usuarios ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <span class="text-orange-600 text-xl">👥</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimos Usuarios Registrados</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ultimos_usuarios as $user)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $user['name'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $user['email'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td class="px-4 py-2 text-center" colspan="3">No hay usuarios registrados recientemente.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestión de Permisos</h3>
                <div class="space-y-3">
                    <a href="{{ route('seguridad.permisos.create') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">➕</span>
                        <span class="text-gray-700 font-medium">Crear Nuevo Permiso</span>
                    </a>
                    <a href="{{ route('seguridad.permisos.index') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">📋</span>
                        <span class="text-gray-700 font-medium">Listar Permisos</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestión de Roles</h3>
                <div class="space-y-3">
                    <a href="{{ route('seguridad.roles.create') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">➕</span>
                        <span class="text-gray-700 font-medium">Crear Nuevo Rol</span>
                    </a>
                    <a href="{{ route('seguridad.roles.index') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">📋</span>
                        <span class="text-gray-700 font-medium">Listar Roles</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestión de Usuarios</h3>
                <div class="space-y-3">
                    <a href="{{ route('usuarios.create') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">➕</span>
                        <span class="text-gray-700 font-medium">Crear Nuevo Usuario</span>
                    </a>
                    <a href="{{ route('usuarios.index') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">📋</span>
                        <span class="text-gray-700 font-medium">Listar Usuarios</span>
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestión de Operaciones</h3>
                <div class="space-y-3">
                    <a href="{{ route('operaciones.create') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">➕</span>
                        <span class="text-gray-700 font-medium">Crear Nueva Operación</span>
                    </a>
                    <a href="{{ route('operaciones.index') }}"
                        class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <span class="text-orange-600 mr-3">📋</span>
                        <span class="text-gray-700 font-medium">Listar Operaciones</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>