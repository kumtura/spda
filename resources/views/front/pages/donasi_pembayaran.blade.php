@extends('mobile_layout_public')

@section('content')
<style>
    /* Hide bottom navbar on this page */
    nav.fixed.bottom-0 {
        display: none !important;
    }
    .mobile-container {
        padding-bottom: 0 !important;
    }
</style>
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <a href="{{ route('public.donasi.detail', $program->id_program_donasi) }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2">Penyaluran Donasi</h1>
            <p class="text-white/80 text-xs font-medium">{{ $program->nama_program }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10">
        <form action="{{ route('public.donasi.submit') }}" method="POST" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6" 
              x-data="{ isAnonymous: false }" x-init="isAnonymous = $refs.anonCheckbox.checked">
            @csrf
            <input type="hidden" name="id_program_donasi" value="{{ $program->id_program_donasi }}">
            
            <!-- Anonymous Toggle -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" x-model="isAnonymous" x-ref="anonCheckbox" class="h-5 w-5 text-[#00a6eb] rounded">
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-800">Donasi Anonim</p>
                        <p class="text-[9px] text-slate-500 mt-0.5">Identitas Anda tidak akan ditampilkan</p>
                    </div>
                    <i class="bi bi-incognito text-[#00a6eb] text-xl"></i>
                </label>
            </div>

            <input type="hidden" name="cmb_kategori_sumbangan" :value="isAnonymous ? '1' : '2'">

            <!-- Personal Info (Hidden when anonymous) -->
            <div class="space-y-4" x-show="!isAnonymous" x-transition>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Pribadi</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="text_title_new" :required="!isAnonymous"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email</label>
                    <input type="email" name="text_email_usaha_new" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="email@example.com">
                </div>
            </div>

            <!-- Anonymous Info Message -->
            <div class="bg-slate-50 border border-slate-100 rounded-xl p-4" x-show="isAnonymous" x-transition>
                <div class="flex items-start gap-3">
                    <i class="bi bi-shield-check text-[#00a6eb] text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Donasi Anda akan dicatat sebagai "Anonim" dan identitas Anda akan tetap terjaga kerahasiaannya.</p>
                </div>
            </div>

            <!-- Payment Amount -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Jumlah Dana</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="text_minimal_pembayaran" required min="10000" step="1000"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                               placeholder="50.000">
                    </div>
                    <p class="text-[9px] text-slate-400 px-1">Minimal Rp 10.000</p>
                </div>

                <!-- Quick Amount Buttons -->
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 50000"
                            class="bg-slate-50 border border-slate-200 rounded-xl py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                        50K
                    </button>
                    <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 100000"
                            class="bg-slate-50 border border-slate-200 rounded-xl py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                        100K
                    </button>
                    <button type="button" onclick="document.querySelector('input[name=text_minimal_pembayaran]').value = 200000"
                            class="bg-slate-50 border border-slate-200 rounded-xl py-2 text-xs font-bold text-slate-600 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                        200K
                    </button>
                </div>
            </div>

            <!-- Message/Prayer -->
            <div class="space-y-1.5">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Pesan (Opsional)</label>
                <textarea name="text_pesan" rows="3"
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                          placeholder="Tuliskan Pesan Anda untuk program ini..."></textarea>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Setelah ini, Anda akan memilih metode pembayaran untuk menyelesaikan donasi.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-xl font-black text-sm shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 transition-all active:scale-[0.98]">
                Lanjutkan Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection
