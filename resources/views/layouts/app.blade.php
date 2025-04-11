<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Plataforma de microservicios de Vigia Plus Services. Accede a herramientas empresariales para gestión de ingresos, tracking y más.">
    <title>Vps-MicroServices</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logos/VigiaLogo.png') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-white min-h-screen flex flex-col">
    <!-- Sidebar para usuarios autenticados y con rol respectivo -->
    @include('partials.sidebar')

    <!-- Header -->
    @include('components.header')

    <!-- Área de contenido: centra su contenido verticalmente y expande para ajustarse con los demas componentes-->
    <main class="flex-grow px-4 md:ml-64">
        {{-- Aquí se inyecta el contenido principal --}}
        {{ $slot }}
    </main>

    <!-- Footer -->
    @include('components.footer')

    @stack('modals')
    @livewireScripts
    @stack('scripts')
</body>

</html>