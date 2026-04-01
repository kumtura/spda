@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showKategoriModal: false,
    editMode: false,
    wizardStep: 1,
    tipeKategori: '',
    subOrang: '',
    
    // Form fields for single-add/edit
    formNama: '',
    formHarga: '',
    formDeskripsi: '',
    formId: '',
    
    openAdd(type) {
        this.editMode = false;
        this.wizardStep = 1;
        this.tipeKategori = '';
        this.subOrang = '';
        this.formNama = '';
        this.formHarga = '';
        this.formDeskripsi = '';
        this.formId = '';
        this.showKategoriModal = true;
        
        // Pre-select type if provided
        if (type) {
            this.tipeKategori = type;
            this.wizardStep = type === 'kendaraan' ? 3 : 2;
        }
    },
    
    openEdit(id) {
        const kat = kategoriData.find(k => k.id_kategori_tiket === id);
        if (!kat) return;
        this.editMode = true;
        this.tipeKategori = kat.tipe_kategori;
        this.formNama = kat.nama_kategori;
        this.formHarga = kat.harga;
        this.formDeskripsi = kat.deskripsi || '';
        this.formId = kat.id_kategori_tiket;
        this.wizardStep = 4; // Go directly to edit form
        this.showKategoriModal = true;
    },
    
    selectTipe(type) {
        this.tipeKategori = type;
        this.subOrang = '';
        if (type === 'kendaraan') {
            this.wizardStep = 3; // Skip sub-option, go to checkboxes
        } else {
            this.wizardStep = 2; // Show sub-option for orang
        }
    },
    
    selectSubOrang(sub) {
        this.subOrang = sub;
        this.wizardStep = 3;
    },
    
    // For single item add
    selectItem(nama) {
        this.formNama = nama;
        this.wizardStep = 4;
    },
    
    getFormAction() {
        if (this.editMode) {
            return '{{ url("administrator/kelian/tiket/kategori/update") }}/' + this.formId;
        }
        return '{{ url("administrator/kelian/tiket/kategori/store") }}';
    }
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('administrator/objek_wisata') }}" class="h-10 w-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-primary-light hover:border-primary-light hover:bg-blue-50 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Objek Wisata</h1>
                <p class="text-slate-500 font-medium text-sm">Update informasi destinasi dan harga tiket wisata.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-600 text-xl"></i>
        <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Main Edit Form (takes 2 columns) -->
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <form action="{{ url('administrator/objek_wisata/update/'.$objek->id_objek_wisata) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-8">
                        <div class="space-y-5">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-3">Informasi Dasar</h3>
                            
                            @if($objek->foto)
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-700 mb-2">Foto Saat Ini</label>
                                <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="w-full h-48 object-cover rounded-xl border border-slate-200 shadow-sm" alt="{{ $objek->nama_objek }}">
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Nama Objek Wisata <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nama_objek" value="{{ $objek->nama_objek }}" required
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Banjar / Wilayah Kelola <span class="text-rose-500">*</span></label>
                                    <select name="id_data_banjar" required
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">
                                        <option value="">-- Pilih Banjar --</option>
                                        @foreach($banjar as $b)
                                        <option value="{{ $b->id_data_banjar }}" {{ $objek->id_data_banjar == $b->id_data_banjar ? 'selected' : '' }}>
                                            {{ $b->nama_banjar }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Status Operasional <span class="text-rose-500">*</span></label>
                                    <select name="status" required
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">
                                        <option value="aktif" {{ $objek->status == 'aktif' ? 'selected' : '' }}>Aktif (Buka)</option>
                                        <option value="nonaktif" {{ $objek->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Tutup / Renovasi)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Kapasitas Pengunjung Harian</label>
                                    <input type="number" name="kapasitas_harian" value="{{ $objek->kapasitas_harian }}"
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                        placeholder="Kosong jika bebas">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Jam Buka</label>
                                    <input type="time" name="jam_buka" value="{{ $objek->jam_buka }}"
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-2">Jam Tutup</label>
                                    <input type="time" name="jam_tutup" value="{{ $objek->jam_tutup }}"
                                        class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Deskripsi Lengkap <span class="text-rose-500">*</span></label>
                                <textarea name="deskripsi" rows="3" required
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">{{ $objek->deskripsi }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Alamat Lokasi <span class="text-rose-500">*</span></label>
                                <textarea name="alamat" rows="2" required
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all">{{ $objek->alamat }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">Ganti Foto Utama</label>
                                <input type="file" name="foto" accept="image/*"
                                    class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none cursor-pointer
                                           file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold
                                           file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 transition-all">
                                <p class="text-[10px] text-slate-500 mt-2 font-medium"><i class="bi bi-exclamation-triangle mr-1"></i>Kosongkan jika tidak ingin mengubah foto</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-4 bg-primary-light hover:bg-primary-dark text-white text-sm font-black uppercase tracking-widest rounded-xl shadow-lg shadow-primary-light/30 transition-all active:scale-95 flex items-center gap-2">
                            <i class="bi bi-save-fill text-lg"></i> Update Identitas Objek Wisata
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: PRICING WIZARD -->
        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-slate-800 rounded-3xl p-6 shadow-2xl text-white">
                <h3 class="text-xs font-black uppercase tracking-widest mb-6 opacity-60 flex items-center gap-2">
                    <i class="bi bi-tag-fill"></i> Kategori Tiket Baru
                </h3>

                <!-- STEP 1: PILIH KATEGORI UTAMA -->
                <div class="space-y-4 mb-8">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">1. Pilih Tipe Kategori</label>
                    <select name="tipe_kategori_utama" x-model="tipeKategori" @change="subOrang = ''; wizardStep = 2" required
                        class="w-full px-4 py-3 text-sm bg-slate-700 border-none rounded-xl text-white focus:ring-2 focus:ring-primary-light transition-all">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="orang">Per Orang (Pengunjung)</option>
                        <option value="kendaraan">Per Kendaraan (Parkir/Masuk)</option>
                    </select>
                </div>

                <!-- STEP 2: PILIH OPSI PENGATURAN (Only for Orang) -->
                <div class="space-y-4 mb-8" x-show="tipeKategori === 'orang'">
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">2. Pilih Format Harga</label>
                    <select x-model="subOrang" @change="wizardStep = 3"
                        class="w-full px-4 py-3 text-sm bg-slate-700 border-none rounded-xl text-white focus:ring-2 focus:ring-primary-light transition-all">
                        <option value="">-- Pilih Pengaturan --</option>
                        <option value="sama">Harga Sama untuk Semua Usia</option>
                        <option value="kategori">Harga Berbeda Berdasarkan Usia</option>
                    </select>
                </div>

                <!-- STEP 3 & 4: Sub-Categories & Nominal (Dynamic) -->
                <form action="{{ url('administrator/kelian/tiket/kategori/store') }}" method="POST" x-show="tipeKategori != '' && (tipeKategori == 'kendaraan' || subOrang != '')" x-transition>
                    @csrf
                    <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
                    <input type="hidden" name="tipe_kategori" :value="tipeKategori">
                    
                    <h4 class="text-[11px] font-bold text-slate-400 uppercase mb-4 tracking-widest">3. Isi Nominal Harga</h4>
                    
                    <!-- IF ORANG - SAMA -->
                    <div x-show="tipeKategori == 'orang' && subOrang == 'sama'" class="space-y-4">
                        <div class="bg-slate-700/50 p-4 rounded-2xl border border-slate-600">
                            <label class="text-[10px] font-bold text-slate-400 block mb-2">Tiket Umum (Rp)</label>
                            <input type="number" name="harga[umum]" placeholder="0" class="w-full bg-slate-800 border-none rounded-lg text-white font-black">
                            <input type="hidden" name="kategori_aktif[]" value="umum">
                        </div>
                    </div>

                    <!-- IF ORANG - KATEGORI USIA -->
                    <div x-show="tipeKategori == 'orang' && subOrang == 'kategori'" class="space-y-3">
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

                    <button type="submit" class="w-full mt-6 py-4 bg-primary-light hover:bg-primary-dark text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl transition-all active:scale-95">
                        Simpan Kategori Baru
                    </button>
                </form>

                <!-- PLACEHOLDER -->
                <div x-show="tipeKategori == '' || (tipeKategori == 'orang' && subOrang == '')" class="py-12 flex flex-col items-center justify-center text-center opacity-30 border-2 border-dashed border-slate-600 rounded-3xl">
                    <i class="bi bi-tag text-3xl mb-2"></i>
                    <p class="text-[10px] font-bold">Pilih Tipe & Format Harga<br>untuk menambah kategori.</p>
                </div>
            </div>

            <!-- List Existing Categories -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-widest mb-6 text-slate-400 flex items-center gap-2">
                    <i class="bi bi-list-stars"></i> Kategori Terdaftar
                </h3>
                <div class="space-y-3">
                    @forelse($objek->kategoriTiket as $kategori)
                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all">
                        <div>
                            <p class="text-xs font-black text-slate-700">{{ $kategori->nama_kategori }}</p>
                            <p class="text-xs font-bold text-primary-light">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex gap-1">
                            <button @click="openEdit({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-light hover:text-white transition-all"><i class="bi bi-pencil-square"></i></button>
                            <button onclick="deleteKategori({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    @empty
                    <p class="text-[10px] text-center py-4 text-slate-400 italic">Belum ada kategori harga.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

<!-- WIZARD MODAL -->
<template x-teleport="body">
    <div x-show="showKategoriModal" 
         x-cloak
         @click.self="showKategoriModal = false"
         @keydown.escape.window="showKategoriModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-100 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        
        <div @click.stop 
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Modal Header -->
            <div class="bg-linear-to-br from-primary-light to-primary-dark p-6 pb-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showKategoriModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative z-10">
                    <h3 class="text-xl font-black mb-1" x-text="editMode ? 'Edit Harga Tiket' : 'Tambah Harga Tiket'"></h3>
                    <p class="text-white/80 text-xs">
                        <span x-show="wizardStep === 1">Langkah 1: Pilih tipe kategori</span>
                        <span x-show="wizardStep === 2">Langkah 2: Pilih pengaturan harga</span>
                        <span x-show="wizardStep === 3">Langkah 3: Pilih kategori spesifik</span>
                        <span x-show="wizardStep === 4">Langkah <span x-text="editMode ? '' : '4: '"></span>Isi detail harga</span>
                    </p>
                </div>
                <!-- Step indicator -->
                <div class="flex gap-1.5 mt-4 relative z-10" x-show="!editMode">
                    <template x-for="s in 4" :key="s">
                        <div class="h-1 flex-1 rounded-full transition-all" 
                             :class="s <= wizardStep ? 'bg-white' : 'bg-white/20'"></div>
                    </template>
                </div>
            </div>

            <div class="p-6 -mt-4 relative z-20 bg-white rounded-t-3xl">
                
                <!-- STEP 1: Pilih Tipe Kategori -->
                <div x-show="wizardStep === 1" x-transition class="space-y-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Pilih Tipe Kategori</p>
                    
                    <button type="button" @click="selectTipe('orang')" 
                            class="w-full p-4 bg-blue-50 border-2 border-blue-100 rounded-xl text-left hover:border-primary-light hover:bg-blue-100/50 transition-all group flex items-center gap-4">
                        <div class="h-12 w-12 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <i class="bi bi-person-fill text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Per Orang</p>
                            <p class="text-[10px] text-slate-500">Tiket masuk pengunjung</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                    
                    <button type="button" @click="selectTipe('kendaraan')" 
                            class="w-full p-4 bg-amber-50 border-2 border-amber-100 rounded-xl text-left hover:border-amber-500 hover:bg-amber-100/50 transition-all group flex items-center gap-4">
                        <div class="h-12 w-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <i class="bi bi-car-front-fill text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-black text-slate-800">Per Kendaraan</p>
                            <p class="text-[10px] text-slate-500">Parkir / Masuk kendaraan</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                </div>

                <!-- STEP 2: Sub-pilihan untuk Per Orang -->
                <div x-show="wizardStep === 2" x-transition class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" @click="wizardStep = 1; tipeKategori = ''" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengaturan Harga Per Orang</p>
                    </div>
                    
                    <button type="button" @click="selectSubOrang('sama')" 
                            class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-left hover:border-primary-light hover:bg-blue-50/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-check-circle text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Harga Sama Semua Usia</p>
                            <p class="text-[10px] text-slate-500">Satu harga untuk semua pengunjung</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                    
                    <button type="button" @click="selectSubOrang('kategori')" 
                            class="w-full p-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-left hover:border-primary-light hover:bg-blue-50/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-violet-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-people text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Harga Berbeda Berdasarkan Usia</p>
                            <p class="text-[10px] text-slate-500">Dewasa, Anak, Lansia, dll</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                </div>

                <!-- STEP 3: Pilih Kategori Spesifik -->
                <div x-show="wizardStep === 3" x-transition class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" @click="wizardStep = tipeKategori === 'kendaraan' ? 1 : 2" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="tipeKategori === 'kendaraan' ? 'Pilih Jenis Kendaraan' : (subOrang === 'sama' ? 'Tiket Umum' : 'Pilih Kategori Usia')"></p>
                    </div>
                    
                    <!-- Orang - Sama -->
                    <template x-if="tipeKategori === 'orang' && subOrang === 'sama'">
                        <button type="button" @click="selectItem('Umum')" 
                                class="w-full p-4 bg-emerald-50 border-2 border-emerald-100 rounded-xl text-left hover:border-emerald-400 transition-all flex items-center gap-3">
                            <i class="bi bi-ticket-perforated text-emerald-600 text-lg"></i>
                            <span class="text-sm font-bold text-slate-800">Tiket Umum (Semua Usia)</span>
                            <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                        </button>
                    </template>
                    
                    <!-- Orang - Kategori Usia -->
                    <template x-if="tipeKategori === 'orang' && subOrang === 'kategori'">
                        <div class="space-y-2">
                            @foreach(['Dewasa', 'Anak-anak', 'Balita', 'Lansia', 'Pelajar'] as $usia)
                            <button type="button" @click="selectItem('{{ $usia }}')" 
                                    class="w-full p-3.5 bg-slate-50 border-2 border-slate-100 rounded-xl text-left hover:border-primary-light hover:bg-blue-50/50 transition-all flex items-center gap-3">
                                <i class="bi bi-person text-primary-light"></i>
                                <span class="text-sm font-bold text-slate-800">{{ $usia }}</span>
                                <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                            </button>
                            @endforeach
                        </div>
                    </template>
                    
                    <!-- Kendaraan -->
                    <template x-if="tipeKategori === 'kendaraan'">
                        <div class="space-y-2">
                            @foreach(['Motor' => 'bi-scooter', 'Mobil' => 'bi-car-front', 'Bus' => 'bi-bus-front', 'Truk' => 'bi-truck'] as $kend => $icon)
                            <button type="button" @click="selectItem('{{ $kend }}')" 
                                    class="w-full p-3.5 bg-amber-50/50 border-2 border-amber-100/50 rounded-xl text-left hover:border-amber-400 hover:bg-amber-50 transition-all flex items-center gap-3">
                                <i class="bi {{ $icon }} text-amber-600"></i>
                                <span class="text-sm font-bold text-slate-800">{{ $kend }}</span>
                                <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                            </button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <!-- STEP 4: Input Harga -->
                <div x-show="wizardStep === 4" x-transition>
                    <div class="flex items-center gap-2 mb-4" x-show="!editMode">
                        <button type="button" @click="wizardStep = 3" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Isi Harga untuk <span class="text-primary-light" x-text="formNama"></span></p>
                    </div>
                    <div x-show="editMode" class="mb-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Edit harga <span class="text-primary-light" x-text="formNama"></span></p>
                    </div>
                    
                    <form :action="getFormAction()" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
                        <input type="hidden" name="tipe_kategori" :value="tipeKategori">
                        <input type="hidden" name="nama_kategori" :value="formNama">
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Nama Kategori</label>
                            <input type="text" x-model="formNama" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Harga Tiket (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="harga" x-model="formHarga" required min="0" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                                   placeholder="Contoh: 10000">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Keterangan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                            <textarea name="deskripsi" rows="2" x-model="formDeskripsi"
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none"
                                      placeholder="Berlaku hari kerja, termasuk asuransi, dsb..."></textarea>
                        </div>

                        <button type="submit" class="w-full mt-2 bg-primary-light hover:bg-primary-dark text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl transition-all shadow-lg shadow-primary-light/30 active:scale-95 flex items-center justify-center gap-2">
                            <i class="bi bi-save-fill text-lg"></i> 
                            <span x-text="editMode ? 'Update Harga' : 'Simpan Harga'"></span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

</div>

@push('scripts')
<script>
const kategoriData = @json($objek->kategoriTiket);

function deleteKategori(id) {
    if (confirm('Hapus kategori tiket ini secara permanen?')) {
        window.location.href = '{{ url("administrator/kelian/tiket/kategori/delete") }}/' + id;
    }
}
</script>
@endpush
@stop
