@extends('mobile_layout')

@section('isi_menu')
<style>
#qr-reader {
    border: none !important;
    position: relative !important;
    background: #0f172a !important;
}
#qr-reader__dashboard_section, 
#qr-reader__status_span,
#qr-reader img[alt="Info icon"],
#qr-reader img[alt="Camera menu icon"] {
    display: none !important;
}
#qr-reader__scan_region {
    border: none !important;
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
#qr-reader video {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}
#qr-reader__scan_region::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    max-width: 250px; 
    aspect-ratio: 1 / 1;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    box-shadow: 0 0 0 4000px rgba(15, 23, 42, 0.7);
    z-index: 10;
    pointer-events: none;
}
#qr-reader__scan_region::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70%;
    max-width: 250px;
    aspect-ratio: 1 / 1;
    background: 
        linear-gradient(to right, #00a6eb 3px, transparent 3px) 0 0,
        linear-gradient(to right, #00a6eb 3px, transparent 3px) 0 100%,
        linear-gradient(to left, #00a6eb 3px, transparent 3px) 100% 0,
        linear-gradient(to left, #00a6eb 3px, transparent 3px) 100% 100%,
        linear-gradient(to bottom, #00a6eb 3px, transparent 3px) 0 0,
        linear-gradient(to bottom, #00a6eb 3px, transparent 3px) 100% 0,
        linear-gradient(to top, #00a6eb 3px, transparent 3px) 0 100%,
        linear-gradient(to top, #00a6eb 3px, transparent 3px) 100% 100%;
    background-repeat: no-repeat;
    background-size: 24px 24px;
    border-radius: 20px;
    z-index: 11;
    pointer-events: none;
}
</style>

<div class="bg-slate-50 min-h-screen pb-32">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-6 pt-10 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('administrator/ticketcounter/tiket') }}" class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center hover:bg-white/30 transition-all active:scale-95">
                    <i class="bi bi-arrow-left text-white text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold tracking-tight uppercase">Validasi Tiket</h1>
                    <p class="text-[9px] text-white/70 font-medium tracking-widest uppercase mt-0.5">SPDA Check-in System</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-5 -mt-6 relative z-20 space-y-5">
        <!-- Scanner Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div id="scanner-container" class="relative bg-slate-900 overflow-hidden" style="height: 350px;">
                <div id="qr-reader" style="width: 100%; height: 100%;"></div>
            </div>
            
            <div id="scan-result-container">
                <div id="initial-instruction" class="p-5 text-center">
                    <p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest">
                        Arahkan kamera ke tiket pengunjung
                    </p>
                </div>

                <div id="match-result" class="hidden p-5 space-y-4">
                    <div id="match-content"></div>
                    <button onclick="resetScanner()" class="w-full py-3.5 bg-[#00a6eb] text-white text-[11px] font-bold rounded-xl uppercase tracking-widest active:scale-95 transition-all shadow-md">
                        Scan Tiket Lain
                    </button>
                </div>

                <div id="error-result" class="hidden p-5">
                    <div id="error-content"></div>
                    <button onclick="resetScanner()" class="w-full mt-4 py-3 bg-[#00a6eb] text-white text-[11px] font-bold rounded-xl uppercase tracking-widest active:scale-95 transition-all shadow-md">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>

        <!-- Manual Input -->
        <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i class="bi bi-keyboard text-[12px]"></i>
                Input Manual / Barcode Scanner
            </h3>
            <div class="flex gap-2">
                <input type="text" id="manual-code" placeholder="TKT-XXXX-... (atau scan barcode)" 
                    class="flex-1 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[12px] font-medium text-slate-600 focus:ring-2 focus:ring-blue-50 focus:border-[#00a6eb] transition-all outline-none"
                    autofocus>
                <button onclick="validateManual()" class="px-6 py-3 bg-[#00a6eb] text-white text-[10px] font-bold rounded-xl uppercase tracking-widest active:scale-95 transition-all">
                    Cek
                </button>
            </div>
            <div id="scanner-mode-indicator" class="mt-3 hidden">
                <div class="flex items-center gap-2 bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                    <p class="text-[10px] text-emerald-700 font-bold">Barcode Scanner terdeteksi - siap scan</p>
                </div>
            </div>
        </div>

        <div id="camera-error" class="hidden p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-center gap-3">
            <i class="bi bi-camera-video-off text-rose-500"></i>
            <p id="camera-error-text" class="text-[10px] text-rose-600 font-medium"></p>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(startScanner, 500);
});

function startScanner() {
    html5QrCode = new Html5Qrcode("qr-reader");
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            let cameraId = cameras[cameras.length - 1].id;
            if (cameras.length > 1 && !navigator.userAgent.match(/Mobile|Android|iPhone/i)) {
                cameraId = cameras[0].id;
            }
            html5QrCode.start(
                cameraId,
                {
                    fps: 15,
                    qrbox: (w, h) => { return { width: Math.floor(w * 0.7), height: Math.floor(w * 0.7) } },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanError
            ).then(() => { isScanning = true; }).catch(() => showCameraError('Gagal mengakses kamera.'));
        }
    }).catch(() => showCameraError('Kamera tidak ditemukan.'));
}

function onScanSuccess(decodedText) {
    if (isScanning) {
        isScanning = false;
        html5QrCode.pause(true);
        if (navigator.vibrate) navigator.vibrate(50);
        validateTicket(decodedText);
    }
}

function onScanError() {}

function validateTicket(kodeTicket) {
    document.getElementById('initial-instruction').innerHTML = '<p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Validasi...</p>';

    fetch('{{ url("administrator/ticketcounter/tiket/scan/validate") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ kode_tiket: kodeTicket })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMatch(data.data);
        } else {
            let errorTitle = 'Hasil Validasi';
            let message = data.message;
            if(data.waktu_scan){
                const timeStr = new Date(data.waktu_scan).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
                message = `Tiket <b>SUDAH DIGUNAKAN</b> pada pukul ${timeStr}.`;
            }
            displayError(errorTitle, message);
        }
    })
    .catch(() => displayError('Sistem Error', 'Gagal menghubungi server.'));
}

function showMatch(data) {
    hideAllResults();
    let kategoriList = '';
    data.kategori.forEach(k => {
        kategoriList += `<div class="flex justify-between py-2 text-[10px] border-b border-slate-50 last:border-0"><span class="text-slate-400 font-medium">${k.nama}</span><span class="text-slate-800 font-bold">${k.jumlah}x</span></div>`;
    });

    const content = `
        <div class="space-y-5">
            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
                <div class="flex items-start gap-4">
                    <i class="bi bi-info-circle text-[#00a6eb] text-xl shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed pt-0.5">
                        <strong class="text-slate-800">Berhasil!</strong> Tiket valid dan pengunjung dapat langsung masuk.
                    </p>
                </div>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-5 space-y-4 shadow-sm">
                <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                    <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest">Kode Tiket</span>
                    <span class="text-[10px] font-black text-slate-800">${data.kode_tiket}</span>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-bold text-slate-800 mb-2 truncate">${data.objek_wisata}</p>
                    <div class="space-y-0.5">${kategoriList}</div>
                </div>
                <div class="pt-3 border-t border-slate-50 flex justify-between items-center">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Bayar</span>
                    <span class="text-[13px] font-black text-[#00a6eb]">Rp ${data.total_harga.toLocaleString('id-ID')}</span>
                </div>
            </div>
        </div>
    `;
    document.getElementById('match-content').innerHTML = content;
    document.getElementById('match-result').classList.remove('hidden');
}

function displayError(title, message) {
    hideAllResults();
    document.getElementById('error-content').innerHTML = `
        <div class="bg-slate-50 border border-slate-200/50 rounded-2xl p-5">
            <div class="flex items-start gap-4">
                <i class="bi bi-exclamation-circle text-slate-400 text-xl shrink-0"></i>
                <p class="text-[10px] text-slate-600 leading-relaxed pt-0.5">
                    <strong class="text-slate-800">${title}:</strong> ${message}
                </p>
            </div>
        </div>
    `;
    document.getElementById('error-result').classList.remove('hidden');
}

function resetScanner() {
    hideAllResults();
    document.getElementById('initial-instruction').innerHTML = '<p class="text-[11px] font-medium text-slate-400 uppercase tracking-widest">Arahkan kamera ke tiket pengunjung</p>';
    document.getElementById('initial-instruction').classList.remove('hidden');
    if (html5QrCode) { html5QrCode.resume(); isScanning = true; }
}

function hideAllResults() {
    document.getElementById('initial-instruction').classList.add('hidden');
    document.getElementById('match-result').classList.add('hidden');
    document.getElementById('error-result').classList.add('hidden');
}

function validateManual() {
    const code = document.getElementById('manual-code').value.trim();
    if(code) validateTicket(code);
}

// ============================================
// Hardware Barcode Scanner Support
// ============================================
// Barcode scanners emulate keyboard input: they type characters very fast 
// and end with Enter. We detect this pattern by measuring input speed.
let barcodeBuffer = '';
let barcodeTimeout = null;
let lastKeyTime = 0;
const BARCODE_SPEED_THRESHOLD = 50; // Max ms between keystrokes for barcode
const BARCODE_MIN_LENGTH = 5; // Minimum code length to auto-validate

document.addEventListener('keydown', function(e) {
    const activeEl = document.activeElement;
    const manualInput = document.getElementById('manual-code');
    
    // If user is typing in the manual input field, handle Enter
    if (activeEl === manualInput) {
        if (e.key === 'Enter') {
            e.preventDefault();
            validateManual();
        }
        return;
    }
    
    const now = Date.now();
    
    // Enter key = end of barcode scan
    if (e.key === 'Enter') {
        e.preventDefault();
        if (barcodeBuffer.length >= BARCODE_MIN_LENGTH) {
            // Show indicator
            document.getElementById('scanner-mode-indicator').classList.remove('hidden');
            // Set the value in manual input for visibility
            manualInput.value = barcodeBuffer;
            // Validate
            if (navigator.vibrate) navigator.vibrate(50);
            validateTicket(barcodeBuffer);
        }
        barcodeBuffer = '';
        clearTimeout(barcodeTimeout);
        return;
    }
    
    // Only accept printable characters
    if (e.key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
        const timeDiff = now - lastKeyTime;
        
        // If typing is fast enough, it's a barcode scanner
        if (timeDiff > 500) {
            // Too slow = new sequence
            barcodeBuffer = '';
        }
        
        barcodeBuffer += e.key;
        lastKeyTime = now;
        
        // Auto-clear buffer after inactivity
        clearTimeout(barcodeTimeout);
        barcodeTimeout = setTimeout(function() {
            barcodeBuffer = '';
        }, 200);
    }
});

function showCameraError(msg) {
    document.getElementById('camera-error-text').textContent = msg;
    document.getElementById('camera-error').classList.remove('hidden');
}
</script>
@endpush
@endsection
