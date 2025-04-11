<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- Encabezado --}}
        <div class="mb-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Perfil') }}
            </h2>
        </div>

        {{-- Actualizar la información del perfil --}}
        <div class="bg-white shadow sm:rounded-lg mb-4">
            <div class="px-4 py-3 sm:px-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Información del perfil') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Actualiza la información de tu perfil.') }}</p>
            </div>

            <div class="px-4 py-3 sm:p-4">
                {{-- Spinner de carga --}}
                <div id="profile-spinner" class="flex items-center space-x-2 mb-4 hidden">
                    <div class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-blue-600"></div>
                    <span class="text-sm text-gray-600">Cargando perfil...</span>
                </div>

                <form onsubmit="updateProfile(event)">
                    {{-- Campo Nombre --}}
                    <div class="mb-3">
                        <label for="name" class="block text-sm font-medium text-gray-700">{{ __('Nombre') }}</label>
                        <input id="name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                    </div>

                    {{-- Campo Correo --}}
                    <div class="mb-3">
                        <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Correo electrónico') }}</label>
                        <input id="email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required>
                    </div>

                    <div class="mt-2">
                        <button type="submit" class="inline-flex justify-center py-1.5 px-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded hover:bg-blue-700">
                            {{ __('Guardar cambios') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Autenticación en dos factores --}}
        <div class="bg-white shadow sm:rounded-lg mb-4">
            <div class="px-4 py-3 sm:px-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __('Autenticación en dos factores') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('Asegúrate de que tu cuenta esté protegida mediante la autenticación en dos factores.') }}</p>
            </div>

            <div class="px-4 py-3 sm:px-4">
                <button onclick="toggleTwoFactor(event)" class="inline-flex justify-center py-1.5 px-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded hover:bg-blue-700">
                    {{ __('Activar/Desactivar 2FA') }}
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.apiToken = @json($apiToken);
            window.apiBaseUrl = @json($apiUrl);

            if (typeof loadProfile === 'function') {
                loadProfile();
            }
        });
    </script>
    @endpush
</x-app-layout>
