@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-8 pb-24 space-y-8">

    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Dana Punia</h2>
        <p class="text-[10px] text-slate-400 font-bold mt-1">Transparansi pengelolaan dana desa adat.</p>
    </div>

    <!-- Stats Card -->
    <div class="bg-[#00a6eb] rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-wallet2 text-lg"></i>
            </div>
            <span class="text-[8px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-full">Terverifikasi</span>
        </div>
        <p class="text-[9px] font-bold uppercase tracking-wider text-white/60 mb-1">Total Dana Terkumpul</p>
        <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($total_punia, 0, ',', '.') }}</h3>
    </div>

    <!-- Info -->
    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
        <h4 class="text-sm font-bold text-slate-800 mb-3">Tentang Dana Punia</h4>
        <p class="text-xs text-slate-500 leading-relaxed">Dana Punia merupakan kontribusi dari krama desa dan unit usaha untuk mendukung pembangunan sarana keagamaan, sosial, dan budaya di Desa Adat.</p>
    </div>

    <!-- Penggunaan Dana -->
    <div>
        <h4 class="text-sm font-bold text-slate-800 mb-4">Penggunaan Dana</h4>
        <div class="space-y-3">
            @forelse($kategori_punia as $kat)
                <a href="{{ route('public.punia.penggunaan', $kat->id_kategori_punia) }}" 
                   class="block bg-white rounded-xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-md transition-all group">
                    <div class="p-4 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-blue-50/80 rounded-xl flex items-center justify-center shrink-0 border border-blue-100/50 group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb] transition-colors">
                                <i class="bi {{ $kat->ikon ?? 'bi-wallet2' }} text-[#00a6eb] text-lg group-hover:text-white transition-colors"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 group-hover:text-[#00a6eb] transition-colors">{{ $kat->nama_kategori }}</p>
                                <p class="text-[10px] font-semibold text-slate-400 mt-0.5">{{ count($kat->alokasi) }} Transaksi • Rp {{ number_format($kat->alokasi->sum('nominal'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="h-8 w-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 text-slate-400 group-hover:bg-[#00a6eb] group-hover:text-white group-hover:border-[#00a6eb] transition-all">
                            <i class="bi bi-arrow-right text-sm"></i>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-100">
                    <i class="bi bi-clock-history text-2xl mb-2 block"></i>
                    <p class="text-xs font-medium">Belum ada kategori penggunaan dana yang dipublikasikan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- CTA -->
    <div x-data="{ showModal: false }">
        <button @click="showModal = true" type="button" class="block w-full bg-white border border-[#00a6eb]/20 rounded-2xl p-4 shadow-sm group hover:shadow-md transition-shadow text-left">
            <div class="flex items-center gap-3">
                <span class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded border border-blue-100">Punia</span>
                <h3 class="text-slate-800 font-bold text-sm leading-tight flex-1">Salurkan Dana Punia Sekarang</h3>
                <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-[#00a6eb] border border-blue-100 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors shrink-0">
                    <i class="bi bi-arrow-right text-sm"></i>
                </div>
            </div>
        </button>

        <!-- Modal -->
        <div x-show="showModal" 
             x-cloak
             @click.self="showModal = false"
             @keydown.escape.window="showModal = false"
             class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] flex items-center justify-center p-4"
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.stop 
                 class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-90"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-90">
                
                <!-- Header -->
                <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    <button @click="showModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                    <div class="relative">
                        <h3 class="text-xl font-black">Salurkan Dana Punia</h3>
                        <p class="text-white/80 text-xs font-medium mt-1">Pilih kategori Anda untuk melanjutkan</p>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-3">
                    <!-- Masyarakat Umum Option -->
                    <a href="{{ route('public.punia.pembayaran') }}" 
                       class="block bg-gradient-to-br from-blue-50 to-blue-50/50 border-2 border-blue-100 rounded-2xl p-5 hover:border-[#00a6eb] hover:shadow-lg transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                                <i class="bi bi-people-fill text-[#00a6eb] text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Masyarakat Umum</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Untuk krama desa dan masyarakat umum yang ingin berkontribusi</p>
                                <div class="mt-3 flex items-center gap-2 text-[#00a6eb]">
                                    <span class="text-[9px] font-bold uppercase tracking-wider">Bayar Sekarang</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>

                    <!-- Unit Usaha Option -->
                    <a href="{{ route('login') }}" 
                       class="block bg-gradient-to-br from-emerald-50 to-emerald-50/50 border-2 border-emerald-100 rounded-2xl p-5 hover:border-emerald-500 hover:shadow-lg transition-all group">
                        <div class="flex items-start gap-4">
                            <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm group-hover:shadow-md transition-shadow">
                                <i class="bi bi-shop text-emerald-600 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-black text-slate-800 mb-1">Unit Usaha</h4>
                                <p class="text-[10px] text-slate-500 leading-relaxed">Untuk unit usaha terdaftar yang ingin menyalurkan dana punia</p>
                                <div class="mt-3 flex items-center gap-2 text-emerald-600">
                                    <span class="text-[9px] font-bold uppercase tracking-wider">Login Terlebih Dahulu</span>
                                    <i class="bi bi-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="px-6 pb-6">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-slate-400 text-lg shrink-0"></i>
                            <p class="text-[10px] text-slate-500 leading-relaxed">Penggunaan dana punia akan ditampilkan secara transparan untuk akuntabilitas kepada masyarakat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
