@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <!-- Back -->
    <div>
        <a href="{{ url('administrator/penagih/pendatang') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <h2 class="text-lg font-black mb-1">{{ $pendatang->nama }}</h2>
            <p class="text-white/80 text-xs mb-3">NIK: {{ $pendatang->nik }}</p>
            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <p class="text-white/60 text-[9px] uppercase tracking-widest mb-0.5">Asal</p>
                    <p class="font-bold">{{ $pendatang->asal }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-[9px] uppercase tracking-widest mb-0.5">No HP</p>
                    <p class="font-bold">{{ $pendatang->no_hp }}</p>
                </div>
                <div>
                    <p class="text-white/60 text-[9px] uppercase tracking-widest mb-0.5">Status</p>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold {{ $pendatang->status == 'aktif' ? 'bg-emerald-500/20 text-emerald-200' : 'bg-rose-500/20 text-rose-200' }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $pendatang->status == 'aktif' ? 'bg-emerald-400' : 'bg-rose-400' }}"></span>
                        {{ ucfirst($pendatang->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-white/60 text-[9px] uppercase tracking-widest mb-0.5">Punia / Bulan</p>
                    <p class="font-bold">Rp {{ number_format($pendatang->punia_rutin_bulanan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ url('administrator/penagih/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}" 
           class="bg-white border border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors">
            <i class="bi bi-credit-card text-[#00a6eb] text-xl mb-1"></i>
            <p class="text-xs font-bold text-slate-700">Kartu Punia</p>
        </a>
        <a href="{{ url('administrator/penagih/pendatang/generate-tagihan/'.$pendatang->id_pendatang) }}" 
           class="bg-white border border-slate-200 rounded-xl p-4 text-center hover:bg-slate-50 transition-colors"
           onclick="return confirm('Generate tagihan bulan ini?')">
            <i class="bi bi-receipt text-amber-500 text-xl mb-1"></i>
            <p class="text-xs font-bold text-slate-700">Generate Tagihan</p>
        </a>
    </div>

    <!-- Punia History -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Riwayat Punia</h3>
        @if($pendatang->puniaPendatang->count() > 0)
        <div class="space-y-2">
            @foreach($pendatang->puniaPendatang as $punia)
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-3">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800">
                            @if($punia->jenis_punia === 'rutin')
                                Punia Rutin - {{ $punia->bulan_tahun }}
                            @else
                                {{ $punia->nama_acara ?? 'Punia Acara' }}
                            @endif
                        </p>
                        <p class="text-[10px] text-slate-400 mt-0.5">
                            Rp {{ number_format($punia->nominal, 0, ',', '.') }}
                            @if($punia->tanggal_bayar)
                            &middot; Bayar {{ $punia->tanggal_bayar->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    <div class="shrink-0">
                        @if($punia->status_pembayaran === 'lunas')
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Lunas</span>
                        @else
                            <form action="{{ url('administrator/penagih/pendatang/punia/bayar/'.$punia->id_punia_pendatang) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="metode_pembayaran" value="cash">
                                <button type="submit" onclick="return confirm('Bayar punia ini sebagai CASH?')" 
                                        class="text-[9px] font-bold text-white bg-[#00a6eb] px-3 py-1 rounded-lg hover:bg-[#0090d0] transition-colors">
                                    Bayar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat punia</p>
        </div>
        @endif
    </div>
</div>
@endsection
