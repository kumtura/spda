@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-24" x-data="{
    metode: 'xendit',
    isAnonymous: false,
    nominal: 50000
}">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-12 pb-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <a href="{{ route('public.tentang_desa') }}" class="inline-flex items-center gap-1.5 text-white/70 hover:text-white transition-colors mb-3">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>
        <h1 class="text-xl font-black text-white tracking-tight relative z-10">Punia Pura</h1>
        <p class="text-xs text-white/70 mt-1 relative z-10">{{ $pura->nama_pura }}</p>
    </div>

    <div class="px-5 -mt-4 space-y-4 relative z-10">
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-3">
            <p class="text-xs text-red-700"><i class="bi bi-x-circle-fill mr-1"></i>{{ session('error') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-3">
            @foreach($errors->all() as $error)
            <p class="text-xs text-red-700">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('public.pura.punia.submit') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="id_pura" value="{{ $pura->id_pura }}">
            <input type="hidden" name="metode" value="xendit">

            <!-- Info Donatur -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-3">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Informasi Donatur</h3>
                
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_anonymous" x-model="isAnonymous" class="rounded border-slate-300 text-[#00a6eb] focus:ring-[#00a6eb]/20">
                    <span class="text-xs text-slate-600">Donasi sebagai Hamba Tuhan (anonim)</span>
                </label>

                <div x-show="!isAnonymous" x-transition class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama</label>
                        <input type="text" name="nama_donatur" value="{{ old('nama_donatur') }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Email (opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. WhatsApp (opsional)</label>
                    <input type="text" name="no_wa" value="{{ old('no_wa') }}" placeholder="08xx..."
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                </div>
            </div>

            <!-- Nominal -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 space-y-3">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-widest">Nominal Punia</h3>
                
                <!-- Quick Amount Buttons -->
                <div class="grid grid-cols-3 gap-2">
                    @foreach([10000, 25000, 50000, 100000, 250000, 500000] as $amt)
                    <button type="button" @click="nominal = {{ $amt }}"
                            class="py-2 rounded-xl text-xs font-bold border transition-colors"
                            :class="nominal == {{ $amt }} ? 'border-[#00a6eb] bg-blue-50 text-[#00a6eb]' : 'border-slate-200 text-slate-500 hover:bg-slate-50'">
                        {{ number_format($amt, 0, ',', '.') }}
                    </button>
                    @endforeach
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Atau masukkan nominal (Rp)</label>
                    <input type="number" name="nominal" x-model="nominal" min="1000" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10">
                </div>
            </div>

            <!-- Keterangan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Keterangan / Doa (opsional)</label>
                <textarea name="keterangan" rows="2"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10"
                          placeholder="Om Swastyastu..."></textarea>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white font-bold text-sm py-3.5 rounded-xl shadow-md shadow-blue-200/50 hover:shadow-lg transition-all">
                <i class="bi bi-heart-fill mr-1.5"></i>Lanjut ke Pembayaran
            </button>
        </form>
    </div>
</div>
@endsection
