<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-bold mb-4">Crear Nuevo Usuario</h2>

            <form id="form-user" data-method="{{ isset($id) ? 'PUT' : 'POST' }}">
                {{-- Nombre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                </div>

                {{-- Correo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Correo</label>
                    <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                </div>

                {{-- Rol --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Rol</label>
                    <select name="role" id="role" class="block w-full rounded border-gray-300 shadow-sm text-sm" required>
                        <option value="">Seleccione un rol</option>
                    </select>
                </div>

                {{-- Operación --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Operación</label>
                    <select name="operacion" id="operacion" class="block w-full rounded border-gray-300 shadow-sm text-sm" required>
                        <option value="">Seleccione una operación</option>
                    </select>
                </div>

                {{-- Contraseña --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" class="block w-full rounded border-gray-300 shadow-sm text-sm" required>
                </div>

                {{-- Confirmar --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="block w-full rounded border-gray-300 shadow-sm text-sm" required>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const method = document.getElementById('form-user')?.dataset?.method || 'POST';

            // Definir directamente en window
            window.apiToken = @json($apiToken);
            window.apiBaseUrl = @json($apiUrl);
            window.submitUrl = @json($submitUrl);

            // Usar las globales directamente
            handleFormSubmit('form-user', window.submitUrl, method, window.apiToken, window.apiBaseUrl);
        });
    </script>
    @endpush
</x-app-layout>