// File: resources/views/layouts/app.blade.php

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LOKUS - Dashboard</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 min-h-screen">
    <div id="dashboardSystem" class="min-h-screen flex">
        @include('partials.sidebar')
        <main class="flex-1 bg-stone-50">
            {{ $slot }}
        </main>
    </div>
    <script src="{{ asset('js/scripts.js') }}"></script>
     <script src="{{ asset('js/scripts.js') }}"></script>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirmationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-xl">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900" id="deleteModalTitle">Delete Item</h3>
                <p class="mt-2 text-sm text-gray-500" id="deleteModalText">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="mt-6 flex justify-center gap-4">
                <button type="button" id="cancelDeleteBtn" class="px-6 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                {{-- Form ini akan diisi oleh JavaScript --}}
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>