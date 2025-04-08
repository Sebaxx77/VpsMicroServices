<x-app-layout>
    <div id="mainContent" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Panel de Control Autorizador</h2>

        <!-- Bienvenida -->
        <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Bienvenido, {{ $user['name'] }}</h1>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Solicitudes -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Solicitudes</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $total_solicitudes ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <!-- Icono: Llave -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6 0a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Solicitudes Pendientes -->
            <div class="bg-white p-6 rounded-xl shadow-sm border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Solicitudes Pendientes</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">{{ $solicitudes_pendientes ?? 0 }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <!-- Icono: Escudo -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6 0a9 9 0 11-18 0a9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div></div>
        </div>

        <!-- Últimas Solicitudes de Agendamiento -->
        <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Últimas Solicitudes de Agendamiento</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">OP</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Proveedor</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Bodega</th>
                            <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Fecha De Entrega</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($ultimas_solicitudes as $solicitud)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $solicitud['op'] ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $solicitud['proveedor'] ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">{{ $solicitud['bodega'] ?? '-' }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800">
                                {{ $solicitud['fecha_entrega'] ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr><td class="px-4 py-2 text-center" colspan="3">No se encontraron solicitudes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gestión de Solicitudes -->
            <div class="bg-white p-6 rounded-xl shadow-sm">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Gestionar Solicitudes</h3>
                <div class="space-y-3">
                    <a href="{{ route('solicitudes.gestion') }}" class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <!-- Icono de lista -->
                        <span class="text-orange-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <span class="text-gray-700 font-medium">Listar Solicitudes</span>
                    </a>
                    <a href="{{ route('solicitudes.pendientes') }}" class="flex items-center p-3 bg-gray-50 hover:bg-orange-50 rounded-lg transition-colors">
                        <!-- Icono de reloj -->
                        <span class="text-orange-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </span>
                        <span class="text-gray-700 font-medium">Solicitudes Pendientes</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>