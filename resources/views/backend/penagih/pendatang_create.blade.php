@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/penagih/pendatang') }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            <h1 class="text-2xl font-black mb-1">Tambah Pendatang</h1>
            <p class="text-white/80 text-xs font-medium uppercase tracking-widest">Daftarkan pendatang baru</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="px-4 -mt-6 relative z-10">
        <form method="POST" action="{{ url('administrator/penagih/pendatang/store') }}" 
              class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            
            @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                <ul class="text-xs text-rose-600 space-y-1">
                    @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest px-1">Informasi Identitas</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama" required value="{{ old('nama') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="Masukkan nama lengkap">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        NIK (Nomor Induk Kependudukan) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nik" required maxlength="20" value="{{ old('nik') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="16 digit NIK">
                </div>
            </div>

            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest px-1">Informasi Kontak & Domisili</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Asal (Kota/Kabupaten) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="asal" required value="{{ old('asal') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="Contoh: Denpasar, Singaraja">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Nomor WhatsApp/HP <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="no_hp" required value="{{ old('no_hp') }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="08xxxxxxxxxx">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Alamat Lengkap di Banjar
                    </label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all resize-none"
                              placeholder="Masukkan alamat tinggal lengkap">{{ old('alamat_tinggal') }}</textarea>
                </div>
            </div>

            <!-- Lama Tinggal Section -->
            <div class="space-y-4" x-data="{ belumYakin: false }">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest px-1">Lama Tinggal</h3>

                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-xs font-bold text-slate-700">Belum yakin / Not sure yet</p>
                        <p class="text-[9px] text-slate-400">Centang jika belum menentukan durasi tinggal</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tinggal_belum_yakin" value="1" x-model="belumYakin" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                    </label>
                </div>

                <div x-show="!belumYakin" x-transition class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Dari Tanggal</label>
                        <input type="date" name="tinggal_dari"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sampai Tanggal</label>
                        <input type="date" name="tinggal_sampai"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                </div>
            </div>

            <!-- Hidden Punia Settings -->
            <input type="hidden" name="use_global_punia" value="1">
            <input type="hidden" name="punia_rutin_bulanan" value="0">
            
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-bold text-slate-700 mb-1">Pengaturan Iuran (Punia)</p>
                        <p class="text-[10px] text-slate-600 leading-relaxed">Secara default, pendatang baru akan menggunakan tarif iuran global. Anda dapat menyesuaikannya nanti di halaman detail pendatang jika diperlukan.</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-2xl font-black text-sm shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 transition-all active:scale-[0.98]">
                <i class="bi bi-person-plus-fill mr-2"></i> Simpan Data Pendatang
            </button>
        </form>
    </div>
</div>
@endsection
