@extends('mobile_layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24">
    <!-- Hero Image -->
    <div class="relative h-64 bg-slate-100">
        @if($objek->foto)
        <img src="{{ asset('storage/wisata/'.$objek->foto) }}" 
            class="w-full h-full object-cover" 
            alt="{{ $objek->nama_objek }}">
        @else
        <div class="w-full h-full flex items-center justify-center">
            <i class="bi bi-image text-slate-300 text-5xl"></i>
        </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <!-- Back Button -->
        <a href="{{ url('wisata') }}" class="absolute top-4 left-4 h-9 w-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg">
            <i class="bi bi-arrow-left text-slate-800"></i>
        </a>
        
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <h1 class="text-xl font-black text-white mb-1">{{ $objek->nama_objek }}</h1>
            <div class="flex items-center gap-1.5 text-white/90">
                <i class="bi bi-geo-alt text-xs"></i>
                <span class="text-xs">{{ $objek->alamat }}</span>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">
        <!-- Info Cards -->
        <div class="grid grid-cols-2 gap-2">
            @if($objek->jam_buka && $objek->jam_tutup)
            <div class="bg-white border border-slate-100 rounded-xl p-3">
                <div class="flex items-center gap-2 mb-1">
                    <div class="h-7 w-7 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-clock text-[#00a6eb] text-xs"></i>
                    </div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase">Jam Buka</p>
                </div>
                <p class="text-[10px] font-bold text-slate-800">{{ $objek->jam_buka }} - {{ $objek->jam_tutup }}</p>
                <p class="text-[9px] text-slate-500">WITA</p>
            </div>
            @endif

            @if($objek->kapasitas_harian)
            <div class="bg-white border border-slate-100 rounded-xl p-3">
                <div class="flex items-center gap-2 mb-1">
                    <div class="h-7 w-7 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-people text-[#00a6eb] text-xs"></i>
                    </div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase">Kapasitas</p>
                </div>
                <p class="text-[10px] font-bold text-slate-800">{{ number_format($objek->kapasitas_harian, 0, ',', '.') }}</p>
                <p class="text-[9px] text-slate-500">Orang/Hari</p>
            </div>
            @endif
        </div>

        <!-- Description -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <h2 class="text-sm font-black text-slate-800 mb-3">Tentang Objek Wisata</h2>
            <p class="text-xs text-slate-600 leading-relaxed whitespace-pre-line">{{ $objek->deskripsi }}</p>
        </div>

        <!-- Harga Tiket Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-4 text-white">
                <p class="text-[9px] uppercase text-white/70 mb-1">Harga Tiket</p>
                @php
                    $minPrice = $objek->kategoriTiket->min('harga') ?? $objek->harga_tiket ?? 0;
                @endphp
                <h3 class="text-2xl font-black mb-0.5">Rp {{ number_format($minPrice, 0, ',', '.') }}</h3>
                <p class="text-[10px] text-white/80">Mulai dari</p>
            </div>
            
            <div class="p-4">
                @if($objek->kategoriTiket->count() > 0)
                @php
                    $orangWna = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'wna');
                    $orangLocal = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'local');
                    $orangAll = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'all');
                    $kendaraanKategori = $objek->kategoriTiket->where('tipe_kategori', 'kendaraan');
                    $hasMultipleMarkets = ($orangWna->count() > 0 && $orangLocal->count() > 0);
                @endphp

                <div class="space-y-4 mb-4">
                    @if($orangLocal->count() > 0)
                    <div>
                        @if($hasMultipleMarkets)
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-geo-alt"></i> Lokal
                            </span>
                        </div>
                        @endif
                        <div class="space-y-2">
                            @foreach($orangLocal as $kategori)
                            <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[9px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($orangWna->count() > 0)
                    <div>
                        @if($hasMultipleMarkets)
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-globe"></i> WNA
                            </span>
                        </div>
                        @endif
                        <div class="space-y-2">
                            @foreach($orangWna as $kategori)
                            <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[9px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($orangAll->count() > 0)
                    <div>
                        <div class="space-y-2">
                            @foreach($orangAll as $kategori)
                            <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[9px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($kendaraanKategori->count() > 0)
                    <div>
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-100 text-slate-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-car-front"></i> Kendaraan
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach($kendaraanKategori as $kategori)
                            <div class="flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg">
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[9px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-sm font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <a href="{{ url('wisata/beli/' . $objek->slug) }}" 
                    class="block w-full py-3 bg-[#00a6eb] text-white text-center text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i class="bi bi-ticket-perforated mr-2"></i>Beli Tiket Sekarang
                </a>
                
                <div class="mt-3 pt-3 border-t border-slate-100">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-info-circle text-slate-400 text-xs mt-0.5"></i>
                        <p class="text-[9px] text-slate-500 leading-relaxed">
                            Tiket dapat digunakan pada tanggal yang Anda pilih. Tunjukkan QR code saat memasuki objek wisata.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail & Termasuk Section -->
        @if($objek->detail_termasuk || $objek->cara_penggunaan || $objek->pembatalan || $objek->syarat_ketentuan)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 space-y-4">
            <h2 class="text-sm font-black text-slate-800">Detail & Termasuk</h2>

            @if($objek->detail_termasuk)
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-file-text text-[#00a6eb] text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-700">Deskripsi</h3>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed whitespace-pre-line pl-8">{{ $objek->detail_termasuk }}</p>
            </div>
            @endif

            @if($objek->cara_penggunaan)
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-signpost-split text-[#00a6eb] text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-700">Cara Penggunaan</h3>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed whitespace-pre-line pl-8">{{ $objek->cara_penggunaan }}</p>
            </div>
            @endif

            @if($objek->pembatalan)
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="h-6 w-6 bg-rose-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-x-circle text-rose-500 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-700">Pembatalan</h3>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed whitespace-pre-line pl-8">{{ $objek->pembatalan }}</p>
            </div>
            @endif

            @if($objek->syarat_ketentuan)
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <div class="h-6 w-6 bg-amber-50 rounded-lg flex items-center justify-center">
                        <i class="bi bi-shield-check text-amber-600 text-[10px]"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-700">Syarat & Ketentuan</h3>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed whitespace-pre-line pl-8">{{ $objek->syarat_ketentuan }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
