@extends('index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="{ 
    showDeleteModal: false,
    deleteId: null,
    deleteName: '',
    filterBanjar: '{{ request('banjar') }}',
    filterStatus: '{{ request('status', 'aktif') }}',
    applyFilter() {
        let url = '{{ url('administrator/pendatang') }}?status=' + this.filterStatus;
        if (this.filterBanjar) url += '&banjar=' + this.filterBanjar;
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
            <a href="{{ url('administrator/kelian/pendatang/setting') }}" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all">
                <i class="bi bi-gear"></i> Atur Punia Global
            </a>
            <a href="{{ url('administrator/kelian/pendatang/create') }}" class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
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
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Total Pendatang Aktif</p>
            <p class="text-2xl font-black tracking-tight">{{ $totalAktif }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Pendatang</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $totalPendatang }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tagihan Belum Bayar</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $totalBelumBayar }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Banjar</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $banjarList->count() }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
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
                Filter
            </button>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-12">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama / NIK</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tagihan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendatangList as $i => $pendatang)
                    @php
                        $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-slate-800">{{ $pendatang->nama }}</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $pendatang->nik }}</p>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $pendatang->banjar ? $pendatang->banjar->nama_banjar : '-' }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $pendatang->asal }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg {{ $pendatang->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $pendatang->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($belumBayar > 0)
                            <span class="text-[10px] font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-lg">{{ $belumBayar }} belum</span>
                            @else
                            <span class="text-[10px] font-bold text-slate-400">Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ url('administrator/kelian/pendatang/detail/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/kelian/pendatang/edit/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-100 transition-all" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/kelian/pendatang/toggle/'.$pendatang->id_pendatang) }}" class="h-8 w-8 bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-all" title="Toggle Status">
                                    <i class="bi bi-toggle-on text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-people text-4xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">Belum ada data pendatang</p>
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
