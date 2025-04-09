<x-app-layout>
<div class="container mx-auto px-4 mt-5">
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="text-2xl font-bold mb-6">Editar Usuario</h2>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nombre del Usuario -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nombre del Usuario:
                </label>
                <input type="text" name="name" id="name" placeholder="Ingresa el nombre"
                    value="{{ old('name', $usuario->name) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                @error('name')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Correo Electrónico -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Correo Electrónico:
                </label>
                <input type="email" name="email" id="email" placeholder="Ingresa el correo electrónico"
                    value="{{ old('email', $usuario->email) }}"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                @error('email')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Selección de Rol -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                    Rol:
                </label>
                <select name="role" id="role"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Seleccione un rol</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Selección de Operación -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="operacion_id">
                    Operación:
                </label>
                <select name="operacion_id" id="operacion_id"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    required>
                    <option value="">Seleccione una operación</option>
                    @foreach($operaciones as $operacion)
                    <option value="{{ $operacion->id }}">{{ $operacion->nombre }}</option>
                    @endforeach
                </select>
                @error('operacion_id')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>


            <!-- Contraseña (opcional, para actualizar si se desea) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Contraseña (dejar en blanco para mantener la actual):
                </label>
                <input type="password" name="password" id="password" placeholder="Ingresa la nueva contraseña"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('password')
                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
                    Confirmar Contraseña:
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirma la nueva contraseña"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-between md:justify-center md:space-x-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Actualizar
                </button>
                <a href="{{ route('usuarios.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</x-app-layout>