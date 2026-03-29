@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ activeFilter: 'all' }">
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Program Donasi</h2>
        <p class="text-[10px] text-slate-400 mt-1">Dukung pembangunan desa</p>
    </div>

    <!-- Category Filter Pills -->
    @if($kategori_donasi->count() > 0)
    <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
        <button @click="activeFilter = 'all'"
                :class="activeFilter === 'all' ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200'"
                class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all">
            Semua
        </button>
        @foreach($kategori_donasi as $kat)
        <button @click="activeFilter = '{{ $kat->id_kategori_donasi }}'"
                :class="activeFilter === '{{ $kat->id_kategori_donasi }}' ? 'bg-[#00a6eb] text-white border-[#00a6eb]' : 'bg-white text-slate-500 border-slate-200'"
                class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all">
            {{ $kat->nama_kategori }}
        </button>
        @endforeach
    </div>
    @endif

    <!-- Program List -->
    <div class="space-y-3">
        @forelse($programs as $prog)
            <a href="{{ url('administrator/usaha/donasi/detail/'.$prog->id_program_donasi) }}"
               x-show="activeFilter === 'all' || activeFilter === '{{ $prog->id_kategori_donasi }}'"
               x-transition
               class="block bg-white rounded-xl border border-slate-100 overflow-hidden hover:shadow-md transition-all group">
                <div class="flex gap-0">
                    <div class="w-[120px] shrink-0 bg-slate-50 flex items-center justify-center relative overflow-hidden">
                        @if($prog->foto)
                            <img src="{{ asset('storage/program_donasi/'.$prog->foto) }}" class="h-full w-full object-cover" alt="{{ $prog->nama_program }}">
                        @else
                            <i class="bi bi-image text-3xl text-slate-200"></i>
                        @endif
                    </div>
                    <div class="flex-1 p-3.5 flex flex-col justify-center min-w-0">
                        @if($prog->kategori)
                        <span class="text-[#00a6eb] text-[8px] font-bold uppercase tracking-wider mb-1">{{ $prog->kategori->nama_kategori }}</span>
                        @endif
                        <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 line-clamp-2 group-hover:text-[#00a6eb] transition-colors">{{ $prog->nama_program }}</h4>

                        @php
                            $target = $prog->target_dana ?: 1;
                            $pct = min(100, round(($prog->terkumpul / $target) * 100));
                        @endphp
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2">
                            <div class="bg-[#00a6eb] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[8px] text-slate-400 font-bold uppercase">Terkumpul</p>
                                <p class="text-xs font-bold text-[#00a6eb]">Rp {{ number_format($prog->terkumpul, 0, ',', '.') }}</p>
                            </div>
                            <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-colors"></i>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="py-10 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <i class="bi bi-heart text-3xl text-slate-200 mb-2 block"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada program aktif</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
