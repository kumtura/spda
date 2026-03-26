@extends('mobile_layout')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('administrator/') }}" class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
        <h1 class="text-xl font-black tracking-tight text-slate-800">Bayar Iuran Bulanan</h1>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-100 rounded-3xl p-5 mb-8">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-[#00a6eb] text-white rounded-lg flex items-center justify-center shrink-0">
                <i class="bi bi-info-circle text-md"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-[#00a6eb] uppercase tracking-widest mb-1">Informasi</p>
                <p class="text-[11px] text-slate-600 font-medium leading-relaxed">
                    Pembayaran iuran bulanan digunakan untuk operasional Desa Adat dan Banjar. Pastikan data nominal sesuai dengan anggaran yang ditentukan.
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ url('administrator/post_pembayaran_baru/' . Auth::user()->id) }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Nominal Iuran (Rp)</label>
            <input type="number" name="nominal" placeholder="Contoh: 50000" required
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-[#00a6eb] transition-colors py-4 px-1 text-lg font-black text-slate-800 focus:outline-none placeholder-slate-300">
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Periode Bulan</label>
            <select name="bulan" required
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-[#00a6eb] transition-colors py-4 px-1 text-sm font-bold text-slate-800 focus:outline-none appearance-none">
                <option value="{{ date('m') }}">{{ date('F Y') }} (Bulan Ini)</option>
                <option value="{{ date('m', strtotime('+1 month')) }}">{{ date('F Y', strtotime('+1 month')) }}</option>
            </select>
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Keterangan (Opsional)</label>
            <textarea name="keterangan" rows="3" placeholder="Tambahkan catatan jika ada..."
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-[#00a6eb] transition-colors py-4 px-1 text-sm font-medium text-slate-800 focus:outline-none placeholder-slate-300 resize-none"></textarea>
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="w-full bg-[#00a6eb] hover:bg-[#0095d4] py-4 rounded-3xl text-white font-black tracking-tight shadow-lg shadow-[#00a6eb]/20 transition-all flex items-center justify-center gap-2">
                Bayar Sekarang
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </form>
</div>
@endsection
