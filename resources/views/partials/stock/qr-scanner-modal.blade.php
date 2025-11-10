<!-- QR Scanner Modal -->
<div id="qrScannerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Scan Stock Item QR Code</h3>
            <button id="closeQRModal" class="text-gray-500 hover:text-gray-700">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        
        <div class="w-full">
            {{-- Area ini akan digunakan untuk menampilkan kamera --}}
            <div id="qr-reader" class="w-full border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-100 min-h-[250px]"></div>
            <div id="qr-reader-results" class="mt-2 text-sm text-green-600 font-semibold h-4"></div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Arahkan kamera ke QR Code. Pemindaian akan dimulai secara otomatis.</p>
        </div>
    </div>
</div>