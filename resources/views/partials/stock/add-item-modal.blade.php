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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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