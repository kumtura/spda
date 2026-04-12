@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $paidCount = $pendatangList->where('payment_status', 'lunas')->count();
    $unpaidCount = $pendatangList->where('payment_status', 'belum')->count();
    $totalPaid = $pendatangList->where('payment_status', 'lunas')->sum(function($p) {
        return $p->payment_data ? $p->payment_data->nominal : 0;
    });
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
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }
        window.location = url;
    },
    
    // Quick Pay Modal
    showPayModal: false,
    payData: {
        id: null,
        name: '',
        month: '',
        year: '',
        nominal: 0
    },
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

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-indigo-600 rounded-2xl p-5 text-white shadow-md shadow-indigo-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Terkumpul Periode Ini</p>
            <p class="text-lg md:text-2xl font-black tracking-tight">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Sudah Bayar</p>
            <p class="text-lg md:text-2xl font-black text-emerald-500 tracking-tight">{{ $paidCount }} <span class="text-sm">orang</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm col-span-2 md:col-span-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Belum Bayar</p>
            <p class="text-lg md:text-2xl font-black text-rose-500 tracking-tight">{{ $unpaidCount }} <span class="text-sm">orang</span></p>
        </div>
    </div>

    <!-- Month & Year Selector -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4 mb-6">
        <div class="flex flex-wrap items-center gap-3">
            <input type="number" x-model="selectedYear" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 text-center transition-all">
            
            <select x-model="filterBanjar" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all">
                <option value="">Semua Banjar</option>
                @foreach($banjarList as $b)
                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                @endforeach
            </select>

            <div class="relative flex-1 min-w-[200px]">
                <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" x-model="searchQuery" @keydown.enter="filter()" placeholder="Cari nama atau NIK..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-indigo-500/5 transition-all">
            </div>

            <button @click="filter()" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-600 transition-all shadow-md transform hover:-translate-y-0.5">Filter</button>
        </div>
        
        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
            <template x-for="month in months" :key="month.id">
                <button @click="selectedMonth = month.id; filter()"
                        :class="selectedMonth == month.id ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100'"
                        class="px-5 py-2 rounded-xl text-xs font-bold transition-all transform hover:-translate-y-0.5">
                    <span x-text="month.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Data List -->
    <div class="space-y-3">
        @forelse($pendatangList as $p)
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl {{ $p->payment_status == 'lunas' ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-rose-50 text-rose-500 border border-rose-100' }} flex items-center justify-center font-black text-lg uppercase shadow-sm shrink-0">
                        {{ substr($p->nama, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-black text-slate-800 tracking-tight truncate">{{ $p->nama }}</p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $p->banjar->nama_banjar ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-4 bg-slate-50 sm:bg-transparent p-3 sm:p-0 rounded-xl">
                    <div class="text-right">
                        @if($p->payment_status == 'lunas')
                            <p class="text-sm font-black text-slate-800 leading-none">Rp {{ number_format($p->payment_data->nominal, 0, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mt-1">Lunas</p>
                        @else
                            <p class="text-sm font-black text-slate-400 leading-none italic">Rp {{ number_format($p->punia_rutin_bulanan, 0, ',', '.') }}</p>
                            <p class="text-[9px] font-bold text-rose-500 uppercase tracking-widest mt-1">Belum Bayar</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-2">
                        @if($p->payment_status == 'lunas')
                            <a href="{{ url('administrator/pendatang/kartu-punia/'.$p->id_pendatang.'?year='.$year) }}" class="h-9 w-9 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:text-indigo-600 hover:border-indigo-100 transition-all">
                                <i class="bi bi-receipt"></i>
                            </a>
                        @else
                            <button @click="openPayModal('{{ $p->id_pendatang }}', '{{ $p->nama }}', selectedMonth, selectedYear, {{ $p->punia_rutin_bulanan }})" 
                                    class="h-9 px-4 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-sm">
                                Bayar
                            </button>
                        @endif
                        <a href="{{ url('administrator/pendatang/detail/'.$p->id_pendatang) }}" class="h-9 w-9 bg-white border border-slate-200 text-slate-400 rounded-xl flex items-center justify-center hover:text-indigo-600 hover:border-indigo-100 transition-all">
                            <i class="bi bi-chevron-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-2xl p-12 text-center border dashed border-slate-200">
            <div class="h-16 w-16 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 border border-slate-100">
                <i class="bi bi-people text-3xl text-slate-300"></i>
            </div>
            <p class="text-sm font-black text-slate-600 tracking-tight mb-1">Tidak Ada Data</p>
            <p class="text-xs text-slate-400 font-medium">Belum ada data pendatang untuk filter ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Quick Pay Modal -->
    <div x-show="showPayModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white rounded-3xl w-full max-w-sm overflow-hidden shadow-2xl transform"
             @click.away="showPayModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-8 scale-95"
             x-transition:enter-end="translate-y-0 scale-100">
            
            <div class="p-6 text-center">
                <div class="h-16 w-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-emerald-100">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Catat Pembayaran</h3>
                <p class="text-xs text-slate-400 font-medium px-4 mt-1">Konfirmasi pembayaran iuran bulanan untuk warga pendatang.</p>
            </div>

            <div class="px-6 pb-6 space-y-4">
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/50">
                    <div class="flex justify-between mb-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pendatang</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Periode</span>
                    </div>
                    <div class="flex justify-between items-start gap-4">
                        <p class="text-sm font-black text-slate-800 truncate" x-text="payData.name"></p>
                        <p class="text-sm font-black text-slate-800 shrink-0" x-text="months.find(m => m.id == payData.month)?.name + ' ' + payData.year"></p>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-200/50">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nominal</span>
                            <span class="text-lg font-black text-emerald-600" x-text="'Rp ' + (payData.nominal).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </div>

                <form action="{{ url('administrator/pendatang/kartu-punia/bayar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pendatang" :value="payData.id">
                    <input type="hidden" name="bulan" :value="payData.month">
                    <input type="hidden" name="tahun" :value="payData.year">
                    
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_pembayaran" value="cash" checked class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 group-hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/30 transition-all text-center">
                                <i class="bi bi-cash text-xl mb-1 block text-slate-400 peer-checked:text-emerald-500"></i>
                                <span class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-emerald-600 tracking-widest">Tunai</span>
                            </div>
                        </label>
                        <label class="relative cursor-pointer group">
                            <input type="radio" name="metode_pembayaran" value="qris" class="peer sr-only">
                            <div class="p-4 rounded-2xl border-2 border-slate-100 group-hover:bg-slate-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50/30 transition-all text-center">
                                <i class="bi bi-qr-code-scan text-xl mb-1 block text-slate-400 peer-checked:text-emerald-500"></i>
                                <span class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-emerald-600 tracking-widest">QRIS</span>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="showPayModal = false" class="px-6 py-3.5 rounded-2xl bg-slate-100 text-slate-600 font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="px-6 py-3.5 rounded-2xl bg-emerald-500 text-white font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-200">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
