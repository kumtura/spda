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
             class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/60 backdrop-blur-sm"
             x-cloak>
            
            <div class="bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col"
                 @click.away="showDonateModal = false"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="translate-y-full sm:scale-95"
                 x-transition:enter-end="translate-y-0 sm:scale-100">
                
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-[#00a6eb] text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest">Kirim Donasi</p>
                            <h3 class="text-sm font-black text-slate-800 truncate max-w-[200px]">{{ $program->nama_program }}</h3>
                        </div>
                    </div>
                    <button @click="showDonateModal = false" class="text-slate-400 hover:text-rose-500 p-2">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <div class="overflow-y-auto p-6 no-scrollbar">
                    <form action="{{ route('public.donasi.submit') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="id_program_donasi" value="{{ $program->id_program_donasi }}">
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Donatur</label>
                            <select name="cmb_kategori_sumbangan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                                <option value="2">Masyarakat Umum</option>
                                <option value="1">Anonim</option>
                                <option value="3">Unit Usaha / Perusahaan</option>
                            </select>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                <input type="text" name="text_title_new" placeholder="Masukkan nama Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Nominal Donasi (Rp)</label>
                                <input type="number" name="text_minimal_pembayaran" required min="1000" placeholder="Minimal Rp 1.000" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-[#00a6eb] outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pesan</label>
                                <textarea name="text_email_usaha_new" rows="2" placeholder="Tuliskan pesan Anda..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-4 rounded-xl font-black text-[11px] uppercase tracking-[0.2em] shadow-lg shadow-blue-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                            Kirim Donasi <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
