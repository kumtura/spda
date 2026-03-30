@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-8 pb-6">
        <div class="flex items-center gap-3">
            <a href="{{ url('administrator/kelian/tiket') }}" class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-arrow-left text-white"></i>
            </a>
            <div>
                <h1 class="text-lg font-black text-white">Scan Tiket</h1>
                <p class="text-[10px] text-white/70">Validasi QR code tiket pengunjung</p>
            </div>
        </div>
    </div>

    <div class="px-5 space-y-4">
        <!-- Scanner Container -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden -mt-3">
            <div id="scanner-container" class="relative bg-slate-900" style="height: 300px;">
                <div id="qr-reader" style="width: 100%; height: 100%;"></div>
            </div>
            
            <div class="p-4 bg-slate-50 border-t border-slate-100">
                <p class="text-[10px] text-slate-500 text-center">
                    <i class="bi bi-info-circle mr-1"></i>
                    Arahkan kamera ke QR code pada tiket
                </p>
            </div>
        </div>

        <!-- Manual Input -->
        <div>
            <button onclick="toggleManualInput()" class="w-full text-xs font-bold text-[#00a6eb] py-2">
                <i class="bi bi-keyboard mr-1"></i> Input Manual Kode Tiket
            </button>
            
            <div id="manual-input" class="hidden mt-3">
                <div class="flex gap-2">
                    <input type="text" id="manual-code" placeholder="Masukkan kode tiket" 
                        class="flex-1 px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    <button onclick="validateManual()" class="px-4 py-2 bg-[#00a6eb] text-white text-xs font-bold rounded-lg">
                        Validasi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-5">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 text-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="bi bi-check-circle text-4xl text-white"></i>
            </div>
            <h3 class="text-lg font-black text-white">Tiket Valid</h3>
        </div>
        <div id="success-content" class="p-6"></div>
        <div class="p-4 border-t border-slate-100">
            <button onclick="closeModal()" class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl">
                Scan Tiket Lain
            </button>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div id="error-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-5">
    <div class="bg-white rounded-2xl max-w-sm w-full shadow-2xl">
        <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 text-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="bi bi-x-circle text-4xl text-white"></i>
            </div>
            <h3 class="text-lg font-black text-white">Tiket Tidak Valid</h3>
        </div>
        <div id="error-content" class="p-6"></div>
        <div class="p-4 border-t border-slate-100">
            <button onclick="closeModal()" class="w-full py-3 bg-slate-600 text-white text-sm font-black rounded-xl">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrCode;
let isScanning = false;

document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});

function startScanner() {
    html5QrCode = new Html5Qrcode("qr-reader");
    
    Html5Qrcode.getCameras().then(cameras => {
        if (cameras && cameras.length) {
            const cameraId = cameras[cameras.length - 1].id; // Use back camera
            
            html5QrCode.start(
                cameraId,
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess,
                onScanError
            ).then(() => {
                isScanning = true;
            }).catch(err => {
                console.error('Scanner start error:', err);
                alert('Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.');
            });
        } else {
            alert('Tidak ada kamera yang terdeteksi');
        }
    }).catch(err => {
        console.error('Camera detection error:', err);
        alert('Gagal mendeteksi kamera');
    });
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) {
        isScanning = false;
        html5QrCode.pause(true);
        validateTicket(decodedText);
    }
}

function onScanError(errorMessage) {
    // Ignore scan errors
}

function validateTicket(kodeTicket) {
    fetch('{{ url("administrator/kelian/tiket/scan/validate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ kode_tiket: kodeTicket })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess(data.data);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        showError('Terjadi kesalahan saat validasi tiket');
    });
}

function validateManual() {
    const kodeTicket = document.getElementById('manual-code').value.trim();
    if (!kodeTicket) {
        alert('Masukkan kode tiket');
        return;
    }
    validateTicket(kodeTicket);
}

function showSuccess(data) {
    const content = `
        <div class="space-y-3">
            <div class="bg-slate-50 rounded-lg p-3">
                <p class="text-[9px] text-slate-400 mb-1">Kode Tiket</p>
                <p class="text-xs font-bold text-slate-800">${data.kode_tiket}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-3">
                <p class="text-[9px] text-slate-400 mb-1">Nama Pengunjung</p>
                <p class="text-xs font-bold text-slate-800">${data.nama_pengunjung}</p>
            </div>
            <div class="bg-slate-50 rounded-lg p-3">
                <p class="text-[9px] text-slate-400 mb-1">Objek Wisata</p>
                <p class="text-xs font-bold text-slate-800">${data.objek_wisata}</p>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-slate-50 rounded-lg p-3">
                    <p class="text-[9px] text-slate-400 mb-1">Jumlah Tiket</p>
                    <p class="text-xs font-bold text-slate-800">${data.jumlah_tiket} Tiket</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-3">
                    <p class="text-[9px] text-slate-400 mb-1">Tanggal Kunjungan</p>
                    <p class="text-xs font-bold text-slate-800">${data.tanggal_kunjungan}</p>
                </div>
            </div>
        </div>
    `;
    document.getElementById('success-content').innerHTML = content;
    document.getElementById('success-modal').classList.remove('hidden');
    document.getElementById('success-modal').classList.add('flex');
}

function showError(message) {
    const content = `
        <div class="text-center">
            <p class="text-sm text-slate-600">${message}</p>
        </div>
    `;
    document.getElementById('error-content').innerHTML = content;
    document.getElementById('error-modal').classList.remove('hidden');
    document.getElementById('error-modal').classList.add('flex');
}

function closeModal() {
    document.getElementById('success-modal').classList.add('hidden');
    document.getElementById('success-modal').classList.remove('flex');
    document.getElementById('error-modal').classList.add('hidden');
    document.getElementById('error-modal').classList.remove('flex');
    document.getElementById('manual-code').value = '';
    
    if (html5QrCode && isScanning === false) {
        html5QrCode.resume();
        isScanning = true;
    }
}

function toggleManualInput() {
    const manualInput = document.getElementById('manual-input');
    manualInput.classList.toggle('hidden');
}
</script>
@endpush
@endsection
