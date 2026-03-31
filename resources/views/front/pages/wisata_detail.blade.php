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
                <div class="mb-4">
                    <p class="text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Tiket</p>
                    <div class="space-y-2">
                        @foreach($objek->kategoriTiket as $kategori)
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

                <a href="{{ url('wisata/beli/'.$objek->id_objek_wisata) }}" 
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
    </div>
</div>
@endsection
