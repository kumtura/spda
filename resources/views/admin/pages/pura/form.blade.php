@extends('index')

@section('isi_menu')
@php
    $isEdit = isset($pura);
@endphp

<div class="space-y-6">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/puniapura') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-primary-light transition-colors">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-xs font-bold">Kembali</span>
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-2">{{ $isEdit ? 'Edit Pura' : 'Tambah Pura Baru' }}</h1>
    </div>

    <form action="{{ $isEdit ? url('administrator/puniapura/update/'.$pura->id_pura) : url('administrator/puniapura/store') }}" 
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($isEdit)
        @method('PUT')
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <ul class="text-sm text-red-600 list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Info Dasar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-widest">Informasi Dasar</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pura *</label>
                    <input type="text" name="nama_pura" value="{{ old('nama_pura', $isEdit ? $pura->nama_pura : '') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10 focus:border-primary-light">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Banjar</label>
                    <select name="id_data_banjar" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                        <option value="">-- Pilih Banjar --</option>
                        @foreach($banjar as $b)
                        <option value="{{ $b->id_data_banjar }}" {{ old('id_data_banjar', $isEdit ? $pura->id_data_banjar : '') == $b->id_data_banjar ? 'selected' : '' }}>{{ $b->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lokasi / Alamat</label>
                <textarea name="lokasi" rows="2" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">{{ old('lokasi', $isEdit ? $pura->lokasi : '') }}</textarea>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Link Google Maps</label>
                <input type="url" name="google_maps_url" value="{{ old('google_maps_url', $isEdit ? $pura->google_maps_url : '') }}" placeholder="https://maps.google.com/..."
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">{{ old('deskripsi', $isEdit ? $pura->deskripsi : '') }}</textarea>
            </div>
        </div>

        <!-- Pengurus -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4" x-data="{
            ketuaBanjarMode: '{{ old('banjar_ketua_manual', ($isEdit && $pura->banjar_ketua_manual) ? $pura->banjar_ketua_manual : '') ? 'manual' : 'select' }}',
            pemangkuBanjarMode: '{{ old('banjar_pemangku_manual', ($isEdit && $pura->banjar_pemangku_manual) ? $pura->banjar_pemangku_manual : '') ? 'manual' : 'select' }}'
        }">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-widest">Informasi Pengurus</h2>
            
            <!-- Ketua Pura -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Ketua Pura</label>
                    <input type="text" name="nama_ketua_pura" value="{{ old('nama_ketua_pura', $isEdit ? $pura->nama_ketua_pura : '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon Ketua</label>
                    <input type="text" name="no_telp_ketua" value="{{ old('no_telp_ketua', $isEdit ? $pura->no_telp_ketua : '') }}" placeholder="08xx..."
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar Ketua</label>
                    <button type="button" @click="ketuaBanjarMode = ketuaBanjarMode === 'select' ? 'manual' : 'select'" class="text-[10px] font-bold text-primary-light hover:underline">
                        <span x-text="ketuaBanjarMode === 'select' ? 'Input Manual' : 'Pilih dari Daftar'"></span>
                    </button>
                </div>
                <div x-show="ketuaBanjarMode === 'select'">
                    <select name="id_banjar_ketua" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                        <option value="">-- Pilih Banjar --</option>
                        @foreach($banjar as $b)
                        <option value="{{ $b->id_data_banjar }}" {{ old('id_banjar_ketua', $isEdit ? $pura->id_banjar_ketua : '') == $b->id_data_banjar ? 'selected' : '' }}>{{ $b->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="ketuaBanjarMode === 'manual'" x-cloak>
                    <input type="text" name="banjar_ketua_manual" value="{{ old('banjar_ketua_manual', $isEdit ? $pura->banjar_ketua_manual : '') }}" placeholder="Nama banjar (jika di luar desa)"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Pemangku -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama Pemangku</label>
                    <input type="text" name="nama_pemangku" value="{{ old('nama_pemangku', $isEdit ? $pura->nama_pemangku : '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon Pemangku</label>
                    <input type="text" name="no_telp_pemangku" value="{{ old('no_telp_pemangku', $isEdit ? $pura->no_telp_pemangku : '') }}" placeholder="08xx..."
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar Pemangku</label>
                    <button type="button" @click="pemangkuBanjarMode = pemangkuBanjarMode === 'select' ? 'manual' : 'select'" class="text-[10px] font-bold text-primary-light hover:underline">
                        <span x-text="pemangkuBanjarMode === 'select' ? 'Input Manual' : 'Pilih dari Daftar'"></span>
                    </button>
                </div>
                <div x-show="pemangkuBanjarMode === 'select'">
                    <select name="id_banjar_pemangku" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                        <option value="">-- Pilih Banjar --</option>
                        @foreach($banjar as $b)
                        <option value="{{ $b->id_data_banjar }}" {{ old('id_banjar_pemangku', $isEdit ? $pura->id_banjar_pemangku : '') == $b->id_data_banjar ? 'selected' : '' }}>{{ $b->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                <div x-show="pemangkuBanjarMode === 'manual'" x-cloak>
                    <input type="text" name="banjar_pemangku_manual" value="{{ old('banjar_pemangku_manual', $isEdit ? $pura->banjar_pemangku_manual : '') }}" placeholder="Nama banjar (jika di luar desa)"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>
        </div>

        <!-- Odalan -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-widest">Informasi Odalan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Wuku Odalan</label>
                    <select name="wuku_odalan" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
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
                        <option value="{{ $w }}" {{ old('wuku_odalan', $isEdit ? $pura->wuku_odalan : '') == $w ? 'selected' : '' }}>{{ $w }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Odalan Terdekat</label>
                    <input type="date" name="odalan_terdekat" value="{{ old('odalan_terdekat', $isEdit ? $pura->odalan_terdekat : '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>
        </div>

        <!-- Gambar -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
            <h2 class="text-sm font-black text-slate-700 uppercase tracking-widest">Gambar Pura</h2>
            
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gambar Utama</label>
                @if($isEdit && $pura->gambar_pura)
                <div class="mb-2">
                    <img src="{{ asset($pura->gambar_pura) }}" class="h-32 rounded-xl object-cover" alt="Current">
                </div>
                @endif
                <input type="file" name="gambar_pura" accept="image/*"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-light file:text-white">
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Gallery (bisa upload banyak)</label>
                @if($isEdit && isset($gallery) && $gallery->count() > 0)
                <div class="flex gap-2 flex-wrap mb-2">
                    @foreach($gallery as $g)
                    <div class="relative group">
                        <img src="{{ asset($g->gambar) }}" class="h-20 w-20 rounded-lg object-cover" alt="Gallery">
                        <a href="{{ url('administrator/puniapura/gallery/delete/'.$g->id_gallery_pura) }}" 
                           onclick="return confirm('Hapus foto ini?')"
                           class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white rounded-full flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
                <input type="file" name="gallery[]" accept="image/*" multiple
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-600">
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end gap-3">
            <a href="{{ url('administrator/puniapura') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-light hover:bg-primary-dark text-white text-sm font-bold shadow-md shadow-blue-200/50 transition-all">
                <i class="bi bi-check-circle mr-1.5"></i>{{ $isEdit ? 'Perbarui' : 'Simpan' }}
            </button>
        </div>
    </form>
</div>
@endsection
