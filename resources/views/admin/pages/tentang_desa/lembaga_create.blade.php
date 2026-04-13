@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{
    pengurusList: [{ nama: '', keterangan: '', no_telp: '', fotoPreview: null }],
    addPengurus() {
        this.pengurusList.push({ nama: '', keterangan: '', no_telp: '', fotoPreview: null });
    },
    removePengurus(index) {
        if (this.pengurusList.length > 1) this.pengurusList.splice(index, 1);
    },
    previewFoto(event, index) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => this.pengurusList[index].fotoPreview = e.target.result;
            reader.readAsDataURL(file);
        }
    },
    galleryPreviews: [],
    previewGallery(event) {
        this.galleryPreviews = [];
        Array.from(event.target.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = e => this.galleryPreviews.push(e.target.result);
            reader.readAsDataURL(file);
        });
    }
}">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1">
                <i class="bi bi-arrow-left mr-1"></i>
                <a href="{{ url('administrator/tentang-desa/lembaga') }}">Lembaga Desa Adat</a>
            </p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Lembaga Baru</h1>
            <p class="text-slate-500 font-medium text-sm">Isi informasi lengkap lembaga desa adat.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="p-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200">
        <p class="font-bold mb-1">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ url('administrator/tentang-desa/lembaga/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ── INFORMASI DASAR ── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-info-circle text-primary-light"></i> Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lembaga <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga') }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                        placeholder="Contoh: Lembaga Adat Desa Kumtura">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Ketua</label>
                    <input type="text" name="ketua" value="{{ old('ketua') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                        placeholder="Nama ketua lembaga">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Logo Lembaga (Opsional)</label>
                    <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg"
                        class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                    <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi Lembaga</label>
                <textarea id="deskripsi_editor" name="deskripsi" rows="8"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-y"
                    placeholder="Tulis deskripsi, sejarah, visi, misi lembaga...">{{ old('deskripsi') }}</textarea>
                <p class="text-[10px] text-slate-400 mt-1">Gunakan toolbar di atas untuk memformat teks.</p>
            </div>
        </div>

        {{-- ── PENGURUS / PENANGGUNG JAWAB ── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-black text-slate-700 flex items-center gap-2">
                    <i class="bi bi-people text-primary-light"></i> Pengurus / Penanggung Jawab
                </h3>
                <button type="button" @click="addPengurus()"
                        class="flex items-center gap-1.5 bg-slate-100 hover:bg-primary-light hover:text-white text-slate-600 px-4 py-2 rounded-xl font-bold text-xs transition-all">
                    <i class="bi bi-plus-lg"></i> Tambah Pengurus
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(p, index) in pengurusList" :key="index">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 relative">
                        <button type="button" @click="removePengurus(index)" x-show="pengurusList.length > 1"
                                class="absolute top-3 right-3 h-7 w-7 flex items-center justify-center bg-white border border-rose-200 text-rose-400 rounded-lg hover:bg-rose-50 transition-colors text-xs">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Foto --}}
                            <div class="flex flex-col items-center gap-3">
                                <div class="h-24 w-24 rounded-2xl bg-white border-2 border-dashed border-slate-300 overflow-hidden flex items-center justify-center">
                                    <template x-if="p.fotoPreview">
                                        <img :src="p.fotoPreview" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!p.fotoPreview">
                                        <i class="bi bi-person-fill text-3xl text-slate-300"></i>
                                    </template>
                                </div>
                                <label class="cursor-pointer text-[10px] font-bold text-primary-light hover:underline">
                                    <span>Pilih Foto</span>
                                    <input type="file" :name="'pengurus[' + index + '][foto]'" accept="image/png,image/jpeg,image/jpg"
                                           @change="previewFoto($event, index)" class="hidden">
                                </label>
                                <p class="text-[9px] text-slate-400 text-center">Opsional. Maks 2MB.</p>
                            </div>

                            {{-- Fields --}}
                            <div class="md:col-span-2 space-y-3">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="'pengurus[' + index + '][nama]'" x-model="p.nama" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                                        placeholder="Nama lengkap">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Keterangan / Jabatan</label>
                                    <input type="text" :name="'pengurus[' + index + '][keterangan]'" x-model="p.keterangan"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                                        placeholder="Contoh: Ketua, Sekretaris, Bendahara">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon (Opsional)</label>
                                    <input type="text" :name="'pengurus[' + index + '][no_telp]'" x-model="p.no_telp"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10"
                                        placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- ── GALLERY ── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-images text-primary-light"></i> Gallery Foto
            </h3>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Upload Foto Gallery (Bisa Lebih dari Satu)</label>
                <input type="file" name="gallery[]" multiple accept="image/png,image/jpeg,image/jpg"
                    @change="previewGallery($event)"
                    class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 3MB per foto. Pilih beberapa file sekaligus.</p>
            </div>

            {{-- Preview gallery --}}
            <div x-show="galleryPreviews.length > 0" class="grid grid-cols-3 md:grid-cols-5 gap-3">
                <template x-for="(src, i) in galleryPreviews" :key="i">
                    <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                        <img :src="src" class="h-full w-full object-cover">
                    </div>
                </template>
            </div>
        </div>

        {{-- ── ACTIONS ── --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ url('administrator/tentang-desa/lembaga') }}"
               class="px-6 py-3 text-slate-500 font-bold text-sm hover:text-slate-700 transition-colors">
                <i class="bi bi-arrow-left mr-1"></i> Batal
            </a>
            <button type="submit"
                    class="bg-primary-light hover:bg-primary-dark text-white px-10 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-check-lg mr-2"></i>Simpan Lembaga
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('deskripsi_editor', {
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                    { name: 'styles', items: ['Styles', 'Format'] },
                    { name: 'links', items: ['Link', 'Unlink'] },
                    { name: 'tools', items: ['Maximize'] },
                ],
                height: 300,
                removePlugins: 'elementspath',
                resize_enabled: false,
            });
        }
    });
</script>
@endsection
