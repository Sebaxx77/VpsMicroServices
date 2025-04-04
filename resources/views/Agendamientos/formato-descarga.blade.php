<x-guest-layout>
<div class="container mx-auto px-4 mt-5 mb-5">
    <!-- Se agrega un margin bottom para espacio en dispositivos móviles -->

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h2 class="text-2xl font-bold mb-6 text-center">Agendar Descarga de Pedidos</h2>

    <form action="{{ route('agendamiento.formato-descarga.enviar') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="fecha_entrega" class="block font-bold">Fecha de Descarga:</label>
                <input type="date" name="fecha_entrega" id="fecha_entrega" required class="w-full p-2 border rounded"
                    placeholder="Seleccione fecha">
            </div>

            <div>
                <label for="bodega" class="block font-bold">Bodega de Destino:</label>
                <input type="text" name="bodega" id="bodega" required class="w-full p-2 border rounded"
                    placeholder="Ingrese la bodega, Ej: (Bodega 12g)">
            </div>

            <div>
                <label for="op" class="block font-bold">Orden de Producción (OP):</label>
                <input type="text" name="op" id="op" required class="w-full p-2 border rounded"
                    placeholder="Ingrese el número de OP">
            </div>

            <div>
                <label for="proveedor" class="block font-bold">Proveedor:</label>
                <input type="text" name="proveedor" id="proveedor" required class="w-full p-2 border rounded"
                    placeholder="Ingrese el proveedor">
            </div>

            <div>
                <label for="codigo_articulo" class="block font-bold">Código de Artículo:</label>
                <input type="text" name="codigo_articulo" id="codigo_articulo" required
                    class="w-full p-2 border rounded" placeholder="Ingrese el código del artículo">
            </div>

            <div>
                <label for="nombre_articulo" class="block font-bold">Nombre del Artículo:</label>
                <input type="text" name="nombre_articulo" id="nombre_articulo" required
                    class="w-full p-2 border rounded" placeholder="Ingrese el nombre del artículo">
            </div>

            <div>
                <label for="cantidades_pedidas" class="block font-bold">Cantidad Pedida:</label>
                <input type="number" name="cantidades_pedidas" id="cantidades_pedidas" required
                    class="w-full p-2 border rounded" placeholder="Ingrese la cantidad pedida">
            </div>

            <div>
                <label for="placa" class="block font-bold">Placa Vehiculo:</label>
                <input type="text" name="placa" id="placa" required class="w-full p-2 border rounded"
                    placeholder="Ingrese la placa, Ej: (ABC-123)">
            </div>

            <div>
                <label for="conductor" class="block font-bold">Conductor:</label>
                <input type="text" name="conductor" id="conductor" required class="w-full p-2 border rounded"
                    placeholder="Ingrese el nombre del conductor">
            </div>

            <div>
                <label for="cedula" class="block font-bold">Cédula del Conductor:</label>
                <input type="text" name="cedula" id="cedula" required class="w-full p-2 border rounded"
                    placeholder="Ingrese la cédula del conductor">
            </div>

            <div>
                <label for="correo_solicitante" class="block font-bold">Correo del Solicitante:</label>
                <input type="email" name="correo_solicitante" id="correo_solicitante" required
                    class="w-full p-2 border rounded" placeholder="Ingrese su correo">
            </div>

            <div>
                <label for="celular" class="block font-bold">Celular:</label>
                <input type="text" name="celular" id="celular" required class="w-full p-2 border rounded"
                    placeholder="Ingrese su número de celular">
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Enviar Solicitud
            </button>
        </div>
    </form>
</div>
</x-guest-layout>