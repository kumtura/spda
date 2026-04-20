@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24">
    <!-- Hero Image -->
    <div class="relative h-56 bg-slate-100">
        @if($banjar->gambar_banjar)
        <img src="{{ asset($banjar->gambar_banjar) }}" class="w-full h-full object-cover" alt="{{ $banjar->nama_banjar }}" onerror="this.parentElement.classList.add('flex','items-center','justify-center'); this.outerHTML='<i class=\'bi bi-houses text-slate-300 text-5xl\'></i>'">
        @else
        <div class="w-full h-full flex items-center justify-center">
            <i class="bi bi-houses text-slate-300 text-5xl"></i>
        </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        <a href="{{ route('public.tentang_desa') }}" class="absolute top-4 left-4 h-8 w-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white">
            <i class="bi bi-arrow-left text-sm"></i>
        </a>
    </div>

    <div class="px-5 -mt-10 relative z-10 space-y-4">
        <!-- Title Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">{{ $banjar->nama_banjar }}</h1>
            @if($banjar->alamat_banjar)
            <p class="text-xs text-slate-500 mt-2 leading-relaxed"><i class="bi bi-geo-alt mr-1 text-[#00a6eb]"></i>{{ $banjar->alamat_banjar }}</p>
            @endif
        </div>

        <!-- Informasi Utama -->
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-3">Informasi Utama</h3>
            <div class="bg-white rounded-2xl border border-slate-100 p-5 space-y-4">
                <div class="grid grid-cols-3 gap-1.5 sm:gap-2">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 sm:p-3 text-center">
                        <div class="h-8 w-8 sm:h-9 sm:w-9 mx-auto bg-[#00a6eb]/10 rounded-lg flex items-center justify-center mb-1.5 sm:mb-2">
                            <i class="bi bi-people text-[#00a6eb] text-xs sm:text-sm"></i>
                        </div>
                        <p class="text-[7px] sm:text-[8px] text-slate-400 uppercase tracking-wide sm:tracking-widest">Krama Tamiu</p>
                        <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5">{{ $kramaTamiu }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 sm:p-3 text-center">
                        <div class="h-8 w-8 sm:h-9 sm:w-9 mx-auto bg-[#00a6eb]/10 rounded-lg flex items-center justify-center mb-1.5 sm:mb-2">
                            <i class="bi bi-ticket-perforated text-[#00a6eb] text-xs sm:text-sm"></i>
                        </div>
                        <p class="text-[7px] sm:text-[8px] text-slate-400 uppercase tracking-wide sm:tracking-widest">Tiket Wisata</p>
                        <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5">{{ count($tiketWisata) }}</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-2.5 sm:p-3 text-center">
                        <div class="h-8 w-8 sm:h-9 sm:w-9 mx-auto bg-[#00a6eb]/10 rounded-lg flex items-center justify-center mb-1.5 sm:mb-2">
                            <i class="bi bi-building text-[#00a6eb] text-xs sm:text-sm"></i>
                        </div>
                        <p class="text-[7px] sm:text-[8px] text-slate-400 uppercase tracking-wide sm:tracking-widest">Pura</p>
                        <p class="text-sm sm:text-base font-black text-slate-800 mt-0.5">{{ count($pura) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelian Adat -->
        @if($kelianAdat)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-3">Kelian Adat</h3>
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <p class="text-sm font-black text-slate-800 mb-1">{{ $kelianAdat->name }}</p>
                @if($banjar->alamat_kelian_adat)
                <p class="text-xs text-slate-500 mb-2"><i class="bi bi-geo-alt mr-1 text-[#00a6eb]"></i>{{ $banjar->alamat_kelian_adat }}</p>
                @endif
                @if($banjar->no_telp_kelian_adat)
                <a href="tel:{{ $banjar->no_telp_kelian_adat }}" class="inline-flex items-center gap-1.5 text-xs text-[#00a6eb] font-semibold hover:text-[#0090d0] transition-colors">
                    <i class="bi bi-telephone"></i>{{ $banjar->no_telp_kelian_adat }}
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Kelian Dinas -->
        @if($banjar->nama_kelian_dinas)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-3">Kelian Dinas</h3>
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <p class="text-sm font-black text-slate-800 mb-1">{{ $banjar->nama_kelian_dinas }}</p>
                @if($banjar->alamat_kelian_dinas)
                <p class="text-xs text-slate-500 mb-2"><i class="bi bi-geo-alt mr-1 text-[#00a6eb]"></i>{{ $banjar->alamat_kelian_dinas }}</p>
                @endif
                @if($banjar->no_telp_kelian_dinas)
                <a href="tel:{{ $banjar->no_telp_kelian_dinas }}" class="inline-flex items-center gap-1.5 text-xs text-[#00a6eb] font-semibold hover:text-[#0090d0] transition-colors">
                    <i class="bi bi-telephone"></i>{{ $banjar->no_telp_kelian_dinas }}
                </a>
                @endif
            </div>
        </div>
        @endif

        <!-- Tiket Wisata -->
        @if(count($tiketWisata) > 0)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-3">Tiket Wisata Terdaftar</h3>
            <div class="space-y-3">
                @foreach($tiketWisata as $tiket)
                @php
                    $minPrice = $tiket->kategoriTiket->min('harga') ?? $tiket->harga_tiket ?? 0;
                @endphp
                <div class="block bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md hover:border-[#00a6eb]/30 transition-all">
                    <div class="flex gap-3 p-3">
                        <div class="h-24 w-24 rounded-xl bg-slate-100 overflow-hidden shrink-0">
                            @if(!empty($tiket->foto))
                                <img src="{{ asset('storage/wisata/'.$tiket->foto) }}" class="w-full h-full object-cover" alt="{{ $tiket->nama_objek }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="bi bi-ticket-perforated text-2xl text-slate-300"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0 flex flex-col justify-between">
                            <div>
                                <h3 class="text-sm font-black text-slate-800 leading-tight mb-1 line-clamp-2">{{ $tiket->nama_objek }}</h3>
                                @if($tiket->alamat)
                                <p class="text-[10px] text-slate-500 mb-1 line-clamp-1">
                                    <i class="bi bi-geo-alt mr-1 text-[#00a6eb]"></i>{{ $tiket->alamat }}
                                </p>
                                @endif
                                @if($tiket->deskripsi)
                                <p class="text-[10px] text-slate-500 line-clamp-2">{{ $tiket->deskripsi }}</p>
                                @endif
                            </div>

                            <div class="pt-2 mt-2 border-t border-slate-100 flex items-end justify-between gap-2">
                                <div>
                                    <p class="text-[8px] text-slate-400 uppercase">Mulai dari</p>
                                    <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                                </div>
                                @if(!empty($tiket->slug))
                                <a href="{{ url('wisata/' . $tiket->slug) }}" class="inline-flex items-center gap-1 text-[10px] font-bold text-[#00a6eb] hover:text-[#0090d0] transition-colors">
                                    Lihat
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Pura di Banjar -->
        @if(count($pura) > 0)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-3">Pura di Banjar Ini</h3>
            <div class="space-y-3">
                @foreach($pura as $item)
                <a href="{{ route('public.pura.detail', $item->id_pura) }}" class="block bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md hover:border-[#00a6eb]/30 transition-all">
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
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
