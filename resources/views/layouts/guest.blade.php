<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LOKUS - Logbook & Stock Opname System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <!-- Logo (BAGIAN YANG DIPERBARUI) -->
            <div class="text-center mb-8">
                <a href="/">
                    {{-- Tampilkan gambar logo dari folder public/images --}}
                    <img src="{{ asset('images/logo.png') }}" alt="LOKUS Logo" class="mx-auto h-16 w-auto">
                </a>
                <p class="text-gray-600 mt-4">Logbook & Stock Opname System</p>
            </div>
            
            <!-- Card Content -->
            <div class="bg-white login-card rounded-lg p-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>