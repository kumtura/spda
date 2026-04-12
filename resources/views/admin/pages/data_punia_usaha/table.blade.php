@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $paidCount = $usahaList->where('payment_status', 'lunas')->count();
    $unpaidCount = $usahaList->where('payment_status', 'belum')->count();
    $totalPaid = $usahaList->where('payment_status', 'lunas')->sum(function($u) {
        return $u->payment_data ? $u->payment_data->jumlah_dana : 0;
    });
    $totalTerutang = $usahaList->where('payment_status', 'belum')->sum('minimal_bayar');
@endphp

<div id="admin-page-container" class="space-y-6" x-data="{ 
    selectedMonth: '{{ $month }}',
    selectedYear: '{{ $year }}',
    filterBanjar: '{{ request('banjar') }}',
    searchQuery: '{{ request('search') }}',
    months: [
        { id: '1', name: 'Jan' }, { id: '2', name: 'Feb' }, { id: '3', name: 'Mar' },
        { id: '4', name: 'Apr' }, { id: '5', name: 'Mei' }, { id: '6', name: 'Jun' },
        { id: '7', name: 'Jul' }, { id: '8', name: 'Agu' }, { id: '9', name: 'Sep' },
        { id: '10', name: 'Okt' }, { id: '11', name: 'Nov' }, { id: '12', name: 'Des' }
    ],
    filter() {
        let url = '{{ url('administrator/datapunia_usaha') }}/' + this.selectedMonth + '/' + this.selectedYear;
        let params = [];
        if (this.filterBanjar) params.push('banjar=' + this.filterBanjar);
        if (this.searchQuery) params.push('search=' + encodeURIComponent(this.searchQuery));
        if (params.length > 0) url += '?' + params.join('&');
        window.location = url;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ url('administrator/datapunia_wajib') }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Penerimaan Punia
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Iuran Unit Usaha</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring penerimaan iuran bulanan dari unit usaha desa adat.</p>
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
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Terkumpul Periode Ini</p>
            <p class="text-lg font-black tracking-tight">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Sudah Bayar</p>
            <p class="text-2xl font-black text-emerald-500 tracking-tight">{{ $paidCount }} <span class="text-sm">usaha</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Belum Bayar</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $unpaidCount }} <span class="text-sm">usaha</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Min. Terutang</p>
            <p class="text-lg font-black text-slate-800 tracking-tight">Rp {{ number_format($totalTerutang, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <input type="number" x-model="selectedYear" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 text-center transition-all">
            <select x-model="filterBanjar" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Banjar</option>
                @foreach($banjarList as $b)
                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                @endforeach
            </select>
            <div class="relative flex-1 min-w-[200px] max-w-md">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" x-model="searchQuery" 
                       @keydown.enter="filter()"
                       placeholder="Cari nama usaha atau pemilik..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
            </div>
            <button @click="filter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            @if(request('search') || request('banjar'))
            <a href="{{ url('administrator/datapunia_usaha/'.$month.'/'.$year) }}" class="text-xs text-slate-500 hover:text-rose-500 font-bold transition-colors">
                <i class="bi bi-x-circle mr-1"></i>Reset
            </a>
            @endif
        </div>

        <div class="flex flex-wrap gap-2 pt-3 mt-3 border-t border-slate-100">
            <template x-for="m in months" :key="m.id">
                <button @click="selectedMonth = m.id; filter()"
                        :class="selectedMonth == m.id ? 'bg-primary-light text-white shadow-md shadow-blue-100' : 'text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100'"
                        class="px-5 py-2 rounded-xl text-xs font-bold transition-all transform hover:-translate-y-0.5">
                    <span x-text="m.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-12">No</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Usaha / Pemilik</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tgl Bayar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($usahaList as $i => $u)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 {{ $u->payment_status == 'lunas' ? 'bg-emerald-50 border-emerald-100' : 'bg-slate-50 border-slate-100' }} border rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                                    @if($u->logo)
                                        @php
                                            $logoPath = file_exists(public_path('usaha/icon/'.$u->logo)) 
                                                ? 'usaha/icon/'.$u->logo 
                                                : 'storage/usaha/icon/'.$u->logo;
                                        @endphp
                                        <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                                    @else
                                        <i class="bi bi-building text-slate-300"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $u->nama_usaha }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $u->nama }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $u->nama_banjar ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($u->payment_status == 'lunas')
                                <span class="text-sm font-bold text-slate-700">Rp {{ number_format($u->payment_data->jumlah_dana, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm font-medium text-slate-400 italic">Min. Rp {{ number_format($u->minimal_bayar ?? 0, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($u->payment_status == 'lunas')
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600">Lunas</span>
                            @else
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600">Belum</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center text-xs text-slate-500">
                            @if($u->payment_status == 'lunas' && $u->payment_data)
                                {{ \Carbon\Carbon::parse($u->payment_data->tanggal_pembayaran)->format('d/m/Y') }}
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ url('administrator/data_usaha/detail/'.$u->id_usaha.'?from=punia_usaha') }}" class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Lihat Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-building text-4xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">
                                    @if(request('search'))
                                    Tidak ditemukan data untuk "{{ request('search') }}"
                                    @else
                                    Belum ada unit usaha aktif untuk periode ini.
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

@stop
