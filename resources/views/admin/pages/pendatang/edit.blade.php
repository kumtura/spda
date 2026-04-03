@extends('index')

@section('isi_menu')
@php
    $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
    $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
@endphp

<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/pendatang/detail/'.$pendatang->id_pendatang) }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke Detail
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Pendatang</h1>
        <p class="text-slate-500 font-medium text-sm">Update informasi {{ $pendatang->nama }}.</p>
    </div>

    <div class="max-w-2xl">
        <form method="POST" action="{{ url('administrator/pendatang/update/'.$pendatang->id_pendatang) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                        <input type="text" name="nama" value="{{ old('nama', $pendatang->nama) }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">NIK <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $pendatang->nik) }}" required maxlength="20"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Asal <span class="text-rose-500">*</span></label>
                        <input type="text" name="asal" value="{{ old('asal', $pendatang->asal) }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $pendatang->no_hp) }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none">{{ old('alamat_tinggal', $pendatang->alamat_tinggal) }}</textarea>
                </div>
                <div class="max-w-md">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Banjar</label>
                    <select name="id_data_banjar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        <option value="">— Pilih Banjar —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}" {{ old('id_data_banjar', $pendatang->id_data_banjar) == $banjar->id_data_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5" x-data="{ belumYakin: {{ $pendatang->tinggal_belum_yakin ? 'true' : 'false' }} }">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Lama Tinggal</h3>

                <div class="flex items-center justify-between bg-slate-50 rounded-xl p-4">
                    <div>
                        <p class="text-sm font-bold text-slate-700">Belum yakin / Not sure yet</p>
                        <p class="text-xs text-slate-400 mt-0.5">Centang jika belum menentukan durasi tinggal</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tinggal_belum_yakin" value="1" x-model="belumYakin" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-light"></div>
                    </label>
                </div>

                <div x-show="!belumYakin" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
                        <input type="date" name="tinggal_dari" value="{{ old('tinggal_dari', $pendatang->tinggal_dari ? $pendatang->tinggal_dari->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="tinggal_sampai" value="{{ old('tinggal_sampai', $pendatang->tinggal_sampai ? $pendatang->tinggal_sampai->format('Y-m-d') : '') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5" x-data="{ useGlobal: {{ $pendatang->use_global_punia ? 'true' : 'false' }} }">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pengaturan Iuran (Punia)</h3>
                <div class="bg-slate-50 rounded-xl p-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-700">Gunakan tarif global</p>
                            <p class="text-xs text-slate-400 mt-0.5">Tarif saat ini: Rp {{ number_format($puniaGlobal, 0, ',', '.') }} / bulan</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="use_global_punia" value="1" x-model="useGlobal" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-light"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nominal Punia Bulanan <span class="text-rose-500">*</span></label>
                        <div class="relative max-w-md">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" name="punia_rutin_bulanan" required min="0" step="1000" 
                                   value="{{ old('punia_rutin_bulanan', $pendatang->punia_rutin_bulanan) }}"
                                   :disabled="useGlobal" :class="useGlobal ? 'opacity-50 cursor-not-allowed' : ''"
                                   class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-check-circle-fill mr-2"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
