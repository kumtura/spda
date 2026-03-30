@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Donasi</h1>
        <p class="text-slate-400 text-[10px] mt-1">Monitor donasi dari banjar</p>
    </div>

    @php
        $kelianBanjar = Auth::user()->banjar;
        
        if(!$kelianBanjar) {
            // If no banjar assigned, show empty data
            $usahaIds = collect([]);
            $donasi = collect([]);
            $totalDonasi = 0;
            $totalDonatur = 0;
        } else {
            // Get all usaha in this banjar
            $usahaIds = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->pluck('tb_usaha.id_usaha');
            
            // Get donations from usaha in this banjar
            $donasi = App\Models\Sumbangan::with('programDonasi')
                ->whereIn('id_usaha', $usahaIds)
                ->where('aktif', '1')
                ->where('status_pembayaran', 'completed')
                ->orderBy('id_sumbangan_sukarela', 'desc')
                ->get();
            
            $totalDonasi = $donasi->sum('nominal');
            $totalDonatur = $donasi->count();
        }
        
        // Group by program
        $programs = \App\Models\ProgramDonasi::where('aktif', '1')->get();
    @endphp

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-heart-pulse text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Total Kontribusi</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Donasi Banjar {{ $kelianBanjar ? $kelianBanjar->nama_banjar : 'Adat' }}</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Total Donatur</p>
                    <p class="font-bold">{{ $totalDonatur }} Transaksi</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Unit Usaha</p>
                    <p class="font-bold">{{ $usahaIds->count() }} Usaha</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Donation List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Riwayat Donasi</h3>
        @if($donasi->count() > 0)
        <div class="space-y-2.5">
            @foreach($donasi as $d)
            @php
                $usaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                    ->where('tb_usaha.id_usaha', $d->id_usaha)->first();
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl p-3.5">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-heart-pulse text-emerald-500 text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5">{{ $usaha->nama_usaha ?? 'Unit Usaha' }}</p>
                        <p class="text-[10px] text-slate-400 mb-1">{{ $d->programDonasi->nama_program ?? 'Program Donasi' }}</p>
                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($d->tanggal)->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($d->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-heart text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada donasi dari banjar ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
