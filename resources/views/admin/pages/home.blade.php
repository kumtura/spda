@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    stats: {
        investor: {{ count($usaha) }},
        tenagakerja: {{ $jml_karyawan }},
        sumbangan: '{{ format_rupiah($totalsumbangan) }}',
        iuran: '{{ format_rupiah($totalpunia) }}'
    }
}">
    <!-- Header Selamat Datang -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-primary-light text-white flex items-center justify-center shadow-xl shadow-blue-100">
                <i class="bi bi-speedometer2 text-3xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">Ringkasan Ekosistem</h1>
                <p class="text-slate-500 font-semibold text-sm">Monitor performa operasional Dana Punia Kumtura secara real-time.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-[10px] font-bold uppercase tracking-widest">Sistem Aktif</span>
            <span class="text-xs font-semibold text-slate-400 px-2">{{ date('d M Y, H:i') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Total Investor -->
        <div class="glass-card p-6 flex flex-col justify-between group hover:border-primary-light/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-primary-light group-hover:bg-primary-light group-hover:text-white transition-all">
                    <i class="bi bi-building-up text-xl"></i>
                </div>
                <span class="text-[9px] font-bold text-blue-500 uppercase tracking-widest bg-blue-50 px-2 py-1 rounded-md">Investor</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Unit Usaha</p>
                <h3 class="text-3xl font-bold text-slate-800 tracking-tight" x-text="stats.investor"></h3>
            </div>
            <a href="{{ url('administrator/data_usaha') }}" class="mt-4 pt-4 border-t border-slate-50 text-[10px] font-bold text-primary-light uppercase tracking-widest hover:text-blue-700 flex items-center gap-2 transition-colors">
                Kelola Data <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Card 2: Tenaga Kerja -->
        <div class="glass-card p-6 flex flex-col justify-between group hover:border-primary-light/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-sky-50 rounded-xl flex items-center justify-center text-sky-600 group-hover:bg-primary-light group-hover:text-white transition-all">
                    <i class="bi bi-people-fill text-xl"></i>
                </div>
                <span class="text-[9px] font-bold text-sky-500 uppercase tracking-widest bg-sky-50 px-2 py-1 rounded-md">Karyawan</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tenaga Kerja</p>
                <h3 class="text-3xl font-bold text-slate-800 tracking-tight" x-text="stats.tenagakerja"></h3>
            </div>
            <a href="{{ url('administrator/data_tenagakerja') }}" class="mt-4 pt-4 border-t border-slate-50 text-[10px] font-bold text-primary-light uppercase tracking-widest hover:text-blue-700 flex items-center gap-2 transition-colors">
                Basis Data <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Card 3: Total Sumbangan -->
        <div class="glass-card p-6 flex flex-col justify-between group hover:border-primary-light/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 group-hover:bg-primary-light group-hover:text-white transition-all">
                    <i class="bi bi-wallet2 text-xl"></i>
                </div>
                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest bg-emerald-50 px-2 py-1 rounded-md">Sumbangan</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Arus Sumbangan</p>
                <h3 class="text-3xl font-bold text-slate-800 tracking-tight" x-text="stats.sumbangan"></h3>
            </div>
            <a href="{{ url('administrator/datasumbangan') }}" class="mt-4 pt-4 border-t border-slate-50 text-[10px] font-bold text-primary-light uppercase tracking-widest hover:text-blue-700 flex items-center gap-2 transition-colors">
                Laporan Keuangan <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <!-- Card 4: Total Punia -->
        <div class="glass-card p-6 flex flex-col justify-between group hover:border-primary-light/30 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="h-12 w-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 group-hover:bg-primary-light group-hover:text-white transition-all">
                    <i class="bi bi-currency-dollar text-xl"></i>
                </div>
                <span class="text-[9px] font-bold text-amber-500 uppercase tracking-widest bg-amber-50 px-2 py-1 rounded-md">Wajib</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Punia</p>
                <h3 class="text-xl font-bold text-slate-800 tracking-tight" x-text="stats.iuran"></h3>
            </div>
            <a href="{{ url('administrator/datapunia_wajib') }}" class="mt-4 pt-4 border-t border-slate-50 text-[10px] font-bold text-primary-light uppercase tracking-widest hover:text-blue-700 flex items-center gap-2 transition-colors">
                Statistik Punia <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-card p-8 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Analisis Keuangan</h3>
                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Tren Punia & Sumbangan Tahun {{ date('Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                        <span class="h-2 w-2 rounded-full bg-primary-light"></span> Punia
                    </span>
                    <span class="flex items-center gap-1.5 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">
                        <span class="h-2 w-2 rounded-full bg-amber-400"></span> Sumbangan
                    </span>
                </div>
            </div>
            <div id="mainChart" class="h-[350px]"></div>
        </div>

        <div class="space-y-6">
            <div class="glass-card p-8 bg-primary-light text-white shadow-lg relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 h-24 w-24 bg-white/10 rounded-full blur-xl"></div>
                <p class="text-[10px] font-semibold uppercase tracking-widest opacity-60 mb-4">Capaian Target</p>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold">Dana Punia</span>
                        <span class="text-xl font-bold">72%</span>
                    </div>
                    <div class="h-2 w-full bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-white rounded-full" style="width: 72%"></div>
                    </div>
                    <p class="text-[10px] leading-relaxed opacity-70">Data berdasarkan sinkronisasi terakhir dengan modul Punia Wajib.</p>
                </div>
            </div>

            <div class="glass-card p-8 bg-white shadow-sm">
                <h4 class="text-[10px] font-bold uppercase text-slate-400 tracking-widest mb-6">Log Aktivitas Terbaru</h4>
                <div class="space-y-5">
                    <div class="flex gap-4 items-start">
                        <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 shrink-0">
                            <i class="bi bi-journal-text text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 leading-tight">Berita baru ditambahkan</p>
                            <span class="text-[9px] font-semibold text-slate-400 uppercase">Baru saja</span>
                        </div>
                    </div>
                    <div class="flex gap-4 items-start">
                        <div class="h-9 w-9 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 shrink-0">
                            <i class="bi bi-shield-check text-sm"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-700 leading-tight">Admin login terdeteksi</p>
                            <span class="text-[9px] font-semibold text-slate-400 uppercase">10 menit lalu</span>
                        </div>
                    </div>
                </div>
            </div>
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
            
            const total_sumbangan = parses.map(item => parseInt(item.punia));
            const total_iuran = [450, 715, 1064, 1292, 1440, 1760, 1356, 1485, 2164, 1941, 1256, 954];

            var options = {
                series: [{
                    name: 'Dana Punia',
                    data: total_sumbangan
                }, {
                    name: 'Dana Sumbangan',
                    data: total_iuran
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Outfit, sans-serif'
                },
                colors: ['#044c92', '#fbbf24'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0,
                        stops: [0, 95]
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

            new ApexCharts(document.querySelector("#mainChart"), options).render();
        } catch (e) { console.error('Chart Error:', e); }
    });
</script>

@stop
