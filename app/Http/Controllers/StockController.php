<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Validation\Rule; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; 
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StockController extends Controller
{
    /**
     * Menampilkan daftar semua item stok.
     */
    public function index()
    {
        $stockItems = StockItem::latest()->paginate(10);
        return view('pages.stock', compact('stockItems'));
    }

    /**
     * Menampilkan form untuk membuat item baru (belum dipakai, tapi harus ada).
     */
    public function create()
    {
        // Biasanya mengarah ke view form terpisah, untuk saat ini kita arahkan ke index
        return redirect()->route('stock.index');
    }

    /**
     * Menyimpan item stok baru.
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

        StockItem::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category' => $request->category,
            'location' => $request->location,
            'physical_count' => $request->physical_count,
            'system_count' => $request->system_count,
            'last_checked_at' => now(),
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock item berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu item (belum dipakai, tapi harus ada).
     */
   public function show(StockItem $stock) // Nama parameter '$stock' cocok dengan nama rute
{
    // Kita mengirim variabel '$stock' ke view, TAPI kita memberinya NAMA ALIAS 'stockItem'
    // agar cocok dengan yang diharapkan oleh view.
    return view('pages.stock.show', ['stockItem' => $stock]); 
}
    /**
     * Menampilkan form untuk mengedit item (belum dipakai, tapi harus ada).
     */
   public function edit(StockItem $stock)
    {
        // Sebelumnya, baris ini salah. Sekarang akan merender view yang benar.
        return view('pages.stock.edit', ['stockItem' => $stock]);
    }

    /**
     * Memperbarui item di database (belum dipakai, tapi harus ada).
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
     * Menghapus item dari database (belum dipakai, tapi harus ada).
     */
    public function destroy(StockItem $stock)
{
    // HAPUS BARIS INI
    // Gate::authorize('delete-stock-item');
    
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
                $row['Difference'] = $item->physical_count - $item->system_count;
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->sku,
                    $item->category,
                    $item->location,
                    $item->physical_count,
                    $item->system_count,
                    $row['Difference'],
                    $item->last_checked_at ? $item->last_checked_at->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
      public function generateQrCode(StockItem $stock)
    {
        // Data yang ingin kita masukkan ke dalam QR code
        // Menggunakan format JSON agar mudah dibaca oleh scanner kita
        $dataToEncode = json_encode([
            'sku' => $stock->sku,
            'name' => $stock->name,
            'category' => $stock->category,
        ]);
        
        // Menghasilkan gambar QR code dalam format SVG dan mengembalikannya sebagai response HTTP
        $qrCode = QrCode::size(250)->format('svg')->generate($dataToEncode);
        
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }
}