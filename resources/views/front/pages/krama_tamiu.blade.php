@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-28 space-y-6">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Krama Tamiu</h2>
        <p class="text-[10px] text-slate-400 mt-1">Data warga pendatang/tamu di desa adat</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Data Terkini</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Krama Tamiu Terdaftar</p>
            <h3 class="text-3xl font-black mb-3">{{ $totalKramaTamiu }} Orang</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Jumlah Banjar</p>
                    <p class="font-bold">{{ $banjarList->count() }} Banjar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="bg-white rounded-xl border border-slate-100 p-4">
        <h4 class="text-xs font-bold text-slate-800 mb-2">Tentang Krama Tamiu</h4>
        <p class="text-[11px] text-slate-500 leading-relaxed">
            Krama Tamiu adalah warga pendatang atau tamu yang tinggal di wilayah desa adat. 
            Sebagai bagian dari komunitas, Krama Tamiu memiliki kewajiban punia (iuran) bulanan 
            yang digunakan untuk mendukung kegiatan dan pembangunan desa adat.
        </p>
    </div>

    <!-- Panduan Pendaftaran -->
    <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
        <h4 class="text-xs font-bold text-slate-700 mb-3">Cara Mendaftar</h4>
        <div class="space-y-2.5">
            <div class="flex items-start gap-3">
                <div class="h-5 w-5 bg-[#00a6eb] text-white rounded-full flex items-center justify-center shrink-0 text-[9px] font-bold">1</div>
                <p class="text-[11px] text-slate-600">Tekan tombol "Daftar Krama Tamiu" di bawah</p>
            </div>
            <div class="flex items-start gap-3">
                <div class="h-5 w-5 bg-[#00a6eb] text-white rounded-full flex items-center justify-center shrink-0 text-[9px] font-bold">2</div>
                <p class="text-[11px] text-slate-600">Isi data diri lengkap dan pilih banjar tempat tinggal</p>
            </div>
            <div class="flex items-start gap-3">
                <div class="h-5 w-5 bg-[#00a6eb] text-white rounded-full flex items-center justify-center shrink-0 text-[9px] font-bold">3</div>
                <p class="text-[11px] text-slate-600">Data akan diverifikasi oleh Kelian Banjar</p>
            </div>
        </div>
    </div>

    <!-- Statistik per Banjar -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Krama Tamiu per Banjar</h3>
        
        @if($banjarList->count() > 0)
        <div class="space-y-2">
            @foreach($banjarList as $banjar)
            @php $count = $banjarStats[$banjar->id_data_banjar] ?? 0; @endphp
            <div class="bg-white border border-slate-100 rounded-xl px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-pin-map text-slate-400 text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800">{{ $banjar->nama_banjar }}</p>
                        @if($banjar->alamat_banjar)
                        <p class="text-[10px] text-slate-400 truncate">{{ $banjar->alamat_banjar }}</p>
                        @endif
                    </div>
                    <div class="shrink-0 pl-3">
                        <span class="text-sm font-semibold text-slate-700">{{ $count }}</span>
                        <span class="text-[10px] text-slate-400 ml-0.5">orang</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada data banjar</p>
        </div>
        @endif
    </div>

</div>

<!-- Floating Register Button -->
<div class="fixed bottom-[75px] left-1/2 -translate-x-1/2 w-full max-w-[480px] px-5 z-40">
    <a href="{{ route('public.krama_tamiu.register') }}" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-white/20">
        <i class="bi bi-person-plus-fill"></i> Daftar Krama Tamiu
    </a>
</div>
@endsection
