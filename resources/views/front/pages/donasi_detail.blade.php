@extends('mobile_layout_public')

@section('content')
<div class="bg-white pb-28" x-data="{ 
    showDonateModal: false 
}">

    <!-- Hero Section -->
    <div class="relative h-[200px] bg-slate-100 flex items-center justify-center overflow-hidden">
        @if($program->foto)
            <img src="{{ asset('storage/program_donasi/'.$program->foto) }}" class="w-full h-full object-cover" alt="{{ $program->nama_program }}">
        @else
            <i class="bi bi-image text-[80px] text-slate-200/80"></i>
        @endif
        @if($program->kategori)
        <span class="absolute top-6 right-4 bg-[#00a6eb] text-white text-[8px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider shadow-md">{{ $program->kategori->nama_kategori }}</span>
        @endif
        <!-- Back Button -->
        <a href="{{ route('public.donasi') }}" class="absolute top-6 left-4 h-9 w-9 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-slate-600 shadow-md border border-white/50 active:scale-90 transition-transform">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="px-5 -mt-6 relative z-10 space-y-6">

        <!-- Title Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 space-y-4">
            <h1 class="text-lg font-black text-slate-800 leading-tight">{{ $program->nama_program }}</h1>
            
            <div class="flex items-center gap-2">
                <div class="h-7 w-7 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                    <i class="bi bi-shield-check text-[#00a6eb] text-xs"></i>
                </div>
                <span class="text-[10px] font-bold text-slate-500">Terverifikasi oleh SPDA</span>
            </div>

            <!-- Amount -->
            <div>
                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Dana terkumpul</p>
                <h3 class="text-2xl font-black text-[#00a6eb] tracking-tight">Rp {{ number_format($program->terkumpul, 0, ',', '.') }}</h3>
            </div>

            <!-- Progress -->
            @php
                $target = $program->target_dana ?: 1;
                $pct = min(100, round(($program->terkumpul / $target) * 100));
            @endphp
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] text-slate-400 font-bold">{{ $pct }}% dari target</span>
                    <span class="text-[9px] text-slate-400 font-bold">Rp {{ number_format($program->target_dana, 0, ',', '.') }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-[#00a6eb] h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
            <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Deskripsi Program -->
        @if($program->deskripsi)
        <div class="bg-white rounded-2xl border border-slate-100 p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-800">Deskripsi Program</h4>
                @if($program->tanggal_mulai)
                <span class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($program->tanggal_mulai)->translatedFormat('d M Y') }}</span>
                @endif
            </div>
            <p class="text-xs text-slate-500 leading-relaxed">{{ $program->deskripsi }}</p>
        </div>
        @endif

        <!-- Kontribusi Terkini -->
        <div>
            <h4 class="text-sm font-bold text-slate-800 mb-4">Kontribusi Terkini</h4>
            <div class="space-y-3">
                @forelse($donatur as $item)
                    <div class="bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                        <div class="h-10 w-10 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center shrink-0 overflow-hidden">
                            @if($item->profile && file_exists(public_path('sumbangan/thumbnail/'.$item->profile)))
                                <img src="{{ asset('sumbangan/thumbnail/'.$item->profile) }}" class="h-full w-full object-cover" alt="">
                            @else
                                <i class="bi bi-person text-slate-300"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $item->nama ?: 'Donatur Anonim' }}</p>
                            <p class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[11px] font-black text-[#00a6eb]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <i class="bi bi-people text-3xl text-slate-200 mb-2 block"></i>
                        <p class="text-xs font-bold text-slate-400">Belum ada donatur.</p>
                        <p class="text-[10px] text-slate-400 mt-1">Jadilah yang pertama berdonasi untuk program ini!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Donate Button -->
    <div class="fixed bottom-[75px] left-1/2 -translate-x-1/2 w-full max-w-[480px] px-5 z-40">
        <button @click="showDonateModal = true" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-white/20">
            <i class="bi bi-heart-fill"></i> Donasi Sekarang
        </button>
    </div>

    <!-- Modal Donasi -->
    <template x-teleport="body">
        <div x-show="showDonateModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             x-cloak
             @click.self="showDonateModal = false">
            
            <div @click.stop 
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <!-- Header -->
                <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <button @click="showDonateModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                    <div class="relative">
                        <h3 class="text-xl font-black">Salurkan Donasi</h3>
                        <p class="text-white/80 text-xs font-medium mt-1">Pilih kategori Anda untuk melanjutkan</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-3 overflow-y-auto no-scrollbar max-h-[60vh]">
                    <!-- Masyarakat Umum Option -->
                    <a href="{{ route('public.donasi.pembayaran', $program->id_program_donasi) }}" 
                       class="block bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg hover:shadow-blue-500/5 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                                <i class="bi bi-people-fill text-slate-400 text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Masyarakat Umum</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Untuk krama desa dan masyarakat umum yang ingin berkontribusi</p>
                                <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Donasi Sekarang</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Unit Usaha Option -->
                    <a href="{{ route('login') }}" 
                       class="block bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:shadow-lg hover:shadow-blue-500/5 transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                                <i class="bi bi-shop text-slate-400 text-xl group-hover:text-white"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Unit Usaha / Investor</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Gunakan akun bisnis Anda untuk penyaluran donasi resmi</p>
                                <div class="mt-3 flex items-center gap-2 text-slate-400 group-hover:text-[#00a6eb]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider transition-colors">Login Terlebih Dahulu</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="px-6 pb-6 pt-2">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-slate-400 text-lg shrink-0"></i>
                            <p class="text-[10px] text-slate-500 leading-relaxed">Setiap donasi yang masuk akan diverifikasi secara transparan untuk akuntabilitas publik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
