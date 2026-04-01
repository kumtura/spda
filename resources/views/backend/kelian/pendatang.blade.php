@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Pendatang</h1>
            <p class="text-slate-400 text-[10px] mt-1">Kelola data pendatang dan punia</p>
        </div>
        <a href="{{ url('administrator/kelian/pendatang/setting') }}" class="text-xs font-bold text-[#00a6eb]">
            Atur Punia Global
        </a>
    </div>

    @php
        $pendatangList = App\Models\Pendatang::with('banjar')->where('aktif', '1')->orderBy('created_at', 'desc')->get();
        $totalPendatang = $pendatangList->where('status', 'aktif')->count();
        $totalTagihanBelumBayar = App\Models\PuniaPendatang::where('status_pembayaran', 'belum_bayar')
            ->where('aktif', '1')
            ->count();
    @endphp

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </div>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Pendatang Aktif</p>
            <h3 class="text-3xl font-black mb-3">{{ $totalPendatang }} Orang</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Tagihan Belum Bayar</p>
                    <p class="font-bold">{{ $totalTagihanBelumBayar }} Tagihan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Button - Floating -->
    <a href="{{ url('administrator/kelian/pendatang/create') }}" class="fixed bottom-20 right-4 z-50 bg-[#00a6eb] text-white h-12 px-5 rounded-full font-bold text-sm shadow-lg flex items-center gap-2 hover:bg-[#0090d0] transition-colors">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah</span>
    </a>

    <!-- Punia Acara Section -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Punia Acara</h3>
            <a href="{{ url('administrator/kelian/pendatang/create-acara') }}" 
               class="text-[10px] font-bold text-[#00a6eb] flex items-center gap-1">
                <i class="bi bi-plus-lg"></i>
                <span>Buat Acara</span>
            </a>
        </div>
        @if(isset($acaraList) && $acaraList->count() > 0)
        <div class="space-y-2">
            @foreach($acaraList as $acara)
            @php
                $totalPendatang = $acara->puniaPendatang->count();
                $sudahBayar = $acara->puniaPendatang->where('status_pembayaran', 'lunas')->count();
                $persen = $totalPendatang > 0 ? round(($sudahBayar / $totalPendatang) * 100) : 0;
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <p class="text-xs font-medium text-slate-800 truncate">{{ $acara->nama_acara }}</p>
                            @if($acara->tanggal_acara)
                            <span class="text-[9px] text-slate-400 shrink-0">{{ $acara->tanggal_acara->format('d M Y') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[11px] font-medium text-slate-600">Rp {{ number_format($acara->nominal, 0, ',', '.') }}</span>
                            <span class="text-[9px] text-slate-400">&middot;</span>
                            <span class="text-[9px] text-slate-500">{{ $sudahBayar }}/{{ $totalPendatang }} bayar ({{ $persen }}%)</span>
                        </div>
                    </div>
                    <div class="flex gap-1 shrink-0">
                        <button onclick="toggleAcara({{ $acara->id_acara_punia }})" class="h-7 w-7 text-slate-400 rounded-lg flex items-center justify-center hover:bg-slate-50">
                            <i class="bi bi-toggle-{{ $acara->status === 'aktif' ? 'on' : 'off' }} text-sm"></i>
                        </button>
                        <button onclick="deleteAcara({{ $acara->id_acara_punia }})" class="h-7 w-7 text-slate-400 rounded-lg flex items-center justify-center hover:bg-slate-50">
                            <i class="bi bi-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <p class="text-[10px] text-slate-400">Belum ada punia acara</p>
        </div>
        @endif
    </div>

    <!-- Pendatang List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Daftar Pendatang</h3>
        @if($pendatangList->count() > 0)
        <div class="space-y-2">
            @foreach($pendatangList as $pendatang)
            <a href="{{ url('administrator/kelian/pendatang/detail/'.$pendatang->id_pendatang) }}" class="block bg-white border border-slate-100 rounded-xl hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3 px-3 py-2.5">
                    <div class="h-9 w-9 bg-slate-100 rounded-lg flex items-center justify-center shrink-0 text-slate-500 text-[11px] font-medium">
                        {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $pendatang->nama }}</p>
                        <p class="text-[10px] text-slate-400 truncate">{{ $pendatang->asal }} &middot; {{ $pendatang->nik }}@if($pendatang->banjar) &middot; {{ $pendatang->banjar->nama_banjar }}@endif</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        @php
                            $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->count();
                        @endphp
                        @if($belumBayar > 0)
                        <span class="text-[9px] text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded">
                            {{ $belumBayar }}
                        </span>
                        @endif
                        <i class="bi bi-chevron-right text-slate-300 text-sm"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada data pendatang</p>
        </div>
        @endif
    </div>
</div>

<script>
function toggleAcara(id) {
    if (confirm('Ubah status acara ini?')) {
        window.location.href = '{{ url("administrator/kelian/pendatang/acara/toggle") }}/' + id;
    }
}

function deleteAcara(id) {
    if (confirm('Hapus acara ini? Semua tagihan terkait akan dihapus.')) {
        window.location.href = '{{ url("administrator/kelian/pendatang/acara/delete") }}/' + id;
    }
}
</script>
@endsection
