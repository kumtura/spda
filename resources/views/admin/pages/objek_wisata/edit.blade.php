@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showKategoriModal: false,
    editMode: false,
    wizardStep: 1,
    tipeKategori: '',
    subOrang: '',
    marketType: 'all',
    
    // Form fields for single-add/edit
    formNama: '',
    formHarga: '',
    formHargaLocal: '',
    formHargaWna: '',
    formDeskripsi: '',
    formId: '',
    formMarketType: 'all',
    
    openAdd(type) {
        this.editMode = false;
        this.wizardStep = 1;
        this.tipeKategori = '';
        this.subOrang = '';
        this.marketType = 'all';
        this.formNama = '';
        this.formHarga = '';
        this.formHargaLocal = '';
        this.formHargaWna = '';
        this.formDeskripsi = '';
        this.formId = '';
        this.formMarketType = 'all';
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
        this.formMarketType = kat.market_type || 'all';
        this.wizardStep = 5;
        this.showKategoriModal = true;
    },
    
    selectTipe(type) {
        this.tipeKategori = type;
        this.subOrang = '';
        if (type === 'kendaraan') {
            this.wizardStep = 3;
        } else {
            this.wizardStep = 2;
        }
    },
    
    selectMarket(market) {
        this.marketType = market;
        this.formMarketType = market;
        this.wizardStep = 3;
    },
    
    selectSubOrang(sub) {
        this.subOrang = sub;
        this.wizardStep = 4;
    },
    
    selectItem(nama) {
        this.formNama = nama;
        this.wizardStep = 5;
    },
    
    getFormAction() {
        if (this.editMode) {
            return '{{ url('administrator/objek_wisata/kategori/update') }}/' + this.formId;
        }
        return '{{ url('administrator/objek_wisata/kategori/store') }}';
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

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-link-45deg mr-1"></i>Permalink / Slug
                                </label>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-slate-400 whitespace-nowrap">{{ url('wisata') }}/</span>
                                    <input type="text" name="slug" value="{{ $objek->slug }}"
                                        class="flex-1 px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                        placeholder="contoh: daerah-konservasi">
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 font-medium"><i class="bi bi-info-circle mr-1"></i>URL frontend untuk halaman detail & beli tiket. Kosongkan untuk auto-generate dari nama.</p>
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
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-ticket-perforated mr-1"></i>Batas Penjualan Tiket Per Hari
                                </label>
                                <input type="number" name="batas_tiket_harian" value="{{ $objek->batas_tiket_harian }}" min="1"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                    placeholder="Kosongkan jika tidak ada batas (unlimited)">
                                <p class="text-[10px] text-slate-500 mt-1 font-medium"><i class="bi bi-info-circle mr-1"></i>Jika dikosongkan, penjualan tiket tidak dibatasi per hari</p>
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

                        <!-- Ticket Description Section -->
                        <div class="space-y-5">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-3">
                                <i class="bi bi-card-text mr-1"></i>Detail & Termasuk Tiket
                            </h3>
                            <p class="text-[10px] text-slate-500 -mt-3">Informasi yang akan ditampilkan di halaman detail tiket untuk pengunjung.</p>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-file-text mr-1"></i>Deskripsi Tiket
                                </label>
                                <textarea name="detail_termasuk" rows="3"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                    placeholder="Contoh: Tiket masuk sudah termasuk akses ke seluruh area wisata, parkir gratis, dan asuransi pengunjung.">{{ $objek->detail_termasuk }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-signpost-split mr-1"></i>Cara Penggunaan
                                </label>
                                <textarea name="cara_penggunaan" rows="3"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                    placeholder="Contoh: 1. Tunjukkan QR code di loket masuk. 2. Scan QR code pada mesin scanner. 3. Tiket berlaku 1 hari.">{{ $objek->cara_penggunaan }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-x-circle mr-1"></i>Kebijakan Pembatalan
                                </label>
                                <textarea name="pembatalan" rows="3"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                    placeholder="Contoh: Pembatalan dapat dilakukan maksimal H-1 sebelum tanggal kunjungan. Refund 100% jika dibatalkan H-3.">{{ $objek->pembatalan }}</textarea>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-2">
                                    <i class="bi bi-shield-check mr-1"></i>Syarat & Ketentuan
                                </label>
                                <textarea name="syarat_ketentuan" rows="3"
                                    class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light transition-all"
                                    placeholder="Contoh: Pengunjung wajib mengenakan pakaian sopan. Dilarang membawa hewan peliharaan. Anak di bawah 5 tahun gratis.">{{ $objek->syarat_ketentuan }}</textarea>
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
                <h3 class="text-xs font-black uppercase tracking-widest mb-3 opacity-60 flex items-center gap-2">
                    <i class="bi bi-tag-fill"></i> Tambah Kategori Tiket
                </h3>
                <p class="text-[10px] text-slate-400 mb-5">Pilih tipe untuk menambah kategori harga baru.</p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="openAdd('orang')"
                        class="p-4 bg-slate-700 hover:bg-primary-light rounded-xl text-center transition-all group">
                        <i class="bi bi-people-fill text-xl block mb-1 text-slate-400 group-hover:text-white"></i>
                        <span class="text-[10px] font-bold text-slate-300 group-hover:text-white">Per Orang</span>
                    </button>
                    <button type="button" @click="openAdd('kendaraan')"
                        class="p-4 bg-slate-700 hover:bg-amber-500 rounded-xl text-center transition-all group">
                        <i class="bi bi-car-front-fill text-xl block mb-1 text-slate-400 group-hover:text-white"></i>
                        <span class="text-[10px] font-bold text-slate-300 group-hover:text-white">Per Kendaraan</span>
                    </button>
                </div>
            </div>

            <!-- List Existing Categories -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-widest mb-6 text-slate-400 flex items-center gap-2">
                    <i class="bi bi-list-stars"></i> Kategori Terdaftar
                </h3>

                @php
                    $orangWna = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && $k->market_type === 'wna');
                    $orangLocal = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && $k->market_type === 'local');
                    $orangAll = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && !in_array($k->market_type, ['wna', 'local']));
                    $kendaraanKategori = $objek->kategoriTiket->where('tipe_kategori', 'kendaraan');
                @endphp

                <div class="space-y-4">
                    {{-- WNA Section --}}
                    @if($orangWna->count() > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-violet-100 text-violet-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-globe"></i> WNA
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach($orangWna as $kategori)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-violet-100 bg-violet-50/30 hover:bg-violet-50 transition-all">
                                <div>
                                    <p class="text-xs font-black text-slate-700">{{ $kategori->nama_kategori }}</p>
                                    <p class="text-xs font-bold text-violet-600">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="openEdit({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-light hover:text-white transition-all"><i class="bi bi-pencil-square"></i></button>
                                    <button onclick="deleteKategori({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Local Section --}}
                    @if($orangLocal->count() > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-geo-alt"></i> Lokal
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach($orangLocal as $kategori)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-emerald-100 bg-emerald-50/30 hover:bg-emerald-50 transition-all">
                                <div>
                                    <p class="text-xs font-black text-slate-700">{{ $kategori->nama_kategori }}</p>
                                    <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="openEdit({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-light hover:text-white transition-all"><i class="bi bi-pencil-square"></i></button>
                                    <button onclick="deleteKategori({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- All (Semua) Section --}}
                    @if($orangAll->count() > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-people"></i> Semua
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach($orangAll as $kategori)
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
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Kendaraan Section --}}
                    @if($kendaraanKategori->count() > 0)
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-100 text-amber-700 rounded-lg text-[9px] font-bold">
                                <i class="bi bi-car-front"></i> Kendaraan
                            </span>
                        </div>
                        <div class="space-y-2">
                            @foreach($kendaraanKategori as $kategori)
                            <div class="flex items-center justify-between p-3 rounded-2xl border border-amber-100 bg-amber-50/30 hover:bg-amber-50 transition-all">
                                <div>
                                    <p class="text-xs font-black text-slate-700">{{ $kategori->nama_kategori }}</p>
                                    <p class="text-xs font-bold text-amber-600">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="openEdit({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-primary-light hover:text-white transition-all"><i class="bi bi-pencil-square"></i></button>
                                    <button onclick="deleteKategori({{ $kategori->id_kategori_tiket }})" class="h-8 w-8 rounded-lg bg-slate-100 text-slate-400 hover:bg-rose-500 hover:text-white transition-all"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($objek->kategoriTiket->count() == 0)
                    <p class="text-[10px] text-center py-4 text-slate-400 italic">Belum ada kategori harga.</p>
                    @endif
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
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden max-h-[90vh] overflow-y-auto"
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
                        <span x-show="wizardStep === 2">Langkah 2: Pilih kategori pasar</span>
                        <span x-show="wizardStep === 3">Langkah 3: Pengaturan harga</span>
                        <span x-show="wizardStep === 4">Langkah 4: Pilih kategori spesifik</span>
                        <span x-show="wizardStep === 5">Langkah <span x-text="editMode ? '' : '5: '"></span>Isi detail harga</span>
                    </p>
                </div>
                <!-- Step indicator -->
                <div class="flex gap-1.5 mt-4 relative z-10" x-show="!editMode">
                    <template x-for="s in 5" :key="s">
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

                <!-- STEP 2: Market Type (WNA/Local) -->
                <div x-show="wizardStep === 2" x-transition class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" @click="wizardStep = 1; tipeKategori = ''" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori Pasar</p>
                    </div>
                    
                    <button type="button" @click="selectMarket('local')" 
                            class="w-full p-4 bg-emerald-50 border-2 border-emerald-100 rounded-xl text-left hover:border-emerald-400 hover:bg-emerald-100/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-emerald-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-geo-alt text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Lokal (Domestik)</p>
                            <p class="text-[10px] text-slate-500">Harga untuk pengunjung Indonesia</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                    
                    <button type="button" @click="selectMarket('wna')" 
                            class="w-full p-4 bg-violet-50 border-2 border-violet-100 rounded-xl text-left hover:border-violet-400 hover:bg-violet-100/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-violet-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-globe text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">WNA (Wisatawan Asing)</p>
                            <p class="text-[10px] text-slate-500">Harga untuk pengunjung mancanegara</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                    
                    <button type="button" @click="selectMarket('dual')" 
                            class="w-full p-4 bg-gradient-to-r from-emerald-50 to-violet-50 border-2 border-emerald-100 rounded-xl text-left hover:border-emerald-400 hover:from-emerald-100/50 hover:to-violet-100/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-gradient-to-br from-emerald-500 to-violet-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-arrow-left-right text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Lokal & WNA</p>
                            <p class="text-[10px] text-slate-500">Buat harga Lokal dan WNA sekaligus</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>

                    <button type="button" @click="selectMarket('all')" 
                            class="w-full p-4 bg-blue-50 border-2 border-blue-100 rounded-xl text-left hover:border-blue-400 hover:bg-blue-100/50 transition-all group flex items-center gap-4">
                        <div class="h-10 w-10 bg-blue-500 text-white rounded-lg flex items-center justify-center">
                            <i class="bi bi-people text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Semua Pengunjung</p>
                            <p class="text-[10px] text-slate-500">Harga sama tanpa pembedaan</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                    </button>
                </div>

                <!-- STEP 3: Format Harga / Pilih Item -->
                <div x-show="wizardStep === 3" x-transition class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" @click="wizardStep = tipeKategori === 'kendaraan' ? 1 : 2" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pengaturan Harga</p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[8px] font-bold ml-auto"
                              :class="marketType === 'wna' ? 'bg-violet-100 text-violet-700' : (marketType === 'local' ? 'bg-emerald-100 text-emerald-700' : (marketType === 'dual' ? 'bg-gradient-to-r from-emerald-100 to-violet-100 text-emerald-700' : 'bg-blue-100 text-blue-700'))"
                              x-show="tipeKategori === 'orang'">
                            <span x-text="marketType === 'wna' ? 'WNA' : (marketType === 'local' ? 'Lokal' : (marketType === 'dual' ? 'Lokal & WNA' : 'Semua'))"></span>
                        </span>
                    </div>
                    
                    <!-- Orang sub-options -->
                    <template x-if="tipeKategori === 'orang'">
                        <div class="space-y-3">
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
                    </template>

                    <!-- Kendaraan items -->
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
                            <button type="button" @click="formNama = ''; wizardStep = 5"
                                    class="w-full p-3.5 bg-amber-50/50 border-2 border-dashed border-amber-200 rounded-xl text-left hover:border-amber-400 hover:bg-amber-50 transition-all flex items-center gap-3">
                                <i class="bi bi-plus-circle text-amber-500"></i>
                                <span class="text-sm font-bold text-amber-600">Lainnya (Custom)</span>
                                <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                            </button>
                        </div>
                    </template>
                </div>

                <!-- STEP 4: Pilih Kategori Spesifik -->
                <div x-show="wizardStep === 4" x-transition class="space-y-3">
                    <div class="flex items-center gap-2 mb-4">
                        <button type="button" @click="wizardStep = 3" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest" x-text="subOrang === 'sama' ? 'Tiket Umum' : 'Pilih Kategori Usia'"></p>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[8px] font-bold ml-auto"
                              :class="marketType === 'wna' ? 'bg-violet-100 text-violet-700' : (marketType === 'local' ? 'bg-emerald-100 text-emerald-700' : (marketType === 'dual' ? 'bg-gradient-to-r from-emerald-100 to-violet-100 text-emerald-700' : 'bg-blue-100 text-blue-700'))">
                            <span x-text="marketType === 'wna' ? 'WNA' : (marketType === 'local' ? 'Lokal' : (marketType === 'dual' ? 'Lokal & WNA' : 'Semua'))"></span>
                        </span>
                    </div>
                    
                    <template x-if="subOrang === 'sama'">
                        <button type="button" @click="selectItem('Umum')" 
                                class="w-full p-4 bg-emerald-50 border-2 border-emerald-100 rounded-xl text-left hover:border-emerald-400 transition-all flex items-center gap-3">
                            <i class="bi bi-ticket-perforated text-emerald-600 text-lg"></i>
                            <span class="text-sm font-bold text-slate-800">Tiket Umum (Semua Usia)</span>
                            <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                        </button>
                    </template>
                    
                    <template x-if="subOrang === 'kategori'">
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
                </div>

                <!-- STEP 5: Input Harga -->
                <div x-show="wizardStep === 5" x-transition>
                    <div class="flex items-center gap-2 mb-4" x-show="!editMode">
                        <button type="button" @click="wizardStep = tipeKategori === 'kendaraan' ? 3 : 4" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 hover:text-primary-light transition-colors">
                            <i class="bi bi-arrow-left text-sm"></i>
                        </button>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Isi Harga untuk <span class="text-primary-light" x-text="formNama"></span></p>
                    </div>
                    <div x-show="editMode" class="mb-4">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Edit harga <span class="text-primary-light" x-text="formNama"></span></p>
                    </div>

                    <!-- Market type badge -->
                    <div class="mb-3" x-show="tipeKategori === 'orang'">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[9px] font-bold"
                              :class="formMarketType === 'wna' ? 'bg-violet-100 text-violet-700' : (formMarketType === 'local' ? 'bg-emerald-100 text-emerald-700' : (formMarketType === 'dual' ? 'bg-gradient-to-r from-emerald-100 to-violet-100 text-emerald-700' : 'bg-blue-100 text-blue-700'))">
                            <i class="bi" :class="formMarketType === 'wna' ? 'bi-globe' : (formMarketType === 'local' ? 'bi-geo-alt' : (formMarketType === 'dual' ? 'bi-arrow-left-right' : 'bi-people'))"></i>
                            <span x-text="formMarketType === 'wna' ? 'WNA' : (formMarketType === 'local' ? 'Lokal' : (formMarketType === 'dual' ? 'Lokal & WNA' : 'Semua'))"></span>
                        </span>
                    </div>
                    
                    <form :action="getFormAction()" method="POST" class="space-y-4">
                        @csrf
                        <template x-if="editMode">
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
                        <input type="hidden" name="tipe_kategori" :value="tipeKategori">
                        <input type="hidden" name="market_type" :value="formMarketType">
                        <input type="hidden" name="nama_kategori" :value="formNama">
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Nama Kategori</label>
                            <input type="text" x-model="formNama" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        </div>

                        <div x-show="editMode">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kategori Pasar</label>
                            <select x-model="formMarketType"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                                <option value="all">Semua Pengunjung</option>
                                <option value="local">Lokal (Domestik)</option>
                                <option value="wna">WNA (Wisatawan Asing)</option>
                            </select>
                        </div>
                        
                        <!-- Single price (non-dual mode) -->
                        <div x-show="formMarketType !== 'dual'">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Harga Tiket (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="harga" x-model="formHarga" :required="formMarketType !== 'dual'" min="0" autocomplete="off"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                                   placeholder="Contoh: 10000">
                        </div>

                        <!-- Dual price (Lokal & WNA mode) -->
                        <div x-show="formMarketType === 'dual'" class="space-y-3">
                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                                <label class="block text-[10px] font-bold text-emerald-700 uppercase mb-2 flex items-center gap-1">
                                    <i class="bi bi-geo-alt"></i> Harga Lokal (Rp) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="harga_local" x-model="formHargaLocal" :required="formMarketType === 'dual'" min="0" autocomplete="off"
                                       class="w-full bg-white border border-emerald-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all"
                                       placeholder="Harga untuk pengunjung domestik">
                            </div>
                            <div class="bg-violet-50 border border-violet-100 rounded-xl p-4">
                                <label class="block text-[10px] font-bold text-violet-700 uppercase mb-2 flex items-center gap-1">
                                    <i class="bi bi-globe"></i> Harga WNA (Rp) <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" name="harga_wna" x-model="formHargaWna" :required="formMarketType === 'dual'" min="0" autocomplete="off"
                                       class="w-full bg-white border border-violet-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 outline-none focus:ring-4 focus:ring-violet-500/10 transition-all"
                                       placeholder="Harga untuk wisatawan asing">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Keterangan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                            <textarea name="deskripsi" rows="2" x-model="formDeskripsi"
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none"
                                      placeholder="Berlaku hari kerja, termasuk asuransi, dsb..."></textarea>
                        </div>

                        <button type="submit" class="w-full mt-2 bg-primary-light hover:bg-primary-dark text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl transition-all shadow-lg shadow-primary-light/30 active:scale-95 flex items-center justify-center gap-2">
                            <i class="bi bi-save-fill text-lg"></i> 
                            <span x-text="editMode ? 'Update Harga' : (formMarketType === 'dual' ? 'Simpan Harga Lokal & WNA' : 'Simpan Harga')"></span>
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
        window.location.href = '{{ url("administrator/objek_wisata/kategori/delete") }}/' + id;
    }
}
</script>
@endpush
@stop
