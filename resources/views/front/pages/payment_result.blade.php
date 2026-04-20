@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>

<div class="bg-white min-h-screen">
    @php
        $paidState = $showSuccessState ?? ($isCompleted ?? in_array($record->status_pembayaran, ['completed', 'lunas'], true));
        $isPendingVerification = $paidState && !($isCompleted ?? false) && (($record->status_verifikasi ?? null) === 'pending');
        $successTitle = $isPendingVerification ? 'Pembayaran Berhasil Dicatat' : ($paymentContext['title'] ?? 'Pembayaran Berhasil');
        $successMessage = $isPendingVerification
            ? (($canDirectVerify ?? false)
                ? 'Pembayaran sudah masuk. Penagih yang sedang login bisa langsung memverifikasi di bawah.'
                : 'Pembayaran sudah masuk dan sedang menunggu verifikasi admin.')
            : ($paymentContext['thank_you'] ?? 'Terima kasih atas kontribusi Anda');
    @endphp
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="flex items-center justify-between relative z-10 mb-6">
            <a href="{{ route('public.home') }}" class="h-8 w-8 bg-white/20 backdrop-blur-md rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition-all">
                <i class="bi bi-house-door"></i>
            </a>
            <div class="text-right">
                <div class="text-[9px] text-white/60 uppercase tracking-wider">Order ID</div>
                <div class="text-xs font-bold text-white">#{{ $order_id }}</div>
            </div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-1">Status Pembayaran</h1>
            <p class="text-white/80 text-xs">Selesaikan pembayaran Anda</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 -mt-6 relative z-10 pb-8">
        @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-100 rounded-2xl px-4 py-3 text-xs font-medium text-emerald-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 bg-rose-50 border border-rose-100 rounded-2xl px-4 py-3 text-xs font-medium text-rose-700">
            {{ session('error') }}
        </div>
        @endif

        <!-- Success View -->
        <div id="successView" class="hidden">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 text-center space-y-6">
                <div class="h-20 w-20 {{ $isPendingVerification ? 'bg-amber-50 text-amber-500' : 'bg-emerald-50 text-emerald-500' }} rounded-full flex items-center justify-center mx-auto">
                    <i class="bi {{ $isPendingVerification ? 'bi-hourglass-split text-4xl text-amber-500' : 'bi-check-circle-fill text-4xl' }}"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 mb-2">{{ $successTitle }}</h2>
                    <p class="text-xs text-slate-500">{{ $successMessage }}</p>
                </div>
                
                <div class="bg-slate-50 rounded-2xl p-4 space-y-3">
                    @if(!empty($paymentContext['subject_name']))
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">{{ $paymentContext['subject_label'] ?? 'Atas Nama' }}</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $paymentContext['subject_name'] }}</span>
                    </div>
                    @endif
                    @if(!empty($paymentContext['period_label']))
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Periode</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $paymentContext['period_label'] }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Metode</span>
                        <span class="text-xs font-bold text-slate-800">{{ str_replace('_', ' ', $method) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Waktu</span>
                        <span class="text-xs font-bold text-slate-800">{{ now()->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    @if(!empty($verifiedByName))
                    <div class="flex justify-between items-center">
                        <span class="text-xs text-slate-500">Diverifikasi Oleh</span>
                        <span class="text-xs font-bold text-slate-800 text-right">{{ $verifiedByName }}</span>
                    </div>
                    @endif
                </div>

                @if($canDirectVerify)
                <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 text-left space-y-3">
                    <div>
                        <p class="text-[10px] text-amber-700 uppercase tracking-widest font-black mb-1">Verifikasi Langsung</p>
                        <p class="text-xs text-amber-700">Transfer ini masih pending. Penagih yang login bisa langsung memverifikasi dan sistem akan mencatat akun verifier secara otomatis.</p>
                    </div>

                    <form method="POST" action="{{ route('penagih.transfer.verify') }}">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order_id }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit" class="w-full py-3 bg-amber-500 text-white rounded-xl font-bold text-xs">
                            Verifikasi Sekarang
                        </button>
                    </form>
                </div>
                @endif

                @if(in_array($type, ['punia', 'punia_pendatang'], true) && $receiptUrl)
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 space-y-3 text-left">
                    <div>
                        <p class="text-[10px] text-blue-600 uppercase tracking-widest font-black mb-1">Receipt Pembayaran</p>
                        <p class="text-xs text-blue-700">Kode receipt: <span class="font-bold">{{ $receiptCode }}</span></p>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ $receiptUrl }}" class="block text-center py-3 bg-white border border-blue-200 text-[#00a6eb] rounded-xl font-bold text-xs">
                            Lihat Receipt
                        </a>
                        <a href="{{ $receiptDownloadUrl }}" class="block text-center py-3 bg-white border border-blue-200 text-[#00a6eb] rounded-xl font-bold text-xs">
                            Download
                        </a>
                    </div>

                    <button type="button" onclick="toggleReceiptWhatsAppForm()" class="w-full py-3 bg-emerald-500 text-white rounded-xl font-bold text-xs">
                        Kirim ke WhatsApp
                    </button>

                    <div id="receiptWhatsAppForm" class="hidden space-y-2">
                        <input id="receiptWhatsAppNumber" type="tel" placeholder="Masukkan nomor WhatsApp tujuan" class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                        <p class="text-[10px] text-slate-500">Jika diawali 0, sistem akan otomatis mengubah ke kode negara 62.</p>
                        <button type="button" onclick="sendReceiptToWhatsApp()" class="w-full py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-xs">
                            Buka WhatsApp
                        </button>
                    </div>
                </div>
                @endif

                <a href="{{ route('public.home') }}" class="block w-full py-4 bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white rounded-xl font-bold text-sm shadow-lg">
                    Kembali ke Beranda
                </a>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div id="instructionsView" class="{{ $paidState ? 'hidden' : '' }} space-y-4">
            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-8 text-center border-b border-slate-100 bg-white">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-50 text-slate-500 border border-slate-200 rounded-full text-[10px] font-bold mb-4">
                        Menunggu Pembayaran
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1">Total Pembayaran</p>
                        <h2 class="text-3xl font-black text-slate-800">Rp {{ number_format($record->nominal ?? $record->jumlah_dana, 0, ',', '.') }}</h2>
                        <p class="text-[11px] text-slate-500 mt-2">Bayar sebelum <span class="font-bold text-slate-700">{{ isset($payment_data['expiration_date']) ? \Carbon\Carbon::parse($payment_data['expiration_date'])->translatedFormat('d M Y, H:i') : '24 jam ke depan' }}</span></p>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="p-6 space-y-4">
                    <div class="bg-slate-50 rounded-2xl p-5 space-y-4">
                        @if(!empty($paymentContext['subject_name']) || !empty($paymentContext['period_label']))
                        <div class="bg-white rounded-xl p-4 border border-slate-200 space-y-2">
                            @if(!empty($paymentContext['subject_name']))
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">{{ $paymentContext['subject_label'] ?? 'Atas Nama' }}</span>
                                <span class="text-xs font-bold text-slate-800 text-right">{{ $paymentContext['subject_name'] }}</span>
                            </div>
                            @endif
                            @if(!empty($paymentContext['period_label']))
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-[9px] text-slate-400 uppercase tracking-wider">Periode</span>
                                <span class="text-xs font-bold text-slate-800 text-right">{{ $paymentContext['period_label'] }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                                <p class="text-sm font-bold text-slate-800">{{ $channel->name ?? str_replace('_', ' ', $method) }}</p>
                            </div>
                            @if($channel && $channel->icon_url)
                                <img src="{{ asset($channel->icon_url) }}" class="h-6 object-contain" alt="{{ $channel->name }}">
                            @endif
                        </div>

                        <!-- 1. Virtual Account Section -->
                        @if(str_contains($method, '_VA'))
                        <div x-data="{ copied: false }" class="bg-white rounded-xl p-4 border border-slate-200 flex items-center justify-between cursor-pointer hover:border-[#00a6eb] transition-all" 
                             @click="copyToClipboard('{{ $payment_data['account_number'] ?? ($payment_data['external_id'] ?? '') }}'); copied = true; setTimeout(() => copied = false, 2000)">
                            <div>
                                <p class="text-[9px] text-slate-400 uppercase tracking-wider mb-1">Nomor Virtual Account</p>
                                <span class="text-lg font-black text-slate-800">{{ $payment_data['account_number'] ?? ($payment_data['external_id'] ?? '-') }}</span>
                            </div>
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center transition-colors"
                                 :class="copied ? 'bg-emerald-50 text-emerald-500' : 'bg-blue-50 text-[#00a6eb]'">
                                <i class="bi" :class="copied ? 'bi-check-lg text-lg' : 'bi-files'"></i>
                            </div>
                        </div>
                        @endif

                        <!-- 2. QR Code Section (Always check and show if available) -->
                        @php 
                            $qr_string = $payment_data['qr_string'] ?? ($payment_data['actions']['qr_checkout_string'] ?? null);
                            $is_ewallet = in_array($method, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY']);
                        @endphp
                        
                        @if($qr_string && $qr_string !== 'test-qr-string')
                        <div class="bg-white rounded-2xl p-4 border border-slate-200 text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($qr_string) }}" class="w-48 h-48 mx-auto mb-3" alt="QR">
                            <p class="text-[9px] text-slate-400 uppercase tracking-wider">Scan dengan aplikasi pembayaran</p>
                        </div>
                        @elseif($qr_string === 'test-qr-string' || $method === 'ID_DANA')
                        <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100 text-center">
                            <i class="bi bi-info-circle text-[#00a6eb] mb-1 block"></i>
                            <p class="text-[10px] text-slate-500 leading-relaxed font-medium">Klik tombol <b>Buka Aplikasi Pembayaran</b> di bawah untuk menyelesaikan pembayaran Anda.</p>
                        </div>
                        @endif

                        <!-- 3. Checkout Button Section (Always check and show if available) -->
                        @php
                            $checkout_url = null;
                            if(isset($payment_data['actions'])) {
                                $actions = $payment_data['actions'];
                                if(isset($actions['mobile_deeplink_checkout_url'])) {
                                    $checkout_url = $actions['mobile_deeplink_checkout_url'];
                                } elseif(isset($actions['mobile_web_checkout_url'])) {
                                    $checkout_url = $actions['mobile_web_checkout_url'];
                                } elseif(isset($actions['desktop_web_checkout_url'])) {
                                    $checkout_url = $actions['desktop_web_checkout_url'];
                                } elseif(is_array($actions)) {
                                    $action = collect($actions)->whereIn('url_type', ['MOBILE_WEB', 'WEB'])->first();
                                    $checkout_url = $action['url'] ?? null;
                                }
                            }
                        @endphp
                        @if($checkout_url)
                            <a id="btnOpenApp" href="{{ $checkout_url }}" class="block w-full py-4 bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white rounded-xl font-bold text-sm text-center shadow-lg transform active:scale-95 transition-all">
                                Buka Aplikasi Pembayaran
                            </a>
                        @endif
                    </div>

                    <!-- Simulation (Sandbox Only - Hide for E-Wallets as they use the main button) -->
                    @if($is_sandbox && $record->status_pembayaran === 'pending' && !$is_ewallet)
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="h-1.5 w-1.5 bg-[#00a6eb] rounded-full animate-pulse"></span>
                            <p class="text-[9px] text-[#00a6eb] uppercase tracking-wider font-bold">Instruksi Pembayaran</p>
                        </div>
                        <button id="btnSimulate" onclick="simulatePayment()" class="w-full py-3 bg-white border border-blue-200 text-[#00a6eb] rounded-xl text-xs font-bold hover:bg-[#00a6eb] hover:text-white transition-all">
                            Simulasi Pembayaran
                        </button>
                    </div>
                    @endif

                    <!-- Steps -->
                    <div class="space-y-3 pt-2">
                        <h3 class="text-xs font-bold text-slate-800">Cara Pembayaran</h3>
                        @php
                            // Determine steps based on payment method
                            if(str_contains($method, '_VA')) {
                                $steps = [
                                    'Buka aplikasi M-Banking atau ATM',
                                    'Pilih menu Transfer atau Bayar',
                                    'Masukkan nomor Virtual Account di atas',
                                    'Konfirmasi nominal dan selesaikan pembayaran',
                                    'Status akan terupdate otomatis'
                                ];
                            } elseif(in_array($method, ['QRIS', 'ID_QRIS'])) {
                                $steps = [
                                    'Buka aplikasi e-wallet atau mobile banking Anda',
                                    'Pilih menu Scan QR atau QRIS',
                                    'Arahkan kamera ke QR Code di atas',
                                    'Konfirmasi nominal dan selesaikan pembayaran',
                                    'Status akan terupdate otomatis'
                                ];
                            } elseif(in_array($method, ['ID_OVO', 'ID_DANA', 'ID_SHOPEEPAY', 'ID_LINKAJA', 'ID_GOPAY'])) {
                                $wallet_name = str_replace('ID_', '', $method);
                                $wallet_name = ucfirst(strtolower($wallet_name));
                                $steps = [
                                    'Klik tombol "Buka Aplikasi Pembayaran" di atas',
                                    'Anda akan diarahkan ke aplikasi ' . $wallet_name,
                                    'Konfirmasi detail pembayaran di aplikasi',
                                    'Masukkan PIN dan selesaikan pembayaran',
                                    'Status akan terupdate otomatis'
                                ];
                            } else {
                                $steps = [
                                    'Ikuti instruksi pembayaran yang tersedia',
                                    'Selesaikan pembayaran sesuai metode yang dipilih',
                                    'Status akan terupdate otomatis'
                                ];
                            }
                        @endphp
                        @foreach($steps as $index => $step)
                        <div class="flex gap-3 items-start">
                            <div class="h-5 w-5 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-bold shrink-0">
                                {{ $index + 1 }}
                            </div>
                            <p class="text-xs text-slate-600 pt-0.5">{{ $step }}</p>
                        </div>
                        @endforeach
                    </div>

                    <!-- Instructions -->
                </div>
            </div>

            <a href="{{ route('public.home') }}" class="block w-full text-center py-4 bg-[#00a6eb] text-white rounded-xl text-xs font-bold shadow-sm hover:bg-[#0090d0] transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
    }

    function checkPaymentStatus() {
        $.get('{{ route("public.payment_status", $order_id) }}', function(res) {
            if (res.status === 'completed') {
                $('#instructionsView').fadeOut(300, function() {
                    $('#successView').removeClass('hidden').fadeIn(300);
                });
                clearInterval(statusInterval);
            }
        });
    }

    const statusInterval = setInterval(checkPaymentStatus, 3000);

    if(@json($paidState)) {
        $('#instructionsView').hide();
        $('#successView').removeClass('hidden');
    }

    function simulatePayment() {
        const btn = $('#btnSimulate');
        const originalText = btn.text();
        btn.prop('disabled', true).text('Memproses...');

        $.ajax({
            url: '{{ route("public.payment_simulate") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_id: '{{ $order_id }}',
                type: '{{ $type }}',
                amount: {{ $record->nominal ?? $record->jumlah_dana }}
            },
            success: function(res) {
                if (res.status === 'redirect_to_checkout') {
                    // Open checkout URL in same tab
                    const checkoutUrl = $('#btnOpenApp').attr('href');
                    if (checkoutUrl) {
                        window.location.href = checkoutUrl;
                    } else {
                        alert('Gagal menemukan link pembayaran. Silakan coba tombol "Buka Aplikasi" secara manual.');
                        btn.prop('disabled', false).text(originalText);
                    }
                } else {
                    location.reload(); 
                }
            },
            error: function(err) {
                alert('Simulasi gagal: ' + (err.responseJSON?.message || 'Terjadi kesalahan'));
                btn.prop('disabled', false).text(originalText);
            }
        });
    }

    function toggleReceiptWhatsAppForm() {
        const form = document.getElementById('receiptWhatsAppForm');
        if (form) {
            form.classList.toggle('hidden');
        }
    }

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

    function sendReceiptToWhatsApp() {
        const input = document.getElementById('receiptWhatsAppNumber');
        const waNumber = normalizeWhatsappNumber(input ? input.value : '');

        if (!waNumber) {
            alert('Masukkan nomor WhatsApp tujuan terlebih dahulu.');
            return;
        }

        const message = encodeURIComponent('Om Swastyastu,%0A%0ABerikut link receipt pembayaran punia:%0A{{ $receiptUrl }}%0A%0AKode receipt: {{ $receiptCode }}');
        window.open('https://wa.me/' + waNumber + '?text=' + message, '_blank');
    }
</script>
@endsection
