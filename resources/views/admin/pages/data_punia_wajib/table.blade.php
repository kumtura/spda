@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $monthNames = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    $currentMonthName = $monthNames[(int)$month] ?? '';
@endphp

<div id="admin-page-container" class="space-y-6" x-data="{ 
    selectedMonth: '{{ $month }}',
    selectedYear: '{{ $year }}',
    months: [
        { id: '1', name: 'Jan' }, { id: '2', name: 'Feb' }, { id: '3', name: 'Mar' },
        { id: '4', name: 'Apr' }, { id: '5', name: 'Mei' }, { id: '6', name: 'Jun' },
        { id: '7', name: 'Jul' }, { id: '8', name: 'Agu' }, { id: '9', name: 'Sep' },
        { id: '10', name: 'Okt' }, { id: '11', name: 'Nov' }, { id: '12', name: 'Des' }
    ],
    showRecent: true,
    filter() {
        window.location = '{{ url('administrator/datapunia_wajib') }}/' + this.selectedMonth + '/' + this.selectedYear;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Penerimaan Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Dashboard gabungan penerimaan punia — {{ $currentMonthName }} {{ $year }}</p>
        </div>
    </div>

    <!-- Grand Total Card -->
    <div class="bg-gradient-to-br from-primary-light to-blue-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/10 rounded-full -ml-14 -mb-14"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="bi bi-wallet2 text-xl"></i>
                </div>
                <span class="text-[9px] font-black uppercase bg-white/20 px-3 py-1 rounded-full tracking-widest">{{ $currentMonthName }} {{ $year }}</span>
            </div>
            <p class="text-[10px] uppercase text-white/60 tracking-widest font-bold mb-1">Total Penerimaan Punia</p>
            <h3 class="text-3xl md:text-4xl font-black mb-4">Rp {{ number_format($totalGabungan, 0, ',', '.') }}</h3>
            
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] uppercase tracking-widest font-bold mb-0.5">Unit Usaha</p>
                    <p class="font-black text-lg">Rp {{ number_format($totalUsaha, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] uppercase tracking-widest font-bold mb-0.5">Krama Tamiu</p>
                    <p class="font-black text-lg">Rp {{ number_format($totalTamiu, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Allocation Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Desa Allocation -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="bi bi-building text-emerald-600"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800">Alokasi Desa</h3>
            </div>
            <p class="text-2xl font-black text-emerald-600 mb-2">Rp {{ number_format($totalDesaGabungan, 0, ',', '.') }}</p>
            <div class="space-y-2 text-xs">
                @if($pengaturanUsaha)
                <div class="flex justify-between text-slate-600">
                    <span>Usaha ({{ $pengaturanUsaha->persen_desa }}%)</span>
                    <span class="font-bold">Rp {{ number_format($totalUsahaDesa, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($pengaturanTamiu)
                <div class="flex justify-between text-slate-600">
                    <span>Tamiu ({{ $pengaturanTamiu->persen_desa }}%)</span>
                    <span class="font-bold">Rp {{ number_format($totalTamiuDesa, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Banjar Allocation -->
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-4">
                <div class="h-8 w-8 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class="bi bi-houses text-amber-600"></i>
                </div>
                <h3 class="text-sm font-black text-slate-800">Alokasi Banjar</h3>
            </div>
            <p class="text-2xl font-black text-amber-600 mb-2">Rp {{ number_format($totalBanjarGabungan, 0, ',', '.') }}</p>
            <div class="space-y-2 text-xs">
                @if($pengaturanUsaha)
                <div class="flex justify-between text-slate-600">
                    <span>Usaha ({{ $pengaturanUsaha->persen_banjar }}%)</span>
                    <span class="font-bold">Rp {{ number_format($totalUsahaBanjar, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($pengaturanTamiu)
                <div class="flex justify-between text-slate-600">
                    <span>Tamiu ({{ $pengaturanTamiu->persen_banjar }}%)</span>
                    <span class="font-bold">Rp {{ number_format($totalTamiuBanjar, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Usaha Lunas</p>
            <p class="text-2xl font-black text-emerald-500 tracking-tight">{{ $usahaPaid }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Usaha Belum</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $usahaUnpaid }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Usaha</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $usahaList->count() }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tamiu Lunas</p>
            <p class="text-2xl font-black text-emerald-500 tracking-tight">{{ $tamiuPaid }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tamiu Belum</p>
            <p class="text-2xl font-black text-rose-500 tracking-tight">{{ $tamiuUnpaid }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Tamiu</p>
            <p class="text-2xl font-black text-slate-800 tracking-tight">{{ $pendatangAktif }}</p>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ url('administrator/datapunia_usaha') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary-light/30 transition-all group">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-amber-50 border border-amber-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-amber-100 transition-colors">
                    <i class="bi bi-building text-amber-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-slate-800 tracking-tight">Iuran Unit Usaha</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $usahaPaid }}/{{ $usahaList->count() }} lunas bulan ini</p>
                </div>
                <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light transition-colors"></i>
            </div>
        </a>
        <a href="{{ url('administrator/datapunia_pendatang') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md hover:border-primary-light/30 transition-all group">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-blue-100 transition-colors">
                    <i class="bi bi-people text-primary-light text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-slate-800 tracking-tight">Iuran Krama Tamiu</h3>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $tamiuPaid }}/{{ $pendatangAktif }} lunas bulan ini</p>
                </div>
                <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light transition-colors"></i>
            </div>
        </a>
    </div>

    <!-- Chart Section -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Tren Penerimaan Punia</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Tahun {{ $year }}</p>
            </div>
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
        <div id="puniaChart" class="h-[250px] sm:h-[300px] w-full"></div>
    </div>

    <!-- Month & Year Selector -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center gap-3">
            <input type="number" x-model="selectedYear" class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 text-center transition-all">
            <button @click="filter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md transform hover:-translate-y-0.5">Filter</button>
        </div>
        <div class="flex flex-wrap gap-2 pt-2 border-t border-slate-100">
            <template x-for="m in months" :key="m.id">
                <button @click="selectedMonth = m.id; filter()"
                        :class="selectedMonth == m.id ? 'bg-primary-light text-white shadow-md shadow-blue-100' : 'text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100'"
                        class="px-5 py-2 rounded-xl text-xs font-bold transition-all transform hover:-translate-y-0.5">
                    <span x-text="m.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Recent Payments -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <button @click="showRecent = !showRecent" class="w-full flex items-center justify-between p-5 hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
                <div class="h-9 w-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="bi bi-cash-stack text-emerald-600"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-sm font-black text-slate-800">Riwayat Penerimaan Terbaru</h3>
                    <p class="text-[10px] text-slate-400 font-medium">Gabungan dari semua sumber punia</p>
                </div>
            </div>
            <i class="bi text-slate-400" :class="showRecent ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
        </button>

        <div x-show="showRecent" x-collapse>
            @if($recentAll->count() > 0)
            <div class="border-t border-slate-100 divide-y divide-slate-50">
                @foreach($recentAll as $r)
                <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 {{ $r->sumber === 'usaha' ? 'bg-amber-50' : 'bg-blue-50' }} rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi {{ $r->sumber === 'usaha' ? 'bi-building text-amber-600' : 'bi-person text-primary-light' }} text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-800">{{ $r->nama }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-0.5">
                                <span>{{ $r->tanggal ? \Carbon\Carbon::parse($r->tanggal)->format('d M Y') : '-' }}</span>
                                @if($r->metode_pembayaran)
                                <span>&middot; {{ strtoupper($r->metode_pembayaran) }}</span>
                                @endif
                                <span>&middot; {{ $r->sumber === 'usaha' ? 'Unit Usaha' : 'Krama Tamiu' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold text-emerald-600">+Rp {{ number_format($r->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="border-t border-slate-100 px-6 py-8 text-center">
                <p class="text-sm text-slate-400">Belum ada riwayat penerimaan</p>
            </div>
            @endif
        </div>
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
                colors: ['#3b82f6'],
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
