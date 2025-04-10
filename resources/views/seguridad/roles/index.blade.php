<x-app-layout>
    <div class="container mx-auto px-4 mb-5 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Roles</h2>
            <a href="{{ route('seguridad.roles.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Nuevo Rol
            </a>
        </div>
    
        <!-- Alertas -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
    
        <div class="bg-white rounded-lg shadow p-3 overflow-x-auto">
            <table id="roles-table" class="table table-bordered table-hover w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initDataTable({
                selector: '#roles-table',
                apiUrl: @json($apiUrl),
                apiToken: @json($apiToken),
                columns: [
                    { data: 'name' },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="/seguridad/roles/${row.id}/edit" class="text-blue-500 hover:text-blue-600 mr-3">Editar</a>
                                <form action="/seguridad/roles/${row.id}" method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name=csrf-token]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="text-red-500 hover:text-red-600" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                                </form>
                            `;
                        }
                    }
                ]
            });
        });
    </script>
    @endpush
</x-app-layout>    