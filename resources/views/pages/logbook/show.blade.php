
<x-app-layout>
    <div class="p-8">
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert"><p>{{ session('success') }}</p></div>
        @endif

        <div class="mb-8">
            <a href="{{ route('logbook.index') }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center mb-4">&larr; Back to Logbook List</a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Logbook Entry Details</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900">{{ $logbookEntry->title }}</h2>
                <div class="flex items-center text-sm text-gray-500 mt-2">
                    <span>Created by {{ $logbookEntry->user->first_name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $logbookEntry->created_at->format('F d, Y \a\t H:i') }}</span>
                </div>
            </div>

            <div class="prose max-w-none text-gray-700 mb-6">
                <p>{{ $logbookEntry->description }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t pt-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1"><span class="status-tag {{ $logbookEntry->priority === 'High Priority' ? 'status-high' : 'status-normal' }}">{{ $logbookEntry->priority }}</span></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><span class="status-tag {{ $logbookEntry->status === 'Completed' ? 'status-completed' : 'status-pending' }}">{{ $logbookEntry->status }}</span></dd>
                </div>
            </div>
            
            <div class="mt-8 pt-6 border-t flex items-center gap-4">
                <a href="{{ route('logbook.edit', $logbookEntry) }}" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">Edit Entry</a>
                <form method="POST" action="{{ route('logbook.destroy', $logbookEntry) }}" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-md hover:bg-red-700">Delete Entry</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>