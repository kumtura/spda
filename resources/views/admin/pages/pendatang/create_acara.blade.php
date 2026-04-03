@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/pendatang') }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Kembali
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Buat Acara Punia</h1>
        <p class="text-slate-500 font-medium text-sm">Tagihan otomatis untuk semua pendatang aktif.</p>
    </div>

    <div class="max-w-lg">
        <form method="POST" action="{{ url('administrator/pendatang/acara/store') }}" class="space-y-6">
            @csrf

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Acara <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_acara" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                           placeholder="Contoh: Piodalan, Ngaben, dll">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none"
                              placeholder="Keterangan acara"></textarea>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nominal Punia <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="nominal" required min="0" step="1000"
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="0">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Acara</label>
                        <input type="date" name="tanggal_acara"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Batas Bayar</label>
                        <input type="date" name="batas_pembayaran"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                <p class="text-sm text-slate-600">
                    <i class="bi bi-info-circle text-primary-light mr-1"></i>
                    Tagihan akan otomatis dibuat untuk semua pendatang dengan status aktif.
                </p>
            </div>

            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-calendar-plus mr-2"></i> Buat Acara & Tagihan
            </button>
        </form>
    </div>
</div>
@endsection
