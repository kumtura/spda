@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{
    pengurusList: @json(array_values($lembaga['pengurus'] ?? [])),
    addPengurus() {
        this.pengurusList.push({ id: '', nama: '', keterangan: '', no_telp: '', foto: null, fotoPreview: null, foto_existing: null });
    },
    removePengurus(index) {
        this.pengurusList.splice(index, 1);
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
    },
    removeGallery(index) {
        this.$refs['gallery_keep_' + index].disabled = true;
        this.$el.querySelector('[data-gallery-item=\'' + index + '\']').remove();
    }
}">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1">
                <i class="bi bi-arrow-left mr-1"></i>
                <a href="{{ url('administrator/tentang-desa/lembaga') }}">Lembaga Desa Adat</a>
            </p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Lembaga</h1>
            <p class="text-slate-500 font-medium text-sm">Perbarui informasi lembaga desa adat.</p>
        </div>
    </div>

    @if($errors->any())
    <div class="p-4 text-sm text-rose-800 rounded-2xl bg-rose-50 border border-rose-200">
        <p class="font-bold mb-1">Terdapat kesalahan:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ url('administrator/tentang-desa/lembaga/' . $lembaga['id'] . '/update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ── INFORMASI DASAR ── --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
            <h3 class="text-sm font-black text-slate-700 flex items-center gap-2 pb-3 border-b border-slate-100">
                <i class="bi bi-info-circle text-primary-light"></i> Informasi Dasar
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lembaga <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $lembaga['nama_lembaga']) }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Ketua</label>
                    <input type="text" name="ketua" value="{{ old('ketua', $lembaga['ketua'] ?? '') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Logo Lembaga</label>
                    @if(!empty($lembaga['logo']))
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ asset('storage/tentang_desa/lembaga/' . $lembaga['logo']) }}" class="h-12 w-12 rounded-xl object-cover border border-slate-200" alt="Logo">
                        <span class="text-[10px] text-slate-400">Logo saat ini. Upload baru untuk mengganti.</span>
                    </div>
                    @endif
                    <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg"
                        class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi Lembaga</label>
                <textarea id="deskripsi_editor" name="deskripsi" rows="8"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-y">{{ old('deskripsi', $lembaga['deskripsi'] ?? '') }}</textarea>
            </div>
        </div>

        {{-- ── PENGURUS ── --}}
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
                        <button type="button" @click="removePengurus(index)"
                                class="absolute top-3 right-3 h-7 w-7 flex items-center justify-center bg-white border border-rose-200 text-rose-400 rounded-lg hover:bg-rose-50 transition-colors text-xs">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        {{-- hidden id --}}
                        <input type="hidden" :name="'pengurus[' + index + '][id]'" :value="p.id">
                        <input type="hidden" :name="'pengurus[' + index + '][foto_existing]'" :value="p.foto">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex flex-col items-center gap-3">
                                <div class="h-24 w-24 rounded-2xl bg-white border-2 border-dashed border-slate-300 overflow-hidden flex items-center justify-center">
                                    <template x-if="p.fotoPreview">
                                        <img :src="p.fotoPreview" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!p.fotoPreview && p.foto">
                                        <img :src="'/storage/tentang_desa/lembaga/' + p.foto" class="h-full w-full object-cover">
                                    </template>
                                    <template x-if="!p.fotoPreview && !p.foto">
                                        <i class="bi bi-person-fill text-3xl text-slate-300"></i>
                                    </template>
                                </div>
                                <label class="cursor-pointer text-[10px] font-bold text-primary-light hover:underline">
                                    <span>Ganti Foto</span>
                                    <input type="file" :name="'pengurus[' + index + '][foto]'" accept="image/png,image/jpeg,image/jpg"
                                           @change="previewFoto($event, index)" class="hidden">
                                </label>
                            </div>
                            <div class="md:col-span-2 space-y-3">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nama <span class="text-rose-500">*</span></label>
                                    <input type="text" :name="'pengurus[' + index + '][nama]'" x-model="p.nama" required
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Keterangan / Jabatan</label>
                                    <input type="text" :name="'pengurus[' + index + '][keterangan]'" x-model="p.keterangan"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">No. Telepon (Opsional)</label>
                                    <input type="text" :name="'pengurus[' + index + '][no_telp]'" x-model="p.no_telp"
                                        class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
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

            {{-- Existing gallery --}}
            @if(!empty($lembaga['gallery']) && count($lembaga['gallery']) > 0)
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Foto Saat Ini — Hapus centang untuk menghapus</p>
                <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                    @foreach($lembaga['gallery'] as $gi => $gfoto)
                    <div data-gallery-item="{{ $gi }}" class="relative group">
                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                            <img src="{{ asset('storage/tentang_desa/lembaga/' . $gfoto) }}" class="h-full w-full object-cover" alt="Gallery">
                        </div>
                        <label class="absolute top-1 right-1 cursor-pointer">
                            <input type="checkbox" name="gallery_keep[]" value="{{ $gfoto }}" checked
                                   x-ref="gallery_keep_{{ $gi }}"
                                   class="w-4 h-4 rounded border-slate-300 text-primary-light focus:ring-primary-light">
                        </label>
                        <p class="text-[9px] text-slate-400 text-center mt-1 truncate">{{ $gfoto }}</p>
                    </div>
                    @endforeach
                </div>
                <p class="text-[10px] text-slate-400 mt-2">Hilangkan centang pada foto yang ingin dihapus.</p>
            </div>
            @endif

            {{-- Upload new --}}
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tambah Foto Baru (Opsional)</label>
                <input type="file" name="gallery_new[]" multiple accept="image/png,image/jpeg,image/jpg"
                    @change="previewGallery($event)"
                    class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white hover:file:bg-primary-dark">
                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG. Maks 3MB per foto.</p>
            </div>

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
                <i class="bi bi-check-lg mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('deskripsi_editor', {
            toolbar: [
                { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','-','RemoveFormat'] },
                { name: 'paragraph',   items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','JustifyLeft','JustifyCenter','JustifyRight'] },
                { name: 'styles',      items: ['Styles','Format','FontSize'] },
                { name: 'colors',      items: ['TextColor','BGColor'] },
                { name: 'links',       items: ['Link','Unlink'] },
                { name: 'insert',      items: ['Image','Table','HorizontalRule'] },
                { name: 'tools',       items: ['Maximize'] },
            ],
            height: 300,
            removePlugins: 'elementspath',
            resize_enabled: false,
        });
    }
});
</script>
@endsection
