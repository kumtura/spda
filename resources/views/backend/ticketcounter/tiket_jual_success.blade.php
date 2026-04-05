@extends('mobile_layout')

@section('isi_menu')
<style>
@media print {
    /* Hide everything except the receipt */
    body * { visibility: hidden !important; }
    #thermal-receipt, #thermal-receipt * { visibility: visible !important; }
    #thermal-receipt {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 58mm !important;
        max-width: 58mm !important;
        margin: 0 !important;
        padding: 2mm !important;
        background: white !important;
        font-family: 'Courier New', monospace !important;
        font-size: 10px !important;
        color: #000 !important;
        z-index: 99999;
    }
    #thermal-receipt .receipt-header { text-align: center; margin-bottom: 4px; }
    #thermal-receipt .receipt-header h2 { font-size: 13px; font-weight: bold; margin: 0; }
    #thermal-receipt .receipt-header p { font-size: 9px; margin: 1px 0; }
    #thermal-receipt .receipt-divider { border-top: 1px dashed #000; margin: 3px 0; }
    #thermal-receipt .receipt-row { display: flex; justify-content: space-between; font-size: 10px; line-height: 1.4; }
    #thermal-receipt .receipt-row .label { color: #333; }
    #thermal-receipt .receipt-row .value { font-weight: bold; text-align: right; }
    #thermal-receipt .receipt-item { font-size: 10px; line-height: 1.4; }
    #thermal-receipt .receipt-total { font-size: 14px; font-weight: bold; text-align: center; margin: 4px 0; }
    #thermal-receipt .receipt-qr { text-align: center; margin: 4px 0; }
    #thermal-receipt .receipt-qr canvas, #thermal-receipt .receipt-qr img { width: 30mm !important; height: 30mm !important; }
    #thermal-receipt .receipt-footer { text-align: center; font-size: 8px; margin-top: 4px; }
    @page { size: 58mm auto; margin: 0; }
}
</style>

<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-12 pb-8 text-center">
        <div class="h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-check-circle text-5xl text-white"></i>
        </div>
        <h1 class="text-xl font-black text-white mb-2">Tiket Berhasil Dijual</h1>
        <p class="text-xs text-white/80 font-medium">Pembayaran telah dicatat dalam sistem</p>
    </div>

    <div class="px-5 -mt-4">
        <!-- Ticket Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Kode Tiket</span>
                    <span class="text-xs font-black text-slate-800">{{ $tiket->kode_tiket }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Objek Wisata</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->objekWisata->nama_objek }}</span>
                </div>
                
                <div class="pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest block mb-2">Detail Tiket</span>
                    <div class="space-y-1.5">
                        @foreach($tiket->details as $detail)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $detail->kategoriTiket->nama_kategori }}</span>
                                @if($detail->kategoriTiket->market_type && $detail->kategoriTiket->market_type != 'all')
                                <span class="text-[8px] font-bold px-1 py-0.5 rounded {{ $detail->kategoriTiket->market_type == 'local' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ strtoupper($detail->kategoriTiket->market_type) }}</span>
                                @endif
                                <span class="text-[9px] text-slate-400">× {{ $detail->jumlah }}</span>
                            </div>
                            <span class="font-bold text-slate-700">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Tanggal Kunjungan</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->tanggal_kunjungan->translatedFormat('d F Y') }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Metode Pembayaran</span>
                    <span class="text-xs font-bold text-slate-800">{{ strtoupper($tiket->metode_pembayaran) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">TOTAL</span>
                    <span class="text-xl font-black text-[#00a6eb]">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="bg-blue-50 border-t border-blue-100 p-4">
                <div class="flex items-start gap-2">
                    <i class="bi bi-info-circle text-[#00a6eb] text-sm mt-0.5"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed font-semibold">
                        <strong>Berhasil!</strong> Tiket sudah tercatat dan pengunjung dapat langsung masuk. Simpan kode tiket untuk referensi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 space-y-3">
            <button onclick="printReceipt()" class="block w-full py-3 bg-slate-800 text-white text-sm font-black rounded-xl text-center shadow-lg active:scale-95 transition-all">
                <i class="bi bi-printer mr-2"></i>Print Tiket
            </button>
            <a href="{{ url('administrator/ticketcounter/tiket/jual') }}" class="block w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl text-center shadow-lg">
                <i class="bi bi-plus-circle mr-2"></i>Jual Tiket Lagi
            </a>
            <a href="{{ url('administrator/ticketcounter/tiket') }}" class="block w-full py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl text-center">
                Kembali ke Dashboard Tiket
            </a>
        </div>
    </div>
</div>

<!-- Hidden Thermal Receipt Template -->
<div id="thermal-receipt" style="display: none;">
    <div class="receipt-header">
        <h2>{{ $tiket->objekWisata->nama_objek }}</h2>
        <p>TIKET MASUK</p>
        <p>{{ $tiket->objekWisata->alamat ?? '' }}</p>
    </div>
    <div class="receipt-divider"></div>
    <div class="receipt-row"><span class="label">No:</span><span class="value">{{ $tiket->kode_tiket }}</span></div>
    <div class="receipt-row"><span class="label">Tanggal:</span><span class="value">{{ $tiket->tanggal_kunjungan->format('d/m/Y') }}</span></div>
    <div class="receipt-row"><span class="label">Waktu:</span><span class="value">{{ now()->format('H:i') }}</span></div>
    <div class="receipt-row"><span class="label">Bayar:</span><span class="value">{{ strtoupper($tiket->metode_pembayaran) }}</span></div>
    <div class="receipt-divider"></div>
    @foreach($tiket->details as $detail)
    <div class="receipt-item">
        {{ $detail->kategoriTiket->nama_kategori }}@if($detail->kategoriTiket->market_type && $detail->kategoriTiket->market_type != 'all') ({{ strtoupper($detail->kategoriTiket->market_type) }})@endif
    </div>
    <div class="receipt-row">
        <span class="label">{{ $detail->jumlah }} x Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
        <span class="value">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
    </div>
    @endforeach
    <div class="receipt-divider"></div>
    <div class="receipt-total">TOTAL: Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</div>
    <div class="receipt-divider"></div>
    <div class="receipt-qr">
        <canvas id="receipt-qr-canvas"></canvas>
    </div>
    <div class="receipt-footer">
        <p>Tunjukkan tiket ini saat masuk</p>
        <p>Terima kasih atas kunjungan Anda</p>
        <p style="margin-top: 3px;">-- SPDA --</p>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate QR code on the receipt canvas
    const canvas = document.getElementById('receipt-qr-canvas');
    if (canvas && typeof QRCode !== 'undefined') {
        QRCode.toCanvas(canvas, @json($tiket->kode_tiket), {
            width: 120,
            margin: 1,
            color: { dark: '#000000', light: '#ffffff' }
        });
    }
});

function printReceipt() {
    const receipt = document.getElementById('thermal-receipt');
    receipt.style.display = 'block';
    
    setTimeout(function() {
        window.print();
        setTimeout(function() {
            receipt.style.display = 'none';
        }, 500);
    }, 300);
}
</script>
@endpush
@endsection
