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
        $pendatangList = App\Models\Pendatang::where('aktif', '1')->orderBy('created_at', 'desc')->get();
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

    <!-- Add Button -->
    <a href="{{ url('administrator/kelian/pendatang/create') }}" class="block w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg text-center">
        <i class="bi bi-plus-lg mr-2"></i>Tambah Pendatang
    </a>

    <!-- Punia Acara Section -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Punia Acara</h3>
            <a href="{{ url('administrator/kelian/pendatang/create-acara') }}" 
               class="h-9 px-3 bg-[#00a6eb] text-white rounded-xl flex items-center gap-1.5 shadow-lg shadow-[#00a6eb]/20 transition-all active:scale-95 text-[10px] font-bold">
                <i class="bi bi-plus-lg"></i>
                <span>Buat Acara</span>
            </a>
        </div>
        @if(isset($acaraList) && $acaraList->count() > 0)
        <div class="space-y-2.5">
            @foreach($acaraList as $acara)
            <div class="bg-white border border-slate-100 rounded-xl p-3">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-slate-800 mb-1">{{ $acara->nama_acara }}</h4>
                        @if($acara->tanggal_acara)
                        <p class="text-[9px] text-slate-500 mb-1">
                            <i class="bi bi-calendar mr-1"></i>{{ $acara->tanggal_acara->format('d M Y') }}
                        </p>
                        @endif
                        <p class="text-sm font-bold text-[#00a6eb]">Rp {{ number_format($acara->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex gap-1 shrink-0">
                        <button onclick="toggleAcara({{ $acara->id_acara_punia }})" class="h-7 w-7 bg-slate-50 text-slate-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-toggle-{{ $acara->status === 'aktif' ? 'on' : 'off' }} text-xs"></i>
                        </button>
                        <button onclick="deleteAcara({{ $acara->id_acara_punia }})" class="h-7 w-7 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center">
                            <i class="bi bi-trash text-xs"></i>
                        </button>
                    </div>
                </div>
                @php
                    $totalPendatang = $acara->puniaPendatang->count();
                    $sudahBayar = $acara->puniaPendatang->where('status_pembayaran', 'lunas')->count();
                @endphp
                <div class="bg-slate-50 rounded-lg p-2 flex items-center justify-between text-[9px]">
                    <span class="text-slate-600">{{ $sudahBayar }}/{{ $totalPendatang }} sudah bayar</span>
                    <span class="font-bold text-slate-800">{{ $totalPendatang > 0 ? round(($sudahBayar / $totalPendatang) * 100) : 0 }}%</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <i class="bi bi-calendar-event text-2xl text-slate-300 mb-1"></i>
            <p class="text-[10px] text-slate-400">Belum ada punia acara</p>
        </div>
        @endif
    </div>

    <!-- Pendatang List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Daftar Pendatang</h3>
        @if($pendatangList->count() > 0)
        <div class="space-y-3">
            @foreach($pendatangList as $pendatang)
            <a href="{{ url('administrator/kelian/pendatang/detail/'.$pendatang->id_pendatang) }}" class="block bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all">
                <div class="flex items-start gap-4 p-4">
                    <div class="h-12 w-12 bg-slate-100 rounded-xl flex items-center justify-center shrink-0 text-slate-600 text-sm font-bold">
                        {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-800 mb-1">{{ $pendatang->nama }}</h3>
                        <p class="text-[10px] text-slate-500 mb-1">{{ $pendatang->nik }}</p>
                        <p class="text-[10px] text-slate-500 mb-2">{{ $pendatang->asal }}</p>
                        @php
                            $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->count();
                        @endphp
                        @if($belumBayar > 0)
                        <span class="text-[9px] text-rose-600 bg-rose-50 px-2 py-0.5 rounded">
                            {{ $belumBayar }} tagihan
                        </span>
                        @endif
                    </div>
                    <i class="bi bi-chevron-right text-slate-300 text-lg"></i>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-people text-3xl text-slate-300 mb-2"></i>
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
