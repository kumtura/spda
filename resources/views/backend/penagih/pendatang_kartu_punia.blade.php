@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ showBayarModal: false, selectedPuniaId: null, selectedBulan: '' }">
    <!-- Back -->
    <div>
        <a href="{{ url('administrator/penagih/pendatang/detail/'.$pendatang->id_pendatang) }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali ke Detail</span>
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

    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <p class="text-[9px] uppercase text-white/60 tracking-widest mb-1">Kartu Punia</p>
            <h2 class="text-lg font-black">{{ $pendatang->nama }}</h2>
            <p class="text-white/60 text-xs">Tahun {{ $year }}</p>
        </div>
    </div>

    <!-- Year Navigation -->
    <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
        <a href="{{ url('administrator/penagih/pendatang/kartu-punia/'.$pendatang->id_pendatang.'?year='.($year-1)) }}" 
           class="h-8 w-8 flex items-center justify-center bg-white rounded-lg border border-slate-200 text-slate-400 hover:text-[#00a6eb]">
            <i class="bi bi-chevron-left"></i>
        </a>
        <span class="text-sm font-black text-slate-800">{{ $year }}</span>
        <a href="{{ url('administrator/penagih/pendatang/kartu-punia/'.$pendatang->id_pendatang.'?year='.($year+1)) }}" 
           class="h-8 w-8 flex items-center justify-center bg-white rounded-lg border border-slate-200 text-slate-400 hover:text-[#00a6eb]">
            <i class="bi bi-chevron-right"></i>
        </a>
    </div>

    @php
        $bulanNames = [1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'Mei',6=>'Jun',7=>'Jul',8=>'Agu',9=>'Sep',10=>'Okt',11=>'Nov',12=>'Des'];
        $puniaByMonth = [];
        foreach($puniaList as $p) {
            $parts = explode('/', $p->bulan_tahun);
            if (count($parts) == 2) {
                $puniaByMonth[(int)$parts[0]] = $p;
            }
        }
    @endphp

    <!-- Monthly Grid -->
    <div class="grid grid-cols-3 gap-3">
        @for($m = 1; $m <= 12; $m++)
        @php
            $punia = $puniaByMonth[$m] ?? null;
            $isLunas = $punia && $punia->status_pembayaran === 'lunas';
            $isBelum = $punia && $punia->status_pembayaran === 'belum_bayar';
        @endphp
        <div class="rounded-xl border p-3 text-center {{ $isLunas ? 'bg-emerald-50 border-emerald-200' : ($isBelum ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200') }}">
            <p class="text-[9px] font-bold uppercase tracking-widest mb-1 {{ $isLunas ? 'text-emerald-600' : ($isBelum ? 'text-rose-600' : 'text-slate-400') }}">
                {{ $bulanNames[$m] }}
            </p>
            @if($isLunas)
                <i class="bi bi-check-circle-fill text-emerald-500 text-lg"></i>
                <p class="text-[8px] text-emerald-600 mt-1">Lunas</p>
            @elseif($isBelum)
                <button @click="selectedPuniaId = {{ $punia->id_punia_pendatang }}; selectedBulan = '{{ $bulanNames[$m] }} {{ $year }}'; showBayarModal = true"
                        class="text-[9px] font-bold text-white bg-[#00a6eb] px-3 py-1 rounded-lg hover:bg-[#0090d0] transition-colors">
                    Bayar
                </button>
                <p class="text-[8px] text-rose-500 mt-1">Rp {{ number_format($punia->nominal, 0, ',', '.') }}</p>
            @else
                <i class="bi bi-dash text-slate-300 text-lg"></i>
                <p class="text-[8px] text-slate-400 mt-1">-</p>
            @endif
        </div>
        @endfor
    </div>

    <!-- Bayar Modal -->
    <template x-teleport="body">
        <div x-show="showBayarModal" class="fixed inset-0 z-50 flex items-end justify-center bg-slate-900/60 px-4 pb-4" x-transition x-cloak>
            <div class="bg-white w-full max-w-[480px] rounded-2xl overflow-hidden shadow-2xl" @click.away="showBayarModal = false">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800">Bayar Punia</h3>
                    <p class="text-[10px] text-slate-400" x-text="selectedBulan"></p>
                </div>
                <form action="{{ url('administrator/penagih/pendatang/kartu-punia/bayar') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="id_punia" :value="selectedPuniaId">
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer bg-slate-50 border-slate-200 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300">
                                <input type="radio" name="metode_pembayaran" value="cash" checked class="w-4 h-4 text-[#00a6eb]">
                                <span class="text-xs font-bold text-slate-700">Cash</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer bg-slate-50 border-slate-200 has-[:checked]:bg-blue-50 has-[:checked]:border-blue-300">
                                <input type="radio" name="metode_pembayaran" value="qris" class="w-4 h-4 text-[#00a6eb]">
                                <span class="text-xs font-bold text-slate-700">QRIS</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button type="button" @click="showBayarModal = false" class="flex-1 py-3 text-xs font-bold text-slate-400 bg-slate-50 rounded-xl">Batal</button>
                        <button type="submit" class="flex-1 py-3 text-xs font-bold text-white bg-[#00a6eb] rounded-xl shadow-lg">Konfirmasi Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
