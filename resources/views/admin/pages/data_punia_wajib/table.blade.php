@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $paidCount = 0; $unpaidCount = 0; $totalPaid = 0;
    foreach($datalist as $d) {
        if($d->jumlah_dana > 0) { $paidCount++; $totalPaid += $d->jumlah_dana; } else { $unpaidCount++; }
    }
@endphp

<div id="admin-page-container" class="space-y-6" x-data="{ 
    selectedMonth: '{{ Request::segment(3) ?: date('m') }}',
    selectedYear: '{{ Request::segment(4) ?: date('Y') }}',
    months: [
        { id: '1', name: 'Jan' }, { id: '2', name: 'Feb' }, { id: '3', name: 'Mar' },
        { id: '4', name: 'Apr' }, { id: '5', name: 'Mei' }, { id: '6', name: 'Jun' },
        { id: '7', name: 'Jul' }, { id: '8', name: 'Agu' }, { id: '9', name: 'Sep' },
        { id: '10', name: 'Okt' }, { id: '11', name: 'Nov' }, { id: '12', name: 'Des' }
    ],
    filter() {
        window.location = '{{ url('administrator/datapunia_wajib') }}/' + this.selectedMonth + '/' + this.selectedYear;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Penerimaan Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring penerimaan punia dari unit usaha desa adat.</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Terkumpul Bulan Ini</p>
            <p class="text-lg md:text-2xl font-black tracking-tight">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Lunas</p>
            <p class="text-lg md:text-2xl font-black text-emerald-500 tracking-tight">{{ $paidCount }} <span class="text-sm">usaha</span></p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm col-span-2 md:col-span-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Belum Bayar</p>
            <p class="text-lg md:text-2xl font-black text-rose-500 tracking-tight">{{ $unpaidCount }} <span class="text-sm">usaha</span></p>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm mb-6">
        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Tren Penerimaan Punia WP</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Tahun {{ date('Y') }}</p>
            </div>
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
        <div id="puniaChart" class="h-[250px] sm:h-[300px] w-full"></div>
    </div>

    <!-- Month Tabs & Year -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4 mb-6">
        <div class="flex items-center gap-3">
            <input type="number" x-model="selectedYear" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 text-center transition-all">
            <button @click="filter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md transform hover:-translate-y-0.5">Filter</button>
        </div>
        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
            <template x-for="month in months" :key="month.id">
                <button @click="selectedMonth = month.id; filter()"
                        :class="selectedMonth == month.id ? 'bg-primary-light text-white shadow-md shadow-blue-100' : 'text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100'"
                        class="px-5 py-2 rounded-xl text-xs font-bold transition-all transform hover:-translate-y-0.5">
                    <span x-text="month.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Data List -->
    <div class="space-y-4">
        @forelse($datalist as $rows)
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-3">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl {{ $rows->jumlah_dana > 0 ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-rose-50 text-rose-500 border border-rose-100' }} flex items-center justify-center font-black text-lg uppercase shadow-sm">
                        {{ substr($rows->nama_usaha, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-base font-black text-slate-800 tracking-tight">{{ $rows->nama_usaha }}</p>
                        <p class="text-xs font-bold text-slate-500">{{ $rows->nama ?? '' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    @if($rows->jumlah_dana > 0)
                    <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg uppercase tracking-widest border border-emerald-100 shadow-sm">Lunas</span>
                    @else
                    <span class="text-[9px] font-black text-rose-500 bg-rose-50 px-3 py-1.5 rounded-lg uppercase tracking-widest border border-rose-100 shadow-sm">Belum</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center justify-between text-xs font-bold text-slate-500 bg-slate-50 px-4 py-3 rounded-xl border border-slate-100">
                <span class="{{ $rows->jumlah_dana > 0 ? 'text-slate-700 font-black' : '' }} text-sm">{{ $rows->jumlah_dana > 0 ? 'Rp ' . number_format($rows->jumlah_dana, 0, ',', '.') : 'Belum ada pembayaran' }}</span>
                @if($rows->tanggal_pembayaran && $rows->tanggal_pembayaran != '-')
                <span class="text-[10px] uppercase tracking-widest text-slate-400"><i class="bi bi-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($rows->tanggal_pembayaran)->format('d M Y') }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-2xl p-12 text-center border dashed border-slate-200">
            <div class="h-16 w-16 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-sm mb-4 border border-slate-100">
                <i class="bi bi-inbox text-3xl text-slate-300"></i>
            </div>
            <p class="text-sm font-black text-slate-600 tracking-tight mb-1">Tidak Ada Data</p>
            <p class="text-xs text-slate-400 font-medium">Belum ada data untuk periode ini.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        try {
            const response = await fetch('{{ url('administrator/get_danapunia_range') }}');
            if (!response.ok) throw new Error('Load failed');
            
            const rawData = await response.json();
            const parses = JSON.parse(rawData.total_punia);
            
            const total_punia = parses.map(item => parseInt(item.punia));

            var options = {
                series: [{
                    name: 'Penerimaan Punia',
                    data: total_punia
                }],
                chart: {
                    type: 'area',
                    height: window.innerWidth < 640 ? 250 : 300,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Inter, sans-serif'
                },
                colors: ['#3b82f6'], // matching default tailwind blue-500 typical for primary
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0,
                        stops: [0, 100]
                    }
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    labels: { style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#94a3b8', fontSize: '10px', fontWeight: 600 },
                        formatter: val => "Rp " + val.toLocaleString()
                    }
                },
                grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
                legend: { show: false },
                tooltip: { theme: 'light', x: { show: false } }
            };

            if(document.querySelector("#puniaChart")) {
                new ApexCharts(document.querySelector("#puniaChart"), options).render();
            }
        } catch (e) { console.error('Chart Error:', e); }
    });
</script>

@stop
