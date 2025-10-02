<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportsController extends Controller
{
    /**
     * Menampilkan halaman utama Laporan dengan statistik.
     */
    public function index()
    {
        // Statistik untuk kartu
        $totalReports = 156; // Masih statis untuk contoh
        $stockItemCount = StockItem::count();
        $logbookEntryCount = LogbookEntry::count();
        $discrepancyCount = StockItem::whereRaw('physical_count != system_count')->count();

        // Data untuk tabel laporan
        $recentStockDiscrepancies = StockItem::whereRaw('physical_count != system_count')
                                            ->latest('updated_at')
                                            ->take(5)
                                            ->get();

        $recentLogbookEntries = LogbookEntry::with('user')
                                            ->latest()
                                            ->take(5)
                                            ->get();

        return view('pages.reports', compact(
            'totalReports', 
            'stockItemCount', 
            'logbookEntryCount', 
            'discrepancyCount',
            'recentStockDiscrepancies',
            'recentLogbookEntries'
        ));
    }

    /**
     * Menampilkan form untuk membuat laporan (tidak dipakai, tapi harus ada).
     */
    public function create()
    {
        return redirect()->route('reports.index');
    }

    /**
     * Menyimpan laporan baru (tidak dipakai, tapi harus ada).
     */
    public function store(Request $request)
    {
        // Logika untuk menyimpan laporan kustom bisa ditambahkan di sini
    }

    /**
     * Menampilkan detail satu laporan (tidak dipakai, tapi harus ada).
     */
    public function show($id)
    {
        // Logika untuk menampilkan detail satu laporan bisa ditambahkan di sini
        return "Menampilkan laporan dengan ID: " . $id;
    }

    /**
     * Menampilkan form untuk mengedit laporan (tidak dipakai, tapi harus ada).
     */
    public function edit($id)
    {
        //
    }

    /**
     * Memperbarui laporan (tidak dipakai, tapi harus ada).
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Menghapus laporan (tidak dipakai, tapi harus ada).
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Menghasilkan dan mengunduh laporan berdasarkan filter.
     */
     public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:stock_summary,logbook_activity,stock_discrepancy',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $request->input('report_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $fileName = $type . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $callback = function() use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            if ($type === 'stock_summary' || $type === 'stock_discrepancy') {
                $columns = ['ID', 'Name', 'SKU', 'Category', 'Location', 'Physical', 'System', 'Difference', 'Last Updated'];
                fputcsv($file, $columns);

                $query = StockItem::query();
                if ($type === 'stock_discrepancy') {
                    $query->whereRaw('physical_count != system_count');
                }
                
                // Ambil data dalam 'chunks' untuk efisiensi memori jika data sangat besar
                $query->chunk(500, function ($items) use ($file) {
                    foreach ($items as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->name,
                            $item->sku,
                            $item->category,
                            $item->location,
                            $item->physical_count,
                            $item->system_count,
                            $item->physical_count - $item->system_count,
                            $item->updated_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            } elseif ($type === 'logbook_activity') {
                $columns = ['ID', 'Title', 'Priority', 'Status', 'Created By', 'Created At'];
                fputcsv($file, $columns);

                $query = LogbookEntry::with('user'); // Eager load relasi 'user'

                if ($startDate) {
                    $query->whereDate('created_at', '>=', $startDate);
                }
                if ($endDate) {
                    $query->whereDate('created_at', '<=', $endDate);
                }
                
                $query->chunk(500, function ($entries) use ($file) {
                    foreach ($entries as $entry) {
                        // Cek jika relasi user ada untuk menghindari error
                        $userName = $entry->user ? ($entry->user->first_name . ' ' . $entry->user->last_name) : 'N/A';
                        
                        fputcsv($file, [
                            $entry->id,
                            $entry->title,
                            $entry->priority,
                            $entry->status,
                            $userName,
                            $entry->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                });
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}