<x-guest-layout>
<div class="w-full flex flex-col space-y-12 mt-5 mb-5 ">
    <!-- Sección de Bienvenida -->
    <section class="text-center px-4">
        <h1 class="w-full text-2xl md:text-4xl font-bold text-gray-800 mb-4">
            Bienvenido a Vp-Services
        </h1>
        <p class="text-base md:text-lg text-gray-600 mb-6">
            Accede y haz uso de nuestros funciones empresariales.
        </p>
        <a href="{{ route('login') }}"
            class="inline-flex items-center bg-orange-500 text-white font-semibold py-4 px-4 rounded-lg shadow-md transition transform duration-300 hover:bg-orange-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-opacity-75">
            Ingresar a nuestros servicios
        </a>
    </section>

    <!-- Cards informativos horizontales -->
    <section class="w-full max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
        <!-- Card de Acceso -->
        <div class="bg-blue-100 border border-blue-200 rounded-lg shadow-md p-6 text-center">
            <h2 class="text-xl md:text-2xl font-bold text-blue-900 mb-2">¿Cómo Acceder?</h2>
            <p class="text-xs md:text-sm text-blue-800 break-words">
                Si ya cuentas con una cuenta de usuario correspondiente a nuestro personal empresarial, da click en el botón naranja de ingreso e
                inicia sesión.
            </p>
        </div>
        <!-- Card de Quiénes Somos -->
        <div class="bg-green-100 border border-green-200 rounded-lg shadow-md p-6 text-center">
            <h2 class="text-xl md:text-2xl font-bold text-green-900 mb-2">¿Eres un Cliente?</h2>
            <p class="text-xs md:text-sm text-green-800 break-words">
                Si eres un cliente, actualmente contamos con nuestro servicio de agendamientos de descarga operando, accede mediante el botón Agendar Descarga ubicado en la barra lateral, tambien puedes usar la barra de busqueda para encontrarlo.
            </p>
        </div>
        <!-- Card de Servicios -->
        <div class="bg-purple-100 border border-purple-200 rounded-lg shadow-md p-6 text-center">
            <h2 class="text-xl md:text-2xl font-bold text-purple-900 mb-2">Nuestros Servicios</h2>
            <p class="text-xs md:text-sm text-purple-800 break-words">
                Buscamos automatizar tareas y funciones internas de la compañia, mediante desarrollo de software, con el fin de que este sea de uso tanto interno en la compañia como para el cliente o personal externo a la compañia Vigia Plus Logistics.
            </p>
        </div>
    </section>

    <!-- Slider de Proyectos -->
    <section class="w-full max-w-4xl mx-auto mt-10 px-4">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 text-center">
            Servicios y Detalles
        </h2>
        <div class="relative">
            <!-- Contenedor del slider con barra de desplazamiento visible -->
            <div
                class="auto-slider flex whitespace-nowrap overflow-x-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-gray-200">
                <!-- Slide 1 -->
                <div class="slide-item inline-block w-64 md:w-80 mr-4 bg-orange-100 rounded-lg shadow-md p-6">
                    <h3 class="text-lg md:text-xl font-semibold mb-2">Vps-Access:</h3>
                    <p class="text-xs md:text-sm text-gray-700 break-words whitespace-normal">
                        Control y autorización de agendamientos para el ingreso a nuestras instalaciones.
                    </p>
                </div>
                <!-- Slide 2 -->
                <div class="slide-item inline-block w-64 md:w-80 mr-4 bg-orange-100 rounded-lg shadow-md p-6">
                    <h3 class="text-lg md:text-xl font-semibold mb-2">Vps-Tracking:</h3>
                    <p class="text-xs md:text-sm text-gray-700 break-words whitespace-normal">
                        Proyecto en Desarrollo...
                    </p>
                </div>
                <!-- Slide 3 -->
                <div class="slide-item inline-block w-64 md:w-80 mr-4 bg-orange-100 rounded-lg shadow-md p-6">
                    <h3 class="text-lg md:text-xl font-semibold mb-2">Militar Standard:</h3>
                    <p class="text-xs md:text-sm text-gray-700 break-words whitespace-normal">
                        Proyecto en Desarrollo...
                    </p>
                </div>
                <!-- Agrega más slides si es necesario -->
            </div>
        </div>
    </section>
</div>
</x-guest-layout>