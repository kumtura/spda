@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24">

    {{-- ── HEADER ── --}}
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-12 pb-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full -ml-16 -mb-16"></div>
        <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1.5 text-white/70 hover:text-white transition-colors mb-4">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold uppercase tracking-widest">Beranda</span>
        </a>
        <h1 class="text-2xl font-black text-white tracking-tight relative z-10">Tentang Desa</h1>
        <p class="text-xs text-white/70 mt-1 relative z-10 font-semibold">{{ $village['name'] ?? 'Desa Adat' }}</p>
    </div>

    {{-- ── STATS CARDS ── --}}
    <div class="px-4 -mt-5 relative z-10">
        <div class="grid grid-cols-4 gap-2">
            <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm text-center">
                <i class="bi bi-houses text-[#00a6eb] text-lg block mb-1"></i>
                <p class="text-lg font-black text-slate-800 leading-none">{{ $totalBanjar }}</p>
                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Banjar</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm text-center">
                <i class="bi bi-building text-[#00a6eb] text-lg block mb-1"></i>
                <p class="text-lg font-black text-slate-800 leading-none">{{ $totalPura }}</p>
                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Pura</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm text-center">
                <i class="bi bi-people text-[#00a6eb] text-lg block mb-1"></i>
                <p class="text-lg font-black text-slate-800 leading-none">{{ $totalKramaTamiu }}</p>
                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Tamiu</p>
            </div>
            <div class="bg-white border border-slate-100 rounded-2xl p-3 shadow-sm text-center">
                <i class="bi bi-briefcase text-[#00a6eb] text-lg block mb-1"></i>
                <p class="text-lg font-black text-slate-800 leading-none">{{ $totalUsaha }}</p>
                <p class="text-[9px] text-slate-400 font-bold uppercase mt-0.5">Usaha</p>
            </div>
        </div>
    </div>

    {{-- ── TAB NAVIGATION ── --}}
    <div class="px-4 mt-5" x-data="{ tab: 'sejarah' }">
        <div class="flex gap-1 bg-slate-100 rounded-2xl p-1 mb-5 overflow-x-auto no-scrollbar">
            @foreach([
                ['key' => 'sejarah',  'label' => 'Sejarah',  'icon' => 'bi-book'],
                ['key' => 'pengurus', 'label' => 'Pengurus', 'icon' => 'bi-person-badge'],
                ['key' => 'lembaga',  'label' => 'Lembaga',  'icon' => 'bi-building-check'],
                ['key' => 'bupda',    'label' => 'BUPDA',    'icon' => 'bi-shop'],
            ] as $t)
            <button @click="tab = '{{ $t['key'] }}'"
                    :class="tab === '{{ $t['key'] }}' ? 'bg-white text-[#00a6eb] shadow-sm' : 'text-slate-400'"
                    class="flex-1 min-w-[70px] flex flex-col items-center gap-0.5 py-2 px-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                <i class="bi {{ $t['icon'] }} text-base"></i>
                {{ $t['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ── TAB: SEJARAH ── --}}
        <div x-show="tab === 'sejarah'" x-transition>
            @if($sejarah)
            <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="h-8 w-8 bg-[#00a6eb]/10 rounded-xl flex items-center justify-center">
                        <i class="bi bi-book-half text-[#00a6eb]"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-800">Sejarah Desa Adat</h3>
                </div>
                <div class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $sejarah }}</div>
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <i class="bi bi-book text-3xl text-slate-300 block mb-2"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada konten sejarah.</p>
                <p class="text-[10px] text-slate-300 mt-1">Admin dapat menambahkan melalui panel administrator.</p>
            </div>
            @endif
        </div>

        {{-- ── TAB: PENGURUS ── --}}
        <div x-show="tab === 'pengurus'" x-transition>
            @if(count($pengurus) > 0)
            <div class="space-y-3">
                @foreach($pengurus as $p)
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                        @if(!empty($p['foto']))
                            <img src="{{ asset('storage/tentang_desa/pengurus/' . $p['foto']) }}" class="h-full w-full object-cover" alt="{{ $p['nama'] }}">
                        @else
                            <i class="bi bi-person-fill text-2xl text-slate-300"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-black text-slate-800 leading-tight">{{ $p['nama'] }}</p>
                        <span class="inline-block mt-1 bg-[#00a6eb]/10 text-[#00a6eb] text-[9px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">{{ $p['jabatan'] }}</span>
                        @if(!empty($p['no_hp']))
                        <p class="text-[10px] text-slate-400 mt-1"><i class="bi bi-telephone mr-1"></i>{{ $p['no_hp'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <i class="bi bi-people text-3xl text-slate-300 block mb-2"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada data pengurus.</p>
            </div>
            @endif
        </div>

        {{-- ── TAB: LEMBAGA ── --}}
        <div x-show="tab === 'lembaga'" x-transition>
            @if(count($lembaga) > 0)
            <div class="space-y-3">
                @foreach($lembaga as $l)
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                            @if(!empty($l['logo']))
                                <img src="{{ asset('storage/tentang_desa/lembaga/' . $l['logo']) }}" class="h-full w-full object-cover" alt="{{ $l['nama_lembaga'] }}">
                            @else
                                <i class="bi bi-building text-2xl text-slate-300"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-800 leading-tight">{{ $l['nama_lembaga'] }}</p>
                            @if(!empty($l['ketua']))
                            <p class="text-[10px] text-[#00a6eb] font-bold mt-0.5">Ketua: {{ $l['ketua'] }}</p>
                            @endif
                            @if(!empty($l['deskripsi']))
                            <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed line-clamp-2">{{ $l['deskripsi'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <i class="bi bi-building text-3xl text-slate-300 block mb-2"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada data lembaga.</p>
            </div>
            @endif
        </div>

        {{-- ── TAB: BUPDA ── --}}
        <div x-show="tab === 'bupda'" x-transition>
            @if(!empty($bupda['nama']))
            <div class="space-y-4">
                {{-- Info --}}
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                            <i class="bi bi-shop text-2xl text-slate-300"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-800 leading-tight">{{ $bupda['nama'] }}</p>
                            @if(!empty($bupda['tahun_berdiri']))
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="bi bi-calendar3 mr-1"></i>Berdiri {{ $bupda['tahun_berdiri'] }}</p>
                            @endif
                            @if(!empty($bupda['deskripsi']))
                            <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed">{{ $bupda['deskripsi'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Foto Struktur --}}
                @if(!empty($bupda['foto_struktur']))
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Struktur Organisasi</p>
                    <div class="rounded-2xl overflow-hidden border border-slate-100">
                        <img src="{{ asset('storage/tentang_desa/bupda/' . $bupda['foto_struktur']) }}" class="w-full object-contain" alt="Struktur BUPDA">
                    </div>
                </div>
                @endif

                {{-- Tim --}}
                @if(!empty($bupda['tim']) && count($bupda['tim']) > 0)
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Tim BUPDA</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($bupda['tim'] as $anggota)
                        <div class="bg-white border border-slate-100 rounded-xl p-3 text-center shadow-sm">
                            <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden mx-auto mb-2 flex items-center justify-center">
                                @if(!empty($anggota['foto']))
                                    <img src="{{ asset('storage/tentang_desa/bupda/' . $anggota['foto']) }}" class="h-full w-full object-cover" alt="{{ $anggota['nama'] }}">
                                @else
                                    <i class="bi bi-person-fill text-xl text-slate-300"></i>
                                @endif
                            </div>
                            <p class="text-[10px] font-black text-slate-800 leading-tight">{{ $anggota['nama'] }}</p>
                            <p class="text-[9px] text-[#00a6eb] font-bold mt-0.5">{{ $anggota['jabatan'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Program --}}
                @if(!empty($bupda['program']) && count($bupda['program']) > 0)
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Program BUPDA</p>
                    <div class="space-y-3">
                        @foreach($bupda['program'] as $prog)
                        <div class="bg-white border border-slate-100 rounded-xl overflow-hidden shadow-sm">
                            @if(!empty($prog['foto']))
                            <div class="h-32 bg-slate-100 overflow-hidden">
                                <img src="{{ asset('storage/tentang_desa/bupda/' . $prog['foto']) }}" class="w-full h-full object-cover" alt="{{ $prog['nama_program'] }}">
                            </div>
                            @endif
                            <div class="p-3">
                                <p class="text-xs font-black text-slate-800">{{ $prog['nama_program'] }}</p>
                                @if(!empty($prog['keterangan']))
                                <p class="text-[10px] text-slate-500 mt-1 leading-relaxed">{{ $prog['keterangan'] }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 mt-2 text-[9px] text-slate-400">
                                    @if(!empty($prog['lokasi']))<span><i class="bi bi-geo-alt mr-0.5 text-[#00a6eb]"></i>{{ $prog['lokasi'] }}</span>@endif
                                    @if(!empty($prog['no_kontak']))<span><i class="bi bi-telephone mr-0.5 text-[#00a6eb]"></i>{{ $prog['no_kontak'] }}</span>@endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Dokumentasi --}}
                @if(!empty($bupda['dokumentasi']) && count($bupda['dokumentasi']) > 0)
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Dokumentasi Kegiatan</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($bupda['dokumentasi'] as $dok)
                        <div class="rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                            <div class="h-28 bg-slate-100 overflow-hidden">
                                <img src="{{ asset('storage/tentang_desa/bupda/' . $dok['foto']) }}" class="w-full h-full object-cover" alt="{{ $dok['judul'] }}">
                            </div>
                            <p class="text-[9px] font-bold text-slate-600 p-2 leading-tight line-clamp-2">{{ $dok['judul'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <i class="bi bi-shop text-3xl text-slate-300 block mb-2"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada data BUPDA.</p>
                <p class="text-[10px] text-slate-300 mt-1">Admin dapat menambahkan melalui panel administrator.</p>
            </div>
            @endif
        </div>

        {{-- ── DAFTAR BANJAR & PURA (selalu tampil di bawah tab) ── --}}
        <div class="mt-6 space-y-5">
            {{-- Banjar --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-7 w-7 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
                        <i class="bi bi-houses text-[#00a6eb] text-sm"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Daftar Banjar</h3>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    @forelse($banjar as $bj)
                    <div class="bg-white border border-slate-100 rounded-xl p-3 flex items-center gap-2.5 shadow-sm">
                        <div class="h-8 w-8 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-house-door text-[#00a6eb] text-sm"></i>
                        </div>
                        <p class="text-[11px] font-bold text-slate-700 leading-tight">{{ $bj->nama_banjar }}</p>
                    </div>
                    @empty
                    <div class="col-span-2 bg-slate-50 rounded-xl border border-dashed border-slate-200 p-4 text-center">
                        <p class="text-xs text-slate-400">Belum ada data banjar</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Pura --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-7 w-7 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
                        <i class="bi bi-building text-[#00a6eb] text-sm"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Daftar Pura</h3>
                </div>
                <div class="space-y-2">
                    @forelse($pura as $item)
                    <div class="bg-white border border-slate-100 rounded-xl p-3.5 flex items-center gap-3 shadow-sm">
                        <div class="h-9 w-9 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-building text-[#00a6eb] text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800">{{ $item->nama_pura }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">
                                <i class="bi bi-geo-alt mr-0.5"></i>Banjar {{ $item->nama_banjar ?? '-' }}
                            </p>
                        </div>
                    </div>
                    @empty
                    <div class="bg-slate-50 rounded-xl border border-dashed border-slate-200 p-4 text-center">
                        <p class="text-xs text-slate-400">Belum ada data pura</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
