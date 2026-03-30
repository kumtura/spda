@extends('front.layout.template')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Hero Image -->
    <div class="relative h-96 bg-slate-100">
        @if($objek->foto)
        <img src="{{ asset('storage/wisata/'.$objek->foto) }}" 
            class="w-full h-full object-cover" 
            alt="{{ $objek->nama_objek }}">
        @else
        <div class="w-full h-full flex items-center justify-center">
            <i class="bi bi-image text-slate-300 text-6xl"></i>
        </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <div class="container mx-auto">
                <h1 class="text-4xl font-black text-white mb-2">{{ $objek->nama_objek }}</h1>
                <div class="flex items-center gap-2 text-white/90">
                    <i class="bi bi-geo-alt"></i>
                    <span class="text-sm">{{ $objek->alamat }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-black text-slate-800 mb-4">Tentang Objek Wisata</h2>
                    <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $objek->deskripsi }}</p>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    @if($objek->jam_buka && $objek->jam_tutup)
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 bg-[#00a6eb] rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-clock text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 mb-1">Jam Operasional</p>
                                <p class="text-sm text-slate-600">{{ $objek->jam_buka }} - {{ $objek->jam_tutup }} WITA</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($objek->kapasitas_harian)
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-5">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 bg-[#00a6eb] rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800 mb-1">Kapasitas Harian</p>
                                <p class="text-sm text-slate-600">{{ number_format($objek->kapasitas_harian, 0, ',', '.') }} Pengunjung</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking Card -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-lg sticky top-24">
                    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white">
                        <p class="text-xs uppercase text-white/70 mb-2">Harga Tiket</p>
                        <h3 class="text-3xl font-black mb-1">Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}</h3>
                        <p class="text-xs text-white/80">Per orang</p>
                    </div>
                    
                    <div class="p-6">
                        <a href="{{ url('wisata/beli/'.$objek->id_objek_wisata) }}" 
                            class="block w-full py-3 bg-[#00a6eb] text-white text-center text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                            <i class="bi bi-ticket-perforated mr-2"></i>Beli Tiket Sekarang
                        </a>
                        
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <div class="flex items-start gap-2">
                                <i class="bi bi-info-circle text-slate-400 text-sm mt-0.5"></i>
                                <p class="text-[10px] text-slate-500 leading-relaxed">
                                    Tiket dapat digunakan pada tanggal yang Anda pilih. Tunjukkan QR code saat memasuki objek wisata.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
