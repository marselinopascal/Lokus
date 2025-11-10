
<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <a href="{{ route('logbook.show', $logbookEntry) }}" class="text-gray-600 hover:text-gray-800 inline-flex items-center mb-4">&larr; Back to Details</a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Logbook Entry</h1>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-8 max-w-2xl mx-auto">
            <form method="POST" action="{{ route('logbook.update', $logbookEntry) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $logbookEntry->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea id="description" name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description', $logbookEntry->description) }}</textarea>
                </div>
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select id="priority" name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="Normal" @selected(old('priority', $logbookEntry->priority) == 'Normal')>Normal</option>
                        <option value="High Priority" @selected(old('priority', $logbookEntry->priority) == 'High Priority')>High Priority</option>
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="Pending" @selected(old('status', $logbookEntry->status) == 'Pending')>Pending</option>
                        <option value="Completed" @selected(old('status', $logbookEntry->status) == 'Completed')>Completed</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('logbook.show', $logbookEntry) }}" class="px-5 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Entry</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>