@extends('mobile_layout_public')

@section('content')
<style>
    /* Hide bottom navbar on this page */
    nav.fixed.bottom-0 {
        display: none !important;
    }
    .mobile-container {
        padding-bottom: 0 !important;
    }
</style>
<div class="bg-slate-50 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        @php
            $back_url = "javascript:history.back()";
            if(isset($order_id)) {
                $id_numeric = explode('-', $order_id)[1] ?? null;
                if($type === 'donasi' && $id_numeric) {
                    $sumbangan = \App\Models\Sumbangan::find($id_numeric);
                    if($sumbangan && $sumbangan->id_program_donasi) {
                        $back_url = route('public.donasi.pembayaran', $sumbangan->id_program_donasi);
                    }
                } elseif($type === 'punia') {
                    $back_url = route('public.punia.pembayaran');
                }
            }
        @endphp

        <a href="{{ $back_url }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2">Metode Pembayaran</h1>
            <p class="text-white/80 text-xs font-medium">Pilih salah satu metode untuk melanjutkan</p>
        </div>
    </div>

    <div class="px-4 -mt-6 relative z-10 space-y-4">
        @if(!$is_configured)
        <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl flex items-start gap-3">
            <i class="bi bi-exclamation-triangle-fill text-rose-500 text-xl shrink-0"></i>
            <div>
                <p class="text-xs font-bold text-rose-700">Payment Gateway Belum Dikonfigurasi</p>
                <p class="text-[10px] text-rose-600/80 mt-1 leading-relaxed">Admin belum memasukkan API Key Xendit di menu Pengaturan. Pembayaran otomatis sementara tidak tersedia.</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-100 p-4 rounded-2xl flex items-center gap-3">
            <i class="bi bi-x-circle-fill text-rose-500 text-xl"></i>
            <p class="text-xs font-bold text-rose-700">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Summary Card -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Total Pembayaran</p>
                    <p class="text-2xl font-black text-[#00a6eb]">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Order ID</p>
                    <p class="text-xs font-black text-slate-800">#{{ $order_id }}</p>
                </div>
            </div>

        <form id="paymentForm" action="{{ route('public.payment_initiate') }}" method="POST">
            @csrf
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="order_id" value="{{ $order_id }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="method" id="selectedMethod" value="">

            <!-- Payment Groups -->
            <div class="space-y-4">
                @php
                    $instantPayments = $channels->whereIn('type', ['EWALLET', 'QRIS']);
                    $vaPayments = $channels->where('type', 'VA');
                @endphp

                <!-- Transfer Manual -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Transfer Manual</h3>
                        <span class="text-[8px] font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded-full">Verifikasi Manual</span>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                        <a href="{{ route('public.payment.manual', ['order_id' => $order_id, 'amount' => $amount, 'type' => $type]) }}" class="w-full p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors text-left group block">
                            <div class="h-10 w-12 flex items-center justify-center">
                                <i class="bi bi-bank text-2xl text-[#00a6eb]"></i>
                            </div>
                            <div class="flex-1">
                                <span class="text-xs font-bold text-slate-700 block">Transfer Bank Manual</span>
                                <span class="text-[9px] text-slate-400">Upload bukti transfer untuk verifikasi</span>
                            </div>
                            <i class="bi bi-chevron-right ms-auto text-slate-300 text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Instant Payment -->
                @if($instantPayments->count() > 0)
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pembayaran Instan</h3>
                        <span class="text-[8px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">Verifikasi Otomatis</span>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                        @foreach($instantPayments as $channel)
                        <button type="button" onclick="submitPayment('{{ $channel->code }}')" class="w-full p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors text-left group">
                            <div class="h-10 w-12 flex items-center justify-center group-hover:grayscale-0 transition-all">
                                <img src="{{ $channel->icon_url }}" class="h-8 object-contain" alt="{{ $channel->name }}">
                            </div>
                            <span class="text-xs font-bold text-slate-700">{{ $channel->name }}</span>
                            <i class="bi bi-chevron-right ms-auto text-slate-300 text-xs"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Virtual Account -->
                @if($vaPayments->count() > 0)
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Virtual Account</h3>
                        <span class="text-[8px] font-bold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full">Verifikasi Otomatis</span>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden divide-y divide-slate-50">
                        @foreach($vaPayments as $channel)
                        <button type="button" onclick="submitPayment('{{ $channel->code }}')" class="w-full p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors text-left group">
                            <div class="h-10 w-12 flex items-center justify-center group-hover:grayscale-0 transition-all">
                                <img src="{{ $channel->icon_url }}" class="h-8 object-contain" alt="{{ $channel->name }}">
                            </div>
                            <span class="text-xs font-bold text-slate-700">{{ $channel->name }}</span>
                            <i class="bi bi-chevron-right ms-auto text-slate-300 text-xs"></i>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </form>
    </div>

    <!-- Footer Security -->
    <div class="mt-8 text-center px-8 pb-12">
        <div class="flex items-center justify-center gap-2 mb-2 text-slate-300">
            <i class="bi bi-shield-lock-fill"></i>
            <span class="text-[9px] font-bold uppercase tracking-widest">Secure Checkout via Xendit</span>
        </div>
        <p class="text-[9px] font-medium text-slate-400">Pembayaran dijamin aman dengan enkripsi standar industri.</p>
    </div>
</div>

<script>
    function submitPayment(method) {
        document.getElementById('selectedMethod').value = method;
        document.getElementById('paymentForm').submit();
    }
</script>
@endsection
