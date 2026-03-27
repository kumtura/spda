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
    <div x-data="{ expandedId: null }">
        <h4 class="text-sm font-bold text-slate-800 mb-4">Penggunaan Dana</h4>
        <div class="space-y-3">
            @forelse($kategori_punia as $kat)
                <div class="bg-white rounded-xl border border-slate-100 overflow-hidden shadow-sm transition-all text-left w-full cursor-pointer" @click="expandedId === {{ $kat->id_kategori_punia }} ? expandedId = null : expandedId = {{ $kat->id_kategori_punia }}">
                    <div class="p-4 flex items-center justify-between gap-3 bg-white hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-blue-50/80 rounded-xl flex items-center justify-center shrink-0 border border-blue-100/50">
                                <i class="bi {{ $kat->ikon ?? 'bi-wallet2' }} text-[#00a6eb] text-lg"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $kat->nama_kategori }}</p>
                                <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ count($kat->alokasi) }} Transaksi Alokasi</p>
                            </div>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 text-slate-400 transition-transform duration-300" :class="expandedId === {{ $kat->id_kategori_punia }} ? 'rotate-180 bg-[#00a6eb] text-white border-transparent shadow-md shadow-blue-500/20' : ''">
                            <i class="bi bi-chevron-down text-sm"></i>
                        </div>
                    </div>
                    
                    <!-- Expanded Details -->
                    <div x-show="expandedId === {{ $kat->id_kategori_punia }}" x-collapse x-cloak>
                        <div class="px-5 py-4 bg-slate-50/50 border-t border-slate-100">
                            <p class="text-[11px] text-slate-500 leading-relaxed mb-4">{{ $kat->deskripsi_singkat ?? 'Tidak ada deskripsi untuk kategori ini.' }}</p>
                            
                            @if(count($kat->alokasi) > 0)
                                <div class="space-y-3">
                                    <h5 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-200 pb-2">Riwayat Alokasi</h5>
                                    @foreach($kat->alokasi->take(5) as $alo)
                                    <div class="flex justify-between items-start pt-1">
                                        <div>
                                            <p class="text-xs font-bold text-slate-700">{{ $alo->judul }}</p>
                                            <p class="text-[9px] font-semibold text-slate-400">{{ \Carbon\Carbon::parse($alo->tanggal_alokasi)->format('d M Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xs font-black text-slate-800">Rp {{ number_format($alo->nominal, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if(count($kat->alokasi) > 5)
                                    <div class="pt-2 text-center text-[10px] font-bold text-[#00a6eb]">
                                        + {{ count($kat->alokasi) - 5 }} Alokasi lainnya
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="py-3 text-center text-slate-400">
                                    <i class="bi bi-inbox text-2xl mb-1 block"></i>
                                    <p class="text-[10px] font-medium">Belum ada alokasi tercatat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="bi bi-clock-history text-2xl mb-2 block"></i>
                    <p class="text-xs font-medium">Belum ada kategori penggunaan dana yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- CTA -->
    <a href="{{ route('public.donasi') }}" class="block w-full bg-white border border-[#00a6eb]/20 rounded-2xl p-4 shadow-sm group hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded border border-blue-100 mb-1 inline-block">Punia</span>
                <h3 class="text-slate-800 font-bold text-sm leading-tight mt-1">Salurkan Dana Punia Sekarang</h3>
            </div>
            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-[#00a6eb] border border-blue-100 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors">
                <i class="bi bi-arrow-right text-sm"></i>
            </div>
        </div>
    </a>
</div>
@endsection
