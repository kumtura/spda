@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Unit Usaha</h1>
        <p class="text-slate-400 text-[10px] mt-1">Daftar usaha di banjar {{ Auth::user()->banjar ? Auth::user()->banjar->nama_banjar : '' }}</p>
    </div>

    @php
        $kelianBanjar = Auth::user()->banjar;
        
        if(!$kelianBanjar) {
            $usahaList = collect([]);
        } else {
            $usahaList = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                ->join('tb_penanggung_jawab','tb_penanggung_jawab.id_penanggung_jawab','tb_usaha.id_penanggung_jawab')
                ->where('tb_detail_usaha.id_banjar', $kelianBanjar->id_data_banjar)
                ->where('tb_usaha.aktif_status', '1')
                ->select('tb_usaha.*', 'tb_detail_usaha.*', 'tb_penanggung_jawab.nama as nama_pj', 'tb_penanggung_jawab.no_telp')
                ->get();
        }
    @endphp

    <!-- Stats -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center mb-4">
                <i class="bi bi-building text-lg"></i>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Unit Usaha</p>
            <h3 class="text-3xl font-black">{{ $usahaList->count() }} Usaha</h3>
        </div>
    </div>

    <!-- Usaha List -->
    @if($usahaList->count() > 0)
    <div class="space-y-3">
        @foreach($usahaList as $usaha)
        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all">
            <div class="flex items-start gap-4 p-4">
                <div class="h-16 w-16 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                    @if($usaha->logo)
                        @php
                            $logoPath = file_exists(public_path('usaha/icon/'.$usaha->logo)) 
                                ? 'usaha/icon/'.$usaha->logo 
                                : 'storage/usaha/icon/'.$usaha->logo;
                        @endphp
                        <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                    @else
                        <i class="bi bi-building text-slate-300 text-2xl"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-black text-slate-800 mb-1">{{ $usaha->nama_usaha }}</h3>
                    <p class="text-[10px] text-slate-500 mb-2">{{ $usaha->alamat_usaha }}</p>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-[9px] font-bold text-[#00a6eb] bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                            Minimal: Rp {{ number_format($usaha->minimal_bayar ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] text-slate-400">
                        <i class="bi bi-person text-xs"></i>
                        <span>{{ $usaha->nama_pj }}</span>
                        @if($usaha->no_telp)
                        <span>•</span>
                        <i class="bi bi-telephone text-xs"></i>
                        <span>{{ $usaha->no_telp }}</span>
                        @endif
                    </div>
                </div>
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
@endsection
