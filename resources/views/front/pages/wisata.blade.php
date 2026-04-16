@extends('mobile_layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <h1 class="text-xl font-black mb-1">Objek Wisata</h1>
            <p class="text-white/80 text-xs">Jelajahi destinasi wisata desa adat</p>
        </div>
    </div>

    <div class="px-4 -mt-6">
        <!-- Search Bar -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-3 mb-4 relative z-10">
            <div class="relative">
                <input type="text" id="searchInput" 
                    class="w-full pl-10 pr-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb]"
                    placeholder="Cari objek wisata...">
                <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
            </div>
        </div>

        @if($objekWisata->count() > 0)
        <div id="wisataGrid" class="grid grid-cols-2 gap-3">
            @foreach($objekWisata as $objek)
            <a href="{{ url('wisata/' . $objek->slug) }}" 
                class="wisata-card block bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-md transition-all"
                data-name="{{ strtolower($objek->nama_objek) }}"
                data-desc="{{ strtolower($objek->deskripsi) }}"
                data-alamat="{{ strtolower($objek->alamat) }}">
                <div class="relative h-32 bg-slate-100 overflow-hidden">
                    @if($objek->foto)
                    <img src="{{ asset('storage/wisata/'.$objek->foto) }}" 
                        class="w-full h-full object-cover" 
                        alt="{{ $objek->nama_objek }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i class="bi bi-image text-slate-300 text-3xl"></i>
                    </div>
                    @endif
                </div>
                
                <div class="p-3">
                    <h3 class="text-xs font-black text-slate-800 mb-1.5 line-clamp-2 leading-tight">
                        {{ $objek->nama_objek }}
                    </h3>
                    
                    <div class="flex items-start gap-1 mb-2">
                        <i class="bi bi-geo-alt text-slate-400 text-[9px] mt-0.5 shrink-0"></i>
                        <span class="text-[9px] text-slate-600 line-clamp-1">{{ $objek->alamat }}</span>
                    </div>
                    
                    <div class="pt-2 border-t border-slate-100">
                        <p class="text-[8px] text-slate-400 uppercase mb-0.5">Mulai dari</p>
                        @php
                            $minPrice = $objek->kategoriTiket->min('harga') ?? $objek->harga_tiket ?? 0;
                        @endphp
                        <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($minPrice, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div id="noResults" class="hidden text-center py-12">
            <i class="bi bi-search text-slate-300 text-5xl mb-3"></i>
            <h3 class="text-sm font-bold text-slate-800 mb-1">Tidak Ada Hasil</h3>
            <p class="text-xs text-slate-500">Coba kata kunci lain</p>
        </div>
        @else
        <div class="text-center py-12">
            <i class="bi bi-ticket-perforated text-slate-300 text-5xl mb-3"></i>
            <h3 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Objek Wisata</h3>
            <p class="text-xs text-slate-500">Objek wisata akan segera tersedia</p>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const wisataCards = document.querySelectorAll('.wisata-card');
    const wisataGrid = document.getElementById('wisataGrid');
    const noResults = document.getElementById('noResults');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            wisataCards.forEach(card => {
                const name = card.dataset.name;
                const desc = card.dataset.desc;
                const alamat = card.dataset.alamat;
                
                if (name.includes(searchTerm) || desc.includes(searchTerm) || alamat.includes(searchTerm)) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0 && searchTerm !== '') {
                wisataGrid.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                wisataGrid.classList.remove('hidden');
                noResults.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
