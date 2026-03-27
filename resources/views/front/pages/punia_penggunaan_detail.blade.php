@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-6 pb-24 space-y-6">

    <!-- Back -->
    <a href="{{ route('public.punia') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-bold transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Category Header -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-50/50 rounded-2xl p-6 border border-blue-100">
        <div class="flex items-start gap-4">
            <div class="h-14 w-14 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                <i class="bi {{ $kategori->ikon ?? 'bi-wallet2' }} text-[#00a6eb] text-2xl"></i>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-black text-slate-800 leading-tight">{{ $kategori->nama_kategori }}</h1>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">{{ $kategori->deskripsi_singkat ?? 'Kategori alokasi dana punia untuk pembangunan desa adat.' }}</p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-white border border-slate-100 rounded-xl p-4">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Alokasi</p>
            <p class="text-2xl font-black text-slate-800">{{ count($kategori->alokasi) }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-xl p-4">
            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Dana</p>
            <p class="text-2xl font-black text-[#00a6eb]">Rp {{ number_format($kategori->alokasi->sum('nominal'), 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="h-px w-full bg-slate-100"></div>

    <!-- Allocation List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-4">Riwayat Alokasi</h3>
        <div class="space-y-3">
            @forelse($kategori->alokasi as $alokasi)
                <div class="bg-white border border-slate-100 rounded-xl p-4 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $alokasi->judul }}</h4>
                            <p class="text-[9px] font-semibold text-slate-400 mt-1">
                                <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($alokasi->tanggal_alokasi)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($alokasi->nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    @if($alokasi->deskripsi)
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">{{ $alokasi->deskripsi }}</p>
                    @endif

                    @if($alokasi->foto && is_array($alokasi->foto) && count($alokasi->foto) > 0)
                    <div class="mt-3" x-data="{ showLightbox: false, currentImage: '' }">
                        @if(count($alokasi->foto) === 1)
                            <!-- Single Image -->
                            <div class="h-48 rounded-xl overflow-hidden bg-slate-100 cursor-pointer hover:opacity-90 transition-opacity" 
                                 @click="showLightbox = true; currentImage = '{{ asset($alokasi->foto[0]) }}'">
                                <img src="{{ asset($alokasi->foto[0]) }}" class="w-full h-full object-cover" alt="{{ $alokasi->judul }}">
                            </div>
                        @else
                            <!-- Gallery Grid -->
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($alokasi->foto as $index => $foto)
                                    @if($index < 4)
                                        <div class="relative h-32 rounded-lg overflow-hidden bg-slate-100 cursor-pointer hover:opacity-90 transition-opacity {{ $index >= 3 ? 'col-span-2' : '' }}"
                                             @click="showLightbox = true; currentImage = '{{ asset($foto) }}'">
                                            <img src="{{ asset($foto) }}" class="w-full h-full object-cover" alt="{{ $alokasi->judul }} - {{ $index + 1 }}">
                                            @if($index === 3 && count($alokasi->foto) > 4)
                                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center pointer-events-none">
                                                    <span class="text-white font-bold text-lg">+{{ count($alokasi->foto) - 4 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Lightbox Modal -->
                        <div x-show="showLightbox" 
                             x-cloak
                             @click="showLightbox = false"
                             @keydown.escape.window="showLightbox = false"
                             class="fixed inset-0 bg-black/90 z-[70] flex items-center justify-center p-4"
                             style="display: none;"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0">
                            
                            <button @click.stop="showLightbox = false" type="button" class="absolute top-4 right-4 h-10 w-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                                <i class="bi bi-x text-white text-2xl"></i>
                            </button>
                            
                            <img :src="currentImage" 
                                 @click.stop
                                 class="max-w-full max-h-full object-contain rounded-lg"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-90"
                                 x-transition:enter-end="opacity-100 scale-100">
                        </div>
                    </div>
                    @endif
                </div>
            @empty
                <div class="py-10 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <i class="bi bi-inbox text-3xl text-slate-200 mb-2 block"></i>
                    <p class="text-xs font-medium text-slate-400">Belum ada alokasi tercatat untuk kategori ini.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
