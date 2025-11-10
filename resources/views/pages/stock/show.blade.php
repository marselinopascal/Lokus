
<x-app-layout>
    <div class="p-8">
        {{-- ... Notifikasi ... --}}

        <div class="mb-8">
            <a href="{{ route('stock.index') }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center mb-4">&larr; Back to Stock List</a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Stock Item Details</h1>
            <p class="text-gray-600">Detailed information for {{ $stockItem->name }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Kolom Kiri & Tengah (Informasi) --}}
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Kolom Kiri --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Item Information</h3>
                            <dl class="space-y-4">
                                <div><dt class="text-sm font-medium text-gray-500">Item Name</dt><dd class="mt-1 text-lg text-gray-900">{{ $stockItem->name }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">SKU</dt><dd class="mt-1 text-lg text-gray-900">{{ $stockItem->sku }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Category</dt><dd class="mt-1 text-lg text-gray-900">{{ $stockItem->category }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Location</dt><dd class="mt-1 text-lg text-gray-900">{{ $stockItem->location }}</dd></div>
                            </dl>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-4">Stock Count</h3>
                            <dl class="space-y-4">
                                <div><dt class="text-sm font-medium text-gray-500">Physical Count</dt><dd class="mt-1 text-lg font-bold text-gray-900">{{ $stockItem->physical_count }}</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">System Count</dt><dd class="mt-1 text-lg font-bold text-gray-900">{{ $stockItem->system_count }}</dd></div>
                                @php $difference = $stockItem->physical_count - $stockItem->system_count; @endphp
                                <div><dt class="text-sm font-medium text-gray-500">Difference</dt><dd class="mt-1 text-lg font-bold {{ $difference == 0 ? 'text-green-600' : 'text-red-600' }}">...</dd></div>
                                <div><dt class="text-sm font-medium text-gray-500">Last Checked</dt><dd class="mt-1 text-lg text-gray-900">{{ $stockItem->last_checked_at ? $stockItem->last_checked_at->format('F d, Y \a\t H:i') : 'Never' }}</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (QR Code) --}}
                <div class="text-center border-l-0 md:border-l md:pl-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Item QR Code</h3>
                    <div class="p-4 border rounded-lg inline-block">
                        {{-- Tampilkan gambar QR Code dari rute yang kita buat --}}
                        <img src="{{ route('stock.qr', $stockItem) }}" alt="QR Code for {{ $stockItem->sku }}">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Scan this code to quickly identify the item.</p>
                    <a href="{{ route('stock.qr', $stockItem) }}" download="qr-{{ $stockItem->sku }}.svg" class="mt-4 inline-block bg-gray-600 text-white px-5 py-2 rounded-md hover:bg-gray-700 text-sm">
                        Download QR
                    </a>
                </div>
            </div>
            
            {{-- Action Buttons --}}
            <div class="mt-8 pt-6 border-t flex items-center gap-4">
                <a href="{{ route('stock.edit', $stockItem) }}" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">Edit Item</a>
                <button type="button" class="delete-button ..." data-url="{{ route('stock.destroy', $stockItem) }}">Delete Item</button>
            </div>
        </div>
    </div>
</x-app-layout>