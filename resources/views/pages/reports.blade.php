
<x-app-layout>
    <div class="p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Reports</h1>
            <p class="text-gray-600">Generate and manage system reports</p>
        </div>

        <!-- Report Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Total Generated Reports</h3><p class="text-3xl font-bold text-gray-800">{{ $totalReports }}</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Total Stock Items</h3><p class="text-3xl font-bold text-gray-800">{{ $stockItemCount }}</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Total Logbook Entries</h3><p class="text-3xl font-bold text-gray-800">{{ $logbookEntryCount }}</p></div>
            <div class="bg-white data-card rounded-lg p-6 shadow-sm"><h3 class="text-sm font-medium text-gray-600 mb-2">Stock Discrepancies</h3><p class="text-3xl font-bold text-gray-800">{{ $discrepancyCount }}</p></div>
        </div>

        <!-- Reports List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Recent System Activities</h2>
                    <button id="generateReportBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">+ Generate New Report</button>
                </div>
            </div>
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Latest Stock Discrepancies</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50"><tr><th class="p-2 text-left">Item Name</th><th class="p-2 text-left">SKU</th><th class="p-2">Difference</th><th class="p-2 text-left">Last Updated</th></tr></thead>
                        <tbody>
                        @forelse($recentStockDiscrepancies as $item)
                            <tr class="border-b">
                                <td class="p-2">{{ $item->name }}</td>
                                <td class="p-2">{{ $item->sku }}</td>
                                <td class="p-2 text-center text-red-600 font-bold">{{ $item->physical_count - $item->system_count }}</td>
                                <td class="p-2">{{ $item->updated_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="p-4 text-center text-gray-500">No discrepancies found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-4">Latest Logbook Entries</h3>
                 <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50"><tr><th class="p-2 text-left">Title</th><th class="p-2 text-left">Created By</th><th class="p-2">Priority</th><th class="p-2 text-left">Date</th></tr></thead>
                        <tbody>
                        @forelse($recentLogbookEntries as $entry)
                             <tr class="border-b">
                                <td class="p-2">{{ $entry->title }}</td>
                                <td class="p-2">{{ $entry->user->first_name }}</td>
                                <td class="p-2 text-center">{{ $entry->priority }}</td>
                                <td class="p-2">{{ $entry->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                             <tr><td colspan="4" class="p-4 text-center text-gray-500">No logbook entries found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="generateReportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-lg mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Generate New Report</h3>
                <button id="closeReportModal" class="text-gray-500 hover:text-gray-700">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <form id="generateReportForm" method="GET" action="{{ route('reports.generate') }}" class="space-y-4">
                {{-- Tidak perlu @csrf karena ini request GET --}}
                
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select id="report_type" name="report_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        <option value="stock_summary">Stock Item Summary</option>
                        <option value="logbook_activity">Logbook Activity</option>
                        <option value="stock_discrepancy">Stock Discrepancy Report</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" id="end_date" name="end_date" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                <p class="text-xs text-gray-500">Leave dates blank to generate a report for all time.</p>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="cancelReportGeneration" class="px-5 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        Download Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>