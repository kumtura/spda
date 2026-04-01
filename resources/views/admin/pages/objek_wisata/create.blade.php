@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    tipeKategori: '',
    opsiHarga: '',
    hasSelection() {
        return this.tipeKategori != '' && (this.tipeKategori == 'kendaraan' || this.opsiHarga != '');
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('administrator/objek_wisata') }}" class="h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-primary-light transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Objek Wisata</h1>
                <p class="text-slate-500 font-medium text-sm italic">Lengkapi informasi dasar dan tentukan kategori harga tiket.</p>
            </div>
        </div>
    </div>

    <form action="{{ url('administrator/objek_wisata/store') }}" method="POST" enctype="multipart/form-data" id="main-form">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: BASIC INFO (ALWAYS UNLOCKED) -->
            <div class="xl:col-span-2 space-y-6">
                <!-- DATA IDENTITAS -->
                <div class="bg-white border-2 border-slate-100 rounded-3xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <i class="bi bi-card-checklist text-primary-light text-xl"></i>
                        Identitas Objek Wisata
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Objek Wisata <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_objek" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Contoh: Pura Sanghyang, Pantai Indah">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Banjar / Wilayah Kelola <span class="text-rose-500">*</span></label>
                            <select name="id_data_banjar" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all">
                                <option value="">-- Pilih Banjar --</option>
                                @foreach($banjar as $b)
                                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                            <textarea name="description" rows="4" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Ceritakan sejarah atau daya tarik wisata ini..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Alamat & Lokasi <span class="text-rose-500">*</span></label>
                            <textarea name="alamat" rows="2" required
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Lokasi lengkap menuju gerbang utama"></textarea>
                        </div>
                    </div>
                </div>

                <!-- FOTO & OPERASIONAL -->
                <div class="bg-white border-2 border-slate-100 rounded-3xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <i class="bi bi-camera-fill text-slate-400 text-xl"></i>
                        Media & Jam Operasional
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Jam Buka</label>
                                <input type="time" name="jam_buka" class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Jam Tutup</label>
                                <input type="time" name="jam_tutup" class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Foto Preview</label>
                            <input type="file" name="foto[]" multiple class="w-full text-xs text-slate-400 file:bg-slate-200 file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:text-[10px] cursor-pointer">
                        </div>
                    </div>

                    <!-- FINAL SUBMIT -->
                    <div class="flex justify-center pt-8 border-t border-slate-100">
                        <button type="submit" class="px-10 py-5 bg-linear-to-r from-primary-light to-primary-dark text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                            <i class="bi bi-cloud-arrow-up-fill text-xl"></i> Daftar Objek Wisata Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: PRICING WIZARD -->
            <div class="xl:col-span-1 space-y-6">
                
                <div class="bg-slate-800 rounded-3xl p-6 shadow-2xl text-white">
                    <h3 class="text-xs font-black uppercase tracking-widest mb-6 opacity-60 flex items-center gap-2">
                        <i class="bi bi-tag-fill"></i> Kategori Tiket
                    </h3>

                    <!-- STEP 1: PILIH KATEGORI UTAMA -->
                    <div class="space-y-4 mb-8">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">1. Pilih Tipe Kategori</label>
                        <select name="tipe_kategori_utama" x-model="tipeKategori" @change="opsiHarga = ''" required
                            class="w-full px-4 py-3 text-sm bg-slate-700 border-none rounded-xl text-white focus:ring-2 focus:ring-primary-light transition-all">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="orang">Per Orang (Pengunjung)</option>
                            <option value="kendaraan">Per Kendaraan (Parkir/Masuk)</option>
                        </select>
                    </div>

                    <!-- STEP 2: PILIH OPSI PENGATURAN -->
                    <div class="space-y-4 mb-8" x-show="tipeKategori === 'orang'">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">2. Pilih Format Harga</label>
                        <select x-model="opsiHarga"
                            class="w-full px-4 py-3 text-sm bg-slate-700 border-none rounded-xl text-white focus:ring-2 focus:ring-primary-light transition-all">
                            <option value="">-- Pilih Pengaturan --</option>
                            <option value="sama">Harga Sama untuk Semua Usia</option>
                            <option value="kategori">Harga Berbeda Berdasarkan Usia</option>
                        </select>
                    </div>

                    <!-- NOMINAL INPUTS (Dynamic) -->
                    <div class="space-y-4" x-show="hasSelection()" x-transition>
                        <h4 class="text-[11px] font-bold text-slate-400 uppercase mb-4 tracking-widest">3. Isi Nominal Harga</h4>
                        
                        <!-- IF ORANG - SAMA -->
                        <div x-show="tipeKategori == 'orang' && opsiHarga == 'sama'" class="space-y-4">
                            <div class="bg-slate-700/50 p-4 rounded-2xl border border-slate-600">
                                <label class="text-[10px] font-bold text-slate-400 block mb-2">Tiket Umum (Rp)</label>
                                <input type="number" name="harga[umum]" placeholder="0" class="w-full bg-slate-800 border-none rounded-lg text-white font-black">
                                <input type="hidden" name="kategori_aktif[]" value="umum">
                            </div>
                        </div>

                        <!-- IF ORANG - KATEGORI USIA -->
                        <div x-show="tipeKategori == 'orang' && opsiHarga == 'kategori'" class="space-y-3">
                            @foreach(['dewasa' => 'Dewasa', 'anak' => 'Anak-anak', 'balita' => 'Balita', 'lansia' => 'Lansia'] as $key => $label)
                            <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group">
                                <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-5 w-5 rounded bg-slate-800 border-none text-primary-light checkbox-trigger">
                                <div class="flex-1">
                                    <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                    <input type="number" name="harga[{{ $key }}]" placeholder="0" disabled 
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- IF KENDARAAN -->
                        <div x-show="tipeKategori == 'kendaraan'" class="space-y-3">
                            @foreach(['motor' => 'Sepeda Motor', 'mobil' => 'Mobil', 'bus' => 'Bus Pariwisata', 'truk' => 'Kendaraan Besar'] as $key => $label)
                            <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group">
                                <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-5 w-5 rounded bg-slate-800 border-none text-amber-500 checkbox-trigger">
                                <div class="flex-1">
                                    <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                    <input type="number" name="harga[{{ $key }}]" placeholder="0" disabled 
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- PLACEHOLDER -->
                    <div x-show="!hasSelection()" class="py-12 flex flex-col items-center justify-center text-center opacity-30 border-2 border-dashed border-slate-600 rounded-3xl">
                        <i class="bi bi-tag text-3xl mb-2"></i>
                        <p class="text-[10px] font-bold">Pilih Tipe & Format Harga<br>untuk mengisi nominal.</p>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handling Checkbox - Input relationship
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('checkbox-trigger')) {
            const container = e.target.closest('.group');
            const input = container.querySelector('.price-input');
            if(input) {
                input.disabled = !e.target.checked;
                if(e.target.checked) input.focus();
            }
        }
    });
});
</script>
@endpush
@stop
