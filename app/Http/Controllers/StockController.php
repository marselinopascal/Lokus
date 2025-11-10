<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StockController extends Controller
{
    /**
     * Menampilkan daftar item stok dengan filter, pencarian, dan metrik.
     */
    public function index(Request $request)
    {
        // --- Kalkulasi untuk Metrics Cards ---
        $totalItems = StockItem::count();
        $discrepancies = StockItem::whereRaw('physical_count != system_count')->count();
        // Hindari pembagian dengan nol jika tidak ada item
        $accuracyRate = ($totalItems > 0) ? (($totalItems - $discrepancies) / $totalItems) * 100 : 100;

        // --- Query Builder untuk Tabel ---
        $query = StockItem::query();

        // Filter pencarian cepat berdasarkan nama atau SKU
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Filter dropdown Category
        if ($request->filled('category') && $request->input('category') != 'all') {
            $query->where('category', $request->input('category'));
        }

        // Filter dropdown Status (Match/Discrepancy)
        if ($request->filled('status')) {
            if ($request->input('status') == 'match') {
                $query->whereRaw('physical_count = system_count');
            } elseif ($request->input('status') == 'discrepancy') {
                $query->whereRaw('physical_count != system_count');
            }
        }

        // Ambil data, urutkan dari yang terbaru, paginasi, dan pertahankan filter di URL
        $stockItems = $query->latest()->paginate(10)->withQueryString();

        return view('pages.stock', compact(
            'stockItems', 
            'totalItems', 
            'discrepancies', 
            'accuracyRate'
        ));
    }

    /**
     * Menampilkan form untuk membuat item baru (tidak dipakai, menggunakan modal).
     */
    public function create()
    {
        return redirect()->route('stock.index');
    }

    /**
     * Menyimpan item stok baru dari form modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:stock_items',
            'category' => 'required|string',
            'location' => 'required|string',
            'physical_count' => 'required|integer|min:0',
            'system_count' => 'required|integer|min:0',
        ]);

        StockItem::create($request->all());

        return redirect()->route('stock.index')->with('success', 'Stock item berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman detail satu item stok.
     */
    public function show(StockItem $stock)
    {
        return view('pages.stock.show', ['stockItem' => $stock]);
    }

    /**
     * Menampilkan halaman form untuk mengedit item stok.
     */
    public function edit(StockItem $stock)
    {
        return view('pages.stock.edit', ['stockItem' => $stock]);
    }

    /**
     * Memperbarui item stok di database.
     */
    public function update(Request $request, StockItem $stock)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:255', Rule::unique('stock_items')->ignore($stock->id)],
            'category' => 'required|string',
            'location' => 'required|string',
            'physical_count' => 'required|integer|min:0',
            'system_count' => 'required|integer|min:0',
        ]);

        $stock->update($request->all());

        return redirect()->route('stock.show', $stock->id)->with('success', 'Stock item berhasil diperbarui!');
    }

    /**
     * Menghapus item stok dari database.
     */
    public function destroy(StockItem $stock)
    {
        $stock->delete();
        return redirect()->route('stock.index')->with('success', 'Stock item berhasil dihapus!');
    }
    
    /**
     * Mengekspor data stok sebagai file CSV.
     */
    public function export()
    {
        $fileName = 'stock_opname_' . date('Y-m-d') . '.csv';
        $stockItems = StockItem::all();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'SKU', 'Category', 'Location', 'Physical Count', 'System Count', 'Difference', 'Last Checked At'];

        $callback = function() use($stockItems, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($stockItems as $item) {
                fputcsv($file, [
                    $item->id, $item->name, $item->sku, $item->category, $item->location,
                    $item->physical_count, $item->system_count,
                    $item->physical_count - $item->system_count,
                    $item->last_checked_at ? $item->last_checked_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Menghasilkan gambar QR code untuk item stok tertentu.
     */
    public function generateQrCode(StockItem $stock)
    {
        $dataToEncode = json_encode([
            'sku' => $stock->sku,
            'name' => $stock->name,
            'category' => $stock->category,
        ]);
        
        $qrCode = QrCode::size(250)->format('svg')->generate($dataToEncode);
        
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}