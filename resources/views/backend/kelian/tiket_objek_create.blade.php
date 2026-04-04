@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/kelian/tiket') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Tambah Objek Wisata</h1>
                <p class="text-[10px] text-white/80">Buat objek wisata baru</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4">
        <form action="{{ url('administrator/kelian/tiket/objek/store') }}" method="POST" enctype="multipart/form-data" id="main-form">
            @csrf
            <input type="hidden" name="bedakan_harga" id="bedakan_harga_input" value="0">

            <!-- IDENTITAS -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 mb-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="bi bi-card-checklist text-[#00a6eb]"></i> Identitas
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Nama Objek Wisata <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_objek" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Contoh: Pura Desa, Air Terjun, Pantai">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Deskripsi <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="3" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Jelaskan tentang objek wisata ini..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Banjar / Wilayah <span class="text-rose-500">*</span></label>
                        <select name="id_data_banjar" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                            <option value="">-- Pilih Banjar --</option>
                            @foreach($banjar as $b)
                            <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Alamat Lengkap <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="2" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Alamat lengkap objek wisata"></textarea>
                    </div>
                </div>
            </div>

            <!-- OPERASIONAL & MEDIA -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 mb-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="bi bi-camera-fill text-slate-400"></i> Media & Operasional
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Buka</label>
                            <input type="time" name="jam_buka" class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Tutup</label>
                            <input type="time" name="jam_tutup" class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Kapasitas Harian</label>
                            <input type="number" name="kapasitas_harian" min="1"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                placeholder="Kosongkan = unlimited">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Batas Tiket / Hari</label>
                            <input type="number" name="batas_tiket_harian" min="1"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                placeholder="Kosongkan = unlimited">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Foto Objek Wisata</label>
                        <input type="file" name="foto[]" accept="image/*" multiple id="foto-input"
                            class="text-xs text-slate-500
                                file:mr-4 file:py-2.5 file:px-4
                                file:rounded-lg file:border-0
                                file:text-xs file:font-bold
                                file:bg-[#00a6eb] file:text-white
                                hover:file:bg-[#0090d0]
                                file:cursor-pointer cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Max 2MB per file, format: JPG, PNG</p>
                        <div id="preview-container" class="mt-3 grid grid-cols-3 gap-2"></div>
                    </div>
                </div>
            </div>

            <!-- KATEGORI TIKET -->
            <div class="bg-slate-800 rounded-2xl shadow-lg p-5 mb-4 text-white">
                <h3 class="text-xs font-black uppercase tracking-wider mb-4 opacity-60 flex items-center gap-2">
                    <i class="bi bi-tag-fill"></i> Kategori Tiket
                </h3>

                <!-- STEP 1: TIPE KATEGORI -->
                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">1. Pilih Tipe Kategori</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" id="btn-orang" onclick="setTipe('orang')"
                            class="py-3 rounded-xl text-xs font-bold transition-all text-center bg-slate-700 text-slate-300">
                            <i class="bi bi-people-fill block text-lg mb-1"></i>Per Orang
                        </button>
                        <button type="button" id="btn-kendaraan" onclick="setTipe('kendaraan')"
                            class="py-3 rounded-xl text-xs font-bold transition-all text-center bg-slate-700 text-slate-300">
                            <i class="bi bi-car-front-fill block text-lg mb-1"></i>Per Kendaraan
                        </button>
                    </div>
                </div>

                <!-- STEP 2: FORMAT HARGA (Per Orang) -->
                <div id="step-format" class="mb-4" style="display:none;">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">2. Format Harga</label>
                    <select id="opsi-harga" onchange="updatePricing()"
                        class="w-full px-3 py-2.5 text-xs bg-slate-700 border-none rounded-lg text-white focus:ring-2 focus:ring-[#00a6eb]">
                        <option value="">-- Pilih Pengaturan --</option>
                        <option value="sama">Harga Sama untuk Semua Usia</option>
                        <option value="kategori">Harga Berbeda Berdasarkan Usia</option>
                    </select>
                </div>

                <!-- STEP 3: BEDAKAN HARGA LOKAL & WNA -->
                <div id="step-bedakan" class="mb-4" style="display:none;">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3">3. Bedakan Harga Lokal & WNA?</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" id="btn-harga-sama" onclick="setBedakan(false)"
                            class="py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-blue-500 text-white ring-2 ring-blue-500/50">
                            <i class="bi bi-dash-circle block text-base mb-0.5"></i>Harga Sama
                        </button>
                        <button type="button" id="btn-harga-beda" onclick="setBedakan(true)"
                            class="py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-slate-700 text-slate-300">
                            <i class="bi bi-arrow-left-right block text-base mb-0.5"></i>Beda Lokal & WNA
                        </button>
                    </div>
                    <p id="bedakan-hint" class="text-[9px] text-slate-500 mt-2" style="display:none;">Setiap kategori akan memiliki 2 harga: Lokal & WNA</p>
                </div>

                <!-- NOMINAL INPUTS -->
                <div id="pricing-area" style="display:none;">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3"><span id="step-num">4</span>. Isi Nominal Harga</h4>

                    <!-- ORANG SAMA - SINGLE PRICE -->
                    <div id="orang-sama-single" style="display:none;">
                        <div class="bg-slate-700/50 p-4 rounded-xl border border-slate-600">
                            <label class="text-[10px] font-bold text-slate-400 block mb-2">Tiket Umum</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500">Rp</span>
                                <input type="number" name="harga[umum]" placeholder="Masukkan harga" min="0"
                                    class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm px-2 py-1.5">
                            </div>
                            <input type="hidden" name="kategori_aktif[]" value="umum">
                        </div>
                    </div>

                    <!-- ORANG SAMA - DUAL PRICE -->
                    <div id="orang-sama-dual" style="display:none;" class="space-y-3">
                        <div class="bg-emerald-900/30 p-4 rounded-xl border border-emerald-700/50">
                            <span class="text-[9px] font-bold text-emerald-400 bg-emerald-500/20 px-2 py-0.5 rounded-lg inline-block mb-2"><i class="bi bi-geo-alt mr-1"></i>Lokal</span>
                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Tiket Umum</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500">Rp</span>
                                <input type="number" name="harga_local[umum]" placeholder="Harga lokal" min="0"
                                    class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm px-2 py-1.5">
                            </div>
                            <input type="hidden" name="kategori_aktif_local[]" value="umum">
                        </div>
                        <div class="bg-violet-900/30 p-4 rounded-xl border border-violet-700/50">
                            <span class="text-[9px] font-bold text-violet-400 bg-violet-500/20 px-2 py-0.5 rounded-lg inline-block mb-2"><i class="bi bi-globe mr-1"></i>WNA</span>
                            <label class="text-[10px] font-bold text-slate-400 block mb-1">Tiket Umum</label>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] text-slate-500">Rp</span>
                                <input type="number" name="harga_wna[umum]" placeholder="Harga WNA" min="0"
                                    class="w-full bg-slate-800 border-none rounded-lg text-white font-black text-sm px-2 py-1.5">
                            </div>
                            <input type="hidden" name="kategori_aktif_wna[]" value="umum">
                        </div>
                    </div>

                    <!-- ORANG KATEGORI - SINGLE PRICE -->
                    <div id="orang-kategori-single" style="display:none;" class="space-y-2">
                        @foreach(['dewasa' => 'Dewasa', 'anak' => 'Anak-anak', 'balita' => 'Balita', 'lansia' => 'Lansia', 'pelajar' => 'Pelajar/Mahasiswa'] as $key => $label)
                        <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group-item">
                            <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-4 w-4 rounded bg-slate-800 border-none text-[#00a6eb] checkbox-trigger">
                            <div class="flex-1">
                                <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-[10px] text-slate-500">Rp</span>
                                    <input type="number" name="harga[{{ $key }}]" placeholder="0" min="0"
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- ORANG KATEGORI - DUAL PRICE -->
                    <div id="orang-kategori-dual" style="display:none;" class="space-y-3">
                        @foreach(['dewasa' => 'Dewasa', 'anak' => 'Anak-anak', 'balita' => 'Balita', 'lansia' => 'Lansia', 'pelajar' => 'Pelajar/Mahasiswa'] as $key => $label)
                        <div class="bg-slate-700/30 rounded-xl border border-slate-600 overflow-hidden group-item">
                            <div class="flex items-center gap-3 p-3 border-b border-slate-600/50">
                                <input type="checkbox" name="kategori_aktif_dual[]" value="{{ $key }}" class="h-4 w-4 rounded bg-slate-800 border-none text-[#00a6eb] checkbox-trigger-dual">
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

                    <!-- KENDARAAN -->
                    <div id="kendaraan-options" style="display:none;" class="space-y-2">
                        @foreach(['motor' => 'Sepeda Motor', 'mobil' => 'Mobil', 'bus' => 'Bus Pariwisata', 'truk' => 'Kendaraan Besar'] as $key => $label)
                        <div class="flex items-center gap-3 bg-slate-700/50 p-3 rounded-xl border border-slate-600 group-item">
                            <input type="checkbox" name="kategori_aktif[]" value="{{ $key }}" class="h-4 w-4 rounded bg-slate-800 border-none text-amber-500 checkbox-trigger">
                            <div class="flex-1">
                                <span class="text-[10px] block font-bold text-slate-400">{{ $label }}</span>
                                <div class="flex items-center gap-1 mt-1">
                                    <span class="text-[10px] text-slate-500">Rp</span>
                                    <input type="number" name="harga[{{ $key }}]" placeholder="0" min="0"
                                        class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!-- Lainnya (Custom) -->
                        <div class="bg-slate-700/50 p-3 rounded-xl border border-dashed border-slate-500 group-item">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="kategori_aktif[]" value="custom_kendaraan" class="h-4 w-4 rounded bg-slate-800 border-none text-amber-500 checkbox-trigger">
                                <div class="flex-1">
                                    <span class="text-[10px] block font-bold text-amber-400 mb-1"><i class="bi bi-plus-circle mr-1"></i>Lainnya (Custom)</span>
                                    <input type="text" name="custom_nama_kendaraan" placeholder="Nama kendaraan, cth: Sepeda, ATV..."
                                        class="w-full bg-slate-800/80 border border-slate-600 rounded-lg px-2.5 py-1.5 text-[10px] text-white placeholder-slate-500 focus:ring-1 focus:ring-amber-500 mb-2">
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga[custom_kendaraan]" placeholder="0" min="0"
                                            class="w-full bg-transparent border-none p-0 focus:ring-0 text-sm font-black text-white price-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PLACEHOLDER -->
                <div id="pricing-placeholder" class="py-10 flex flex-col items-center justify-center text-center opacity-30 border-2 border-dashed border-slate-600 rounded-xl">
                    <i class="bi bi-tag text-2xl mb-2"></i>
                    <p class="text-[10px] font-bold">Pilih Tipe & Format Harga<br>untuk mengisi nominal.</p>
                </div>
            </div>

            <!-- DETAIL & TERMASUK TIKET -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5 mb-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="bi bi-card-text text-[#00a6eb]"></i> Detail & Termasuk Tiket
                </h3>
                <p class="text-[10px] text-slate-400 mb-4 -mt-2">Info untuk pengunjung di halaman detail wisata.</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2"><i class="bi bi-info-circle text-blue-500 mr-1"></i>Deskripsi Tiket</label>
                        <textarea name="detail_termasuk" rows="3"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Apa saja yang termasuk dalam tiket..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2"><i class="bi bi-signpost-2 text-emerald-500 mr-1"></i>Cara Penggunaan</label>
                        <textarea name="cara_penggunaan" rows="3"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Tunjukkan QR, scan di gerbang, dll..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2"><i class="bi bi-x-circle text-rose-500 mr-1"></i>Pembatalan</label>
                        <textarea name="pembatalan" rows="3"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Kebijakan pembatalan dan pengembalian dana..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2"><i class="bi bi-shield-check text-amber-500 mr-1"></i>Syarat & Ketentuan</label>
                        <textarea name="syarat_ketentuan" rows="3"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Syarat dan ketentuan yang berlaku..."></textarea>
                    </div>
                </div>
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all mb-6">
                <i class="bi bi-save mr-2"></i>Simpan Objek Wisata
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
// State
let tipeKategori = '';
let opsiHarga = '';
let bedakanHarga = false;

function setTipe(tipe) {
    tipeKategori = tipe;
    opsiHarga = '';
    bedakanHarga = false;
    document.getElementById('bedakan_harga_input').value = '0';

    // Button styles
    document.getElementById('btn-orang').className = tipe === 'orang'
        ? 'py-3 rounded-xl text-xs font-bold transition-all text-center bg-[#00a6eb] text-white ring-2 ring-[#00a6eb]/50'
        : 'py-3 rounded-xl text-xs font-bold transition-all text-center bg-slate-700 text-slate-300';
    document.getElementById('btn-kendaraan').className = tipe === 'kendaraan'
        ? 'py-3 rounded-xl text-xs font-bold transition-all text-center bg-amber-500 text-white ring-2 ring-amber-500/50'
        : 'py-3 rounded-xl text-xs font-bold transition-all text-center bg-slate-700 text-slate-300';

    // Step visibility
    document.getElementById('step-format').style.display = tipe === 'orang' ? 'block' : 'none';
    document.getElementById('step-bedakan').style.display = 'none';
    document.getElementById('opsi-harga').value = '';

    if (tipe === 'kendaraan') {
        showPricing('kendaraan');
    } else {
        hidePricing();
    }

    resetBedakanButtons();
}

function setBedakan(value) {
    bedakanHarga = value;
    document.getElementById('bedakan_harga_input').value = value ? '1' : '0';

    document.getElementById('btn-harga-sama').className = !value
        ? 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-blue-500 text-white ring-2 ring-blue-500/50'
        : 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-slate-700 text-slate-300';
    document.getElementById('btn-harga-beda').className = value
        ? 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-gradient-to-r from-emerald-500 to-violet-500 text-white ring-2 ring-emerald-500/50'
        : 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-slate-700 text-slate-300';
    document.getElementById('bedakan-hint').style.display = value ? 'block' : 'none';

    updatePricing();
}

function resetBedakanButtons() {
    document.getElementById('btn-harga-sama').className = 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-blue-500 text-white ring-2 ring-blue-500/50';
    document.getElementById('btn-harga-beda').className = 'py-2.5 rounded-xl text-[10px] font-bold transition-all text-center bg-slate-700 text-slate-300';
    document.getElementById('bedakan-hint').style.display = 'none';
}

function updatePricing() {
    opsiHarga = document.getElementById('opsi-harga').value;
    document.getElementById('step-bedakan').style.display = (tipeKategori === 'orang' && opsiHarga) ? 'block' : 'none';

    if (!opsiHarga && tipeKategori === 'orang') {
        hidePricing();
        return;
    }

    if (opsiHarga === 'sama' && !bedakanHarga) {
        showPricing('orang-sama-single');
    } else if (opsiHarga === 'sama' && bedakanHarga) {
        showPricing('orang-sama-dual');
    } else if (opsiHarga === 'kategori' && !bedakanHarga) {
        showPricing('orang-kategori-single');
    } else if (opsiHarga === 'kategori' && bedakanHarga) {
        showPricing('orang-kategori-dual');
    }
}

function showPricing(mode) {
    const area = document.getElementById('pricing-area');
    area.style.display = 'block';
    document.getElementById('pricing-placeholder').style.display = 'none';
    document.getElementById('step-num').textContent = (tipeKategori === 'kendaraan') ? '2' : '4';

    // Hide all sections
    ['orang-sama-single', 'orang-sama-dual', 'orang-kategori-single', 'orang-kategori-dual', 'kendaraan-options'].forEach(function(id) {
        document.getElementById(id).style.display = 'none';
    });

    document.getElementById(mode).style.display = 'block';
}

function hidePricing() {
    document.getElementById('pricing-area').style.display = 'none';
    document.getElementById('pricing-placeholder').style.display = 'flex';
    ['orang-sama-single', 'orang-sama-dual', 'orang-kategori-single', 'orang-kategori-dual', 'kendaraan-options'].forEach(function(id) {
        document.getElementById(id).style.display = 'none';
    });
}

// Photo preview
document.getElementById('foto-input').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
    Array.from(e.target.files).forEach(function(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = '<img src="' + ev.target.result + '" class="w-full h-20 object-cover rounded-lg border border-slate-200"><div class="absolute bottom-1 left-1 right-1 bg-black/50 text-white text-[8px] px-1 py-0.5 rounded truncate">' + file.name + '</div>';
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });
});

// Auto-check checkbox on price input
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('price-input')) {
        var container = e.target.closest('.group-item');
        if (!container) return;
        var cb = container.querySelector('.checkbox-trigger');
        if (cb) cb.checked = e.target.value > 0;
    }
    if (e.target.classList.contains('price-input-dual')) {
        var container = e.target.closest('.group-item');
        if (!container) return;
        var cb = container.querySelector('.checkbox-trigger-dual');
        var inputs = container.querySelectorAll('.price-input-dual');
        var any = false;
        inputs.forEach(function(inp) { if (inp.value > 0) any = true; });
        if (cb) cb.checked = any;
    }
});

// Focus price input on checkbox click
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('checkbox-trigger')) {
        var container = e.target.closest('.group-item');
        var input = container.querySelector('.price-input');
        if (input && e.target.checked) input.focus();
        if (input && !e.target.checked) input.value = '';
    }
    if (e.target.classList.contains('checkbox-trigger-dual')) {
        var container = e.target.closest('.group-item');
        var inputs = container.querySelectorAll('.price-input-dual');
        if (e.target.checked && inputs[0]) inputs[0].focus();
        if (!e.target.checked) inputs.forEach(function(inp) { inp.value = ''; });
    }
});

// Form validation
document.getElementById('main-form').addEventListener('submit', function(e) {
    if (!tipeKategori) {
        e.preventDefault();
        alert('Pilih tipe kategori tiket');
        return false;
    }
    if (tipeKategori === 'orang' && !opsiHarga) {
        e.preventDefault();
        alert('Pilih format harga per orang');
        return false;
    }

    // Check that at least one category is selected & has a price
    if (bedakanHarga) {
        // For dual pricing: validate based on current mode
        if (opsiHarga === 'sama') {
            var localVal = document.querySelector('input[name="harga_local[umum]"]');
            var wnaVal = document.querySelector('input[name="harga_wna[umum]"]');
            if (!localVal.value || localVal.value <= 0 || !wnaVal.value || wnaVal.value <= 0) {
                e.preventDefault();
                alert('Masukkan harga Lokal dan WNA untuk Tiket Umum');
                return false;
            }
        } else {
            var checked = document.querySelectorAll('input[name="kategori_aktif_dual[]"]:checked');
            if (checked.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 kategori usia');
                return false;
            }
            var hasErr = false;
            checked.forEach(function(cb) {
                var key = cb.value;
                var l = document.querySelector('input[name="harga_local[' + key + ']"]');
                var w = document.querySelector('input[name="harga_wna[' + key + ']"]');
                if (!l.value || l.value <= 0 || !w.value || w.value <= 0) hasErr = true;
            });
            if (hasErr) {
                e.preventDefault();
                alert('Masukkan harga Lokal dan WNA untuk semua kategori yang dipilih');
                return false;
            }
        }
    } else {
        // Single pricing or kendaraan
        if (opsiHarga === 'sama') {
            var umumVal = document.querySelector('#orang-sama-single input[name="harga[umum]"]');
            if (!umumVal.value || umumVal.value <= 0) {
                e.preventDefault();
                alert('Masukkan harga Tiket Umum');
                return false;
            }
        } else {
            var checkedSingle = document.querySelectorAll('#' + (tipeKategori === 'kendaraan' ? 'kendaraan-options' : 'orang-kategori-single') + ' .checkbox-trigger:checked');
            if (checkedSingle.length === 0) {
                e.preventDefault();
                alert('Pilih minimal 1 kategori');
                return false;
            }
            var hasErr2 = false;
            checkedSingle.forEach(function(cb) {
                var key = cb.value;
                var h = document.querySelector('input[name="harga[' + key + ']"]');
                if (!h || !h.value || h.value <= 0) hasErr2 = true;
            });
            if (hasErr2) {
                e.preventDefault();
                alert('Masukkan harga untuk semua kategori yang dipilih');
                return false;
            }
        }
    }
});
</script>
@endpush
@endsection
