@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{
    searchQuery: '',
    filter: 'semua',
    isPhoneSearch() {
        return /^\d+$/.test(this.searchQuery.trim());
    },
    matchesSearch(name, phone) {
        const query = this.searchQuery.trim().toLowerCase();
        if (query === '') return true;

        if (this.isPhoneSearch()) {
            const normalizedPhone = String(phone || '').replace(/\D/g, '');
            return normalizedPhone.includes(query);
        }

        return String(name || '').toLowerCase().includes(query);
    }
}">
    <div>
        <div>
            <a href="{{ url('administrator/penagih') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-3">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="text-[10px] font-bold">Kembali</span>
            </a>
            @if(session('success'))
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-3">
                <div class="flex items-center gap-2">
                    <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                    <p class="text-xs text-blue-700">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <div class="flex items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Pendatang</h1>
                    <p class="text-slate-400 text-[10px] mt-1">Banjar {{ $banjar ? $banjar->nama_banjar : '-' }}</p>
                </div>
                <a href="{{ url('administrator/penagih/pendatang/create') }}" class="h-8 px-3 bg-[#00a6eb] hover:bg-[#0090d0] text-white rounded-lg inline-flex items-center justify-center gap-1.5 transition-colors shrink-0">
                    <i class="bi bi-plus-lg text-sm"></i>
                    <span class="text-[10px] font-bold">Tambah</span>
                </a>
            </div>
        </div>
    </div>

    @php
        $totalPendatang = $pendatangList->where('status', 'aktif')->count();
        $totalSudahBayar = 0;
        $totalTagihanBelumBayar = 0;
        foreach($pendatangList as $p) {
            $belum = $p->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
            $lunas = $p->puniaPendatang->where('status_pembayaran', 'lunas')->where('aktif', '1')->count();
            $totalTagihanBelumBayar += $belum;
            if ($belum === 0 && $lunas > 0) {
                $totalSudahBayar++;
            }
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
            <div class="grid grid-cols-3 gap-3 pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Total Pendatang</p>
                    <p class="font-bold text-sm">{{ $totalPendatang }}</p>
                </div>
                <div class="text-center">
                    <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                    <p class="font-bold text-sm text-emerald-300">{{ $totalSudahBayar }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                    <p class="font-bold text-sm text-amber-300">{{ $totalTagihanBelumBayar }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- History Iuran Masuk -->
    @if($recentPayments->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Iuran Masuk Terbaru</h3>
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
            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
            <input type="text" x-model="searchQuery" placeholder="Cari nama atau nomor telepon..."
                   class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#00a6eb]/30 focus:ring-1 focus:ring-[#00a6eb]/20">
        </div>
    </div>

    <!-- Pendatang List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Daftar Krama Tamiu</h3>
            <span class="text-[10px] font-medium text-slate-400">{{ $totalSudahBayar }}/{{ $totalPendatang }} lunas</span>
        </div>
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
               class="block bg-white border border-slate-100 rounded-xl p-3.5 hover:border-[#00a6eb]/30 hover:shadow-sm transition-all"
               x-show="(filter === 'semua' || (filter === 'belum_bayar' && {{ $hasBelumBayar ? 'true' : 'false' }}) || (filter === 'sudah_bayar' && {{ $allPaid ? 'true' : 'false' }})) && matchesSearch(@js($pendatang->nama), @js($pendatang->no_hp))">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-500 text-[11px] font-medium">
                        {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5 truncate">{{ $pendatang->nama }}</p>
                        <div class="flex items-center gap-2 text-[9px] text-slate-400 truncate">
                            @if($pendatang->asal)
                            <span>{{ $pendatang->asal }}</span>
                            @endif
                            @if($pendatang->asal && $pendatang->no_hp)
                            <span>&middot;</span>
                            @endif
                            @if($pendatang->no_hp)
                            <span>{{ $pendatang->no_hp }}</span>
                            @endif
                        </div>
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
                        <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ $belumBayar }} belum</span>
                        @elseif($allPaid)
                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">Lunas</span>
                        @endif
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
