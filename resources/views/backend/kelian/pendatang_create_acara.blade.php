@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/pendatang') }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <h1 class="text-lg font-black">Buat Acara Punia</h1>
            <p class="text-white/80 text-[10px] mt-1">Tagihan otomatis untuk semua pendatang aktif</p>
        </div>
    </div>

    <div class="px-4 pt-4">
        <form method="POST" action="{{ url('administrator/kelian/pendatang/acara/store') }}" class="space-y-4">
            @csrf
            
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nama Acara <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_acara" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="Contoh: Piodalan, Ngaben, dll">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] resize-none"
                              placeholder="Keterangan acara"></textarea>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nominal Punia <span class="text-rose-500">*</span></label>
                    <input type="number" name="nominal" required min="0" step="1000"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="50000">
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Tanggal Acara</label>
                        <input type="date" name="tanggal_acara"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Batas Bayar</label>
                        <input type="date" name="batas_pembayaran"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <p class="text-xs text-slate-600">
                    <i class="bi bi-info-circle text-[#00a6eb] mr-1"></i>
                    Tagihan akan otomatis dibuat untuk semua pendatang dengan status aktif
                </p>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-calendar-plus mr-2"></i>Buat Acara & Tagihan
            </button>
        </form>
    </div>
</div>
@endsection
