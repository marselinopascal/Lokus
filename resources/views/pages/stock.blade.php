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

        <!-- Key Metrics Cards (DINAMIS) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white data-card rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Total Items</h3>
                <p class="text-3xl font-bold text-gray-800">{{ $totalItems }}</p>
            </div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Discrepancies</h3>
                <p class="text-3xl font-bold text-red-600">{{ $discrepancies }}</p>
            </div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm">
                <h3 class="text-sm font-medium text-gray-600 mb-2">Accuracy Rate</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($accuracyRate, 2) }}%</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
            <form method="GET" action="{{ route('stock.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search by Name/SKU</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter keyword..." class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                            <option value="all">All Categories</option>
                            <option value="Electronics" @selected(request('category') == 'Electronics')>Electronics</option>
                            <option value="Office Supplies" @selected(request('category') == 'Office Supplies')>Office Supplies</option>
                            <option value="Equipment" @selected(request('category') == 'Equipment')>Equipment</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                            <option value="">All Status</option>
                            <option value="match" @selected(request('status') == 'match')>Match</option>
                            <option value="discrepancy" @selected(request('status') == 'discrepancy')>Discrepancy</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <a href="{{ route('stock.index') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium">Reset</a>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Stock Items List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Stock Items</h2>
                    <div class="flex gap-3">
                        <button id="scanQRBtn" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center gap-2 text-sm font-medium">📱 Scan QR</button>
                        <a href="{{ route('stock.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center gap-2 text-sm font-medium">📊 Export Excel</a>
                        <button id="addItemBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">+ Add Item</button>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 hidden md:grid grid-cols-12 gap-4 text-sm font-medium text-gray-600">
                <div class="col-span-3">Item Name</div>
                <div class="col-span-2">Category</div>
                <div class="col-span-1">Physical</div>
                <div class="col-span-1">System</div>
                <div class="col-span-1">Difference</div>
                <div class="col-span-2">Location</div>
                <div class="col-span-1">Last Check</div>
                <div class="col-span-1">Action</div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse ($stockItems as $item)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                            <div class="col-span-1 md:col-span-3">
                                <div class="flex items-center">
                                    <div><h3 class="font-semibold text-gray-800">{{ $item->name }}</h3><p class="text-sm text-gray-600">SKU: {{ $item->sku }}</p></div>
                                    @php $difference = $item->physical_count - $item->system_count; @endphp
                                    <span class="status-tag {{ $difference == 0 ? 'status-normal' : 'status-high' }} ml-3">{{ $difference == 0 ? 'Match' : 'Discrepancy' }}</span>
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-2 text-gray-600"><span class="md:hidden font-bold">Category: </span>{{ $item->category }}</div>
                            <div class="col-span-1 font-semibold text-gray-800"><span class="md:hidden font-bold">Physical: </span>{{ $item->physical_count }}</div>
                            <div class="col-span-1 font-semibold text-gray-800"><span class="md:hidden font-bold">System: </span>{{ $item->system_count }}</div>
                            <div class="col-span-1 {{ $difference == 0 ? 'text-green-600' : 'text-red-600' }} font-medium"><span class="md:hidden font-bold">Difference: </span>{{ $difference > 0 ? '+' : '' }}{{ $difference }}</div>
                            <div class="col-span-1 md:col-span-2 text-gray-600"><span class="md:hidden font-bold">Location: </span>{{ $item->location }}</div>
                            <div class="col-span-1 text-sm text-gray-500"><span class="md:hidden font-bold">Last Check: </span>{{ $item->last_checked_at ? $item->last_checked_at->format('M d, Y') : 'N/A' }}</div>
                            <div class="col-span-1"><a href="{{ route('stock.show', $item) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Details</a></div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">No stock items found matching your criteria.</div>
                @endforelse
            </div>
            
            <div class="p-6">{{ $stockItems->links() }}</div>
        </div>
    </div>

    <!-- Modals -->
    @include('partials.stock.add-item-modal')
    @include('partials.stock.qr-scanner-modal')
</x-app-layout>