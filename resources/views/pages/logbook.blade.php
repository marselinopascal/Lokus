// File: resources/views/pages/logbook.blade.php
<x-app-layout>
    <div class="p-8">
        {{-- Notifikasi Sukses & Error --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p class="font-bold">Sukses!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif
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

        <div class="mb-8"><h1 class="text-3xl font-bold text-gray-800 mb-2">Logbook</h1><p class="text-gray-600">Manage and track all system entries</p></div>
        
        {{-- ... Bagian Search/Filter dan Summary Cards (masih statis) ... --}}

        <!-- Entries List -->
       <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Recent Entries</h2>
                    <button id="addLogbookBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        + New Entry
                    </button>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse ($logbookEntries as $entry)
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 mr-4">
                                <h3 class="font-semibold text-gray-800 mb-2">{{ $entry->title }}</h3>
                                <p class="text-gray-600 mb-3 text-sm">{{ Str::limit($entry->description, 150) }}</p>
                                <div class="flex items-center text-sm text-gray-500">
                                    <span>Created by {{ $entry->user->first_name }} {{ $entry->user->last_name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $entry->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                @php
                                    $priorityClass = $entry->priority === 'High Priority' ? 'status-high' : 'status-normal';
                                    $statusClass = $entry->status === 'Completed' ? 'status-completed' : 'status-pending';
                                @endphp
                                <span class="status-tag {{ $priorityClass }}">{{ $entry->priority }}</span>
                                <span class="status-tag {{ $statusClass }}">{{ $entry->status }}</span>
                                {{-- TOMBOL DETAILS BARU --}}
                                <a href="{{ route('logbook.show', $entry) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2">Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        No logbook entries found. Please create a new entry.
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
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <option value="Normal" @selected(old('priority') == 'Normal')>Normal</option>
                        <option value="High Priority" @selected(old('priority') == 'High Priority')>High Priority</option>
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