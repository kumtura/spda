@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-8">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Dana Punia</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Transparansi pengelolaan dana desa adat.</p>
    </div>

    <!-- Stats Card -->
    <div class="bg-[#00a6eb] rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-wallet2 text-lg"></i>
            </div>
            <span class="text-[8px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">Terverifikasi</span>
        </div>
        <p class="text-[9px] font-bold uppercase tracking-wider text-white/60 mb-1">Total Dana Terkumpul</p>
        <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($total_punia, 0, ',', '.') }}</h3>
    </div>

    <!-- Info -->
    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
        <h4 class="text-sm font-bold text-slate-800 mb-3">Tentang Dana Punia</h4>
        <p class="text-xs text-slate-500 leading-relaxed">Dana Punia merupakan kontribusi dari krama desa dan unit usaha untuk mendukung pembangunan sarana keagamaan, sosial, dan budaya di Desa Adat.</p>
    </div>

    <!-- Penggunaan Dana -->
    <div>
        <h4 class="text-sm font-bold text-slate-800 mb-4">Penggunaan Dana</h4>
        <div class="space-y-3">
            <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-start gap-3">
                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-building text-[#00a6eb] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Pembangunan & Renovasi Pura</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Perbaikan dan pemeliharaan pura desa serta pelinggih.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-start gap-3">
                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-calendar-event text-[#00a6eb] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Upacara & Kegiatan Adat</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Pembiayaan piodalan, ngaben massal, dan upacara lainnya.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-start gap-3">
                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-people text-[#00a6eb] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Bantuan Sosial Krama</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Santunan bagi krama yang membutuhkan dan bantuan darurat.</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-start gap-3">
                <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <i class="bi bi-tools text-[#00a6eb] text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">Infrastruktur Desa</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">Perbaikan jalan desa, balai banjar, dan fasilitas umum.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <a href="{{ route('public.donasi') }}" class="block w-full bg-white border border-[#00a6eb]/20 rounded-2xl p-4 shadow-sm group hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded border border-blue-100 mb-1 inline-block">Donasi</span>
                <h3 class="text-slate-800 font-bold text-sm leading-tight mt-1">Salurkan Bantuan Sekarang</h3>
            </div>
            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-[#00a6eb] border border-blue-100 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors">
                <i class="bi bi-arrow-right text-sm"></i>
            </div>
        </div>
    </a>
</div>
@endsection
