@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    selectedMonth: '{{ Request::segment(3) ?: date('m') }}',
    selectedYear: '{{ Request::segment(4) ?: date('Y') }}',
    selectedStatus: '{{ $_GET['status'] ?? '' }}',
    
    months: [
        { id: '1', name: 'Januari' }, { id: '2', name: 'Februari' }, { id: '3', name: 'Maret' },
        { id: '4', name: 'April' }, { id: '5', name: 'Mei' }, { id: '6', name: 'Juni' },
        { id: '7', name: 'Juli' }, { id: '8', name: 'Agustus' }, { id: '9', name: 'September' },
        { id: '10', name: 'Oktober' }, { id: '11', name: 'November' }, { id: '12', name: 'Desember' }
    ],

    filter() {
        let url = '{{ url('administrator/datapunia_wajib') }}/' + this.selectedMonth + '/' + this.selectedYear;
        if (this.selectedStatus) url += '?status=' + this.selectedStatus;
        window.location = url;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Iuran Punia Wajib</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring kontribusi iuran wajib dari seluruh unit usaha terdaftar.</p>
        </div>
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-primary-light rounded-lg border border-blue-100">
                <i class="bi bi-calendar3 text-xs"></i>
                <span class="text-[10px] font-black uppercase tracking-widest" x-text="months.find(m => m.id == parseInt(selectedMonth)).name + ' ' + selectedYear"></span>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 bg-primary-light text-white shadow-lg shadow-blue-100 border-none">
            <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="bi bi-cash-stack text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-white/70 uppercase tracking-widest leading-none mb-1">Total Penerimaan</p>
                <p class="text-lg font-black tracking-tight">Rp {{ number_format($datalist->where('status_bayar', 'Completed')->sum('jumlah_dana'), 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                <i class="bi bi-hourglass-split text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Outstanding</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ $datalist->where('status_bayar', 'OnProgress')->count() }} Tagihan</p>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tahun Anggaran</label>
                <input type="number" x-model="selectedYear" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
            </div>
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Filter Status</label>
                <select x-model="selectedStatus" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                    <option value="">Semua Status</option>
                    <option value="1">Completed</option>
                    <option value="2">OnProgress</option>
                    <option value="3">Due Date</option>
                </select>
            </div>
            <button @click="filter()" 
                    class="h-10 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all transform hover:-translate-y-0.5 shadow-md">
                <i class="bi bi-funnel mr-1.5"></i> Terapkan
            </button>
        </div>

        <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-100">
            <template x-for="month in months" :key="month.id">
                <button @click="selectedMonth = month.id; filter()"
                        :class="selectedMonth == month.id ? 'bg-primary-light text-white shadow-md' : 'text-slate-400 hover:bg-slate-50 border border-transparent hover:border-slate-100 shadow-sm'"
                        class="px-4 py-2 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                    <span x-text="month.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pemberi Dana</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl & Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($datalist as $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-[10px]">
                                    {{ substr($rows->nama_usaha, 0, 1) }}
                                </div>
                                <span class="text-xs font-black text-slate-700 tracking-tight">{{ $rows->nama_usaha }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-primary-light tracking-tight">Rp {{ number_format($rows->jumlah_dana, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-400">{{ $rows->tanggal_pembayaran != "-" ? tgl_indo($rows->tanggal_pembayaran) : 'Belum Bayar' }}</p>
                                @php
                                    $statusColor = match(true) {
                                        str_contains(strtolower($rows->status_bayar), 'complete') => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        str_contains(strtolower($rows->status_bayar), 'progress') => 'bg-amber-50 text-amber-600 border-amber-100',
                                        str_contains(strtolower($rows->status_bayar), 'due') => 'bg-rose-50 text-rose-600 border-rose-100',
                                        default => 'bg-slate-100 text-slate-400 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-tight border {{ $statusColor }}">
                                    {{ $rows->status_bayar }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" target="_blank"
                               class="h-8 w-8 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i class="bi bi-inbox text-3xl text-slate-200 mb-2 block"></i>
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum Ada Data</h3>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
