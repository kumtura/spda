@extends('front.layout.template')

@section('content')
<div class="bg-white">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl font-black mb-4">Objek Wisata Desa Adat</h1>
                <p class="text-lg text-white/90">Jelajahi keindahan dan budaya desa adat kami</p>
            </div>
        </div>
    </div>

    <!-- Objek Wisata List -->
    <div class="container mx-auto px-4 py-12">
        @php
            $objekWisata = App\Models\ObjekWisata::where('aktif', '1')->where('status', 'aktif')->get();
        @endphp

        @if($objekWisata->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($objekWisata as $objek)
            <a href="{{ url('wisata/detail/'.$objek->id_objek_wisata) }}" 
                class="bg-white rounded-2xl overflow-hidden shadow-lg border border-slate-100 hover:shadow-2xl transition-all group">
                <div class="relative h-48 bg-slate-100 overflow-hidden">
                    @if($objek->foto)
                    <img src="{{ asset('storage/wisata/'.$objek->foto) }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" 
                        alt="{{ $objek->nama_objek }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-image text-slate-300 text-5xl"></i>
                    </div>
                    @endif
                </div>
                
                <div class="p-5">
                    <h3 class="text-lg font-black text-slate-800 mb-2 group-hover:text-[#00a6eb] transition-colors">
                        {{ $objek->nama_objek }}
                    </h3>
                    <p class="text-xs text-slate-500 mb-3 line-clamp-2">{{ $objek->deskripsi }}</p>
                    
                    <div class="flex items-center gap-2 mb-3">
                        <i class="bi bi-geo-alt text-slate-400 text-xs"></i>
                        <span class="text-xs text-slate-600">{{ $objek->alamat }}</span>
                    </div>
                    
                    @if($objek->jam_buka && $objek->jam_tutup)
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-clock text-slate-400 text-xs"></i>
                        <span class="text-xs text-slate-600">{{ $objek->jam_buka }} - {{ $objek->jam_tutup }} WITA</span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div>
                            <p class="text-[9px] text-slate-400 uppercase mb-1">Harga Tiket</p>
                            <p class="text-lg font-black text-[#00a6eb]">Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}</p>
                        </div>
                        <div class="h-10 w-10 bg-[#00a6eb] rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="bi bi-arrow-right text-white"></i>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="max-w-md mx-auto text-center py-12">
            <i class="bi bi-ticket-perforated text-slate-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Objek Wisata</h3>
            <p class="text-sm text-slate-500">Objek wisata akan segera tersedia</p>
        </div>
        @endif
    </div>
</div>
@endsection
