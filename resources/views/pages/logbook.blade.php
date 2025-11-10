<x-app-layout>
    {{-- CSS Kustom untuk status "On Progress" --}}
    <style>
        .status-on-progress { background-color: #e0f2fe; color: #0284c7; } /* light-blue-100, sky-600 */
    </style>

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
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Logbook</h1>
            <p class="text-gray-600">Manage and track all system entries</p>
        </div>
        
        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
            <form method="GET" action="{{ route('logbook.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search by Title/Description</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Enter keyword..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                            <option value="all">All Categories</option>
                            <option value="Perawatan" @selected(request('category') == 'Perawatan')>Perawatan</option>
                            <option value="Peminjaman" @selected(request('category') == 'Peminjaman')>Peminjaman</option>
                            <option value="Pengembalian" @selected(request('category') == 'Pengembalian')>Pengembalian</option>
                            <option value="Instalasi" @selected(request('category') == 'Instalasi')>Instalasi</option>
                            <option value="Perbaikan" @selected(request('category') == 'Perbaikan')>Perbaikan</option>
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500 sm:text-sm">
                            <option value="all">All Status</option>
                            <option value="Pending" @selected(request('status') == 'Pending')>Pending</option>
                            <option value="On Progress" @selected(request('status') == 'On Progress')>On Progress</option>
                            <option value="Completed" @selected(request('status') == 'Completed')>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-4">
                    <a href="{{ route('logbook.index') }}" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 text-sm font-medium">Reset</a>
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 text-sm font-medium">Apply Filters</button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Logbook Entries</h2>
                    <div class="flex gap-3">
                        <button id="addLogbookBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm font-medium">+ New Entry</button>
                        <a href="{{ route('logbook.export', request()->query()) }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm font-medium flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            Export
                        </a>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($logbookEntries as $entry)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between flex-wrap">
                            <div class="flex-1 mr-4 mb-4 md:mb-0">
                                <h3 class="font-semibold text-gray-800 mb-2">{{ $entry->title }}</h3>
                                <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($entry->description, 150) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>Created by {{ $entry->user->first_name }} {{ $entry->user->last_name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $entry->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-start sm:items-end space-y-2">
                                @php
                                    $priorityClass = $entry->priority === 'High Priority' ? 'status-high' : 'status-normal';
                                    $statusClass = 'status-pending';
                                    if ($entry->status === 'Completed') $statusClass = 'status-completed';
                                    if ($entry->status === 'On Progress') $statusClass = 'status-on-progress';
                                @endphp
                                <span class="status-tag {{ $priorityClass }}">{{ $entry->priority }}</span>
                                <span class="status-tag {{ $statusClass }}">{{ $entry->status }}</span>
                                <a href="{{ route('logbook.show', $entry) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2">Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No logbook entries found matching your criteria.
                    </div>
                @endforelse
            </div>
            <div class="p-6">
                {{ $logbookEntries->links() }}
            </div>
        </div>
    </div>

    <!-- Add Logbook Entry Modal -->
    <div id="addLogbookModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Create New Logbook Entry</h3>
                <button id="closeLogbookModal" class="text-gray-500 hover:text-gray-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <form id="addLogbookForm" method="POST" action="{{ route('logbook.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select id="category" name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="Perawatan">Perawatan</option>
                        <option value="Peminjaman">Peminjaman</option>
                        <option value="Pengembalian">Pengembalian</option>
                        <option value="Instalasi">Instalasi</option>
                        <option value="Perbaikan">Perbaikan</option>
                    </select>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="Normal">Normal</option>
                        <option value="High Priority">High Priority</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelLogbookEntry" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Create Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>