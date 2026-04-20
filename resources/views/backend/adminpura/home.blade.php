@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{
    activeSection: 'info',
    showGalleryModal: false,
    galleryImage: '',
    editMode: false
}">
    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Dashboard Pura</h2>
        <p class="text-[10px] text-slate-400 mt-1">{{ Auth::user()->name }}</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
    @endif

    @if(!$pura)
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
        <i class="bi bi-exclamation-triangle text-3xl text-amber-400 mb-2"></i>
        <p class="text-sm font-bold text-slate-700">Belum Ada Pura yang Ditugaskan</p>
        <p class="text-[10px] text-slate-400 mt-1">Hubungi admin sistem untuk menghubungkan akun Anda ke pura.</p>
    </div>
    @else

    {{-- Stats Card --}}
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-building text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $pura->nama_pura }}</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Terkumpul</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPunia ?? 0, 0, ',', '.') }}</h3>
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Punia Hari Ini</p>
                    <p class="font-bold">Rp {{ number_format($puniaHariIni ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Transaksi Hari Ini</p>
                    <p class="font-bold">{{ $transaksiHariIni ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-3 gap-3">
        <a href="{{ url('administrator/pura/punia') }}" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-center">
            <div class="w-10 h-10 mx-auto bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-2 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-wallet2 text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-[11px]">Punia</h3>
        </a>
        <a href="{{ url('administrator/pura/verifikasi') }}" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-center">
            <div class="w-10 h-10 mx-auto bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-2 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-clipboard-check text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-[11px]">Verifikasi</h3>
        </a>
        @if(isset($qris))
        <a href="{{ url('administrator/puniapura/qris/'.$pura->id_pura) }}" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-center">
            <div class="w-10 h-10 mx-auto bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-2 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-qr-code text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-[11px]">QRIS</h3>
        </a>
        @else
        <a href="{{ url('pura/'.$pura->id_pura) }}" target="_blank" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-center">
            <div class="w-10 h-10 mx-auto bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-2 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-link-45deg text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-[11px]">Link Publik</h3>
        </a>
        @endif
    </div>

    {{-- Section Tabs --}}
    <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-4 px-4 pb-1">
        <button @click="activeSection = 'info'" :class="activeSection === 'info' ? 'bg-[#00a6eb] text-white' : 'bg-slate-100 text-slate-500'"
                class="px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all">
            <i class="bi bi-info-circle mr-1"></i>Informasi Pura
        </button>
        <button @click="activeSection = 'pemangku'" :class="activeSection === 'pemangku' ? 'bg-[#00a6eb] text-white' : 'bg-slate-100 text-slate-500'"
                class="px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all">
            <i class="bi bi-person mr-1"></i>Pemangku
        </button>
        <button @click="activeSection = 'odalan'" :class="activeSection === 'odalan' ? 'bg-[#00a6eb] text-white' : 'bg-slate-100 text-slate-500'"
                class="px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all">
            <i class="bi bi-calendar-event mr-1"></i>Odalan
        </button>
        <button @click="activeSection = 'gallery'" :class="activeSection === 'gallery' ? 'bg-[#00a6eb] text-white' : 'bg-slate-100 text-slate-500'"
                class="px-4 py-2 rounded-full text-[11px] font-bold whitespace-nowrap transition-all">
            <i class="bi bi-images mr-1"></i>Gallery
        </button>
    </div>

    {{-- ========== INFORMASI PURA SECTION ========== --}}
    <div x-show="activeSection === 'info'" x-transition>
        <form action="{{ url('administrator/pura/update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                {{-- Gambar Utama --}}
                <div class="h-48 bg-slate-100 relative">
                    @if($pura->gambar_pura)
                    <img src="{{ asset($pura->gambar_pura) }}" class="w-full h-full object-cover" alt="{{ $pura->nama_pura }}">
                    @else
                    <div class="flex items-center justify-center h-full">
                        <i class="bi bi-image text-4xl text-slate-300"></i>
                    </div>
                    @endif
                    <label class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-sm text-slate-600 px-3 py-1.5 rounded-lg text-[10px] font-bold cursor-pointer hover:bg-white transition-colors shadow-sm">
                        <i class="bi bi-camera mr-1"></i>Ganti Foto
                        <input type="file" name="gambar_pura" accept="image/*" class="hidden">
                    </label>
                </div>

                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pura *</label>
                        <input type="text" name="nama_pura" value="{{ old('nama_pura', $pura->nama_pura) }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi / Alamat</label>
                        <textarea name="lokasi" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">{{ old('lokasi', $pura->lokasi) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Link Google Maps</label>
                        <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $pura->google_maps_url) }}" placeholder="https://maps.google.com/..."
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                        @if($pura->google_maps_url)
                        <a href="{{ $pura->google_maps_url }}" target="_blank" class="inline-flex items-center gap-1 mt-1.5 text-[10px] font-bold text-[#00a6eb]">
                            <i class="bi bi-geo-alt"></i> Buka di Google Maps
                        </a>
                        @endif
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">{{ old('deskripsi', $pura->deskripsi) }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl bg-[#00a6eb] hover:bg-[#0090d0] text-white text-sm font-bold shadow-md shadow-blue-200/50 transition-all">
                        <i class="bi bi-check-circle mr-1.5"></i>Simpan Informasi
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ========== PEMANGKU SECTION ========== --}}
    <div x-show="activeSection === 'pemangku'" x-transition>
        <form action="{{ url('administrator/pura/update') }}" method="POST">
            @csrf
            {{-- Hidden fields to preserve existing data --}}
            <input type="hidden" name="nama_pura" value="{{ $pura->nama_pura }}">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                        <i class="bi bi-person-badge text-[#00a6eb] text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Informasi Pemangku</h3>
                        <p class="text-[10px] text-slate-400">Data petugas pemangku pura</p>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pemangku</label>
                    <input type="text" name="nama_pemangku" value="{{ old('nama_pemangku', $pura->nama_pemangku) }}" placeholder="Masukkan nama pemangku"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon Pemangku</label>
                    <input type="text" name="no_telp_pemangku" value="{{ old('no_telp_pemangku', $pura->no_telp_pemangku) }}" placeholder="08xx..."
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                </div>

                @if($pura->nama_pemangku || $pura->no_telp_pemangku)
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Data Saat Ini</p>
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center border border-slate-200">
                            <i class="bi bi-person text-slate-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $pura->nama_pemangku ?: '-' }}</p>
                            @if($pura->no_telp_pemangku)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pura->no_telp_pemangku) }}" target="_blank" class="text-[10px] text-[#00a6eb] font-bold">
                                <i class="bi bi-whatsapp"></i> {{ $pura->no_telp_pemangku }}
                            </a>
                            @else
                            <p class="text-[10px] text-slate-400">Belum ada nomor telepon</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <button type="submit" class="w-full py-3 rounded-xl bg-[#00a6eb] hover:bg-[#0090d0] text-white text-sm font-bold shadow-md shadow-blue-200/50 transition-all">
                    <i class="bi bi-check-circle mr-1.5"></i>Simpan Pemangku
                </button>
            </div>
        </form>
    </div>

    {{-- ========== ODALAN SECTION ========== --}}
    <div x-show="activeSection === 'odalan'" x-transition>
        <form action="{{ url('administrator/pura/update') }}" method="POST">
            @csrf
            <input type="hidden" name="nama_pura" value="{{ $pura->nama_pura }}">

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center border border-amber-100">
                        <i class="bi bi-calendar-event text-amber-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Informasi Odalan</h3>
                        <p class="text-[10px] text-slate-400">Jadwal upacara dan wuku odalan</p>
                    </div>
                </div>

                @if($pura->wuku_odalan || $pura->odalan_terdekat)
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[9px] uppercase text-amber-600/60 font-bold">Wuku</p>
                            <p class="text-lg font-black text-amber-800">{{ $pura->wuku_odalan ?: '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] uppercase text-amber-600/60 font-bold">Odalan Terdekat</p>
                            <p class="text-sm font-bold text-amber-800">
                                {{ $pura->odalan_terdekat ? \Carbon\Carbon::parse($pura->odalan_terdekat)->translatedFormat('d M Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Wuku Odalan</label>
                    <select name="wuku_odalan" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                        <option value="">-- Pilih Wuku --</option>
                        @php
                            $wukuList = [
                                'Sinta', 'Landep', 'Ukir', 'Kulantir', 'Tolu', 'Gumbreg',
                                'Wariga', 'Warigadean', 'Julungwangi', 'Sungsang', 'Dungulan', 'Kuningan',
                                'Langkir', 'Medangsia', 'Pujut', 'Pahang', 'Krulut', 'Merakih',
                                'Tambir', 'Medangkungan', 'Matal', 'Uye', 'Menail', 'Perangbakat',
                                'Bala', 'Ugu', 'Wayang', 'Klawu', 'Dukut', 'Watugunung'
                            ];
                        @endphp
                        @foreach($wukuList as $w)
                        <option value="{{ $w }}" {{ old('wuku_odalan', $pura->wuku_odalan) == $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Odalan Terdekat</label>
                    <input type="date" name="odalan_terdekat" value="{{ old('odalan_terdekat', $pura->odalan_terdekat) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]">
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-[#00a6eb] hover:bg-[#0090d0] text-white text-sm font-bold shadow-md shadow-blue-200/50 transition-all">
                    <i class="bi bi-check-circle mr-1.5"></i>Simpan Odalan
                </button>
            </div>
        </form>
    </div>

    {{-- ========== GALLERY SECTION ========== --}}
    <div x-show="activeSection === 'gallery'" x-transition>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-purple-50 rounded-xl flex items-center justify-center border border-purple-100">
                        <i class="bi bi-images text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800">Gallery Pura</h3>
                        <p class="text-[10px] text-slate-400">{{ isset($gallery) ? $gallery->count() : 0 }} foto</p>
                    </div>
                </div>
            </div>

            {{-- Gallery Grid --}}
            @if(isset($gallery) && $gallery->count() > 0)
            <div class="grid grid-cols-3 gap-2">
                @foreach($gallery as $g)
                <div class="relative group aspect-square">
                    <img src="{{ asset($g->gambar) }}" 
                         @click="galleryImage = '{{ asset($g->gambar) }}'; showGalleryModal = true"
                         class="w-full h-full object-cover rounded-xl cursor-pointer hover:opacity-80 transition-opacity" alt="Gallery">
                    <a href="{{ url('administrator/pura/gallery/delete/'.$g->id_gallery_pura) }}"
                       onclick="return confirm('Hapus foto ini dari gallery?')"
                       class="absolute top-1 right-1 h-6 w-6 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity shadow-md">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="bi bi-image text-3xl text-slate-200"></i>
                <p class="text-xs text-slate-400 mt-2">Belum ada foto gallery</p>
            </div>
            @endif

            {{-- Upload Gallery --}}
            <form action="{{ url('administrator/pura/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="nama_pura" value="{{ $pura->nama_pura }}">
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center">
                    <label class="cursor-pointer block">
                        <i class="bi bi-cloud-arrow-up text-2xl text-slate-300"></i>
                        <p class="text-[11px] font-bold text-slate-500 mt-1">Pilih foto untuk diupload</p>
                        <p class="text-[9px] text-slate-400">Bisa pilih banyak foto sekaligus</p>
                        <input type="file" name="gallery[]" accept="image/*" multiple class="hidden" onchange="this.closest('form').querySelector('.upload-btn').classList.remove('hidden')">
                    </label>
                    <button type="submit" class="upload-btn hidden mt-3 w-full py-2.5 rounded-xl bg-[#00a6eb] hover:bg-[#0090d0] text-white text-sm font-bold transition-all">
                        <i class="bi bi-upload mr-1.5"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    @endif
</div>

{{-- Gallery Lightbox Modal --}}
<template x-teleport="body">
    <div x-show="showGalleryModal" x-cloak
         class="fixed inset-0 bg-black/80 flex items-center justify-center z-[100] p-4"
         @click.self="showGalleryModal = false" @keydown.escape.window="showGalleryModal = false">
        <div class="relative max-w-lg w-full">
            <img :src="galleryImage" class="w-full rounded-xl shadow-2xl" alt="Gallery">
            <button @click="showGalleryModal = false" class="absolute -top-3 -right-3 h-8 w-8 bg-white rounded-full flex items-center justify-center shadow-lg text-slate-600 hover:text-red-500 transition-colors">
                <i class="bi bi-x-lg text-sm"></i>
            </button>
        </div>
    </div>
</template>
@endsection
