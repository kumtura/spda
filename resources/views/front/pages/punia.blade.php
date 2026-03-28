@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ activeTab: 'pemasukan' }">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Dana Punia</h2>
        <p class="text-[10px] text-slate-400 mt-1">Transparansi pengelolaan dana desa adat</p>
    </div>

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Terverifikasi</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Dana Terkumpul</p>
            <h3 class="text-3xl font-black">Rp {{ number_format($total_punia, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-2xl border border-slate-100 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-slate-800">Alokasi Dana</h4>
            
            <!-- Download Button with Dropdown -->
            <div x-data="{ showMonthPicker: false }" class="relative">
                <button @click="showMonthPicker = !showMonthPicker" 
                        class="h-8 w-8 bg-slate-50 hover:bg-[#00a6eb] text-slate-600 hover:text-white rounded-lg flex items-center justify-center transition-all border border-slate-200 hover:border-[#00a6eb]">
                    <i class="bi bi-download text-sm"></i>
                </button>
                
                <!-- Month Picker Dropdown -->
                <div x-show="showMonthPicker" 
                     @click.away="showMonthPicker = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 top-10 w-48 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50"
                     style="display: none;">
                    <div class="px-3 py-2 border-b border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Pilih Bulan</p>
                    </div>
                    <div class="max-h-64 overflow-y-auto">
                        @php
                            $months = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                            $currentYear = date('Y');
                        @endphp
                        @foreach($months as $num => $name)
                        <a href="{{ route('public.punia.download', ['month' => $num, 'year' => $currentYear]) }}" 
                           class="block px-3 py-2 text-xs text-slate-600 hover:bg-slate-50 hover:text-[#00a6eb] transition-colors">
                            <i class="bi bi-file-earmark-pdf text-rose-500 mr-2"></i>{{ $name }} {{ $currentYear }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        @if($total_pengeluaran > 0)
        <!-- Donut Chart -->
        <div class="flex items-center justify-center mb-6">
            <div class="relative w-48 h-48">
                <canvas id="puniaChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <p class="text-[9px] text-slate-400 uppercase">Total Terpakai</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div class="space-y-2">
            @foreach($kategori_punia as $index => $kat)
            @php
                $colors = ['#00a6eb', '#60a5fa', '#34d399', '#fbbf24', '#f87171'];
                $color = $colors[$index % count($colors)];
                $percentage = $total_pengeluaran > 0 ? number_format(($kat->alokasi->sum('nominal') / $total_pengeluaran) * 100, 0) : 0;
            @endphp
            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                    <span class="h-3 w-3 rounded-full" style="background-color: {{ $color }}"></span>
                    <span class="text-slate-600">{{ $kat->nama_kategori }}</span>
                </div>
                <span class="font-bold text-slate-800">{{ $percentage }}%</span>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="py-10 text-center">
            <div class="h-48 w-48 mx-auto mb-4 flex items-center justify-center">
                <i class="bi bi-pie-chart text-6xl text-slate-200"></i>
            </div>
            <p class="text-xs text-slate-400">Belum ada data alokasi dana</p>
        </div>
        @endif
    </div>

    <!-- Tabs -->
    <div class="bg-slate-50 rounded-xl p-1 flex gap-1">
        <button @click="activeTab = 'pemasukan'" 
                :class="activeTab === 'pemasukan' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500'"
                class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">
            Pemasukan
        </button>
        <button @click="activeTab = 'pengeluaran'" 
                :class="activeTab === 'pengeluaran' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500'"
                class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">
            Pengeluaran
        </button>
    </div>

    <!-- Pemasukan List -->
    <div x-show="activeTab === 'pemasukan'" x-transition class="space-y-3">
        <h4 class="text-sm font-bold text-slate-800">Riwayat Pemasukan</h4>
        @forelse($pemasukan as $item)
        <div class="bg-white rounded-xl border border-slate-100 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <i class="bi bi-arrow-down-circle text-emerald-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800">{{ $item->nama_donatur }}</p>
                    <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->translatedFormat('d M Y') }}</p>
                </div>
            </div>
            <p class="text-sm font-black text-emerald-600">+Rp {{ number_format($item->jumlah_dana, 0, ',', '.') }}</p>
        </div>
        @empty
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada pemasukan tercatat</p>
        </div>
        @endforelse
    </div>

    <!-- Pengeluaran List -->
    <div x-show="activeTab === 'pengeluaran'" x-transition class="space-y-4" x-data="{ selectedKategori: 'all' }">
        <h4 class="text-sm font-bold text-slate-800">Riwayat Pengeluaran</h4>
        
        <!-- Category Filter Pills -->
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
            <button @click="selectedKategori = 'all'" 
                    :class="selectedKategori === 'all' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all active:scale-95">
                Semua
            </button>
            @foreach($kategori_punia as $kat)
            <button @click="selectedKategori = '{{ $kat->id_kategori_punia }}'" 
                    :class="selectedKategori === '{{ $kat->id_kategori_punia }}' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all active:scale-95">
                {{ $kat->nama_kategori }}
            </button>
            @endforeach
        </div>

        <!-- Pengeluaran Items -->
        <div class="space-y-3">
            @forelse($pengeluaran as $item)
            <a href="{{ route('public.punia.alokasi.detail', $item->id_alokasi_punia) }}" 
               x-show="selectedKategori === 'all' || selectedKategori === '{{ $item->id_kategori_punia }}'"
               x-transition
               class="block bg-white rounded-xl border border-slate-100 p-4 hover:shadow-md hover:border-[#00a6eb]/30 transition-all group">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 bg-rose-50 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-arrow-up-circle text-rose-500 text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-1 group-hover:text-[#00a6eb] transition-colors">{{ $item->judul }}</p>
                        <p class="text-[10px] text-slate-400 mb-2">{{ $item->kategori->nama_kategori ?? '-' }} • {{ \Carbon\Carbon::parse($item->tanggal_alokasi)->translatedFormat('d M Y') }}</p>
                        <p class="text-sm font-black text-rose-600">-Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div class="shrink-0 self-center">
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-colors"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
                <p class="text-xs text-slate-400">Belum ada pengeluaran tercatat</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- CTA -->
    <div x-data="{ showModal: false }">
        <button @click="showModal = true" type="button" class="block w-full bg-white border border-[#00a6eb]/20 rounded-2xl p-4 shadow-sm group hover:shadow-md transition-shadow text-left">
            <div class="flex items-center gap-3">
                <span class="text-[9px] font-bold text-[#00a6eb] uppercase bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Punia</span>
                <h3 class="text-slate-800 font-bold text-sm leading-tight flex-1">Salurkan Dana Punia Sekarang</h3>
                <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-[#00a6eb] border border-blue-100 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors shrink-0">
                    <i class="bi bi-arrow-right text-sm"></i>
                </div>
            </div>
        </button>

        <!-- Modal -->
        <div x-show="showModal" 
             x-cloak
             @click.self="showModal = false"
             @keydown.escape.window="showModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.stop 
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <!-- Header -->
                <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <button @click="showModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                    <div class="relative">
                        <h3 class="text-xl font-black">Salurkan Dana Punia</h3>
                        <p class="text-white/80 text-xs font-medium mt-1">Pilih kategori Anda untuk melanjutkan</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-3 overflow-y-auto no-scrollbar max-h-[60vh]">
                    <!-- Masyarakat Umum Option -->
                    <a href="{{ route('public.punia.pembayaran') }}" 
                       class="block bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg hover:shadow-blue-500/5 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                                <i class="bi bi-people-fill text-slate-400 text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Masyarakat Umum</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Untuk krama desa dan masyarakat umum yang ingin berkontribusi</p>
                                <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Bayar Sekarang</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Unit Usaha Option -->
                    <a href="{{ route('login') }}" 
                       class="block bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg hover:shadow-blue-500/5 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                                <i class="bi bi-shop text-slate-400 text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Unit Usaha / Investor</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Gunakan akun bisnis Anda untuk penyaluran dana punia resmi</p>
                                <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Login Terlebih Dahulu</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="px-6 pb-6 pt-2">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-slate-400 text-lg shrink-0"></i>
                            <p class="text-[10px] text-slate-500 leading-relaxed">Penggunaan dana punia akan ditampilkan secara transparan untuk akuntabilitas kepada masyarakat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('puniaChart');
    if (ctx) {
        const chartData = @json($chart_data);
        const totalPengeluaran = {{ $total_pengeluaran }};
        
        // Only render chart if there's data
        if (totalPengeluaran > 0 && chartData.length > 0) {
            const colors = ['#00a6eb', '#60a5fa', '#34d399', '#fbbf24', '#f87171'];
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: chartData.map(d => d.label),
                    datasets: [{
                        data: chartData.map(d => d.value),
                        backgroundColor: colors.slice(0, chartData.length),
                        borderWidth: 0,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 12, weight: 'bold' },
                            bodyFont: { size: 11 },
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(0);
                                    return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endsection
