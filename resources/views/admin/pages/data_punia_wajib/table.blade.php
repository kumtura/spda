@extends($base_layout)

@section('isi_menu')
@php
    $level = Session::get('level');
    $isMobile = in_array($level, [2, 3, '2', '3']);
    $paidCount = 0; $unpaidCount = 0; $totalPaid = 0;
    foreach($datalist as $d) {
        if($d->jumlah_dana > 0) { $paidCount++; $totalPaid += $d->jumlah_dana; } else { $unpaidCount++; }
    }
@endphp

<div class="{{ $isMobile ? 'px-6 py-4' : '' }} space-y-6" x-data="{ 
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
    <div>
        <h1 class="text-{{ $isMobile ? 'xl' : '2xl' }} font-black text-slate-800 tracking-tight">Iuran Punia Wajib</h1>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Monitoring penerimaan iuran usaha</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-{{ $isMobile ? '2' : '3' }} gap-3">
        <div class="bg-[#00a6eb] rounded-2xl p-4 text-white">
            <p class="text-[9px] font-bold text-white/70 uppercase tracking-widest mb-1">Terkumpul</p>
            <p class="text-{{ $isMobile ? 'sm' : 'lg' }} font-black">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Belum Bayar</p>
            <p class="text-{{ $isMobile ? 'sm' : 'lg' }} font-black text-rose-500">{{ $unpaidCount }} usaha</p>
        </div>
        @if(!$isMobile)
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Lunas</p>
            <p class="text-lg font-black text-emerald-500">{{ $paidCount }} usaha</p>
        </div>
        @endif
    </div>

    <!-- Month Tabs & Year -->
    <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm space-y-3">
        <div class="flex items-center gap-2">
            <input type="number" x-model="selectedYear" class="w-20 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-xs font-bold text-slate-700 outline-none text-center">
            <button @click="filter()" class="bg-slate-900 text-white px-4 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-[#00a6eb] transition-all">Filter</button>
        </div>
        <div class="flex flex-wrap gap-1.5">
            <template x-for="month in months" :key="month.id">
                <button @click="selectedMonth = month.id; filter()"
                        :class="selectedMonth == month.id ? 'bg-[#00a6eb] text-white shadow-md' : 'text-slate-400 bg-slate-50 hover:bg-slate-100'"
                        class="px-3 py-1.5 rounded-lg text-[9px] font-bold uppercase tracking-widest transition-all">
                    <span x-text="month.name"></span>
                </button>
            </template>
        </div>
    </div>

    <!-- Data List -->
    <div class="space-y-3">
        @forelse($datalist as $rows)
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl {{ $rows->jumlah_dana > 0 ? 'bg-emerald-50 text-emerald-500' : 'bg-rose-50 text-rose-400' }} flex items-center justify-center font-black text-sm uppercase">
                        {{ substr($rows->nama_usaha, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $rows->nama_usaha }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ $rows->nama ?? '' }}</p>
                    </div>
                </div>
                @if($rows->jumlah_dana > 0)
                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md uppercase tracking-widest border border-emerald-100">Lunas</span>
                @else
                <span class="text-[9px] font-bold text-rose-500 bg-rose-50 px-2 py-1 rounded-md uppercase tracking-widest border border-rose-100">Belum</span>
                @endif
            </div>
            <div class="flex items-center justify-between text-[10px] text-slate-400 font-medium">
                <span>{{ $rows->jumlah_dana > 0 ? 'Rp ' . number_format($rows->jumlah_dana, 0, ',', '.') : 'Belum ada pembayaran' }}</span>
                @if($rows->tanggal_pembayaran && $rows->tanggal_pembayaran != '-')
                <span>{{ \Carbon\Carbon::parse($rows->tanggal_pembayaran)->format('d M Y') }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-2xl p-8 text-center">
            <i class="bi bi-inbox text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400 font-medium">Belum ada data untuk periode ini</p>
        </div>
        @endforelse
    </div>
</div>

@stop
