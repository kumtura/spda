@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>

<div class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="flex items-center justify-center relative z-10 mb-6">
            <div class="h-16 w-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-white text-4xl"></i>
            </div>
        </div>
        
        <div class="relative z-10 text-center">
            <h1 class="text-2xl font-black mb-2">Pembayaran Berhasil!</h1>
            <p class="text-white/80 text-xs">Tiket Anda sudah aktif dan siap digunakan</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 -mt-6 relative z-10 pb-8 space-y-4">
        <!-- Ticket Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- QR Code Section -->
            <div class="p-8 text-center bg-gradient-to-br from-slate-50 to-white border-b border-slate-100">
                <div class="inline-block p-4 bg-white rounded-2xl shadow-sm border border-slate-200">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $tiket->qr_code }}" 
                        class="w-48 h-48" alt="QR Code">
                </div>
                <p class="text-xs font-bold text-slate-800 mt-4">{{ $tiket->kode_tiket }}</p>
                <p class="text-[10px] text-slate-500 mt-1">Tunjukkan QR code ini saat masuk</p>
            </div>

            <!-- Ticket Details -->
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1">Objek Wisata</p>
                    <p class="text-sm font-bold text-slate-800">{{ $tiket->objekWisata->nama_objek }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1">Tanggal Kunjungan</p>
                        <p class="text-xs font-bold text-slate-800">{{ \Carbon\Carbon::parse($tiket->tanggal_kunjungan)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1">Total Pembayaran</p>
                        <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Ticket Categories -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-3">Detail Tiket</p>
                    <div class="space-y-2">
                        @foreach($tiket->details as $detail)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 bg-emerald-500 rounded-full"></div>
                                <span class="text-slate-700">{{ $detail->kategoriTiket->nama_kategori }}</span>
                            </div>
                            <span class="font-bold text-slate-800">{{ $detail->jumlah }}x</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2">Metode Pembayaran</p>
                    <div class="flex items-center gap-2">
                        @php
                            $channel = \App\Models\PaymentChannel::where('code', $tiket->metode_pembayaran)->first();
                        @endphp
                        @if($channel && $channel->icon_url)
                            <img src="{{ asset($channel->icon_url) }}" class="h-5 object-contain" alt="{{ $channel->name }}">
                        @endif
                        <span class="text-xs font-bold text-slate-800">{{ $channel->name ?? str_replace('_', ' ', $tiket->metode_pembayaran) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notes -->
        <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100">
            <div class="flex items-start gap-3">
                <i class="bi bi-info-circle-fill text-blue-500 text-lg shrink-0"></i>
                <div>
                    <p class="text-xs font-bold text-blue-900 mb-2">Informasi Penting</p>
                    <ul class="space-y-1.5 text-[10px] text-blue-800">
                        <li class="flex items-start gap-2">
                            <span class="shrink-0">•</span>
                            <span>Simpan QR code ini dengan baik</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0">•</span>
                            <span>Tunjukkan QR code saat memasuki objek wisata</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0">•</span>
                            <span>Tiket berlaku untuk tanggal yang dipilih</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="shrink-0">•</span>
                            <span>Screenshot halaman ini untuk backup</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <button onclick="showDownloadModal()" class="w-full py-4 bg-white border-2 border-[#00a6eb] text-[#00a6eb] rounded-xl font-bold text-sm shadow-sm hover:bg-[#00a6eb] hover:text-white transition-all">
                <i class="bi bi-download mr-2"></i>Download Tiket
            </button>
            <a href="{{ route('public.home') }}" class="block w-full text-center py-4 bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white rounded-xl font-bold text-sm shadow-lg">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<!-- Download Modal -->
<div id="download-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            <button onclick="closeDownloadModal()" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                <i class="bi bi-x text-xl"></i>
            </button>
            <div class="relative">
                <h3 class="text-xl font-black">Download Tiket</h3>
                <p class="text-white/80 text-xs font-medium mt-1">Pilih format download</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-3">
            <!-- PDF Option -->
            <a href="{{ route('public.wisata.tiket.download', $tiket->kode_tiket) }}" 
               class="block bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg transition-all group">
                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-rose-500 group-hover:border-rose-500">
                        <i class="bi bi-file-pdf text-slate-400 text-xl transition-colors group-hover:text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-slate-800 mb-1">Format PDF</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Dokumen portable untuk dicetak atau disimpan</p>
                        <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Download PDF</span>
                            <i class="bi bi-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </div>
            </a>

            <!-- PNG Option -->
            <button onclick="downloadAsImage()" 
                    class="block w-full bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg transition-all group text-left">
                <div class="flex items-start gap-4">
                    <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-blue-500 group-hover:border-blue-500">
                        <i class="bi bi-image text-slate-400 text-xl transition-colors group-hover:text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-black text-slate-800 mb-1">Format PNG</h4>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Gambar berkualitas tinggi untuk dibagikan</p>
                        <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                            <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Download Gambar</span>
                            <i class="bi bi-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                        </div>
                    </div>
                </div>
            </button>
        </div>

        <!-- Footer -->
        <div class="px-6 pb-6 pt-2">
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-slate-400 text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Simpan tiket Anda dengan baik. Tunjukkan QR code saat memasuki objek wisata.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .bg-white.rounded-3xl, .bg-white.rounded-3xl * {
        visibility: visible;
    }
    .bg-white.rounded-3xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/dom-to-image@2.6.0/dist/dom-to-image.min.js"></script>
<script>
function showDownloadModal() {
    document.getElementById('download-modal').classList.remove('hidden');
    document.getElementById('download-modal').classList.add('flex');
}

function closeDownloadModal() {
    document.getElementById('download-modal').classList.add('hidden');
    document.getElementById('download-modal').classList.remove('flex');
}

function downloadAsImage() {
    // Close modal first
    closeDownloadModal();
    
    const ticketCard = document.querySelector('.bg-white.rounded-3xl.shadow-xl');
    
    if (!ticketCard) {
        alert('Tidak dapat menemukan tiket untuk didownload');
        return;
    }
    
    // Check if dom-to-image is loaded
    if (typeof domtoimage === 'undefined') {
        alert('Library belum dimuat. Silakan refresh halaman dan coba lagi.');
        return;
    }
    
    // Show loading indicator
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'loading-overlay';
    loadingDiv.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;display:flex;align-items:center;justify-content:center';
    loadingDiv.innerHTML = '<div style="background:white;border-radius:16px;padding:24px;text-align:center"><div style="width:32px;height:32px;border:4px solid #3b82f6;border-top-color:transparent;border-radius:50%;margin:0 auto 12px;animation:spin 1s linear infinite"></div><p style="font-size:14px;font-weight:bold;color:#1e293b">Membuat gambar...</p></div>';
    document.body.appendChild(loadingDiv);
    
    // Add spin animation
    const style = document.createElement('style');
    style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
    document.head.appendChild(style);
    
    setTimeout(() => {
        domtoimage.toPng(ticketCard, {
            quality: 1,
            width: ticketCard.offsetWidth * 2,
            height: ticketCard.offsetHeight * 2,
            style: {
                transform: 'scale(2)',
                transformOrigin: 'top left',
                width: ticketCard.offsetWidth + 'px',
                height: ticketCard.offsetHeight + 'px'
            }
        })
        .then(function (dataUrl) {
            const link = document.createElement('a');
            link.download = 'Tiket_{{ $tiket->kode_tiket }}.png';
            link.href = dataUrl;
            link.click();
            
            // Cleanup
            document.body.removeChild(loadingDiv);
            document.head.removeChild(style);
        })
        .catch(function (error) {
            console.error('Error generating image:', error);
            document.body.removeChild(loadingDiv);
            document.head.removeChild(style);
            alert('Gagal membuat gambar. Silakan gunakan download PDF sebagai alternatif.');
        });
    }, 100);
}
</script>
@endsection
