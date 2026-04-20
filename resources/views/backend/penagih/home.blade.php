@extends('mobile_layout')

@section('isi_menu')
<div class="px-6 py-4 space-y-8">
    <!-- Welcome Text -->
    <div>
        <h1 class="text-2xl font-black tracking-tight text-slate-800 leading-none mb-1">Rahajeng,</h1>
        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">{{ session('namapt') ?: auth()->user()?->name ?: '-' }} <span class="text-[#00a6eb]">| Penagih Iuran</span></p>
    </div>

    <!-- Status Banjar Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-3xl p-6 text-white shadow-lg shadow-[#00a6eb]/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-xs font-bold text-white/80 uppercase tracking-widest mb-1">Wilayah Penagihan</p>
        <h2 class="text-2xl font-black mb-4">Banjar {{ $banjar ? $banjar->nama_banjar : '-' }}</h2>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Krama Tamiu</p>
                <p class="text-xl font-black">{{ $pendatangCount }}</p>
            </div>
            <div class="bg-white/20 backdrop-blur-md rounded-2xl p-3 border border-white/10 text-center">
                <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Unit Usaha</p>
                <p class="text-xl font-black">{{ $usahaCount }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 gap-4">
        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm">
            <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mb-3">
                <i class="bi bi-exclamation-circle text-xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Belum Bayar</p>
            <h3 class="font-black text-slate-800 text-lg">{{ $tagihanBelumBayar }}</h3>
            <p class="text-[9px] text-slate-400">Tagihan</p>
        </div>
        <div class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-3">
                <i class="bi bi-wallet2 text-xl"></i>
            </div>
            <p class="text-slate-400 text-[9px] font-bold uppercase tracking-widest mb-0.5">Terkumpul</p>
            <h3 class="font-black text-slate-800 text-lg">Rp {{ number_format($totalTerkumpul ?? 0, 0, ',', '.') }}</h3>
            <p class="text-[9px] text-slate-400">Bulan ini</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div>
        <h3 class="font-black text-slate-800 tracking-tight mb-4">Menu Penagihan</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ url('administrator/penagih/pendatang') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-people text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Krama Tamiu</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Data pendatang & tagihan</p>
            </a>

            <a href="{{ url('administrator/penagih/usaha') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
                <div class="w-12 h-12 bg-blue-50 text-[#00a6eb] rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-building text-2xl"></i>
                </div>
                <h3 class="font-bold text-slate-800 text-sm mb-1">Unit Usaha</h3>
                <p class="text-slate-400 text-[10px] leading-tight">Punia wajib usaha</p>
            </a>
        </div>
    </div>

    <!-- Recent Payments -->
    @php
        $recentPayments = collect([]);
        if($banjar) {
            $pendatangIds = App\Models\Pendatang::where('id_data_banjar', $banjar->id_data_banjar)
                ->where('aktif', '1')->pluck('id_pendatang');
            $recentPayments = App\Models\PuniaPendatang::with('pendatang')
                ->whereIn('id_pendatang', $pendatangIds)
                ->where('status_pembayaran', 'lunas')
                ->where('aktif', '1')
                ->orderBy('tanggal_bayar', 'desc')
                ->limit(5)->get();
        }
    @endphp
    @if($recentPayments->count() > 0)
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Iuran Masuk Terbaru</h3>
        <div class="space-y-1.5">
            @foreach($recentPayments as $payment)
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $payment->pendatang->nama ?? '-' }}</p>
                        <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                            <span>{{ $payment->tanggal_bayar ? $payment->tanggal_bayar->format('d M Y') : '-' }}</span>
                            @if($payment->metode_pembayaran)
                            <span>&middot; {{ strtoupper($payment->metode_pembayaran) }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 shrink-0">
                        +Rp {{ number_format($payment->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
