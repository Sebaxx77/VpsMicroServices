<x-app-layout>
    <div class="container mx-auto px-4 mb-5 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Correos Notificables</h2>
            <a href="{{ route('correos-notificables.create') }}"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Nuevo Correo Notificable
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
            <table id="correos_notificables-table" class="table table-bordered table-hover w-full">
                <thead class="table-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
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
                selector: '#correos_notificables-table',  // Asegúrate de que coincida con el id de la tabla en el HTML
                apiUrl: @json($apiUrl),
                apiToken: @json($apiToken),
                columns: [
                    { data: 'nombre' },
                    { data: 'correo' },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `
                                <a href="/correos-notificables/${row.id}/edit" class="text-blue-500 hover:text-blue-600 mr-3">Editar</a>
                                <form action="/correos-notificables/${row.id}" method="POST" style="display:inline;">
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