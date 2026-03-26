@extends('index')

@section('isi_menu')

<div class="space-y-8" x-data="{ 
    activeTab: '{{ $datalist_kategori[0]->id_kategori_slides ?? '' }}',
    showModal: false,
    editMode: false,
    
    // Form Data
    fotoId: '',
    fotoTitle: '',
    fotoAlt: '',
    desktopPreview: '{{ url('public/default.png') }}',
    mobilePreview: '{{ url('public/default.png') }}',
    
    images: [],
    loading: false,

    async fetchImages(tabId) {
        this.activeTab = tabId;
        this.loading = true;
        try {
            const response = await fetch(`{{ url('administrator/ambil_listslides') }}/${tabId}`);
            this.images = await response.json();
        } catch (e) {
            console.error(e);
        } finally {
            this.loading = false;
        }
    },

    openAdd() {
        this.editMode = false;
        this.fotoId = '';
        this.fotoTitle = '';
        this.fotoAlt = '';
        this.desktopPreview = '{{ url('public/default.png') }}';
        this.mobilePreview = '{{ url('public/default.png') }}';
        this.showModal = true;
    },

    async openEdit(id) {
        this.editMode = true;
        this.fotoId = id;
        try {
            const response = await fetch(`{{ url('administrator/get_gambar_slide') }}?id=${id}`);
            const data = await response.json();
            this.fotoTitle = data.title;
            this.fotoAlt = data.alt;
            this.desktopPreview = `{{ url('storage/GambarSlides') }}/${data.image_name}`;
            this.mobilePreview = `{{ url('storage/GambarSlides') }}/${data.image_name_mobile}`;
            this.showModal = true;
        } catch (e) {
            console.error(e);
        }
    },

    async toggleActive(id) {
        try {
            const response = await fetch(`{{ url('administrator/post_active_slides') }}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: `id=${id}`
            });
            this.fetchImages(this.activeTab);
        } catch (e) { console.error(e); }
    }
}" x-init="fetchImages(activeTab)">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight text-center md:text-left">Manajemen Slide Beranda</h1>
            <p class="text-slate-500 font-semibold text-sm text-center md:text-left">Sesuaikan tampilan visual utama website Anda.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-2xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-image-fill text-lg"></i>
            Tambah Gambar
        </button>
    </div>

    <!-- Category Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto pb-4 no-scrollbar -mx-4 px-4 md:mx-0 md:px-0">
        @foreach($datalist_kategori as $cat)
        <button @click="fetchImages('{{ $cat->id_kategori_slides }}')"
                :class="activeTab == '{{ $cat->id_kategori_slides }}' ? 'bg-primary-light text-white shadow-lg shadow-blue-100' : 'bg-white text-slate-400 hover:text-slate-600 border border-slate-200'"
                class="whitespace-nowrap px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all">
            {{ $cat->nama_kategori }}
        </button>
        @endforeach
    </div>

    <!-- Image Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Loading State -->
        <template x-if="loading">
            <div class="col-span-full py-20 text-center">
                <div class="animate-spin h-8 w-8 border-4 border-primary-light border-t-transparent rounded-full mx-auto mb-4"></div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sinkronisasi Visual...</p>
            </div>
        </template>

        <!-- No Data State -->
        <template x-if="!loading && images.length === 0">
            <div class="col-span-full py-20 text-center bg-white border border-slate-100 rounded-3xl border-dashed">
                <i class="bi bi-images text-4xl text-slate-200 block mb-3"></i>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada gambar di kategori ini</p>
            </div>
        </template>

        <!-- Cards -->
        <template x-for="img in images" :key="img.id_gambar_home">
            <div class="group bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="aspect-video relative overflow-hidden bg-slate-100">
                    <img :src="`{{ url('storage/GambarSlides') }}/${img.image_name}`" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-linear-to-t from-slate-900/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    <!-- Badge Status -->
                    <div class="absolute top-3 left-3">
                        <span :class="img.is_slide == 1 ? 'bg-emerald-500 text-white' : 'bg-white/90 text-slate-400 backdrop-blur-sm'"
                              class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-sm"
                              x-text="img.is_slide == 1 ? 'Aktif' : 'Draft'"></span>
                    </div>

                    <!-- Quick Actions -->
                    <div class="absolute top-3 right-3 flex gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="openEdit(img.id_gambar_home)" 
                                class="h-8 w-8 bg-white rounded-lg text-slate-600 flex items-center justify-center shadow-lg hover:text-primary-light transition-colors">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 space-y-4">
                    <h3 class="text-sm font-black text-slate-800 tracking-tight line-clamp-1" x-text="img.title"></h3>
                    
                    <div class="flex items-center justify-between gap-3">
                        <button @click="toggleActive(img.id_gambar_home)"
                                :class="img.is_slide == 1 ? 'bg-rose-50 text-rose-500' : 'bg-primary-light text-white shadow-blue-100 shadow-lg'"
                                class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                            <span x-text="img.is_slide == 1 ? 'Nonaktifkan' : 'Aktifkan Slide'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Modal Add/Edit -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-cloak>
            
            <div class="bg-white w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <div class="flex items-center gap-5">
                        <div class="h-14 w-14 rounded-2xl bg-primary-light text-white flex items-center justify-center shadow-lg transform rotate-3">
                            <i class="bi bi-cloud-arrow-up-fill text-3xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-primary-light uppercase tracking-widest block" x-text="editMode ? 'Modifikasi Slide' : 'Asset Visual Baru'"></span>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Gambar Slide' : 'Tambah Gambar Slide'"></h3>
                        </div>
                    </div>
                </div>

                <form id="form_gambar" onsubmit="window.saveGambar(); return false;" class="p-8 space-y-8">
                    @csrf
                    <input type="hidden" name="edit_hidden_textfield" x-model="fotoId">
                    <input type="hidden" name="urutan_id" x-model="activeTab">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Side: Basic Info -->
                        <div class="space-y-6">
                            <div class="space-y-2 text-center md:text-left">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Judul Gambar</label>
                                <input type="text" name="text_title_new" x-model="fotoTitle" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>

                            <div class="space-y-2 text-center md:text-left">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alt Description</label>
                                <input type="text" name="text_desc_new" x-model="fotoAlt" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                        </div>

                        <!-- Right Side: File Uploads -->
                        <div class="space-y-6">
                            <!-- Desktop Upload -->
                            <div class="p-4 bg-slate-50 rounded-3xl border border-slate-200">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Desktop (Landscape)</span>
                                </div>
                                <div class="relative group aspect-video rounded-xl overflow-hidden bg-white border-2 border-dashed border-slate-200">
                                    <img :src="desktopPreview" class="w-full h-full object-cover" />
                                    <label class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <span class="text-white text-[9px] font-black uppercase tracking-widest bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/30">Ganti Foto</span>
                                        <input type="file" name="edit_f_upload_gambar" class="hidden" @change="desktopPreview = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                </div>
                            </div>

                            <!-- Mobile Upload -->
                            <div class="p-4 bg-slate-50 rounded-3xl border border-slate-200">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Mobile (Potrait)</span>
                                </div>
                                <div class="relative group aspect-9/16 h-32 mx-auto rounded-xl overflow-hidden bg-white border-2 border-dashed border-slate-200">
                                    <img :src="mobilePreview" class="w-full h-full object-cover" />
                                    <label class="absolute inset-0 bg-slate-900/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                        <i class="bi bi-camera text-white"></i>
                                        <input type="file" name="edit_f_upload_gambar_mobile" class="hidden" @change="mobilePreview = URL.createObjectURL($event.target.files[0])">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 mt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-8 py-3 text-[11px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Batalkan</button>
                        <button type="submit" 
                                class="px-10 py-3 bg-slate-900 hover:bg-primary-light text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl transition-all transform hover:-translate-y-1">
                            Konfirmasi Data <i class="bi bi-arrow-right ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script type="text/javascript">
    window.saveGambar = () => {
        const form = document.getElementById('form_gambar');
        const formData = new FormData(form);
        const isEdit = formData.get('edit_hidden_textfield') !== '';
        const url = isEdit ? "{{ url('administrator/post_gambar_baru_edit') }}" : "{{ url('administrator/post_gambar_baru') }}";

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                location.reload();
            }
        });
    }
</script>

@stop
