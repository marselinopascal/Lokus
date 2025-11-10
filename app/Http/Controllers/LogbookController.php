<?php

namespace App\Http\Controllers;

use App\Models\LogbookEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    /**
     * Menampilkan daftar semua entri logbook dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        // Mulai query builder
        $query = LogbookEntry::with('user');

        // Filter berdasarkan kata kunci pencarian (di kolom 'title' atau 'description')
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category') && $request->input('category') != 'all') {
            $query->where('category', $request->input('category'));
        }

        // Filter berdasarkan status
        if ($request->filled('status') && $request->input('status') != 'all') {
            $query->where('status', $request->input('status'));
        }

        // Ambil data, urutkan dari yang terbaru, dan paginasi
        // withQueryString() memastikan filter tetap aktif saat berpindah halaman paginasi
        $logbookEntries = $query->latest()->paginate(10)->withQueryString();
        
        return view('pages.logbook', compact('logbookEntries'));
    }

    /**
     * Menampilkan form untuk membuat entri baru (tidak dipakai karena menggunakan modal).
     */
    public function create()
    {
        return redirect()->route('logbook.index');
    }

    /**
     * Menyimpan entri logbook baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Perawatan,Peminjaman,Pengembalian,Instalasi,Perbaikan',
            'priority' => 'required|in:Normal,High Priority',
        ]);

        LogbookEntry::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'priority' => $request->priority,
            'user_id' => Auth::id(),
            'status' => 'Pending', // Status default saat dibuat
        ]);

        return redirect()->route('logbook.index')->with('success', 'Logbook entry berhasil dibuat!');
    }

    /**
     * Menampilkan detail satu entri.
     */
    public function show(LogbookEntry $logbook)
    {
        $logbook->load('user'); 
        return view('pages.logbook.show', ['logbookEntry' => $logbook]);
    }

    /**
     * Menampilkan form untuk mengedit entri.
     */
    public function edit(LogbookEntry $logbook)
    {
        return view('pages.logbook.edit', ['logbookEntry' => $logbook]);
    }

    /**
     * Memperbarui entri di database.
     */
    public function update(Request $request, LogbookEntry $logbook)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Perawatan,Peminjaman,Pengembalian,Instalasi,Perbaikan',
            'priority' => 'required|in:Normal,High Priority',
            'status' => 'required|in:Pending,On Progress,Completed',
        ]);

        $logbook->update($request->all());

        return redirect()->route('logbook.show', $logbook)->with('success', 'Logbook entry berhasil diperbarui!');
    }

    /**
     * Menghapus entri dari database.
     */
    public function destroy(LogbookEntry $logbook)
    {
        $logbook->delete();
        return redirect()->route('logbook.index')->with('success', 'Logbook entry berhasil dihapus!');
    }

    /**
     * Mengekspor data logbook sebagai file CSV dengan filter yang aktif.
     */
    public function export(Request $request)
    {
        $fileName = 'logbook_export_' . date('Y-m-d_H-i-s') . '.csv';

        // Logika query filter disalin dari metode index() untuk memastikan data yang diekspor sama dengan yang ditampilkan
        $query = LogbookEntry::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('category') && $request->input('category') != 'all') {
            $query->where('category', $request->input('category'));
        }
        if ($request->filled('status') && $request->input('status') != 'all') {
            $query->where('status', $request->input('status'));
        }
        
        $data = $query->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            $columns = ['ID', 'Title', 'Category', 'Description', 'Priority', 'Status', 'Created By', 'Created At'];
            fputcsv($file, $columns);

            foreach ($data as $entry) {
                $userName = $entry->user ? $entry->user->first_name . ' ' . $entry->user->last_name : 'N/A';
                fputcsv($file, [
                    $entry->id,
                    $entry->title,
                    $entry->category,
                    $entry->description,
                    $entry->priority,
                    $entry->status,
                    $userName,
                    $entry->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}