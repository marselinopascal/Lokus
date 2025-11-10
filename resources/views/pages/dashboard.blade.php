
<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Overview</h1>
            <p class="text-gray-600">Welcome to your LOKUS system dashboard, {{ Auth::user()->first_name }}!</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Total Logbook Entries</h3><p class="text-3xl font-bold text-gray-800">{{ $logbookTotal }}</p><p class="text-sm text-gray-600 mt-2">{{ $logbookThisWeek }} entries this week</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Total Stock Items</h3><p class="text-3xl font-bold text-gray-800">{{ $stockTotal }}</p><p class="text-sm text-red-600 mt-2">{{ $discrepancies }} discrepancies found</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Pending Reviews</h3><p class="text-3xl font-bold text-gray-800">{{ $pendingReviews }}</p><p class="text-sm text-yellow-600 mt-2">Requires attention</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Active Users</h3><p class="text-3xl font-bold text-gray-800">{{ $userCount }}</p><p class="text-sm text-green-600 mt-2">{{ $completedToday }} logbooks completed today</p></div>
        </div>
        <div class="bg-white rounded-lg shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200"><h2 class="text-xl font-semibold text-gray-800">Recent Activities</h2></div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <div class="flex items-center justify-between py-3 @unless($loop->last) border-b border-gray-100 @endunless">
                            <div><p class="font-medium text-gray-800">{{ $activity->title }}</p><p class="text-sm text-gray-600">Created by {{ $activity->user->first_name }}</p></div>
                            <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No recent activities found.</p>
                    @endforelse
                </div>
            </div>
        </div>
        {{-- Tombol Quick Actions --}}
        <div>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('logbook.index') }}" class="bg-white data-card rounded-lg p-6 shadow-sm cursor-pointer block"><div class="text-center"><span class="text-3xl mb-3 block">📝</span><h3 class="font-semibold text-gray-800 mb-2">New Entry</h3><p class="text-sm text-gray-600">Create a new logbook entry</p></div></a>
                <a href="{{ route('stock.index') }}" class="bg-white data-card rounded-lg p-6 shadow-sm cursor-pointer block"><div class="text-center"><span class="text-3xl mb-3 block">📦</span><h3 class="font-semibold text-gray-800 mb-2">Stock Count</h3><p class="text-sm text-gray-600">Start new stock opname</p></div></a>
                <a href="{{ route('reports.index') }}" class="bg-white data-card rounded-lg p-6 shadow-sm cursor-pointer block"><div class="text-center"><span class="text-3xl mb-3 block">📈</span><h3 class="font-semibold text-gray-800 mb-2">Generate Report</h3><p class="text-sm text-gray-600">Create system report</p></div></a>
            </div>
        </div>
    </div>
</x-app-layout>