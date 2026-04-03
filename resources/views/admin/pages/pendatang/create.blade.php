@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/pendatang') }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke Data Pendatang
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Pendatang</h1>
        <p class="text-slate-500 font-medium text-sm">Daftarkan pendatang baru ke desa adat.</p>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ url('administrator/pendatang/store') }}" class="space-y-6">
            @csrf
            
            @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
                <ul class="text-sm text-rose-700 space-y-1">
                    @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Identitas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="Masukkan nama lengkap">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">NIK <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" required maxlength="20" value="{{ old('nik') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="16 digit NIK">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Domisili</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Asal (Kota/Kabupaten) <span class="text-rose-500">*</span></label>
                        <input type="text" name="asal" required value="{{ old('asal') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="Contoh: Denpasar, Singaraja">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. WhatsApp/HP <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_hp" required value="{{ old('no_hp') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none"
                              placeholder="Masukkan alamat tinggal lengkap">{{ old('alamat_tinggal') }}</textarea>
                </div>
                
                <div class="max-w-md">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Pilih Banjar</label>
                    <select name="id_data_banjar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        <option value="">— Pilih Banjar —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}" {{ old('id_data_banjar') == $banjar->id_data_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <input type="hidden" name="use_global_punia" value="1">
            <input type="hidden" name="punia_rutin_bulanan" value="0">
            
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-primary-light text-lg shrink-0"></i>
                    <div>
                        <p class="text-sm font-bold text-slate-700 mb-1">Pengaturan Iuran (Punia)</p>
                        <p class="text-xs text-slate-600 leading-relaxed">Secara default, pendatang baru akan menggunakan tarif iuran global. Anda dapat menyesuaikannya nanti di halaman detail pendatang.</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-person-plus-fill mr-2"></i> Simpan Data Pendatang
            </button>
        </form>
    </div>
</div>
@endsection
