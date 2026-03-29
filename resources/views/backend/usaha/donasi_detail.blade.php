@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-28" x-data="{ showDonateModal: false }">

    <!-- Hero Section -->
    <div class="relative h-[180px] bg-slate-100 flex items-center justify-center overflow-hidden">
        @if($program->foto)
            <img src="{{ asset('storage/program_donasi/'.$program->foto) }}" class="w-full h-full object-cover" alt="{{ $program->nama_program }}">
        @else
            <i class="bi bi-image text-[60px] text-slate-200"></i>
        @endif
        @if($program->kategori)
        <span class="absolute top-5 right-4 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded uppercase">{{ $program->kategori->nama_kategori }}</span>
        @endif
        <a href="{{ url('administrator/usaha/donasi') }}" class="absolute top-5 left-4 h-9 w-9 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-slate-600 border border-white/50 active:scale-90 transition-transform">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="px-4 -mt-5 relative z-10 space-y-5">

        <!-- Title Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3.5">
            <h1 class="text-base font-bold text-slate-800 leading-tight">{{ $program->nama_program }}</h1>
            
            <div>
                <p class="text-[9px] font-bold uppercase text-slate-400 mb-1">Dana terkumpul</p>
                <h3 class="text-2xl font-black text-[#00a6eb]">Rp {{ number_format($program->terkumpul, 0, ',', '.') }}</h3>
            </div>

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
                    <div class="bg-[#00a6eb] h-2 rounded-full" style="width: {{ $pct }}%"></div>
                </div>
            </div>
        </div>

        @if($program->deskripsi)
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5">
            <h4 class="text-xs font-bold text-slate-800">Deskripsi Program</h4>
            <p class="text-xs text-slate-600 leading-relaxed">{{ $program->deskripsi }}</p>
        </div>
        @endif

        <!-- Kontribusi Terkini -->
        <div>
            <h4 class="text-xs font-bold text-slate-800 mb-3">Kontribusi Terkini</h4>
            <div class="space-y-2.5">
                @forelse($donatur as $item)
                    <div class="bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                        <div class="h-9 w-9 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center shrink-0 overflow-hidden">
                            @if($item->status_donatur != '1' && $item->profile && file_exists(public_path('storage/usaha/icon/'.$item->profile)))
                                <img src="{{ asset('storage/usaha/icon/'.$item->profile) }}" class="h-full w-full object-cover" alt="">
                            @elseif($item->status_donatur != '1' && $item->profile && file_exists(public_path('sumbangan/thumbnail/'.$item->profile)))
                                <img src="{{ asset('sumbangan/thumbnail/'.$item->profile) }}" class="h-full w-full object-cover" alt="">
                            @else
                                <i class="bi bi-{{ $item->status_donatur == '3' ? 'shop' : 'person' }} text-slate-300"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $item->nama ?: 'Donatur Anonim' }}</p>
                            <p class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}</p>
                        </div>
                        <p class="text-[10px] font-bold text-[#00a6eb]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="bi bi-people text-3xl text-slate-200 mb-2 block"></i>
                        <p class="text-xs font-bold text-slate-400">Belum ada donatur</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Donate Button -->
    <div class="fixed bottom-[75px] left-1/2 -translate-x-1/2 w-full max-w-[480px] px-5 z-40">
        <button @click="showDonateModal = true" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-3.5 rounded-xl font-bold text-xs uppercase tracking-wider shadow-xl transition-all active:scale-95 flex items-center justify-center gap-2">
            <i class="bi bi-heart-fill"></i> Donasi Sekarang
        </button>
    </div>

    <!-- Modal Donasi -->
    <template x-teleport="body">
        <div x-show="showDonateModal" 
             x-cloak
             @click.self="showDonateModal = false"
             @keydown.escape.window="showDonateModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            
            <div @click.stop 
                 class="bg-white rounded-2xl max-w-md w-full overflow-hidden"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-8"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                
                <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-5 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                    <button @click="showDonateModal = false" type="button" class="absolute top-3 right-3 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                    <div class="relative">
                        <h3 class="text-lg font-bold">Salurkan Donasi</h3>
                        <p class="text-white/80 text-[10px] mt-1">Sebagai Unit Usaha</p>
                    </div>
                </div>

                @php
                    $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
                        ->where('tb_usaha.username', Auth::user()->email)->first();
                @endphp

                <form action="{{ route('public.donasi.submit') }}" method="POST" class="p-5 space-y-4" x-data="{ isAnonymous: false }">
                    @csrf
                    <input type="hidden" name="id_program_donasi" value="{{ $program->id_program_donasi }}">
                    <input type="hidden" name="tipe_donatur" value="usaha">
                    <input type="hidden" name="cmb_kategori_sumbangan" :value="isAnonymous ? '1' : '3'">
                    
                    <!-- Anonymous Toggle -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" x-model="isAnonymous" class="h-4 w-4 text-[#00a6eb] rounded">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-800">Donasi Anonim</p>
                                <p class="text-[9px] text-slate-500">Identitas tidak ditampilkan</p>
                            </div>
                        </label>
                    </div>

                    <!-- Nama Unit Usaha (Hidden when anonymous) -->
                    <div x-show="!isAnonymous" x-transition>
                        <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Nama Unit Usaha</label>
                        <input type="text" name="text_title_new" value="{{ $myUsaha->nama_usaha ?? Auth::user()->name }}" :required="!isAnonymous" readonly
                               class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm font-bold text-slate-600 cursor-not-allowed">
                        <p class="text-[9px] text-slate-400 mt-1">Nama usaha tidak dapat diubah</p>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Jumlah Donasi (Rp)</label>
                        <input type="number" name="text_minimal_pembayaran" value="{{ $program->minimal_donasi ?? 50000 }}" min="10000" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                        <p class="text-[9px] text-slate-400 mt-1">Minimal Rp 10.000</p>
                    </div>
                    
                    <!-- Quick Amount Buttons -->
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 50000"
                                class="bg-slate-50 border border-slate-200 rounded-lg py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                            50K
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 100000"
                                class="bg-slate-50 border border-slate-200 rounded-lg py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                            100K
                        </button>
                        <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 200000"
                                class="bg-slate-50 border border-slate-200 rounded-lg py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                            200K
                        </button>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Pesan (Opsional)</label>
                        <textarea name="text_pesan" rows="2" placeholder="Tulis pesan dukungan..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3 rounded-xl transition-all text-sm">
                        <i class="bi bi-send-fill mr-2"></i> Lanjutkan Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
