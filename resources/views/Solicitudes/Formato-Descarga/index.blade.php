<x-app-layout>
    <div class="container mx-auto px-4 mb-5 mt-5">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Gestión de Solicitudes Agendamiento Descarga</h2>
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
            <table id="solicitudes-table" class="table table-bordered table-hover w-full text-xs md:text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th>Fecha Entrega</th>
                        <th>Bodega</th>
                        <th>OP</th>
                        <th>Código Artículo</th>
                        <th>Nombre Artículo</th>
                        <th>Cant. Pedidas</th>
                        <th>Placa</th>
                        <th>Conductor</th>
                        <th>Cédula</th>
                        <th>Correo Solicitante</th>
                        <th>Estatus</th>
                        <th>Fecha Creación</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initDataTable({
                selector: '#solicitudes-table',
                apiUrl: @json($apiUrl),
                apiToken: @json($apiToken),
                columns: [
                    { data: 'fecha_entrega', render: data => formatDate(data) },
                    { data: 'bodega', defaultContent: '-' },
                    { data: 'op', defaultContent: '-' },
                    { data: 'codigo_articulo', defaultContent: '-' },
                    { data: 'nombre_articulo', defaultContent: '-' },
                    { data: 'cantidades_pedidas', defaultContent: '-' },
                    { data: 'placa', defaultContent: '-' },
                    { data: 'conductor', defaultContent: '-' },
                    { data: 'cedula', defaultContent: '-' },
                    { data: 'correo_solicitante', defaultContent: '-' },
                    { data: 'estatus', render: data => capitalize(data) },
                    { data: 'created_at', render: data => formatDate(data) },
                ]
            });
        });

        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            return d.toLocaleDateString('es-ES'); // dd/mm/yyyy
        }

        function capitalize(str) {
            if (!str) return '-';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>
    @endpush
</x-app-layout>
