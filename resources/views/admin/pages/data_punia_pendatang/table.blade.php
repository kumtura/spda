@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $paidCount = $pendatangList->where('payment_status', 'lunas')->count();
    $unpaidCount = $pendatangList->where('payment_status', 'belum')->count();
    $totalPaid = $pendatangList->where('payment_status', 'lunas')->sum(function($p) {
        return $p->payment_data ? $p->payment_data->nominal : 0;
    });
    $totalTerutang = $pendatangList->where('payment_status', 'belum')->sum('punia_rutin_bulanan');
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
        let url = '{{ url('administrator/datapunia_pendatang') }}/' + this.selectedMonth + '/' + this.selectedYear;
        let params = [];
        if (this.filterBanjar) params.push('banjar=' + this.filterBanjar);
        if (this.searchQuery) params.push('search=' + encodeURIComponent(this.searchQuery));
        if (params.length > 0) url += '?' + params.join('&');
        window.location = url;
    },
    
    // Quick Pay Modal
    showPayModal: false,
    payData: { id: null, name: '', month: '', year: '', nominal: 0 },
    openPayModal(id, name, month, year, nominal) {
        this.payData = { id, name, month, year, nominal };
        this.showPayModal = true;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Iuran Punia Pendatang</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring penerimaan iuran rutin dari warga pendatang/tamiu.</p>
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
            <p class="text-2xl font-black text-emerald-500 tracking-tight">{{ $paidCount }} <span class="text-sm">orang</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Belum Bayar</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $unpaidCount }} <span class="text-sm">orang</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Terutang</p>
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
                       placeholder="Cari nama atau NIK..."
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
            </div>
            <button @click="filter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            @if(request('search') || request('banjar'))
            <a href="{{ url('administrator/datapunia_pendatang/'.$month.'/'.$year) }}" class="text-xs text-slate-500 hover:text-rose-500 font-bold transition-colors">
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
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama / NIK</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tgl Bayar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pendatangList as $i => $p)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-4">
                            <a href="{{ url('administrator/pendatang/detail/'.$p->id_pendatang.'?from=punia') }}" class="group">
                                <p class="text-sm font-bold text-slate-800 group-hover:text-primary-light transition-colors">{{ $p->nama }}</p>
                                <p class="text-[10px] text-slate-400 font-medium mt-0.5">{{ $p->nik }} &middot; {{ $p->no_hp }}</p>
                            </a>
                        </td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $p->banjar->nama_banjar ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($p->payment_status == 'lunas')
                                <span class="text-sm font-bold text-slate-700">Rp {{ number_format($p->payment_data->nominal, 0, ',', '.') }}</span>
                            @else
                                <span class="text-sm font-medium text-slate-400 italic">Rp {{ number_format($p->punia_rutin_bulanan, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($p->payment_status == 'lunas')
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600">Lunas</span>
                            @else
                            <span class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600">Belum</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center text-xs text-slate-500">
                            @if($p->payment_status == 'lunas' && $p->payment_data)
                                {{ $p->payment_data->tanggal_bayar ? $p->payment_data->tanggal_bayar->format('d/m/Y') : '-' }}
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ url('administrator/pendatang/detail/'.$p->id_pendatang.'?from=punia') }}" class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Lihat Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/pendatang/kartu-punia/'.$p->id_pendatang.'?year='.$year.'&from=punia') }}" class="h-8 w-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-100 transition-all" title="Kartu Iuran">
                                    <i class="bi bi-wallet2 text-sm"></i>
                                </a>
                                @if($p->payment_status != 'lunas')
                                <button @click="openPayModal('{{ $p->id_pendatang }}', '{{ $p->nama }}', selectedMonth, selectedYear, {{ $p->punia_rutin_bulanan }})" 
                                        class="h-8 px-3 bg-primary-light text-white rounded-lg text-[10px] font-bold hover:bg-primary-dark transition-all" title="Bayar Sekarang">
                                    <i class="bi bi-cash-coin mr-1"></i>Bayar
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-people text-4xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">
                                    @if(request('search'))
                                    Tidak ditemukan data untuk "{{ request('search') }}"
                                    @else
                                    Belum ada data pendatang aktif untuk periode ini.
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

    <!-- Quick Pay Modal -->
    <div x-show="showPayModal" x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showPayModal = false" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-primary-light p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showPayModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <h3 class="text-xl font-black relative">Catat Pembayaran</h3>
                <p class="text-white/80 text-sm font-medium mt-1 relative" x-text="payData.name"></p>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs text-slate-400">Periode</span>
                        <span class="text-sm font-bold text-slate-800" x-text="months.find(m => m.id == payData.month)?.name + ' ' + payData.year"></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Nominal</span>
                        <span class="text-lg font-black text-slate-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(payData.nominal)"></span>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-600 mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="$refs.paymentFormQuick.metode_pembayaran.value = 'cash'; $refs.paymentFormQuick.submit()"
                                class="w-full text-left bg-white border-2 border-slate-100 rounded-xl p-4 hover:border-primary-light/30 hover:bg-slate-50/50 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 group-hover:bg-primary-light group-hover:border-primary-light">
                                    <i class="bi bi-cash-coin text-slate-400 group-hover:text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">Tunai</h4>
                                    <p class="text-[10px] text-slate-400">Cash manual</p>
                                </div>
                            </div>
                        </button>
                        <button type="button" @click="$refs.paymentFormQuick.metode_pembayaran.value = 'qris'; $refs.paymentFormQuick.submit()"
                                class="w-full text-left bg-white border-2 border-slate-100 rounded-xl p-4 hover:border-primary-light/30 hover:bg-slate-50/50 transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 group-hover:bg-primary-light group-hover:border-primary-light">
                                    <i class="bi bi-qr-code text-slate-400 group-hover:text-white"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">QRIS</h4>
                                    <p class="text-[10px] text-slate-400">Scan kode</p>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <form x-ref="paymentFormQuick" action="{{ url('administrator/pendatang/kartu-punia/bayar') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_pendatang" :value="payData.id">
                <input type="hidden" name="bulan" :value="payData.month">
                <input type="hidden" name="tahun" :value="payData.year">
                <input type="hidden" name="metode_pembayaran">
            </form>
            <div class="px-6 pb-5 pt-1 text-center">
                <button @click="showPayModal = false" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batal</button>
            </div>
        </div>
    </div>
</div>

@stop
