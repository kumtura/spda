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
<div class="bg-white min-h-screen pb-8">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10 text-center">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-white/20 rounded-full mb-4">
                <i class="bi bi-check-circle text-3xl"></i>
            </div>
            <h1 class="text-xl font-bold mb-1">Lamaran Terkirim</h1>
            <p class="text-white/80 text-xs">Terima kasih telah melamar</p>
        </div>
    </div>

    <!-- Content -->
    <div class="px-4 -mt-6 relative z-10 space-y-4">
        <!-- Application Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- Job Info -->
            <div class="bg-gradient-to-br from-slate-50 to-white p-6 text-center border-b border-slate-100">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Posisi yang Dilamar</p>
                <h2 class="text-xl font-bold text-slate-800">{{ $loker->judul }}</h2>
                <p class="text-xs text-slate-500 mt-1">{{ $loker->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-xs text-slate-500">Nama</span>
                    <span class="text-xs font-medium text-slate-800">{{ $nama }}</span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-xs text-slate-500">Email</span>
                    <span class="text-xs font-medium text-slate-800">{{ $email }}</span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-slate-100">
                    <span class="text-xs text-slate-500">No. Telepon</span>
                    <span class="text-xs font-medium text-slate-800">{{ $no_telp }}</span>
                </div>

                <div class="flex items-center justify-between py-3">
                    <span class="text-xs text-slate-500">Status</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-medium">
                        <span class="h-1.5 w-1.5 bg-amber-500 rounded-full"></span>
                        Menunggu Review
                    </span>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-xs font-bold text-slate-800 mb-1">Langkah Selanjutnya</p>
                    <p class="text-[10px] text-slate-600 leading-relaxed">
                        Lamaran Anda telah diterima dan sedang dalam proses review. Perusahaan akan menghubungi Anda melalui nomor telepon yang telah didaftarkan untuk proses interview. Pastikan nomor Anda aktif dan dapat dihubungi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3 pt-2">
            <a href="{{ route('public.loker') }}" 
               class="block w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-xl font-black text-sm text-center shadow-lg shadow-blue-200 hover:shadow-xl transition-all">
                Lihat Lowongan Lainnya
            </a>
            
            <a href="{{ route('public.home') }}" 
               class="block w-full bg-white border border-slate-200 text-slate-600 py-4 rounded-xl font-black text-sm text-center hover:bg-slate-50 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
