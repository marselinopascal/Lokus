
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800">User Management</h2>
        <button class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">+ Add User</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            {{-- ... <thead> ... --}}
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $userItem)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><div class="font-medium text-gray-900">{{ $userItem->first_name }} {{ $userItem->last_name }}</div></td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $userItem->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $userItem->department }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <button class="text-blue-600 hover:text-blue-800 mr-3">Edit</button>
                            <button class="text-red-600 hover:text-red-800">Disable</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- TAMBAHKAN PAGINASI --}}
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>