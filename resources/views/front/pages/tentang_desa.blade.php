@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24" x-data="{ tab: 'sejarah' }">

    {{-- ── HERO HEADER WITH GALLERY BACKGROUND ── --}}
    <div class="relative overflow-hidden" x-data="{ currentSlide: 0, totalSlides: {{ !empty($gallery) && count($gallery) > 0 ? count($gallery) : 1 }}, autoPlay: null }"
         x-init="if(totalSlides > 1) autoPlay = setInterval(() => { currentSlide = (currentSlide + 1) % totalSlides }, 5000)"
         x-destroy="if(autoPlay) clearInterval(autoPlay)">
        
        {{-- Gallery Background Images --}}
        @if(!empty($gallery) && count($gallery) > 0)
            @foreach($gallery as $index => $image)
            <div x-show="currentSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 h-[300px] md:h-[400px]">
                <img src="{{ asset('storage/tentang_desa/gallery/' . $image) }}"
                     class="w-full h-full object-cover"
                     loading="lazy"
                     alt="Gallery {{ $index + 1 }}">
            </div>
            @endforeach
        @else
            <div class="absolute inset-0 h-[300px] md:h-[400px] bg-gradient-to-br from-[#00a6eb] to-[#0090d0]"></div>
        @endif

        {{-- Overlay --}}
        <div class="absolute inset-0 h-[300px] md:h-[400px] bg-black/40"></div>

        {{-- Hero Content --}}
        <div class="relative z-10 px-5 pt-12 pb-16 h-[300px] md:h-[400px] flex flex-col justify-between">
            <div>
                <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1.5 text-white/80 hover:text-white transition-colors mb-4">
                    <i class="bi bi-arrow-left text-sm"></i>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Beranda</span>
                </a>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Tentang Desa</h1>
                <p class="text-sm text-white/90 font-semibold">{{ $village['name'] ?? 'Desa Adat' }}</p>
            </div>
            
            {{-- Gallery Controls --}}
            @if(!empty($gallery) && count($gallery) > 1)
            <div class="flex items-center justify-between">
                <button @click="currentSlide = (currentSlide - 1 + totalSlides) % totalSlides"
                        class="h-10 w-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow transition-all">
                    <i class="bi bi-chevron-left text-slate-700"></i>
                </button>
                <div class="flex gap-1.5">
                    @foreach($gallery as $index => $image)
                    <button @click="currentSlide = {{ $index }}"
                            :class="currentSlide === {{ $index }} ? 'bg-white w-4' : 'bg-white/50 w-2'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
                <button @click="currentSlide = (currentSlide + 1) % totalSlides"
                        class="h-10 w-10 bg-white/80 hover:bg-white rounded-full flex items-center justify-center shadow transition-all">
                    <i class="bi bi-chevron-right text-slate-700"></i>
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- ── BENDESA ADAT (side-by-side layout) ── --}}
    @if(!empty($bendesa['nama']))
    <div class="px-4 mt-8">
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm">
            <div class="flex flex-col md:flex-row">
                {{-- Photo Section (50% on desktop) --}}
                <div class="w-full md:w-1/2 h-64 md:h-96 bg-slate-100 relative overflow-hidden">
                    @if(!empty($bendesa['foto']))
                        <img src="{{ asset('storage/tentang_desa/pengurus/' . $bendesa['foto']) }}"
                             class="w-full h-full object-cover"
                             alt="{{ $bendesa['nama'] }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-6xl text-slate-300"></i>
                        </div>
                    @endif
                </div>
                {{-- Information Section (50% on desktop) --}}
                <div class="w-full md:w-1/2 p-6 md:p-8 flex flex-col justify-center">
                    <h3 class="text-xl md:text-2xl font-black text-slate-800 leading-tight mb-2">{{ $bendesa['nama'] }}</h3>
                    <span class="inline-block bg-[#00a6eb] text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-3">Bendesa Adat</span>
                    @if(!empty($bendesa['no_telp']))
                    <p class="text-sm text-slate-500">
                        <i class="bi bi-telephone mr-2 text-[#00a6eb]"></i>{{ $bendesa['no_telp'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── KATA SAMBUTAN (full-width below Bendesa) ── --}}
    @if(!empty($bendesa['sambutan']))
    <div class="px-4 mt-5">
        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
            <p class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest mb-4">Kata Sambutan</p>
            <div class="text-sm text-slate-600 leading-relaxed italic border-l-4 border-[#00a6eb] pl-4">
                <i class="bi bi-quote text-[#00a6eb] text-2xl mr-1"></i>
                {!! nl2br(e(strip_tags($bendesa['sambutan']))) !!}
            </div>
        </div>
    </div>
    @endif

    {{-- ── FOTO STRUKTUR DESA ── --}}
    @if(!empty($fotoStrukturDesa))
    <div class="px-4 mt-5">
        <div class="flex items-center gap-2 mb-3">
            <div class="h-7 w-7 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
                <i class="bi bi-diagram-3 text-[#00a6eb] text-sm"></i>
            </div>
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Struktur Organisasi Desa</h3>
        </div>
        <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
            <img src="{{ asset('storage/tentang_desa/pengurus/' . $fotoStrukturDesa) }}" class="w-full object-contain" alt="Struktur Desa Adat">
        </div>
    </div>
    @endif

    {{-- ── TAB NAVIGATION ── --}}
    <div class="px-4 mt-6">
        <div class="flex gap-1 bg-slate-100 rounded-2xl p-1 mb-5 overflow-x-auto no-scrollbar">
            @foreach([
                ['key'=>'sejarah',  'label'=>'Sejarah',  'icon'=>'bi-book'],
                ['key'=>'lembaga',  'label'=>'Lembaga',  'icon'=>'bi-building-check'],
                ['key'=>'bupda',    'label'=>'BUPDA',    'icon'=>'bi-shop'],
                ['key'=>'hukum',    'label'=>'Produk Hukum','icon'=>'bi-file-earmark-text'],
                ['key'=>'banjar',   'label'=>'Banjar & Pura','icon'=>'bi-houses'],
            ] as $t)
            <button @click="tab = '{{ $t['key'] }}'"
                    :class="tab === '{{ $t['key'] }}' ? 'bg-white text-[#00a6eb] shadow-sm' : 'text-slate-400'"
                    class="flex-1 min-w-[72px] flex flex-col items-center gap-0.5 py-2 px-1 rounded-xl text-[8px] font-black uppercase tracking-widest transition-all">
                <i class="bi {{ $t['icon'] }} text-base"></i>
                {{ $t['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ── TAB: SEJARAH ── --}}
        <div x-show="tab === 'sejarah'" x-transition>
            <div class="space-y-4">
                @if($sejarah)
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-8 w-8 bg-[#00a6eb]/10 rounded-xl flex items-center justify-center">
                            <i class="bi bi-book-half text-[#00a6eb]"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-800">Sejarah Desa Adat</h3>
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed prose prose-sm max-w-none">
                        {!! $sejarah !!}
                    </div>
                </div>
                @else
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                    <i class="bi bi-book text-3xl text-slate-300 block mb-2"></i>
                    <p class="text-xs font-bold text-slate-400">Belum ada konten sejarah.</p>
                </div>
                @endif

                {{-- Video Sejarah --}}
                @if(count($videos) > 0)
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Video Sejarah</p>
                    <div class="space-y-3">
                        @foreach($videos as $vid)
                        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                            <video src="{{ asset('storage/tentang_desa/sejarah/' . $vid['file']) }}" controls
                                   class="w-full max-h-52 bg-black"></video>
                            @if(!empty($vid['judul']))
                            <p class="text-xs font-bold text-slate-700 px-4 py-3">{{ $vid['judul'] }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── TAB: LEMBAGA ── --}}
        <div x-show="tab === 'lembaga'" x-transition>
            @if(count($lembaga) > 0)
            <div class="space-y-4">
                @foreach($lembaga as $l)
                <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-4 flex items-start gap-4">
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
                            <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed line-clamp-3">{!! strip_tags($l['deskripsi']) !!}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Pengurus lembaga --}}
                    @if(!empty($l['pengurus']) && count($l['pengurus']) > 0)
                    <div class="px-4 pb-3 border-t border-slate-50 pt-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Pengurus</p>
                        <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                            @foreach($l['pengurus'] as $pg)
                            <div class="flex-shrink-0 text-center w-16">
                                <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden mx-auto mb-1 flex items-center justify-center border border-slate-200">
                                    @if(!empty($pg['foto']))
                                        <img src="{{ asset('storage/tentang_desa/lembaga/' . $pg['foto']) }}" class="h-full w-full object-cover" alt="{{ $pg['nama'] }}">
                                    @else
                                        <i class="bi bi-person-fill text-lg text-slate-300"></i>
                                    @endif
                                </div>
                                <p class="text-[9px] font-bold text-slate-700 leading-tight line-clamp-2">{{ $pg['nama'] }}</p>
                                @if(!empty($pg['keterangan']))
                                <p class="text-[8px] text-[#00a6eb] font-bold">{{ $pg['keterangan'] }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Gallery lembaga --}}
                    @if(!empty($l['gallery']) && count($l['gallery']) > 0)
                    <div class="px-4 pb-4 border-t border-slate-50 pt-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Gallery</p>
                        <div class="flex gap-2 overflow-x-auto no-scrollbar">
                            @foreach($l['gallery'] as $gfoto)
                            <div class="h-20 w-20 rounded-xl overflow-hidden shrink-0 bg-slate-100 border border-slate-200">
                                <img src="{{ asset('storage/tentang_desa/lembaga/' . $gfoto) }}" class="h-full w-full object-cover" alt="Gallery">
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
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
                <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-[#00a6eb]/10 shrink-0 flex items-center justify-center border border-[#00a6eb]/20">
                            <i class="bi bi-shop text-2xl text-[#00a6eb]"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-800 leading-tight">{{ $bupda['nama'] }}</p>
                            @if(!empty($bupda['tahun_berdiri']))
                            <p class="text-[10px] text-slate-400 mt-0.5"><i class="bi bi-calendar3 mr-1"></i>Berdiri {{ $bupda['tahun_berdiri'] }}</p>
                            @endif
                            @if(!empty($bupda['deskripsi']))
                            <p class="text-[10px] text-slate-500 mt-1.5 leading-relaxed">{!! strip_tags($bupda['deskripsi']) !!}</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($bupda['foto_struktur']))
                <div>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Struktur Organisasi</p>
                    <div class="rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                        <img src="{{ asset('storage/tentang_desa/bupda/' . $bupda['foto_struktur']) }}" class="w-full object-contain" alt="Struktur BUPDA">
                    </div>
                </div>
                @endif

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
            </div>
            @endif
        </div>

        {{-- ── TAB: PRODUK HUKUM ── --}}
        <div x-show="tab === 'hukum'" x-transition>
            @if(count($produkHukum) > 0)
            <div class="space-y-3">
                @foreach($produkHukum as $ph)
                <a href="{{ asset('storage/tentang_desa/produk_hukum/' . $ph['file']) }}" target="_blank"
                   class="flex items-center gap-4 bg-white border border-slate-100 rounded-2xl p-4 shadow-sm hover:border-[#00a6eb]/30 transition-all">
                    <div class="h-12 w-12 rounded-xl flex items-center justify-center shrink-0
                        {{ strtolower($ph['ext'] ?? '') === 'pdf' ? 'bg-rose-50 text-rose-500' : 'bg-blue-50 text-blue-500' }}">
                        <i class="bi {{ strtolower($ph['ext'] ?? '') === 'pdf' ? 'bi-file-earmark-pdf' : 'bi-file-earmark-word' }} text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800 leading-tight">{{ $ph['nama_produk'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ strtoupper($ph['ext'] ?? '') }} &bull; {{ $ph['created_at'] ?? '' }}</p>
                    </div>
                    <i class="bi bi-download text-[#00a6eb] text-lg shrink-0"></i>
                </a>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center">
                <i class="bi bi-file-earmark-text text-3xl text-slate-300 block mb-2"></i>
                <p class="text-xs font-bold text-slate-400">Belum ada produk hukum.</p>
            </div>
            @endif
        </div>

        {{-- ── TAB: BANJAR & PURA ── --}}
        <div x-show="tab === 'banjar'" x-transition>
            <div class="space-y-5">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="h-7 w-7 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
                            <i class="bi bi-houses text-[#00a6eb] text-sm"></i>
                        </div>
                        <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Daftar Banjar</h3>
                    </div>
                    @forelse($banjar as $bj)
                    <div class="flex items-start gap-2.5 mb-2">
                        <span class="text-[#00a6eb] font-bold mt-0.5">•</span>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $bj->nama_banjar }}</p>
                    </div>
                    @empty
                    <div class="bg-slate-50 rounded-xl border border-dashed border-slate-200 p-4 text-center">
                        <p class="text-xs text-slate-400">Belum ada data banjar</p>
                    </div>
                    @endforelse
                </div>

                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-8 w-8 bg-[#00a6eb]/10 rounded-lg flex items-center justify-center">
                            <i class="bi bi-building text-[#00a6eb]"></i>
                        </div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Daftar Pura</h3>
                    </div>
                    @if(count($pura) > 0)
                    <div class="space-y-3">
                        @foreach($pura as $item)
                        <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex gap-4 p-4">
                                {{-- Pura Image --}}
                                <div class="h-24 w-24 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                                    @if(!empty($item->gambar_pura))
                                        <img src="{{ asset($item->gambar_pura) }}"
                                             class="w-full h-full object-cover"
                                             alt="{{ $item->nama_pura }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="bi bi-building text-2xl text-slate-300"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Pura Info --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <h3 class="text-sm font-black text-slate-800 leading-tight mb-2">
                                            {{ $item->nama_pura }}
                                        </h3>
                                        @if(!empty($item->lokasi))
                                        <p class="text-xs text-slate-500 mb-2">
                                            <i class="bi bi-geo-alt mr-1.5 text-[#00a6eb]"></i>{{ $item->lokasi }}
                                        </p>
                                        @endif
                                        <p class="text-xs text-[#00a6eb] font-semibold">
                                            <wa-icon name="om" family="sharp" variant="solid" class="inline mr-1.5"></wa-icon>Donasi untuk Pura
                                        </p>
                                    </div>
                                    
                                    {{-- Donation Button --}}
                                    <a href="{{ route('public.pura.punia', ['id' => $item->id_pura]) }}"
                                       class="inline-block bg-[#00a6eb] hover:bg-[#0090d0] text-white text-xs font-bold py-2 px-4 rounded-lg transition-colors mt-2">
                                        <i class="bi bi-heart-fill mr-1"></i>Donasi
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="bg-slate-50 rounded-xl border border-dashed border-slate-200 p-4 text-center">
                        <p class="text-xs text-slate-400">Belum ada data pura</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .prose img { border-radius: 0.75rem; max-width: 100%; }
</style>
@endsection
