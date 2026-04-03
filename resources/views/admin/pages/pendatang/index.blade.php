@extends('index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="{ 
    showDeleteModal: false,
    deleteId: null,
    deleteName: '',
    filterBanjar: '{{ request('banjar') }}',
    filterStatus: '{{ request('status', 'aktif') }}',
    searchQuery: '{{ request('search') }}',
    showPayments: false,
    applyFilter() {
        let url = '{{ url('administrator/pendatang') }}?status=' + this.filterStatus;
        if (this.filterBanjar) url += '&banjar=' + this.filterBanjar;
        if (this.searchQuery) url += '&search=' + encodeURIComponent(this.searchQuery);
        window.location = url;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Pendatang</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola data pendatang di seluruh banjar desa adat.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('administrator/pendatang/setting') }}" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all">
                <i class="bi bi-gear"></i> Atur Punia Global
            </a>
            <a href="{{ url('administrator/pendatang/create') }}" class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-plus-lg"></i> Tambah Pendatang
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600"></i>
            <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Pendatang Aktif</p>
            <p class="text-2xl font-black tracking-tight">{{ $totalAktif }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Pendatang</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $totalPendatang }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Belum Bayar</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $totalBelumBayar }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Banjar</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $banjarList->count() }}</p>
        </div>
        <div class="bg-emerald-600 rounded-2xl p-5 text-white shadow-md shadow-emerald-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Dana Masuk Bulan Ini</p>
            <p class="text-lg font-black tracking-tight">Rp {{ number_format($totalDanaMasukBulanIni, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Dana Masuk</p>
            <p class="text-lg font-black text-emerald-600 tracking-tight">Rp {{ number_format($totalDanaMasukTotal, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Recent Payments Toggle -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <button @click="showPayments = !showPayments" class="w-full flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="bi bi-cash-stack text-emerald-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-black text-slate-800">Riwayat Dana Masuk</h3>
                    <p class="text-[10px] text-slate-400 font-medium">{{ $recentPayments->count() }} pembayaran terakhir</p>
                </div>
            </div>
            <i class="bi text-slate-400" :class="showPayments ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>

        <div x-show="showPayments" x-collapse>
            @if($recentPayments->count() > 0)
            <div class="border-t border-slate-100 divide-y divide-slate-50">
                @foreach($recentPayments as $payment)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-arrow-down-left text-emerald-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $payment->pendatang->nama ?? '-' }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                <span>{{ $payment->tanggal_bayar ? $payment->tanggal_bayar->format('d M Y H:i') : '-' }}</span>
                                @if($payment->metode_pembayaran)
                                <span>&middot; {{ strtoupper($payment->metode_pembayaran) }}</span>
                                @endif
                                @if($payment->jenis_punia === 'rutin')
                                <span>&middot; Rutin {{ $payment->bulan_tahun }}</span>
                                @else
                                <span>&middot; {{ $payment->nama_acara }}</span>
                                @endif
                                @if($payment->pendatang && $payment->pendatang->banjar)
                                <span>&middot; {{ $payment->pendatang->banjar->nama_banjar }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold text-emerald-600">+Rp {{ number_format($payment->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="border-t border-slate-100 px-6 py-8 text-center">
                <p class="text-sm text-slate-400">Belum ada riwayat pembayaran</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" x-model="searchQuery" 
                       @keydown.enter="applyFilter()"
                       placeholder="Cari nama, NIK, atau no HP..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
            </div>
            <select x-model="filterBanjar" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Banjar</option>
                @foreach($banjarList as $banjar)
                <option value="{{ $banjar->id_data_banjar }}">{{ $banjar->nama_banjar }}</option>
                @endforeach
            </select>
            <select x-model="filterStatus" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
            <button @click="applyFilter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            @if(request('search') || request('banjar') || request('status') !== 'aktif')
            <a href="{{ url('administrator/pendatang') }}" class="text-xs text-slate-500 hover:text-rose-500 font-bold transition-colors">
                <i class="bi bi-x-circle mr-1"></i>Reset
            </a>
            @endif
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-12">No</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama / NIK</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lama Tinggal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tagihan</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendatangList as $i => $pendatang)
                    @php
                        $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ url('administrator/pendatang/detail/'.$pendatang->id_pendatang) }}" class="group">
                                <p class="text-sm font-bold text-slate-800 group-hover:text-primary-light transition-colors">{{ $pendatang->nama }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $pendatang->nik }} &middot; {{ $pendatang->no_hp }}</p>
                            </a>
                        </td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $pendatang->banjar ? $pendatang->banjar->nama_banjar : '-' }}</td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $pendatang->asal }}</td>
                        <td class="px-5 py-4 text-xs text-slate-500">
                            @if($pendatang->tinggal_belum_yakin)
                                <span class="text-amber-600 italic text-[10px]">Belum ditentukan</span>
                            @elseif($pendatang->tinggal_dari || $pendatang->tinggal_sampai)
                                <span class="text-[10px]">
                                    {{ $pendatang->tinggal_dari ? $pendatang->tinggal_dari->format('d/m/Y') : '?' }}
                                    — {{ $pendatang->tinggal_sampai ? $pendatang->tinggal_sampai->format('d/m/Y') : '?' }}
                                </span>
                            @else
                                <span class="text-slate-300 text-[10px]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg {{ $pendatang->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $pendatang->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($belumBayar > 0)
                            <span class="text-[10px] font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-lg">{{ $belumBayar }} belum</span>
                            @else
                            <span class="text-[10px] font-bold text-emerald-500">Lunas</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ url('administrator/pendatang/detail/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Lihat Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/pendatang/edit/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-100 transition-all" title="Edit Data">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-100 transition-all" title="Kartu Iuran">
                                    <i class="bi bi-wallet2 text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/pendatang/toggle/'.$pendatang->id_pendatang) }}" onclick="return confirm('Ubah status {{ $pendatang->nama }}?')" class="h-8 w-8 bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-all" title="Toggle Status">
                                    <i class="bi bi-toggle-{{ $pendatang->status === 'aktif' ? 'on' : 'off' }} text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-people text-4xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">
                                    @if(request('search'))
                                    Tidak ditemukan data untuk "{{ request('search') }}"
                                    @else
                                    Belum ada data pendatang
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
