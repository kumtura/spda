@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>
<div class="bg-slate-50 min-h-screen pb-12">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>

        <a href="javascript:history.back()" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2">Data Pengunjung</h1>
            <p class="text-white/80 text-xs font-medium">Isi data untuk menerima e-ticket via WhatsApp & Email</p>
        </div>

        <!-- Step indicator -->
        <div class="relative z-10 flex items-center gap-2 mt-6">
            <div class="flex items-center gap-1.5">
                <div class="h-6 w-6 rounded-full bg-white/30 flex items-center justify-center">
                    <i class="bi bi-check text-white text-xs"></i>
                </div>
                <span class="text-[10px] font-bold text-white/60">Tiket</span>
            </div>
            <div class="flex-1 h-px bg-white/30"></div>
            <div class="flex items-center gap-1.5">
                <div class="h-6 w-6 rounded-full bg-white flex items-center justify-center">
                    <span class="text-[10px] font-black text-[#00a6eb]">2</span>
                </div>
                <span class="text-[10px] font-bold text-white">Data</span>
            </div>
            <div class="flex-1 h-px bg-white/30"></div>
            <div class="flex items-center gap-1.5">
                <div class="h-6 w-6 rounded-full bg-white/20 flex items-center justify-center">
                    <span class="text-[10px] font-bold text-white/50">3</span>
                </div>
                <span class="text-[10px] font-bold text-white/40">Bayar</span>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-6 relative z-10 space-y-5">
        <!-- Form Data Pengunjung -->
        <form action="{{ url('wisata/data-pengunjung/submit') }}" method="POST" class="bg-white rounded-3xl p-6 shadow-xl border border-slate-100 space-y-5">
            @csrf

            <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl p-4">
                <i class="bi bi-info-circle-fill text-[#00a6eb] text-base shrink-0 mt-0.5"></i>
                <p class="text-[10px] text-slate-600 leading-relaxed">Dengan mengisi data ini, e-ticket akan dikirim otomatis ke WhatsApp & Email Anda setelah pembayaran berhasil.</p>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1 mb-1.5 block">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_pengunjung" required placeholder="Masukkan nama lengkap"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]/50 outline-none transition-all">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1 mb-1.5 block">No. WhatsApp <span class="text-rose-500">*</span></label>
                <input type="tel" name="no_wa" required placeholder="08xxxxxxxxxx"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]/50 outline-none transition-all">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1 mb-1.5 block">Email</label>
                <input type="email" name="email" placeholder="email@contoh.com"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]/50 outline-none transition-all">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-2xl font-black text-sm shadow-lg shadow-blue-200/50 hover:shadow-xl hover:shadow-blue-300/50 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                <span>Lanjutkan ke Pembayaran</span>
                <i class="bi bi-arrow-right"></i>
            </button>
        </form>

        <!-- Lewati Button -->
        <form action="{{ url('wisata/data-pengunjung/submit') }}" method="POST">
            @csrf
            <input type="hidden" name="skip_biodata" value="1">
            <button type="submit"
                class="w-full bg-white border-2 border-slate-200 text-slate-500 py-4 rounded-2xl font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                <i class="bi bi-arrow-right-circle"></i>
                <span>Lewati, Langsung ke Pembayaran</span>
            </button>
        </form>

        <!-- Info lewati -->
        <div class="text-center px-6">
            <p class="text-[10px] text-slate-400 leading-relaxed">Jika dilewati, e-ticket hanya bisa diunduh langsung setelah pembayaran dan tidak akan dikirim via WhatsApp/Email.</p>
        </div>
    </div>
</div>
@endsection
