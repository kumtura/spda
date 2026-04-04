@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    tipeKategori: '',
    opsiHarga: '',
    bedakanHarga: false,
    hasSelection() {
        return this.tipeKategori === 'kendaraan' || (this.tipeKategori === 'orang' && this.opsiHarga !== '');
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
        <input type="hidden" name="bedakan_harga" :value="bedakanHarga ? '1' : '0'">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: BASIC INFO -->
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
                            <textarea name="deskripsi" rows="4" required
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
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Jam Buka</label>
                            <input type="time" name="jam_buka" class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Foto Preview</label>
                            <input type="file" name="foto[]" multiple class="w-full text-xs text-slate-400 file:bg-slate-200 file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:text-[10px] cursor-pointer">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Kapasitas Harian (Orang)</label>
                            <input type="number" name="kapasitas_harian" min="1"
                                class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl"
                                placeholder="Kosongkan jika tidak dibatasi">
                            <p class="text-[10px] text-slate-400 mt-1">Opsional — total pengunjung per hari</p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Batas Penjualan Tiket / Hari</label>
                            <input type="number" name="batas_tiket_harian" min="1"
                                class="w-full px-3 py-2.5 text-sm bg-slate-50 border rounded-xl"
                                placeholder="Kosongkan = unlimited">
                            <p class="text-[10px] text-slate-400 mt-1">Jumlah tiket maksimal dijual per hari. Kosongkan = tidak dibatasi.</p>
                        </div>
                    </div>
                </div>

                <!-- DETAIL & TERMASUK TIKET -->
                <div class="bg-white border-2 border-slate-100 rounded-3xl p-6 md:p-8 shadow-xl">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-6 flex items-center gap-3">
                        <i class="bi bi-card-text text-primary-light text-xl"></i>
                        Detail & Termasuk Tiket
                    </h3>
                    <p class="text-xs text-slate-400 mb-6 -mt-2">Informasi ini akan ditampilkan di halaman detail wisata untuk pengunjung.</p>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">
                                <i class="bi bi-info-circle text-blue-500 mr-1"></i> Deskripsi Tiket
                            </label>
                            <textarea name="detail_termasuk" rows="3"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Apa saja yang termasuk dalam tiket: akses area, fasilitas, dll..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">
                                <i class="bi bi-signpost-2 text-emerald-500 mr-1"></i> Cara Penggunaan
                            </label>
                            <textarea name="cara_penggunaan" rows="3"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Langkah-langkah penggunaan tiket: tunjukkan QR, scan di gerbang, dll..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">
                                <i class="bi bi-x-circle text-rose-500 mr-1"></i> Pembatalan
                            </label>
                            <textarea name="pembatalan" rows="3"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Kebijakan pembatalan dan pengembalian dana..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">
                                <i class="bi bi-shield-check text-amber-500 mr-1"></i> Syarat & Ketentuan
                            </label>
                            <textarea name="syarat_ketentuan" rows="3"
                                class="w-full px-4 py-3 text-sm bg-slate-50 border-2 border-slate-50 rounded-xl focus:outline-none focus:bg-white focus:border-primary-light transition-all"
                                placeholder="Syarat dan ketentuan yang berlaku untuk penggunaan tiket..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- FINAL SUBMIT -->
                <div class="bg-white border-2 border-slate-100 rounded-3xl p-6 md:p-8 shadow-xl">
                    <div class="flex justify-center">
                        <button type="submit" class="px-10 py-5 bg-linear-to-r from-primary-light to-primary-dark text-white text-sm font-black uppercase tracking-widest rounded-2xl shadow-2xl hover:scale-105 active:scale-95 transition-all flex items-center gap-3">
                            <i class="bi bi-cloud-arrow-up-fill text-xl"></i> Daftar Objek Wisata Sekarang
                        </button>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: PRICING WIZARD -->
            <div class="xl:col-span-1 space-y-6">
                
                <div class="bg-slate-800 rounded-3xl p-6 shadow-2xl text-white sticky top-6">
                    <h3 class="text-xs font-black uppercase tracking-widest mb-6 opacity-60 flex items-center gap-2">
                        <i class="bi bi-tag-fill"></i> Kategori Tiket
                    </h3>

                    <!-- STEP 1: PILIH TIPE -->
                    <div class="space-y-4 mb-6">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">1. Pilih Tipe Kategori</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="tipeKategori = 'orang'; opsiHarga = ''; bedakanHarga = false"
                                :class="tipeKategori === 'orang' ? 'bg-primary-light text-white ring-2 ring-primary-light/50' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="py-3 rounded-xl text-xs font-bold transition-all text-center">
                                <i class="bi bi-people-fill block text-lg mb-1"></i>
                                Per Orang
                            </button>
                            <button type="button" @click="tipeKategori = 'kendaraan'; opsiHarga = ''; bedakanHarga = false"
                                :class="tipeKategori === 'kendaraan' ? 'bg-amber-500 text-white ring-2 ring-amber-500/50' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="py-3 rounded-xl text-xs font-bold transition-all text-center">
                                <i class="bi bi-car-front-fill block text-lg mb-1"></i>
                                Per Kendaraan
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: FORMAT HARGA (Per Orang only) -->
                    <div class="space-y-4 mb-6" x-show="tipeKategori === 'orang'" x-transition>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-2 tracking-widest">2. Format Harga</label>
                        <select x-model="opsiHarga"
                            class="w-full px-4 py-3 text-sm bg-slate-700 border-none rounded-xl text-white focus:ring-2 focus:ring-primary-light transition-all">
                            <option value="">-- Pilih Pengaturan --</option>
                            <option value="sama">Harga Sama untuk Semua Usia</option>
                            <option value="kategori">Harga Berbeda Berdasarkan Usia</option>
                        </select>
                    </div>

                    <!-- STEP 3: BEDAKAN HARGA LOKAL & WNA TOGGLE (Per Orang only) -->
                    <div class="mb-6" x-show="tipeKategori === 'orang' && opsiHarga !== ''" x-transition>
                        <label class="block text-[11px] font-bold text-slate-400 uppercase mb-3 tracking-widest">3. Bedakan Harga Lokal & WNA?</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="bedakanHarga = false"
                                :class="!bedakanHarga ? 'bg-blue-500 text-white ring-2 ring-blue-500/50' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="py-2.5 rounded-xl text-[10px] font-bold transition-all text-center">
                                <i class="bi bi-dash-circle block text-base mb-0.5"></i>
                                Harga Sama
                            </button>
                            <button type="button" @click="bedakanHarga = true"
                                :class="bedakanHarga ? 'bg-gradient-to-r from-emerald-500 to-violet-500 text-white ring-2 ring-emerald-500/50' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                                class="py-2.5 rounded-xl text-[10px] font-bold transition-all text-center">
                                <i class="bi bi-arrow-left-right block text-base mb-0.5"></i>
                                Beda Lokal & WNA
                            </button>
                        </div>
                        <p class="text-[9px] text-slate-500 mt-2" x-show="bedakanHarga">Setiap kategori akan memiliki 2 harga: Lokal & WNA</p>
                    </div>

                    <!-- NOMINAL INPUTS -->
                    <div class="space-y-4" x-show="hasSelection()" x-transition>
                        <h4 class="text-[11px] font-bold text-slate-400 uppercase mb-4 tracking-widest">
                            <span x-text="tipeKategori === 'orang' ? '4' : '2'"></span>. Isi Nominal Harga
                        </h4>
                        
                        <!-- ======= PER ORANG - SAMA SEMUA USIA ======= -->
                        <div x-show="tipeKategori === 'orang' && opsiHarga === 'sama'" class="space-y-4">

                            <!-- Single price -->
                            <div x-show="!bedakanHarga">
                                <div class="bg-slate-700/50 p-4 rounded-2xl border border-slate-600">
                                    <label class="text-[10px] font-bold text-slate-400 block mb-2">Tiket Umum</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga[umum]" placeholder="Masukkan harga" min="0" class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm">
                                    </div>
                                    <input type="hidden" name="kategori_aktif[]" value="umum">
                                </div>
                            </div>

                            <!-- Dual price: Lokal + WNA -->
                            <div x-show="bedakanHarga" class="space-y-3">
                                <div class="bg-emerald-900/30 p-4 rounded-2xl border border-emerald-700/50">
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-lg"><i class="bi bi-geo-alt mr-1"></i>Lokal</span>
                                    </div>
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Tiket Umum</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga_local[umum]" placeholder="Harga lokal" min="0" class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm">
                                    </div>
                                    <input type="hidden" name="kategori_aktif_local[]" value="umum">
                                </div>
                                <div class="bg-violet-900/30 p-4 rounded-2xl border border-violet-700/50">
                                    <div class="flex items-center gap-1.5 mb-2">
                                        <span class="text-[9px] font-bold text-violet-400 bg-violet-500/20 px-2 py-0.5 rounded-lg"><i class="bi bi-globe mr-1"></i>WNA</span>
                                    </div>
                                    <label class="text-[10px] font-bold text-slate-400 block mb-1">Tiket Umum</label>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga_wna[umum]" placeholder="Harga WNA" min="0" class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm">
                                    </div>
                                    <input type="hidden" name="kategori_aktif_wna[]" value="umum">
                                </div>
                            </div>
                        </div>

                        <!-- ======= PER ORANG - BERBEDA USIA ======= -->
                        <div x-show="tipeKategori === 'orang' && opsiHarga === 'kategori'" class="space-y-3">

                            <!-- Single price per age -->
                            <div x-show="!bedakanHarga" class="space-y-3">
                                @foreach(['dewasa' => 'Dewasa', 'anak' => 'Anak-anak', 'balita' => 'Balita', 'lansia' => 'Lansia', 'pelajar' => 'Pelajar/Mahasiswa'] as $key => $label)
                                <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group">
                                    <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-5 w-5 rounded bg-slate-800 border-none text-primary-light checkbox-trigger">
                                    <div class="flex-1">
                                        <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                        <div class="flex items-center gap-1 mt-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[{{ $key }}]" placeholder="Masukkan harga" min="0"
                                                class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Dual price per age: Lokal + WNA -->
                            <div x-show="bedakanHarga" class="space-y-4">
                                @foreach(['dewasa' => 'Dewasa', 'anak' => 'Anak-anak', 'balita' => 'Balita', 'lansia' => 'Lansia', 'pelajar' => 'Pelajar/Mahasiswa'] as $key => $label)
                                <div class="bg-slate-700/30 rounded-2xl border border-slate-600 overflow-hidden group">
                                    <div class="flex items-center gap-3 p-3 border-b border-slate-600/50">
                                        <input type="checkbox" name="kategori_aktif_dual[]" value="{{ $key }}" class="h-5 w-5 rounded bg-slate-800 border-none text-primary-light checkbox-trigger-dual">
                                        <span class="text-xs font-bold text-white">{{ $label }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 divide-x divide-slate-600/50">
                                        <div class="p-3">
                                            <span class="text-[8px] font-bold text-emerald-400 uppercase tracking-wider block mb-1"><i class="bi bi-geo-alt mr-0.5"></i>Lokal</span>
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-slate-500">Rp</span>
                                                <input type="number" name="harga_local[{{ $key }}]" placeholder="0" min="0"
                                                    class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input-dual">
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <span class="text-[8px] font-bold text-violet-400 uppercase tracking-wider block mb-1"><i class="bi bi-globe mr-0.5"></i>WNA</span>
                                            <div class="flex items-center gap-1">
                                                <span class="text-[10px] text-slate-500">Rp</span>
                                                <input type="number" name="harga_wna[{{ $key }}]" placeholder="0" min="0"
                                                    class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input-dual">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ======= PER KENDARAAN ======= -->
                        <div x-show="tipeKategori === 'kendaraan'" class="space-y-3">
                            @foreach(['motor' => 'Sepeda Motor', 'mobil' => 'Mobil', 'bus' => 'Bus Pariwisata', 'truk' => 'Kendaraan Besar'] as $key => $label)
                            <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group">
                                <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-5 w-5 rounded bg-slate-800 border-none text-amber-500 checkbox-trigger">
                                <div class="flex-1">
                                    <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                    <div class="flex items-center gap-1 mt-1">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga[{{ $key }}]" placeholder="Masukkan harga" min="0"
                                            class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            <!-- Lainnya (Custom) -->
                            <div class="bg-slate-700/50 p-3 rounded-xl border border-dashed border-slate-500 group">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="kategori_aktif[]" value="custom_kendaraan" class="h-5 w-5 rounded bg-slate-800 border-none text-amber-500 checkbox-trigger">
                                    <div class="flex-1">
                                        <span class="text-[10px] block font-bold text-amber-400 mb-1"><i class="bi bi-plus-circle mr-1"></i>Lainnya (Custom)</span>
                                        <input type="text" name="custom_nama_kendaraan" placeholder="Nama kendaraan, cth: Sepeda, ATV..." 
                                            class="w-full bg-slate-800/80 border border-slate-600 rounded-lg px-2.5 py-1.5 text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-amber-500 focus:border-amber-500 mb-2">
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[custom_kendaraan]" placeholder="Masukkan harga" min="0"
                                                class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PLACEHOLDER -->
                    <div x-show="!tipeKategori" class="py-12 flex flex-col items-center justify-center text-center opacity-30 border-2 border-dashed border-slate-600 rounded-3xl">
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
    // Auto-check checkbox when user types a price (single mode)
    document.addEventListener('input', function(e) {
        if(e.target.classList.contains('price-input')) {
            const container = e.target.closest('.group');
            if(!container) return;
            const checkbox = container.querySelector('.checkbox-trigger');
            if(checkbox) {
                checkbox.checked = e.target.value > 0;
            }
        }
        // Auto-check for dual mode (Lokal/WNA)
        if(e.target.classList.contains('price-input-dual')) {
            const container = e.target.closest('.group');
            if(!container) return;
            const checkbox = container.querySelector('.checkbox-trigger-dual');
            const inputs = container.querySelectorAll('.price-input-dual');
            let anyFilled = false;
            inputs.forEach(function(inp) { if(inp.value > 0) anyFilled = true; });
            if(checkbox) checkbox.checked = anyFilled;
        }
    });

    // Focus price input on checkbox click
    document.addEventListener('change', function(e) {
        if(e.target.classList.contains('checkbox-trigger')) {
            const container = e.target.closest('.group');
            const input = container.querySelector('.price-input');
            if(input && e.target.checked) input.focus();
            if(input && !e.target.checked) input.value = '';
        }
        if(e.target.classList.contains('checkbox-trigger-dual')) {
            const container = e.target.closest('.group');
            const inputs = container.querySelectorAll('.price-input-dual');
            if(e.target.checked && inputs[0]) inputs[0].focus();
            if(!e.target.checked) inputs.forEach(function(inp) { inp.value = ''; });
        }
    });
});
</script>
@endpush
@stop
