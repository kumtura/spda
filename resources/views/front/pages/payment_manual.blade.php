@extends('mobile_layout_public')

@section('content')
<style>
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
        
        <a href="{{ route('public.payment_methods', ['order_id' => $order_id, 'amount' => $amount, 'type' => $type]) }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2">Transfer Manual</h1>
            <p class="text-white/80 text-xs font-medium">Upload bukti transfer Anda</p>
        </div>
    </div>

    <div class="px-4 -mt-6 relative z-10 space-y-4">
        <!-- Summary Card -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100">
            <div class="text-center mb-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pembayaran</p>
                <p class="text-3xl font-black text-[#00a6eb]">Rp {{ number_format($amount, 0, ',', '.') }}</p>
            </div>
            <div class="text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Order ID</p>
                <p class="text-xs font-black text-slate-800">#{{ $order_id }}</p>
            </div>
        </div>

        <!-- Bank Account Info -->
        <div class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Rekening Tujuan</h3>
            
            @php
                $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                $bankAccounts = [];
                
                // Only show banks that have been configured
                if(!empty($settings['bank_bca_number'])) {
                    $bankAccounts[] = ['bank' => 'BCA', 'number' => $settings['bank_bca_number'], 'name' => $settings['bank_bca_name'] ?? 'Desa Adat'];
                }
                if(!empty($settings['bank_bni_number'])) {
                    $bankAccounts[] = ['bank' => 'BNI', 'number' => $settings['bank_bni_number'], 'name' => $settings['bank_bni_name'] ?? 'Desa Adat'];
                }
                if(!empty($settings['bank_mandiri_number'])) {
                    $bankAccounts[] = ['bank' => 'Mandiri', 'number' => $settings['bank_mandiri_number'], 'name' => $settings['bank_mandiri_name'] ?? 'Desa Adat'];
                }
                if(!empty($settings['bank_bri_number'])) {
                    $bankAccounts[] = ['bank' => 'BRI', 'number' => $settings['bank_bri_number'], 'name' => $settings['bank_bri_name'] ?? 'Desa Adat'];
                }
            @endphp

            @if(count($bankAccounts) > 0)
                @foreach($bankAccounts as $bank)
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="h-8 w-12 bg-white rounded border border-slate-200 flex items-center justify-center">
                            <span class="text-[10px] font-black text-slate-700">{{ $bank['bank'] }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-800">{{ $bank['number'] }}</p>
                            <p class="text-[10px] text-slate-500">a.n. {{ $bank['name'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-exclamation-triangle text-amber-500 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Rekening Belum Dikonfigurasi</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Silakan hubungi admin untuk mengatur rekening bank.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Upload Form -->
        <form action="{{ route('public.payment.manual.submit') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 space-y-4">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order_id }}">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <div>
                        <p class="text-xs font-bold text-slate-700 mb-1">Instruksi Transfer</p>
                        <ol class="text-[10px] text-slate-600 leading-relaxed space-y-1 list-decimal list-inside">
                            <li>Transfer sesuai nominal yang tertera</li>
                            <li>Upload bukti transfer (screenshot/foto)</li>
                            <li>Tunggu verifikasi dari admin (maks 1x24 jam)</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Upload Bukti Transfer <span class="text-rose-500">*</span></label>
                <input type="file" name="bukti_transfer" required accept=".jpg,.jpeg,.png,.pdf" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-600 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#00a6eb] file:text-white hover:file:bg-[#0090d0]">
                <p class="text-[9px] text-slate-400 mt-1.5">Format: JPG, PNG, PDF (Maks 2MB)</p>
            </div>

            <div>
                <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Catatan (Opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Tambahkan catatan jika diperlukan"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-black py-4 rounded-xl shadow-lg transition-all text-sm">
                <i class="bi bi-upload mr-2"></i> Upload Bukti Transfer
            </button>
        </form>
    </div>
</div>
@endsection
