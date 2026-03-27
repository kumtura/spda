@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-8" x-data="{ activeFilter: 'all' }">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Program Donasi</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Dukung pembangunan desa melalui donasi terpercaya.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
            <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Stats Card (Identical to Punia) -->
    <div class="bg-[#00a6eb] rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-heart-pulse-fill text-lg"></i>
            </div>
            <span class="text-[8px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">Terverifikasi</span>
        </div>
        <p class="text-[9px] font-bold uppercase tracking-wider text-white/60 mb-1">Total Dana Donasi</p>
        <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($total_sumbangan, 0, ',', '.') }}</h3>
    </div>

    <!-- Tentang Donasi -->
    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
        <h4 class="text-sm font-bold text-slate-800 mb-3">Tentang Program Donasi</h4>
        <p class="text-xs text-slate-500 leading-relaxed">Program Donasi adalah bentuk sumbangan sukarela dari masyarakat umum, unit usaha, maupun donatur anonim untuk mendukung program-program pembangunan dan kegiatan sosial di lingkungan Desa Adat. Setiap donasi tercatat dan terverifikasi secara transparan.</p>
    </div>

    <!-- Program Aktif Section -->
    <div>
        <h4 class="text-sm font-bold text-slate-800 mb-4">Program Aktif</h4>

        <!-- Category Filter Pills -->
        @if($kategori_donasi->count() > 0)
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-4">
            <button @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all active:scale-95">
                Semua
            </button>
            @foreach($kategori_donasi as $kat)
            <button @click="activeFilter = '{{ $kat->id_kategori_donasi }}'"
                    :class="activeFilter === '{{ $kat->id_kategori_donasi }}' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-200/50' : 'bg-white text-slate-500 border-slate-200 hover:border-[#00a6eb]/30'"
                    class="shrink-0 px-4 py-2 rounded-full text-[10px] font-bold border transition-all active:scale-95 flex items-center gap-1.5">
                {{ $kat->nama_kategori }}
            </button>
            @endforeach
        </div>
        @endif

        <!-- Program List -->
        <div class="space-y-4">
            @forelse($programs as $prog)
                <a href="{{ route('public.donasi.detail', $prog->id_program_donasi) }}"
                   x-show="activeFilter === 'all' || activeFilter === '{{ $prog->id_kategori_donasi }}'"
                   x-transition
                   class="block bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all active:scale-[0.98] group">
                    <div class="flex gap-0">
                        <!-- Thumbnail -->
                        <div class="w-[130px] shrink-0 bg-slate-50 flex items-center justify-center relative overflow-hidden">
                            @if($prog->foto)
                                <img src="{{ asset('storage/program_donasi/'.$prog->foto) }}" class="h-full w-full object-cover" alt="{{ $prog->nama_program }}">
                            @else
                                <i class="bi bi-image text-4xl text-slate-200"></i>
                            @endif
                        </div>
                        <!-- Content -->
                        <div class="flex-1 p-4 flex flex-col justify-center min-w-0">
                            @if($prog->kategori)
                            <span class="text-[#00a6eb] text-[7px] font-bold uppercase tracking-wider mb-1 px-0 py-0">{{ $prog->kategori->nama_kategori }}</span>
                            @endif
                            <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 line-clamp-2 group-hover:text-[#00a6eb] transition-colors">{{ $prog->nama_program }}</h4>

                            <!-- Progress bar -->
                            @php
                                $target = $prog->target_dana ?: 1;
                                $pct = min(100, round(($prog->terkumpul / $target) * 100));
                            @endphp
                            <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2">
                                <div class="bg-[#00a6eb] h-1.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[8px] text-slate-400 font-bold uppercase tracking-wider">Terkumpul</p>
                                    <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($prog->terkumpul, 0, ',', '.') }}</p>
                                </div>
                                <div class="h-7 w-7 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-[#00a6eb] group-hover:text-white group-hover:border-transparent transition-all">
                                    <i class="bi bi-chevron-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <i class="bi bi-heart text-3xl text-slate-200 mb-2 block"></i>
                    <p class="text-xs font-bold text-slate-400">Belum ada program aktif.</p>
                    <p class="text-[10px] text-slate-400 mt-1">Program donasi yang dibuat admin akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
