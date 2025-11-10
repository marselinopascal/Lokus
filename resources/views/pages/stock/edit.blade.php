
<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <a href="{{ route('stock.show', $stockItem->id) }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back to Item Details
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Stock Item</h1>
            <p class="text-gray-600">Update the details for {{ $stockItem->name }}</p>
        </div>

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

        <div class="bg-white rounded-lg shadow-sm p-8">
            <form method="POST" action="{{ route('stock.update', $stockItem->id) }}" class="space-y-4">
                @csrf
                @method('PATCH') {{-- PENTING: Memberitahu Laravel ini adalah request UPDATE --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $stockItem->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $stockItem->sku) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="category" name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <option value="Electronics" @selected(old('category', $stockItem->category) == 'Electronics')>Electronics</option>
                            <option value="Office Supplies" @selected(old('category', $stockItem->category) == 'Office Supplies')>Office Supplies</option>
                            <option value="Equipment" @selected(old('category', $stockItem->category) == 'Equipment')>Equipment</option>
                        </select>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <input type="text" id="location" name="location" value="{{ old('location', $stockItem->location) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="physical_count" class="block text-sm font-medium text-gray-700 mb-2">Physical Count</label>
                        <input type="number" id="physical_count" name="physical_count" value="{{ old('physical_count', $stockItem->physical_count) }}" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="system_count" class="block text-sm font-medium text-gray-700 mb-2">System Count</label>
                        <input type="number" id="system_count" name="system_count" value="{{ old('system_count', $stockItem->system_count) }}" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                    <a href="{{ route('stock.show', $stockItem->id) }}" class="px-5 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Update Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>