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
<div class="bg-white min-h-screen pb-8">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10 text-center">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-white/20 rounded-full mb-4">
                <i class="bi bi-check-circle-fill text-3xl"></i>
            </div>
            <h1 class="text-xl font-black mb-1">Bukti Transfer Diterima</h1>
            <p class="text-white/80 text-xs">Menunggu verifikasi admin</p>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 -mt-6 relative z-10 space-y-4">
        <!-- Status Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-slate-50 to-white p-6 text-center border-b border-slate-100">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Order ID</p>
                <h2 class="text-xl font-black text-slate-800">#{{ $order_id }}</h2>
            </div>

            <div class="p-6 space-y-3">
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-clock-history text-slate-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800 mb-1">Menunggu Verifikasi</p>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Bukti transfer Anda akan diverifikasi oleh admin dalam waktu maksimal 1x24 jam.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-shield-check text-slate-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800 mb-1">Data Tersimpan Permanen</p>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Data pembayaran Anda tersimpan di sistem desa adat dan tidak dapat dihapus untuk menjaga transparansi dan akuntabilitas keuangan.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-eye text-slate-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-800 mb-1">Transparansi Publik</p>
                        <p class="text-[10px] text-slate-500 leading-relaxed">Setelah diverifikasi, kontribusi Anda akan muncul di halaman publik bagian riwayat kontribusi.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Note Box -->
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                <div>
                    <p class="text-xs font-bold text-slate-700 mb-1">Catatan Penting</p>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Jika ada pertanyaan atau kendala, silakan hubungi admin desa adat. Terima kasih atas kontribusi Anda untuk kemajuan desa adat.</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3">
            <a href="{{ url('/') }}" class="block w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-black py-4 rounded-xl shadow-lg transition-all text-sm text-center">
                <i class="bi bi-house-door mr-2"></i> Kembali ke Beranda
            </a>
            @if($type === 'punia')
            <a href="{{ url('/punia') }}" class="block w-full bg-white hover:bg-slate-50 text-slate-600 font-bold py-4 rounded-xl border-2 border-slate-200 transition-all text-sm text-center">
                <i class="bi bi-wallet2 mr-2"></i> Lihat Halaman Punia
            </a>
            @else
            <a href="{{ url('/donasi') }}" class="block w-full bg-white hover:bg-slate-50 text-slate-600 font-bold py-4 rounded-xl border-2 border-slate-200 transition-all text-sm text-center">
                <i class="bi bi-heart-pulse mr-2"></i> Lihat Halaman Donasi
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
