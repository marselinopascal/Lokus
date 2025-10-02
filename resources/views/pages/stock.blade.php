// File: resources/views/pages/stock.blade.php

<x-app-layout>
    <div class="p-8">
        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Notifikasi Error Validasi --}}
        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                <p class="font-bold">Terjadi Kesalahan!</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Stock Opname</h1>
            <p class="text-gray-600">Comprehensive inventory management and stock counting system</p>
        </div>

        <!-- Key Metrics Cards (statis untuk saat ini) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- ... Card divs ... --}}
        </div>

        <!-- Search and Filters (statis untuk saat ini) -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            {{-- ... Search and Filter divs ... --}}
        </div>

        <!-- Stock Items List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Stock Items</h2>
                    <div class="flex gap-3">
                        <button id="scanQRBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2">
                            📱 Scan QR
                        </button>
                        <a href="{{ route('stock.export') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2">📊 Export Excel</a>
                        <button id="addItemBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                            + Add Item
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Table Header -->
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 hidden md:grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
                <div class="col-span-3">Item Name</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-1">Physical</div>
                <div class="col-span-1">System</div>
                <div class="col-span-1">Difference</div>
                <div class="col-span-2">Location</div>
                <div class="col-span-2">Last Check</div>
                  <div class="col-span-1">Action</div>
            </div>
            
            <!-- Stock Items (Loop Dinamis) -->
            <div class="divide-y divide-gray-200">
                @forelse ($stockItems as $item)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            {{-- Item Name --}}
                            <div class="col-span-1 md:col-span-3">
                                <div class="flex items-center">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $item->name }}</h3>
                                        <p class="text-sm text-gray-600">SKU: {{ $item->sku }}</p>
                                    </div>
                                    @php $difference = $item->physical_count - $item->system_count; @endphp
                                    <span class="status-tag {{ $difference == 0 ? 'status-normal' : 'status-high' }} ml-3">
                                        {{ $difference == 0 ? 'Match' : 'Discrepancy' }}
                                    </span>
                                </div>
                            </div>
                            {{-- Category --}}
                            <div class="col-span-1 md:col-span-2 text-gray-600"><span class="md:hidden font-bold">Category: </span>{{ $item->category }}</div>
                            {{-- Physical --}}
                            <div class="col-span-1 font-semibold text-gray-800"><span class="md:hidden font-bold">Physical: </span>{{ $item->physical_count }}</div>
                            {{-- System --}}
                            <div class="col-span-1 font-semibold text-gray-800"><span class="md:hidden font-bold">System: </span>{{ $item->system_count }}</div>
                            {{-- Difference --}}
                            <div class="col-span-1 {{ $difference == 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                <span class="md:hidden font-bold">Difference: </span>{{ $difference > 0 ? '+' : '' }}{{ $difference }}
                            </div>
                            {{-- Location --}}
                            <div class="col-span-1 md:col-span-2 text-gray-600"><span class="md:hidden font-bold">Location: </span>{{ $item->location }}</div>
                            {{-- Last Check --}}
                            <div class="col-span-1 md:col-span-2 text-sm text-gray-500"><span class="md:hidden font-bold">Last Check: </span>{{ $item->last_checked_at ? $item->last_checked_at->format('M d, Y') : 'N/A' }}</div>
                         <div class="col-span-1">
                                 <a href="{{ route('stock.show', $item) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        Tidak ada item stok yang ditemukan. Silakan tambahkan item baru.
                    </div>
                @endforelse
            </div>
            
            {{-- Pagination Links --}}
            <div class="p-6">
                {{ $stockItems->links() }}
            </div>
        </div>
    </div>

    <!-- Manual Entry Modal / Add Item Modal -->
    <div id="manualEntryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Add New Item</h3>
                <button id="closeManualModal" class="text-gray-500 hover:text-gray-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <form id="manualEntryForm" method="POST" action="{{ route('stock.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <option value="">Select Category</option>
                            <option value="Electronics" @selected(old('category') == 'Electronics')>Electronics</option>
                            <option value="Office Supplies" @selected(old('category') == 'Office Supplies')>Office Supplies</option>
                            <option value="Equipment" @selected(old('category') == 'Equipment')>Equipment</option>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" required placeholder="e.g., Warehouse A - Rack 12" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="physical_count" class="block text-sm font-medium text-gray-700 mb-2">Physical Count</label>
                        <input type="number" id="physical_count" name="physical_count" value="{{ old('physical_count', 0) }}" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="system_count" class="block text-sm font-medium text-gray-700 mb-2">System Count</label>
                        <input type="number" id="system_count" name="system_count" value="{{ old('system_count', 0) }}" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelManualEntry" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="qrScannerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Scan Stock Item QR Code</h3>
            <button id="closeQRModal" class="text-gray-500 hover:text-gray-700">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        
        <div class="w-full">
            {{-- Area ini akan digunakan untuk menampilkan kamera --}}
            <div id="qr-reader" class="w-full border-2 border-dashed border-gray-300 rounded-lg overflow-hidden"></div>
            <div id="qr-reader-results" class="mt-2 text-sm text-green-600 font-semibold"></div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Arahkan kamera ke QR Code. Pemindaian akan dimulai secara otomatis.</p>
        </div>
    </div>
</div>

</x-app-layout>