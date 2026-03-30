@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Punia</h1>
        <p class="text-slate-400 text-[10px] mt-1">Monitor punia wajib banjar</p>
    </div>

    @php
        $kelianBanjar = Auth::user()->banjar;
        
        if(!$kelianBanjar) {
            // If no banjar assigned, show empty data
            $usahaList = collect([]);
            $totalUsaha = 0;
            $sudahBayar = 0;
            $belumBayar = 0;
            $totalPunia = 0;
        } else {
            // Get all usaha in this banjar
            $usahaList = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->get();
            
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            // Calculate stats for current month
            $totalUsaha = $usahaList->count();
            $sudahBayar = 0;
            $belumBayar = 0;
            $totalPunia = 0;
            
            foreach($usahaList as $usaha) {
                $payment = App\Models\Danapunia::where('id_usaha', $usaha->id_usaha)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->where('bulan_punia', $currentMonth)
                    ->where('tahun_punia', $currentYear)
                    ->first();
                
                if($payment) {
                    $sudahBayar++;
                    $totalPunia += $payment->jumlah_dana;
                } else {
                    $belumBayar++;
                }
            }
        }
    @endphp

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Bulan Ini</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPunia, 0, ',', '.') }}</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                    <p class="font-bold">{{ $sudahBayar }}/{{ $totalUsaha }} Usaha</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                    <p class="font-bold">{{ $belumBayar }} Usaha</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Usaha List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Daftar Unit Usaha</h3>
        @if($usahaList->count() > 0)
        <div class="space-y-2.5">
            @foreach($usahaList as $usaha)
            @php
                $payment = App\Models\Danapunia::where('id_usaha', $usaha->id_usaha)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->where('bulan_punia', $currentMonth)
                    ->where('tahun_punia', $currentYear)
                    ->first();
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl p-3.5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                        @if($usaha->logo)
                            @php
                                $logoPath = file_exists(public_path('usaha/icon/'.$usaha->logo)) 
                                    ? 'usaha/icon/'.$usaha->logo 
                                    : 'storage/usaha/icon/'.$usaha->logo;
                            @endphp
                            <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                        @else
                            <i class="bi bi-building text-slate-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5">{{ $usaha->nama_usaha }}</p>
                        <p class="text-[10px] text-slate-400">Minimal: Rp {{ number_format($usaha->minimal_bayar ?? 0, 0, ',', '.') }}</p>
                    </div>
                    @if($payment)
                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 shrink-0">Lunas</span>
                    @else
                    <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100 shrink-0">Belum</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-building text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada unit usaha di banjar ini</p>
        </div>
        @endif
    </div>
</div>
@endsection
