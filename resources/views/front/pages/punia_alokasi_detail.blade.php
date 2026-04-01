@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-6 pb-24 space-y-6">

    <!-- Back -->
    <a href="{{ route('public.punia') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-bold transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Cover Image -->
    @if($alokasi->foto && is_array($alokasi->foto) && count($alokasi->foto) > 0)
        <div class="h-48 rounded-2xl overflow-hidden bg-slate-100">
            <img src="{{ asset($alokasi->foto[0]) }}" class="w-full h-full object-cover" alt="{{ $alokasi->judul }}">
        </div>
    @endif

    <!-- Article Header -->
    <div>
        <div class="flex items-center gap-2 mb-2">
            <span class="text-[9px] text-[#00a6eb] font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($alokasi->tanggal_alokasi)->translatedFormat('d F Y') }}</span>
            @if($alokasi->kategori)
            <span class="text-[9px] text-slate-400">•</span>
            <span class="text-[9px] text-slate-500 font-bold">{{ $alokasi->kategori->nama_kategori }}</span>
            @endif
        </div>
        <h1 class="text-xl font-black text-slate-800 leading-tight mb-3">{{ $alokasi->judul }}</h1>
        <p class="text-xs text-slate-600">
            <span class="font-semibold">Dana terpakai:</span> 
            <span class="font-bold text-[#00a6eb]">Rp {{ number_format($alokasi->nominal, 0, ',', '.') }}</span>
        </p>
    </div>

    <!-- Description -->
    @if($alokasi->deskripsi)
    <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
        <p class="whitespace-pre-line">{{ $alokasi->deskripsi }}</p>
    </div>
    @endif

    <!-- Gallery Section -->
    @if($alokasi->foto && is_array($alokasi->foto) && count($alokasi->foto) > 0)
    <div class="space-y-3">
        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Dokumentasi</h4>
        <div class="grid grid-cols-2 gap-3">
            @foreach($alokasi->foto as $index => $foto)
                <div class="aspect-square bg-slate-50 rounded-xl overflow-hidden border border-slate-100 cursor-pointer hover:opacity-90 transition-opacity" 
                     onclick="openLightbox({{ $index }})">
                    <img src="{{ asset($foto) }}" class="w-full h-full object-cover" alt="Foto {{ $index + 1 }}">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Share Section -->
    <div class="pt-6 border-t border-slate-100 space-y-4">
        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Bagikan</h4>
        <div class="flex items-center gap-3">
            <a href="https://wa.me/?text={{ urlencode($alokasi->judul . ' - ' . url()->current()) }}" target="_blank" class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-whatsapp"></i>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-facebook"></i>
            </a>
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($alokasi->judul) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="h-10 w-10 bg-sky-50 text-sky-500 rounded-xl flex items-center justify-center transition-all active:scale-90">
                <i class="bi bi-twitter-x"></i>
            </a>
            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(() => alert('Link berhasil disalin!'))" class="h-10 w-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center transition-all active:scale-90 ml-auto">
                <i class="bi bi-link-45deg text-lg"></i>
            </button>
        </div>
    </div>

    <div class="h-px w-full bg-slate-100"></div>

    <!-- Recent Alokasi -->
    @if($recent_alokasi->count() > 0)
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Alokasi Lainnya</h3>
        <div class="space-y-3">
            @foreach($recent_alokasi as $item)
                <a href="{{ route('public.punia.alokasi.detail', $item->id_alokasi_punia) }}" class="flex gap-3 items-center group bg-white border border-slate-50 p-2 rounded-2xl hover:border-blue-100 transition-all shadow-sm">
                    <div class="h-14 w-14 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                        @if($item->foto && is_array($item->foto) && count($item->foto) > 0)
                            @php
                                $itemPath = $item->foto[0];
                                if (!str_contains($itemPath, '/')) $itemPath = 'storage/alokasi_punia/' . $itemPath;
                            @endphp
                            <img src="{{ asset($itemPath) }}" class="h-full w-full object-cover" alt="">
                        @else
                            <div class="h-full w-full flex items-center justify-center">
                                <i class="bi bi-image text-slate-200"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-black text-slate-800 leading-snug group-hover:text-[#00a6eb] transition-colors line-clamp-1 tracking-tight">{{ $item->judul }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-[9px] font-bold text-slate-400 capitalize">{{ \Carbon\Carbon::parse($item->tanggal_alokasi)->translatedFormat('d M Y') }}</p>
                            <span class="text-[9px] text-slate-300">•</span>
                            <p class="text-[9px] font-black text-[#00a6eb]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Lightbox Modal -->
    @if($alokasi->foto && is_array($alokasi->foto) && count($alokasi->foto) > 0)
    <div id="lightbox" class="hidden fixed inset-0 z-70 bg-black/95 backdrop-blur-sm" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 h-10 w-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors z-10">
            <i class="bi bi-x text-2xl"></i>
        </button>
        <div class="h-full flex items-center justify-center p-4" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" class="max-h-full max-w-full object-contain rounded-lg" alt="">
        </div>
        <!-- Navigation -->
        <button onclick="event.stopPropagation(); prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 h-12 w-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
            <i class="bi bi-chevron-left text-2xl"></i>
        </button>
        <button onclick="event.stopPropagation(); nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 h-12 w-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-colors">
            <i class="bi bi-chevron-right text-2xl"></i>
        </button>
        <!-- Counter -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full">
            <span id="lightbox-counter" class="text-white text-xs font-bold"></span>
        </div>
    </div>

    <script>
        const images = @json(array_map(fn($foto) => asset($foto), $alokasi->foto));
        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function updateLightbox() {
            document.getElementById('lightbox-img').src = images[currentIndex];
            document.getElementById('lightbox-counter').textContent = `${currentIndex + 1} / ${images.length}`;
        }

        function nextImage() {
            currentIndex = (currentIndex + 1) % images.length;
            updateLightbox();
        }

        function prevImage() {
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            updateLightbox();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!document.getElementById('lightbox').classList.contains('hidden')) {
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') nextImage();
                if (e.key === 'ArrowLeft') prevImage();
            }
        });
    </script>
    @endif
</div>
@endsection
