@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24">
    <!-- Hero Image -->
    <div class="relative h-56 bg-slate-100">
        @if($pura->gambar_pura)
        <img src="{{ asset($pura->gambar_pura) }}" class="w-full h-full object-cover" alt="{{ $pura->nama_pura }}" onerror="this.parentElement.classList.add('flex','items-center','justify-center'); this.outerHTML='<i class=\'bi bi-building text-slate-300 text-5xl\'></i>'">
        @else
        <div class="w-full h-full flex items-center justify-center">
            <i class="bi bi-building text-slate-300 text-5xl"></i>
        </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
        <a href="{{ route('public.pura') }}" class="absolute top-4 left-4 h-8 w-8 bg-white/20 backdrop-blur rounded-full flex items-center justify-center text-white">
            <i class="bi bi-arrow-left text-sm"></i>
        </a>
    </div>

    <div class="px-5 -mt-10 relative z-10 space-y-4">
        <!-- Title Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <h1 class="text-xl font-black text-slate-800 tracking-tight">{{ $pura->nama_pura }}</h1>
            <p class="text-xs text-slate-400 mt-1"><i class="bi bi-geo-alt"></i> {{ $pura->lokasi ?? 'Lokasi belum diisi' }}</p>
            <p class="text-xs text-slate-400"><i class="bi bi-house-door"></i> Banjar {{ $pura->nama_banjar ?? '-' }}</p>

            <div class="grid grid-cols-2 gap-3 mt-4 pt-4 border-t border-slate-100">
                <div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest mb-0.5">Ketua Pura</p>
                    <p class="text-xs font-bold text-slate-700">{{ $pura->nama_ketua_pura ?? '-' }}</p>
                    @if($pura->no_telp_ketua)
                    <a href="tel:{{ $pura->no_telp_ketua }}" class="text-[10px] text-[#00a6eb]">{{ $pura->no_telp_ketua }}</a>
                    @endif
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest mb-0.5">Pemangku</p>
                    <p class="text-xs font-bold text-slate-700">{{ $pura->nama_pemangku ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest mb-0.5">Wuku Odalan</p>
                    <p class="text-xs font-bold text-[#00a6eb]">{{ $pura->wuku_odalan ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 uppercase tracking-widest mb-0.5">Odalan Terdekat</p>
                    <p class="text-xs font-bold text-slate-700">{{ $pura->odalan_terdekat ? \Carbon\Carbon::parse($pura->odalan_terdekat)->format('d M Y') : '-' }}</p>
                </div>
            </div>

            @if($pura->deskripsi)
            <div class="mt-4 pt-4 border-t border-slate-100">
                <p class="text-xs text-slate-500 leading-relaxed">{{ $pura->deskripsi }}</p>
            </div>
            @endif
        </div>

        <!-- Gallery -->
        @if($gallery->count() > 0)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Gallery</h3>
            <div class="flex gap-2 overflow-x-auto pb-2 -mx-5 px-5">
                @foreach($gallery as $g)
                <img src="{{ asset($g->gambar) }}" class="h-24 w-32 rounded-xl object-cover flex-shrink-0" alt="{{ $g->caption ?? '' }}">
                @endforeach
            </div>
        </div>
        @endif

        <!-- Punia Stats -->
        <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-5 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <p class="text-[9px] text-white/60 uppercase tracking-widest">Total Punia Terkumpul</p>
                <h2 class="text-2xl font-black mt-1">Rp {{ number_format($totalPunia, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Punia Action Buttons -->
        <div class="space-y-2">
            <a href="{{ route('public.pura.punia', $pura->id_pura) }}" class="block w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white font-bold text-sm py-3 rounded-xl text-center shadow-md shadow-blue-200/50 hover:shadow-lg transition-all">
                <i class="bi bi-wallet2 mr-1.5"></i> Punia Online (Xendit)
            </a>
            @if($qris)
            <div class="bg-white rounded-2xl border border-slate-100 p-4 text-center">
                <p class="text-xs font-bold text-slate-700 mb-3">Scan QRIS BPD Bali</p>
                @if($qris->qris_image)
                <img src="{{ asset($qris->qris_image) }}" class="h-48 w-48 mx-auto rounded-xl border border-slate-200" alt="QRIS">
                @endif
                <p class="text-[10px] text-slate-400 mt-2">{{ $qris->merchant_name ?? $pura->nama_pura }}</p>
                <p class="text-[10px] text-slate-400">Scan menggunakan aplikasi m-Banking atau e-Wallet</p>
            </div>
            @endif
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
            <p class="text-xs text-emerald-700"><i class="bi bi-check-circle-fill mr-1"></i>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Recent Punia -->
        @if($recentPunia->count() > 0)
        <div>
            <h3 class="text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Punia Terbaru</h3>
            <div class="space-y-2">
                @foreach($recentPunia as $item)
                <div class="bg-white rounded-xl border border-slate-100 px-4 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-slate-700">
                            @if($item->is_anonymous)
                            <span class="italic text-slate-400">Hamba Tuhan</span>
                            @else
                            {{ $item->nama_donatur ?? 'Anonim' }}
                            @endif
                        </p>
                        <p class="text-[10px] text-slate-400">{{ $item->created_at->format('d M Y') }}</p>
                    </div>
                    <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Map Link -->
        @if($pura->latitude && $pura->longitude)
        <a href="https://maps.google.com/?q={{ $pura->latitude }},{{ $pura->longitude }}" target="_blank" rel="noopener" 
           class="block bg-slate-50 rounded-xl border border-slate-100 p-4 text-center hover:bg-slate-100 transition-colors">
            <i class="bi bi-map text-slate-400 text-xl mb-1"></i>
            <p class="text-xs font-bold text-slate-600">Lihat Lokasi di Maps</p>
        </a>
        @endif
    </div>
</div>
@endsection
