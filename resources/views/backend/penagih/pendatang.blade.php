@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ 
    search: '',
    get filteredPendatang() {
        if (!this.search) return document.querySelectorAll('[data-pendatang-item]');
        const q = this.search.toLowerCase();
        document.querySelectorAll('[data-pendatang-item]').forEach(el => {
            const name = el.dataset.nama.toLowerCase();
            const hp = el.dataset.hp.toLowerCase();
            el.style.display = (name.includes(q) || hp.includes(q)) ? '' : 'none';
        });
        return [];
    }
}">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ url('administrator/penagih') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="text-[10px] font-bold">Kembali</span>
            </a>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Krama Tamiu</h1>
            <p class="text-slate-400 text-[10px] mt-1">Data pendatang di Banjar {{ $banjar ? $banjar->nama_banjar : '-' }}</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @php
        $totalPendatang = $pendatangList->where('status', 'aktif')->count();
        $totalTagihanBelumBayar = 0;
        foreach($pendatangList as $p) {
            $totalTagihanBelumBayar += $p->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
        }
    @endphp

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Pendatang Aktif</p>
            <h3 class="text-3xl font-black mb-3">{{ $totalPendatang }} Orang</h3>
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Tagihan Belum Bayar</p>
                    <p class="font-bold">{{ $totalTagihanBelumBayar }} Tagihan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div>
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" x-model="search" @input="filteredPendatang"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                   placeholder="Cari nama atau nomor HP...">
        </div>
    </div>

    <!-- Kartu Iuran Shortcuts -->
    @php
        $pendatangBelumBayar = $pendatangList->filter(function($p) {
            return $p->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count() > 0;
        });
    @endphp
    @if($pendatangBelumBayar->count() > 0)
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Belum Bayar</h3>
        <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 snap-x">
            @foreach($pendatangBelumBayar->take(10) as $p)
            @php
                $jumlahBelum = $p->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
                $totalTerutang = $p->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->sum('nominal');
            @endphp
            <a href="{{ url('administrator/penagih/pendatang/kartu-punia/'.$p->id_pendatang) }}" 
               class="flex-shrink-0 w-40 bg-white border border-slate-100 rounded-xl p-3 snap-start hover:bg-slate-50 transition-colors">
                <div class="h-8 w-8 bg-rose-50 rounded-lg flex items-center justify-center mb-2">
                    <span class="text-[10px] font-bold text-rose-600">{{ $jumlahBelum }}</span>
                </div>
                <p class="text-xs font-bold text-slate-800 truncate">{{ $p->nama }}</p>
                <p class="text-[10px] text-rose-500 font-medium mt-0.5">Rp {{ number_format($totalTerutang, 0, ',', '.') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Pendatang List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Daftar Pendatang</h3>
        @if($pendatangList->count() > 0)
        <div class="space-y-2">
            @foreach($pendatangList as $pendatang)
            <a href="{{ url('administrator/penagih/pendatang/detail/'.$pendatang->id_pendatang) }}" 
               class="block bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors"
               data-pendatang-item
               data-nama="{{ $pendatang->nama }}"
               data-hp="{{ $pendatang->no_hp }}">
                <div class="flex items-center gap-3 px-3 py-2.5">
                    <div class="h-9 w-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-500 text-[11px] font-medium">
                        {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $pendatang->nama }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ $pendatang->asal }} &middot; {{ $pendatang->no_hp }}</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @php
                            $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
                        @endphp
                        @if($belumBayar > 0)
                        <span class="text-[9px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                            {{ $belumBayar }}
                        </span>
                        @endif
                        <i class="bi bi-chevron-right text-slate-300 text-sm"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada data pendatang di banjar ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
