@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Punia</h1>
        <p class="text-slate-400 text-[10px] mt-1">Rangkuman punia unit usaha & krama tamiu</p>
    </div>

    @php
        $kelianBanjar = Auth::user()->banjar;
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        // === UNIT USAHA PUNIA ===
        if(!$kelianBanjar) {
            $usahaList = collect([]);
            $totalUsaha = 0;
            $usahaSudahBayar = 0;
            $usahaBelumBayar = 0;
            $totalPuniaUsaha = 0;
        } else {
            $usahaList = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->get();
            
            $totalUsaha = $usahaList->count();
            $usahaSudahBayar = 0;
            $usahaBelumBayar = 0;
            $totalPuniaUsaha = 0;
            
            foreach($usahaList as $usaha) {
                $payment = App\Models\Danapunia::where('id_usaha', $usaha->id_usaha)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->where('bulan_punia', $currentMonth)
                    ->where('tahun_punia', $currentYear)
                    ->first();
                
                if($payment) {
                    $usahaSudahBayar++;
                    $totalPuniaUsaha += $payment->jumlah_dana;
                } else {
                    $usahaBelumBayar++;
                }
            }
        }

        // === KRAMA TAMIU PUNIA ===
        $tamiuQuery = App\Models\Pendatang::where('aktif', '1');
        $puniaQueryTamiu = App\Models\PuniaPendatang::where('aktif', '1');
        
        if($kelianBanjar) {
            $tamiuQuery->where('id_data_banjar', $kelianBanjar->id_data_banjar);
            $tamiuIds = App\Models\Pendatang::where('aktif', '1')
                ->where('id_data_banjar', $kelianBanjar->id_data_banjar)
                ->pluck('id_pendatang');
            $puniaQueryTamiu->whereIn('id_pendatang', $tamiuIds);
        }
        
        $totalTamiu = $tamiuQuery->where('status', 'aktif')->count();
        $tamiuSudahBayar = (clone $puniaQueryTamiu)->where('jenis_punia', 'rutin')
            ->where('bulan_tahun', $currentYear.'-'.str_pad($currentMonth, 2, '0', STR_PAD_LEFT))
            ->where('status_pembayaran', 'lunas')->count();
        $totalPuniaTamiu = (clone $puniaQueryTamiu)->where('status_pembayaran', 'lunas')
            ->where('bulan_tahun', 'LIKE', $currentYear.'-'.str_pad($currentMonth, 2, '0', STR_PAD_LEFT).'%')
            ->sum('nominal');
        $tamiuBelumBayar = $totalTamiu - $tamiuSudahBayar;
        if($tamiuBelumBayar < 0) $tamiuBelumBayar = 0;
        
        $totalPuniaGabungan = $totalPuniaUsaha + $totalPuniaTamiu;
    @endphp

    <!-- Total Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Masuk Bulan Ini</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPuniaGabungan, 0, ',', '.') }}</h3>
            
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Unit Usaha</p>
                    <p class="font-bold text-xs">Rp {{ number_format($totalPuniaUsaha, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Krama Tamiu</p>
                    <p class="font-bold text-xs">Rp {{ number_format($totalPuniaTamiu, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ url('administrator/kelian/pendatang') }}" class="bg-white border border-slate-100 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors">
            <div class="h-10 w-10 bg-blue-50 rounded-lg flex items-center justify-center mx-auto mb-2">
                <i class="bi bi-people text-[#00a6eb] text-lg"></i>
            </div>
            <p class="text-xs font-bold text-slate-700">Data Krama Tamiu</p>
            <p class="text-[9px] text-slate-400 mt-0.5">{{ $totalTamiu }} orang aktif</p>
        </a>
        <a href="{{ url('administrator/kelian/data_usaha') }}" class="bg-white border border-slate-100 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors">
            <div class="h-10 w-10 bg-amber-50 rounded-lg flex items-center justify-center mx-auto mb-2">
                <i class="bi bi-building text-amber-600 text-lg"></i>
            </div>
            <p class="text-xs font-bold text-slate-700">Unit Usaha</p>
            <p class="text-[9px] text-slate-400 mt-0.5">{{ $totalUsaha }} usaha aktif</p>
        </a>
    </div>

    <!-- Unit Usaha Summary -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Punia Unit Usaha</h3>
            <span class="text-[10px] font-medium text-slate-400">{{ $usahaSudahBayar }}/{{ $totalUsaha }} bayar</span>
        </div>
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
                        <p class="text-[10px] text-slate-400">
                            @if($payment)
                            Rp {{ number_format($payment->jumlah_dana, 0, ',', '.') }}
                            <span class="text-[9px] text-slate-300 ml-1">{{ $payment->metode_pembayaran ? '• '.strtoupper($payment->metode_pembayaran) : '' }}</span>
                            @else
                            Min. Rp {{ number_format($usaha->minimal_bayar ?? 0, 0, ',', '.') }}
                            @endif
                        </p>
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

    <!-- Krama Tamiu Summary -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Punia Krama Tamiu</h3>
            <span class="text-[10px] font-medium text-slate-400">{{ $tamiuSudahBayar }}/{{ $totalTamiu }} bayar</span>
        </div>
        
        @php
            $tamiuPaymentsRecent = App\Models\PuniaPendatang::with('pendatang')
                ->where('status_pembayaran', 'lunas')
                ->where('aktif', '1');
            
            if($kelianBanjar) {
                $tamiuPaymentsRecent->whereHas('pendatang', function($q) use ($kelianBanjar) {
                    $q->where('id_data_banjar', $kelianBanjar->id_data_banjar);
                });
            }
            $tamiuPaymentsRecent = $tamiuPaymentsRecent->orderBy('tanggal_bayar', 'desc')->limit(15)->get();
        @endphp
        
        @if($tamiuPaymentsRecent->count() > 0)
        <div class="space-y-1.5">
            @foreach($tamiuPaymentsRecent as $pp)
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $pp->pendatang->nama ?? '-' }}</p>
                        <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                            <span>{{ $pp->tanggal_bayar ? $pp->tanggal_bayar->format('d M Y') : '-' }}</span>
                            @if($pp->metode_pembayaran)
                            <span>&middot; {{ strtoupper($pp->metode_pembayaran) }}</span>
                            @endif
                            @if($pp->jenis_punia === 'rutin')
                            <span>&middot; Rutin {{ $pp->bulan_tahun }}</span>
                            @else
                            <span>&middot; {{ $pp->nama_acara }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 shrink-0">
                        +Rp {{ number_format($pp->nominal, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <p class="text-xs text-slate-400">Belum ada pembayaran dari krama tamiu</p>
        </div>
        @endif
    </div>
</div>
@endsection
