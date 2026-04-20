@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{
    activeBanjar: '{{ $banjar ? $banjar->id_data_banjar : 'semua' }}',
    search: '',
    filter: 'semua',
}">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ url('administrator/penagih') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="text-[10px] font-bold">Kembali</span>
            </a>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Pendatang</h1>
            <p class="text-slate-400 text-[10px] mt-1">Kelola data krama tamiu semua banjar</p>
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
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
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

    <!-- Add Button -->
    <div class="fixed bottom-[75px] left-1/2 -translate-x-1/2 w-full max-w-[480px] px-5 z-40">
        <a href="{{ url('administrator/penagih/pendatang/create') }}" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-white/20">
            <i class="bi bi-plus-lg"></i> Tambah Data Pendatang
        </a>
    </div>

    <!-- History Iuran Masuk -->
    @if($recentPayments->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Iuran Masuk Terbaru</h3>
            <a href="{{ url('administrator/penagih/pendatang') }}?tab=log" class="text-[10px] font-bold text-[#00a6eb] hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-1.5">
            @foreach($recentPayments as $payment)
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $payment->pendatang->nama ?? '-' }}</p>
                        <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                            <span>{{ $payment->tanggal_bayar ? $payment->tanggal_bayar->format('d M Y') : '-' }}</span>
                            @if($payment->metode_pembayaran)
                            <span>&middot; {{ strtoupper($payment->metode_pembayaran) }}</span>
                            @endif
                            @if($payment->jenis_punia === 'rutin')
                            <span>&middot; {{ $payment->bulan_tahun }}</span>
                            @else
                            <span>&middot; {{ $payment->nama_acara }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 shrink-0">
                        +Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Banjar Tabs (Slider) -->
    <div>
        <div class="flex gap-2 overflow-x-auto pb-2 -mx-4 px-4 no-scrollbar">
            <button type="button" @click="activeBanjar = 'semua'"
                    :class="activeBanjar === 'semua' ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all">
                Semua <span class="ml-1">({{ $pendatangList->count() }})</span>
            </button>
            @foreach($banjarList as $b)
            @php $countBanjar = $pendatangList->where('id_data_banjar', $b->id_data_banjar)->count(); @endphp
            <button type="button" @click="activeBanjar = '{{ $b->id_data_banjar }}'"
                    :class="activeBanjar === '{{ $b->id_data_banjar }}' ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all">
                {{ $b->nama_banjar }} <span class="ml-1">({{ $countBanjar }})</span>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Filter + Search -->
    <div class="space-y-3">
        <div class="flex gap-2">
            <button type="button" @click="filter = 'semua'"
                    :class="filter === 'semua' ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-500'"
                    class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">Semua</button>
            <button type="button" @click="filter = 'belum_bayar'"
                    :class="filter === 'belum_bayar' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-500'"
                    class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">Belum Bayar</button>
            <button type="button" @click="filter = 'sudah_bayar'"
                    :class="filter === 'sudah_bayar' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-500'"
                    class="px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all">Sudah Bayar</button>
        </div>
        <div class="relative">
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            <input type="text" x-model="search"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-9 pr-4 py-2.5 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                   placeholder="Cari nama atau nomor HP...">
        </div>
    </div>

    <!-- Pendatang List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Daftar Pendatang</h3>
        @if($pendatangList->count() > 0)
        <div class="space-y-2">
            @foreach($pendatangList as $pendatang)
            @php
                $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
                $sudahBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'lunas')->where('aktif', '1')->count();
                $hasBelumBayar = $belumBayar > 0;
                $allPaid = $belumBayar === 0 && $sudahBayar > 0;
            @endphp
            <a href="{{ url('administrator/penagih/pendatang/detail/'.$pendatang->id_pendatang) }}" 
               class="block bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors"
               x-show="(activeBanjar === 'semua' || activeBanjar === '{{ $pendatang->id_data_banjar }}') && (filter === 'semua' || (filter === 'belum_bayar' && {{ $hasBelumBayar ? 'true' : 'false' }}) || (filter === 'sudah_bayar' && {{ $allPaid ? 'true' : 'false' }})) && (search === '' || '{{ strtolower(e($pendatang->nama)) }}'.includes(search.toLowerCase()) || '{{ strtolower(e($pendatang->no_hp)) }}'.includes(search.toLowerCase()))">
                <div class="flex items-center gap-3 px-3 py-2.5">
                    <div class="h-9 w-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-500 text-[11px] font-medium">
                        {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $pendatang->nama }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ $pendatang->asal }} &middot; {{ $pendatang->no_hp }}@if($pendatang->banjar) &middot; {{ $pendatang->banjar->nama_banjar }}@endif</p>
                        @if($pendatang->tinggal_dari)
                        <p class="text-[9px] text-slate-400 mt-0.5">
                            <i class="bi bi-calendar3 mr-0.5"></i>
                            {{ $pendatang->tinggal_dari->format('d/m/Y') }}
                            @if(!$pendatang->tinggal_belum_yakin && $pendatang->tinggal_sampai)
                                — {{ $pendatang->tinggal_sampai->format('d/m/Y') }}
                            @elseif($pendatang->tinggal_belum_yakin)
                                — <span class="italic">Belum ditentukan</span>
                            @endif
                        </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @if($belumBayar > 0)
                        <span class="text-[9px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">{{ $belumBayar }}</span>
                        @endif
                        <i class="bi bi-chevron-right text-slate-300 text-sm"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada data pendatang</p>
        </div>
        @endif
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
