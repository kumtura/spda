@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header with gradient and back button -->
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
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
            <form action="{{ url('administrator/kelian/tiket/objek/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Nama Objek Wisata <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_objek" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Contoh: Pura Desa, Air Terjun, Pantai">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Deskripsi <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required
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

                    <!-- Kategori Tiket -->
<div>
                        <label class="block text-xs font-bold text-slate-700 mb-3">Kategori Tiket <span class="text-rose-500">*</span></label>
                        
                        <!-- Step 1: Pilih Tipe Utama -->
                        <div class="mb-4">
                            <label class="block text-[10px] font-bold text-slate-600 mb-2">Tipe Kategori</label>
                            <select id="tipe-utama" class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                                <option value="">Pilih Tipe Kategori</option>
                                <option value="orang">Per Orang</option>
                                <option value="kendaraan">Per Kendaraan</option>
                            </select>
                        </div>

                        <!-- Step 2: Sub-pilihan untuk Per Orang -->
                        <div id="sub-orang" class="mb-4" style="display: none;">
                            <label class="block text-[10px] font-bold text-slate-600 mb-2">Pengaturan Harga Per Orang</label>
                            <select id="sub-orang-select" class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                                <option value="">Pilih Pengaturan</option>
                                <option value="sama">Harga Sama untuk Semua Usia</option>
                                <option value="kategori">Harga Berbeda Berdasarkan Usia</option>
                            </select>
                        </div>

                        <!-- Step 2b: Kategori Pasar (WNA/Lokal) for Per Orang -->
                        <div id="market-type-select" class="mb-4" style="display: none;">
                            <label class="block text-[10px] font-bold text-slate-600 mb-2">Kategori Pasar</label>
                            <select id="market-type-value" class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                                <option value="all">Semua (Lokal & WNA)</option>
                                <option value="local">Lokal</option>
                                <option value="wna">WNA</option>
                            </select>
                            <p class="text-[9px] text-slate-400 mt-1">Pilih untuk siapa harga ini berlaku</p>
                        </div>

                        <!-- Kategori Options -->
                        <div id="kategori-options" style="display: none;">
                            <!-- Per Orang - Harga Sama -->
                            <div id="orang-sama" class="border border-slate-200 rounded-xl p-4 bg-slate-50" style="display: none;">
                                <p class="text-xs font-bold text-slate-700 mb-3">Tiket Masuk Per Orang</p>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="kat_umum" name="kategori_aktif[]" value="umum" class="h-4 w-4 text-[#00a6eb] rounded" checked>
                                    <label for="kat_umum" class="flex-1 text-xs text-slate-700">Tiket Umum (Semua Usia)</label>
                                    <div class="flex items-center gap-1">
                                        <span class="text-[10px] text-slate-500">Rp</span>
                                        <input type="number" name="harga[umum]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg">
                                    </div>
                                </div>
                            </div>

                            <!-- Per Orang - Berdasarkan Usia -->
                            <div id="orang-kategori" class="border border-slate-200 rounded-xl p-4 bg-slate-50" style="display: none;">
                                <p class="text-xs font-bold text-slate-700 mb-3">Pilih Kategori Usia</p>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_dewasa" name="kategori_aktif[]" value="dewasa" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_dewasa" class="flex-1 text-xs text-slate-700">Dewasa</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[dewasa]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_anak" name="kategori_aktif[]" value="anak" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_anak" class="flex-1 text-xs text-slate-700">Anak-anak</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[anak]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_balita" name="kategori_aktif[]" value="balita" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_balita" class="flex-1 text-xs text-slate-700">Balita</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[balita]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_lansia" name="kategori_aktif[]" value="lansia" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_lansia" class="flex-1 text-xs text-slate-700">Lansia</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[lansia]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_pelajar" name="kategori_aktif[]" value="pelajar" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_pelajar" class="flex-1 text-xs text-slate-700">Pelajar/Mahasiswa</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[pelajar]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Per Kendaraan -->
                            <div id="kendaraan-options" class="border border-slate-200 rounded-xl p-4 bg-slate-50" style="display: none;">
                                <p class="text-xs font-bold text-slate-700 mb-3">Pilih Jenis Kendaraan</p>
                                <div class="space-y-2">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_motor" name="kategori_aktif[]" value="motor" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_motor" class="flex-1 text-xs text-slate-700">Motor</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[motor]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_mobil" name="kategori_aktif[]" value="mobil" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_mobil" class="flex-1 text-xs text-slate-700">Mobil</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[mobil]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_bus" name="kategori_aktif[]" value="bus" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_bus" class="flex-1 text-xs text-slate-700">Bus</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[bus]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" id="kat_truk" name="kategori_aktif[]" value="truk" class="h-4 w-4 text-[#00a6eb] rounded">
                                        <label for="kat_truk" class="flex-1 text-xs text-slate-700">Truk</label>
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-slate-500">Rp</span>
                                            <input type="number" name="harga[truk]" placeholder="0" min="0" class="w-28 px-2 py-1.5 text-xs border border-slate-200 rounded-lg" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Kapasitas Harian</label>
                        <input type="number" name="kapasitas_harian"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Kosongkan jika tidak dibatasi">
                        <p class="text-[10px] text-slate-500 mt-1">Opsional - Kosongkan jika kapasitas tidak dibatasi</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Buka</label>
                            <input type="time" name="jam_buka"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Tutup</label>
                            <input type="time" name="jam_tutup"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
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
                        <p class="text-[10px] text-slate-500 mt-1">Max 2MB per file, format: JPG, PNG. Bisa pilih lebih dari 1 foto</p>
                        <div id="preview-container" class="mt-3 grid grid-cols-3 gap-2"></div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="bi bi-save mr-2"></i>Simpan Objek Wisata
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Photo preview
document.getElementById('foto-input').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    if (files.length > 0) {
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-20 object-cover rounded-lg border border-slate-200">
                        <div class="absolute bottom-1 left-1 right-1 bg-black/50 text-white text-[8px] px-1 py-0.5 rounded truncate">
                            ${file.name}
                        </div>
                    `;
                    previewContainer.appendChild(div);
                };
                
                reader.readAsDataURL(file);
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Script loaded');
    
    const tipeUtama = document.getElementById('tipe-utama');
    const subOrang = document.getElementById('sub-orang');
    const subOrangSelect = document.getElementById('sub-orang-select');
    const kategoriOptions = document.getElementById('kategori-options');
    const orangSama = document.getElementById('orang-sama');
    const orangKategori = document.getElementById('orang-kategori');
    const kendaraanOptions = document.getElementById('kendaraan-options');
    const marketTypeSelect = document.getElementById('market-type-select');

    console.log('Elements found:', {
        tipeUtama: !!tipeUtama,
        subOrang: !!subOrang,
        kategoriOptions: !!kategoriOptions
    });

    // Handle tipe utama change
    tipeUtama.addEventListener('change', function() {
        console.log('Tipe utama changed to:', this.value);
        
        // Reset everything
        subOrang.style.display = 'none';
        subOrangSelect.value = '';
        kategoriOptions.style.display = 'none';
        orangSama.style.display = 'none';
        orangKategori.style.display = 'none';
        kendaraanOptions.style.display = 'none';
        marketTypeSelect.style.display = 'none';
        
        // Clear all checkboxes
        document.querySelectorAll('input[name="kategori_aktif[]"]').forEach(cb => {
            cb.checked = false;
            const key = cb.value;
            const hargaInput = document.querySelector(`input[name="harga[${key}]"]`);
            if (hargaInput) {
                hargaInput.value = '';
                hargaInput.disabled = true;
            }
        });

        if (this.value === 'orang') {
            console.log('Showing sub-orang');
            subOrang.style.display = 'block';
            marketTypeSelect.style.display = 'block';
        } else if (this.value === 'kendaraan') {
            console.log('Showing kendaraan options');
            kategoriOptions.style.display = 'block';
            kendaraanOptions.style.display = 'block';
            marketTypeSelect.style.display = 'none';
        }
    });

    // Handle sub-orang change
    subOrangSelect.addEventListener('change', function() {
        console.log('Sub-orang changed to:', this.value);
        
        kategoriOptions.style.display = 'none';
        orangSama.style.display = 'none';
        orangKategori.style.display = 'none';
        
        // Clear checkboxes
        document.querySelectorAll('#orang-sama input[name="kategori_aktif[]"], #orang-kategori input[name="kategori_aktif[]"]').forEach(cb => {
            cb.checked = false;
            const key = cb.value;
            const hargaInput = document.querySelector(`input[name="harga[${key}]"]`);
            if (hargaInput) {
                hargaInput.value = '';
                hargaInput.disabled = true;
            }
        });

        if (this.value === 'sama') {
            console.log('Showing orang-sama');
            kategoriOptions.style.display = 'block';
            orangSama.style.display = 'block';
            // Auto-check umum
            document.getElementById('kat_umum').checked = true;
            document.querySelector('input[name="harga[umum]"]').disabled = false;
        } else if (this.value === 'kategori') {
            console.log('Showing orang-kategori');
            kategoriOptions.style.display = 'block';
            orangKategori.style.display = 'block';
        }
    });

    // Auto-enable/disable price input when checkbox is checked
    document.querySelectorAll('input[name="kategori_aktif[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const value = this.value;
            const hargaInput = document.querySelector(`input[name="harga[${value}]"]`);
            if (hargaInput) {
                if (this.checked) {
                    hargaInput.disabled = false;
                    hargaInput.required = true;
                    hargaInput.focus();
                } else {
                    hargaInput.disabled = true;
                    hargaInput.required = false;
                    hargaInput.value = '';
                }
            }
        });
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const tipeUtama = document.getElementById('tipe-utama').value;
    
    if (!tipeUtama) {
        e.preventDefault();
        alert('Pilih tipe kategori tiket');
        return false;
    }
    
    if (tipeUtama === 'orang') {
        const subOrang = document.getElementById('sub-orang-select').value;
        if (!subOrang) {
            e.preventDefault();
            alert('Pilih pengaturan harga per orang');
            return false;
        }
    }
    
    const checkboxes = document.querySelectorAll('input[name="kategori_aktif[]"]:checked');
    
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Pilih minimal 1 kategori tiket');
        return false;
    }
    
    // Validate that checked categories have prices
    let hasError = false;
    checkboxes.forEach(checkbox => {
        const value = checkbox.value;
        const hargaInput = document.querySelector(`input[name="harga[${value}]"]`);
        if (!hargaInput.value || hargaInput.value <= 0) {
            hasError = true;
        }
    });
    
    if (hasError) {
        e.preventDefault();
        alert('Masukkan harga untuk semua kategori yang dipilih');
        return false;
    }
    
    // Inject market_type hidden inputs for each checked category
    const marketTypeValue = document.getElementById('market-type-value').value;
    document.querySelectorAll('input[name^="market_type["]').forEach(el => el.remove());
    checkboxes.forEach(checkbox => {
        const value = checkbox.value;
        const mt = (tipeUtama === 'orang') ? marketTypeValue : 'all';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'market_type[' + value + ']';
        input.value = mt;
        this.appendChild(input);
    });
});
</script>
@endpush
@endsection
