document.addEventListener('DOMContentLoaded', function () {
    
    // --- Fungsionalitas TAB di Halaman Settings ---
    const settingsTabs = document.querySelectorAll('.settings-tab');
    const settingsContents = document.querySelectorAll('.settings-content');
    if (settingsTabs.length > 0) {
        settingsTabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                settingsTabs.forEach(t => {
                    t.classList.remove('border-gray-600', 'text-gray-800', 'font-medium');
                    t.classList.add('text-gray-600', 'border-transparent');
                });
                this.classList.add('border-gray-600', 'text-gray-800', 'font-medium');
                this.classList.remove('text-gray-600', 'border-transparent');
                settingsContents.forEach(content => content.classList.add('hidden'));
                const targetTab = this.getAttribute('data-tab') + 'Tab';
                document.getElementById(targetTab).classList.remove('hidden');
            });
        });
    }

    // --- Fungsionalitas MODAL "ADD ITEM" di Halaman Stok ---
    const addItemBtn = document.getElementById('addItemBtn');
    const manualEntryModal = document.getElementById('manualEntryModal');
    const closeManualModalBtn = document.getElementById('closeManualModal');
    const cancelManualEntryBtn = document.getElementById('cancelManualEntry');

    function openManualModal() {
        if (manualEntryModal) manualEntryModal.classList.remove('hidden');
    }
    function closeManualModal() {
        if (manualEntryModal) manualEntryModal.classList.add('hidden');
    }
    if (addItemBtn) addItemBtn.addEventListener('click', openManualModal);
    if (closeManualModalBtn) closeManualModalBtn.addEventListener('click', closeManualModal);
    if (cancelManualEntryBtn) cancelManualEntryBtn.addEventListener('click', closeManualModal);
    if (manualEntryModal) manualEntryModal.addEventListener('click', e => { if (e.target === manualEntryModal) closeManualModal(); });

    // --- Fungsionalitas MODAL "ADD LOGBOOK" di Halaman Logbook ---
    const addLogbookBtn = document.getElementById('addLogbookBtn');
    const addLogbookModal = document.getElementById('addLogbookModal');
    const closeLogbookModalBtn = document.getElementById('closeLogbookModal');
    const cancelLogbookEntryBtn = document.getElementById('cancelLogbookEntry');
    
    function openLogbookModal() {
        if (addLogbookModal) addLogbookModal.classList.remove('hidden');
    }
    function closeLogbookModal() {
        if (addLogbookModal) addLogbookModal.classList.add('hidden');
    }
    if (addLogbookBtn) addLogbookBtn.addEventListener('click', openLogbookModal);
    if (closeLogbookModalBtn) closeLogbookModalBtn.addEventListener('click', closeLogbookModal);
    if (cancelLogbookEntryBtn) cancelLogbookEntryBtn.addEventListener('click', closeLogbookModal);
    if (addLogbookModal) addLogbookModal.addEventListener('click', e => { if (e.target === addLogbookModal) closeLogbookModal(); });

    // --- Fungsionalitas MODAL "SCAN QR" di Halaman Stok ---
 


    // --- Fungsionalitas MODAL KONFIRMASI DELETE (Reusable) ---
    const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
    const deleteForm = document.getElementById('deleteForm');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const deleteModalTitle = document.getElementById('deleteModalTitle');
    const deleteModalText = document.getElementById('deleteModalText');

    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.dataset.url;
            const title = this.dataset.title || 'Delete Item';
            const text = this.dataset.text || 'Are you sure? This action cannot be undone.';

            if (deleteForm) deleteForm.action = url;
            if (deleteModalTitle) deleteModalTitle.textContent = title;
            if (deleteModalText) deleteModalText.textContent = text;
            
            if (deleteConfirmationModal) deleteConfirmationModal.classList.remove('hidden');
        });
    });
    
    function closeDeleteModal() {
        if (deleteConfirmationModal) deleteConfirmationModal.classList.add('hidden');
    }

    if (cancelDeleteBtn) cancelDeleteBtn.addEventListener('click', closeDeleteModal);
    if (deleteConfirmationModal) deleteConfirmationModal.addEventListener('click', e => { if (e.target === deleteConfirmationModal) closeDeleteModal(); });

     const scanQRBtn = document.getElementById('scanQRBtn');
    const qrScannerModal = document.getElementById('qrScannerModal');
    const closeQRModalBtn = document.getElementById('closeQRModal');
    
    // Variabel untuk instance scanner
    let html5QrCodeScanner;

    // Fungsi saat QR berhasil dipindai
    function onScanSuccess(decodedText, decodedResult) {
        // Hentikan pemindaian setelah berhasil
        html5QrCodeScanner.stop().then(ignore => {
            console.log("QR Code scanning is stopped.");
            document.getElementById('qr-reader-results').textContent = `Scan Result: ${decodedText}`;

            // Tutup modal QR
            closeQRModal();

            // Coba parsing data JSON dari hasil pindaian
            try {
                const qrData = JSON.parse(decodedText);
                
                // Isi form "Add Item" dengan data dari QR
                const nameInput = document.getElementById('name');
                const skuInput = document.getElementById('sku');
                const categorySelect = document.getElementById('category');

                if (nameInput && qrData.name) nameInput.value = qrData.name;
                if (skuInput && qrData.sku) skuInput.value = qrData.sku;
                if (categorySelect && qrData.category) categorySelect.value = qrData.category;
                
                // Buka modal "Add Item" agar user bisa verifikasi & simpan
                openManualModal();

            } catch (error) {
                // Jika QR tidak berisi JSON, isi saja ke SKU
                const skuInput = document.getElementById('sku');
                if (skuInput) skuInput.value = decodedText;

                // Buka modal "Add Item"
                openManualModal();
                alert('QR code content is not in the expected JSON format. Please fill other details manually.');
            }

        }).catch(err => {
            console.error("Failed to stop the scanner.", err);
        });
    }

    // Fungsi saat QR gagal dipindai (bisa diabaikan)
    function onScanFailure(error) {
        // console.warn(`Code scan error = ${error}`);
    }

    function startQRScanner() {
        // Buat instance scanner baru setiap kali modal dibuka
        html5QrCodeScanner = new Html5Qrcode("qr-reader");
        
        // Konfigurasi kamera
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Mulai pemindaian
        html5QrCodeScanner.start({ facingMode: "environment" }, config, onScanSuccess, onScanFailure)
            .catch(err => {
                console.error("Unable to start scanning.", err);
                alert("Error starting camera. Please ensure you have given camera permissions.");
            });
    }

    function openQRModal() {
        if (qrScannerModal) {
            qrScannerModal.classList.remove('hidden');
            // Hapus hasil pindaian sebelumnya
            const resultsEl = document.getElementById('qr-reader-results');
            if (resultsEl) resultsEl.textContent = '';
            // Mulai scanner
            startQRScanner();
        }
    }
    function closeQRModal() {
        if (qrScannerModal) {
            // Hentikan scanner jika sedang berjalan untuk mematikan kamera
            if (html5QrCodeScanner && html5QrCodeScanner.isScanning) {
                html5QrCodeScanner.stop().catch(err => console.log("Error stopping scanner on close"));
            }
            qrScannerModal.classList.add('hidden');
        }
    }

    if (scanQRBtn) scanQRBtn.addEventListener('click', openQRModal);
    if (closeQRModalBtn) closeQRModalBtn.addEventListener('click', closeQRModal);
    if (qrScannerModal) qrScannerModal.addEventListener('click', e => { if (e.target === qrScannerModal) closeQRModal(); });

      const generateReportBtn = document.getElementById('generateReportBtn');
    const generateReportModal = document.getElementById('generateReportModal');
    const closeReportModalBtn = document.getElementById('closeReportModal');
    const cancelReportGenerationBtn = document.getElementById('cancelReportGeneration');
    
    function openReportModal() {
        if (generateReportModal) generateReportModal.classList.remove('hidden');
    }
    function closeReportModal() {
        if (generateReportModal) generateReportModal.classList.add('hidden');
    }

    if (generateReportBtn) generateReportBtn.addEventListener('click', openReportModal);
    if (closeReportModalBtn) closeReportModalBtn.addEventListener('click', closeReportModal);
    if (cancelReportGenerationBtn) cancelReportGenerationBtn.addEventListener('click', closeReportModal);
    if (generateReportModal) generateReportModal.addEventListener('click', e => { if (e.target === generateReportModal) closeReportModal(); });
});