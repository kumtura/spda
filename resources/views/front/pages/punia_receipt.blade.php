@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>

@php
    $isCompleted = $payment->status_pembayaran === 'completed';
    $statusLabel = $isCompleted ? 'Lunas dan Terverifikasi' : 'Menunggu Verifikasi';
    $statusClass = $isCompleted ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : 'bg-amber-50 text-amber-700 border-amber-100';
    $receiptUrl = route('public.punia.receipt', ['code' => $receiptCode]);
    $downloadUrl = route('public.punia.receipt.download', ['code' => $receiptCode]);
@endphp

<div class="bg-white min-h-screen pb-8" x-data="{ shareOpen: false, waNumber: '' }">
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>

        <div class="relative z-10 flex items-center justify-between mb-6">
            <a href="{{ route('public.home') }}" class="h-8 w-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition-all">
                <i class="bi bi-house-door"></i>
            </a>
            <div class="text-right">
                <div class="text-[9px] text-white/60 uppercase tracking-wider">Kode Receipt</div>
                <div class="text-xs font-bold text-white">{{ $receiptCode }}</div>
            </div>
        </div>

        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-1">Receipt Pembayaran</h1>
            <p class="text-white/80 text-xs">Bukti pembayaran iuran unit usaha</p>
        </div>
    </div>

    <div class="px-4 -mt-6 relative z-10 space-y-4">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Atas Nama</p>
                        <h2 class="text-lg font-black text-slate-800">{{ $usaha->nama_usaha }}</h2>
                    </div>
                    <span class="text-[10px] font-bold px-3 py-1.5 rounded-full border {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">Banjar</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $usaha->nama_banjar ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">Periode</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $bulanName }} {{ $payment->tahun_punia }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">Tanggal Bayar</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">Metode</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ ucfirst($payment->metode_pembayaran ?? $payment->metode ?? '-') }}</span>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs text-slate-500">Nominal</span>
                        <span class="text-sm font-black text-emerald-600 text-right">Rp {{ number_format($payment->jumlah_dana, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-3">
                <a href="{{ $downloadUrl }}" class="block w-full py-3.5 bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white rounded-xl font-bold text-sm text-center shadow-lg">
                    <i class="bi bi-download mr-1.5"></i> Download Receipt
                </a>

                <button type="button" @click="shareOpen = !shareOpen" class="block w-full py-3.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm text-center">
                    <i class="bi bi-whatsapp mr-1.5"></i> Kirim ke WhatsApp
                </button>

                <div x-show="shareOpen" x-cloak class="bg-slate-50 rounded-2xl border border-slate-200 p-4 space-y-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nomor WhatsApp</label>
                        <input id="receiptWaNumber" type="tel" x-model="waNumber" placeholder="Contoh: 628123456789 atau 08123456789" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                        <p class="text-[10px] text-slate-400 mt-1">Gunakan nomor dengan kode negara. Jika diawali 0, sistem akan ubah ke 62.</p>
                    </div>
                    <button type="button" onclick="sendPuniaReceiptToWhatsApp()" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-bold text-sm">
                        Buka WhatsApp
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
            <p class="text-xs font-bold text-blue-700 mb-1">Link Receipt</p>
            <p class="text-[11px] text-blue-700 break-all">{{ $receiptUrl }}</p>
        </div>
    </div>
</div>

<script>
    function normalizeWhatsappNumber(number) {
        let cleaned = String(number || '').replace(/\D/g, '');
        if (!cleaned) {
            return '';
        }
        if (cleaned.startsWith('0')) {
            cleaned = '62' + cleaned.slice(1);
        } else if (!cleaned.startsWith('62')) {
            cleaned = '62' + cleaned;
        }
        return cleaned;
    }

    function sendPuniaReceiptToWhatsApp() {
        const input = document.getElementById('receiptWaNumber');
        const rawNumber = input ? input.value : '';
        const waNumber = normalizeWhatsappNumber(rawNumber);

        if (!waNumber) {
            alert('Masukkan nomor WhatsApp tujuan terlebih dahulu.');
            return;
        }

        const message = encodeURIComponent('Om Swastyastu,%0A%0ABerikut link receipt pembayaran iuran unit usaha:%0A{{ $receiptUrl }}%0A%0AKode receipt: {{ $receiptCode }}');
        window.open('https://wa.me/' + waNumber + '?text=' + message, '_blank');
    }
</script>
@endsection