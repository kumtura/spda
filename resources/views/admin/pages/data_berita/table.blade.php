@extends('index')

@section('isi_menu')

<div class="space-y-8" x-data="{ 
    view: 'table', // table, form, detail
    activeCategory: '',
    searchQuery: '',
    
    // Form Data
    newsId: '',
    newsTitle: '',
    newsDay: 'senin',
    newsDate: '{{ date('Y-m-d') }}',
    newsTime: '{{ date('H:i') }}',
    newsCategory: '',
    newsContent: '',
    newsImagePreview: null,
    isEdit: false,

    // Detail Data
    detail: {
        title: '',
        content: '',
        image: ''
    },

    init() {
        // Load initial categories
        this.loadCategories();
    },

    loadCategories() {
        $.ajax({
            url: '{{ url('ambil_listkategori_awal') }}',
            dataType: 'json',
            success: (data) => {
                if (data.length > 0) {
                    this.activeCategory = data[0].id_kategori_berita;
                    this.fetchNews();
                }
            }
        });
    },

    fetchNews() {
        // This would call the existing AJAX but we'll adapt it for Alpine if needed
        // For now let's keep the existing jQuery logic for data fetching to ensure business logic parity
        // but render with a modern UI.
        window.ambil_berita(this.activeCategory);
    },

    openAdd() {
        this.isEdit = false;
        this.newsId = '';
        this.newsTitle = '';
        this.newsContent = '';
        this.view = 'form';
        if (CKEDITOR.instances['DSC']) CKEDITOR.instances['DSC'].setData('');
    },

    openEdit(id) {
        $.ajax({
            url: '{{ url('ambil_berita') }}/' + id,
            dataType: 'json',
            success: (data) => {
                this.isEdit = true;
                this.newsId = data.id_berita;
                this.newsTitle = data.judul_berita;
                this.newsDay = data.hari;
                this.newsDate = data.tanggal_berita.split(' ')[0];
                this.newsTime = data.tanggal_berita.split(' ')[1];
                this.newsCategory = data.id_kategori_berita;
                this.newsImagePreview = '{{ url('storage/berita/foto/') }}/' + data.foto;
                this.view = 'form';
                if (CKEDITOR.instances['DSC']) CKEDITOR.instances['DSC'].setData(data.isi_berita);
            }
        });
    },

    openDetail(id) {
        $.ajax({
            url: '{{ url('ambil_berita') }}/' + id,
            dataType: 'json',
            success: (data) => {
                this.detail = {
                    title: data.judul_berita,
                    content: data.isi_berita,
                    image: '{{ url('storage/berita/foto/') }}/' + data.foto
                };
                this.view = 'detail';
            }
        });
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6" x-show="view === 'table'">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Berita & Konten</h1>
            <p class="text-slate-500 font-medium text-sm">Publikasikan informasi terbaru untuk masyarakat dan investor.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative w-64">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" x-model="searchQuery" @keyup.enter="window.ambil_beritaparam(activeCategory, searchQuery, 2)"
                       placeholder="Cari judul berita..." 
                       class="w-full bg-white border border-slate-200 rounded-2xl pl-10 pr-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 transition-all">
            </div>
            <button @click="openAdd()" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-1">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Berita
            </button>
        </div>
    </div>

    <!-- Categories Tab -->
    <div class="flex flex-wrap gap-2 p-1.5 bg-white/50 backdrop-blur-md rounded-4xl border border-white/60 shadow-xl overflow-x-auto no-scrollbar" x-show="view === 'table'">
        @foreach($kategori as $k)
        <button @click="activeCategory = '{{ $k->id_kategori_berita }}'; window.ambil_berita(activeCategory)"
                :class="activeCategory == '{{ $k->id_kategori_berita }}' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-white'"
                class="px-6 py-3 rounded-3xl font-black text-[10px] uppercase tracking-widest transition-all duration-300 shrink-0">
            {{ $k->name }}
        </button>
        @endforeach
    </div>

    <!-- News Grid Container -->
    <div id="div_container_isi" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="view === 'table'">
        <!-- Rendered via jQuery/AJAX for now to keep parity, but target these styles in the JS -->
    </div>

    <!-- Empty State for JS to target -->
    <div id="ditemukan_berita" class="text-center py-12 hidden" x-show="view === 'table'">
        <!-- JS will populate message -->
    </div>

    <!-- Detail View -->
    <div class="max-w-4xl mx-auto space-y-8" x-show="view === 'detail'" x-transition>
        <button @click="view = 'table'" class="flex items-center gap-2 text-slate-400 hover:text-indigo-600 font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="bi bi-arrow-left text-lg"></i> Kembali ke List
        </button>
        
        <div class="glass-card rounded-3xl overflow-hidden border-white/60 shadow-2xl bg-white">
            <div class="h-[400px] w-full overflow-hidden relative">
                <img :src="detail.image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-linear-to-t from-slate-900/60 to-transparent flex flex-col justify-end p-12">
                    <h2 class="text-4xl font-black text-white leading-tight tracking-tight" x-text="detail.title"></h2>
                </div>
            </div>
            <div class="p-12 prose prose-indigo max-w-none prose-slate prose-lg">
                <div x-html="detail.content"></div>
            </div>
        </div>
    </div>

    <!-- Form View -->
    <div class="max-w-4xl mx-auto space-y-8" x-show="view === 'form'" x-transition>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Update Berita Existing' : 'Publikasi Berita Baru'"></h2>
            <button @click="view = 'table'" class="h-12 w-12 flex items-center justify-center bg-white rounded-2xl shadow-sm border border-slate-100 text-slate-400 hover:text-rose-500 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="glass-card rounded-3xl p-10 border-white/60 shadow-2xl bg-white">
            <form id="frm_berita" onsubmit="window.tambahdata(); return false;" class="space-y-8">
                @csrf
                <input type="hidden" name="t_idberita" x-model="newsId">
                <input type="hidden" name="t_aksi_pencarian" :value="isEdit ? 'edit' : ''">

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Judul Artikel</label>
                    <input type="text" name="textinputan" x-model="newsTitle" required
                           class="w-full bg-slate-50 border-none rounded-2xl px-6 py-5 text-lg font-black text-slate-800 focus:ring-4 focus:ring-indigo-500/10 placeholder:text-slate-300">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Kategori</label>
                        <select name="kategoriinputan" x-model="newsCategory" required
                                class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id_kategori_berita }}">{{ $k->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Tanggal</label>
                        <input type="date" name="tanggalinput" x-model="newsDate" required
                               class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Waktu</label>
                        <input type="time" name="waktuinput" x-model="newsTime" required
                               class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Konten Berita</label>
                    <div class="rounded-3xl border border-slate-100 overflow-hidden shadow-sm">
                        <textarea name="DSC" class="materialize-textarea"></textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Cover Image / Foto</label>
                    <div class="flex items-center gap-6">
                        <div class="h-32 w-48 bg-slate-100 rounded-3xl overflow-hidden border-2 border-white shadow-lg shrink-0">
                            <template x-if="newsImagePreview">
                                <img :src="newsImagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!newsImagePreview">
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                    <i class="bi bi-image text-3xl"></i>
                                    <span class="text-[9px] font-black uppercase mt-1">Preview</span>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1 relative">
                            <input type="file" name="uploadinput" id="uploadinput" 
                                   class="absolute inset-0 opacity-0 cursor-pointer"
                                   @change="newsImagePreview = URL.createObjectURL($event.target.files[0])">
                            <div class="w-full py-10 bg-indigo-50 border-2 border-dashed border-indigo-200 rounded-3xl flex flex-col items-center justify-center text-indigo-400 group-hover:bg-indigo-100 transition-all">
                                <i class="bi bi-cloud-arrow-up text-3xl mb-2"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest">Click to Upload Image</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-8">
                    <button type="button" @click="view = 'table'" 
                            class="px-8 py-4 font-black text-[10px] uppercase tracking-widest text-slate-400 hover:text-slate-600">Cancel</button>
                    <button type="submit" 
                            class="px-12 py-4 bg-slate-900 hover:bg-indigo-600 text-white rounded-3xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl transition-all transform hover:-translate-y-1">
                        Post Article <i class="bi bi-send ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Bridge jQuery logic to modern UI
    window.ambil_berita = function(id) {
        $("#div_container_isi").html(`
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-slate-300">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-indigo-500 border-t-transparent mb-4"></div>
                <span class="text-[10px] font-black uppercase tracking-widest">Synchronizing Archive...</span>
            </div>
        `);
        
        $.ajax({
            type: "get",
            url: "{{ url('ambil_listberita_kategori') }}",
            data: "id=" + id,
            dataType: "json",
            success: function(data) {
                render_news_grid(data);
            }
        });
    }

    window.ambil_beritaparam = function(id, cari, is_update) {
        $("#div_container_isi").html("");
        $.ajax({
            type: "get",
            url: "{{ url('ambil_listberita_kategori') }}",
            data: "id=" + id + "&cari=" + cari + "&status_update=" + is_update,
            dataType: "json",
            success: function(data) {
                render_news_grid(data);
            }
        });
    }

    function render_news_grid(data) {
        const container = $("#div_container_isi");
        container.html("");
        
        if (data.length === 0) {
            container.html(`
                <div class="col-span-full py-20 text-center">
                    <div class="h-20 w-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-300 text-3xl mb-4">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-400 tracking-tight">Belum Ada Artikel</h3>
                </div>
            `);
            return;
        }

        $.each(data, function(index, element) {
            let statusBadge = '';
            if (element.approved == "1") {
                statusBadge = `<span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest border border-emerald-100">Verified</span>`;
            } else if (element.sudah_update == "1") {
                statusBadge = `<span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[8px] font-black uppercase tracking-widest border border-amber-100">Draft</span>`;
            }

            const card = `
                <div class="glass-card flex flex-col rounded-3xl overflow-hidden border-white/60 bg-white shadow-xl hover:shadow-2xl transition-all duration-500 group">
                    <div class="h-56 relative overflow-hidden shrink-0">
                        <img src="${element.urlfoto}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-6">
                            <div class="flex items-center gap-2">
                                <button onclick="window.dispatchEvent(new CustomEvent('news-detail', { detail: ${element.id_berita} }))" class="h-10 w-10 flex items-center justify-center bg-white text-indigo-600 rounded-xl shadow-lg hover:scale-110 transition-transform">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button onclick="window.dispatchEvent(new CustomEvent('news-edit', { detail: ${element.id_berita} }))" class="h-10 w-10 flex items-center justify-center bg-white text-emerald-600 rounded-xl shadow-lg hover:scale-110 transition-transform">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <span>${element.tanggal}</span>
                            ${statusBadge}
                        </div>
                        <h4 class="text-lg font-black text-slate-800 tracking-tight leading-tight group-hover:text-indigo-600 transition-colors mb-3">${element.judul_berita}</h4>
                        <div class="text-xs text-slate-400 leading-relaxed font-medium line-clamp-3 mb-6">
                            ${element.isi_berita.replace(/<[^>]*>?/gm, '')}
                        </div>
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-50">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 text-xs font-black uppercase">
                                    ${element.kode_wartawan.charAt(0)}
                                </div>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">${element.kode_wartawan}</span>
                            </div>
                            <button onclick="window.dispatchEvent(new CustomEvent('news-del', { detail: ${element.id_berita} }))" class="text-rose-400 hover:text-rose-600 transition-colors">
                                <i class="bi bi-trash3 text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }

    // Custom Event Handlers for UI communication
    window.addEventListener('news-detail', (e) => {
        const alpineStore = document.querySelector('[x-data]').__x.$data;
        alpineStore.openDetail(e.detail);
    });
    window.addEventListener('news-edit', (e) => {
        const alpineStore = document.querySelector('[x-data]').__x.$data;
        alpineStore.openEdit(e.detail);
    });
    window.addEventListener('news-del', (e) => {
        if(confirm('Hapus berita ini permanen?')) {
            $.ajax({
                type: "GET",
                url: "{{ url('hapusberita/') }}",
                data: "id=" + e.detail,
                success: function(data) {
                    window.ambil_berita(document.querySelector('[x-data]').__x.$data.activeCategory);
                }
            });
        }
    });

    window.tambahdata = function() {
        const form = document.getElementById('frm_berita');
        const formData = new FormData(form);
        formData.append('DSC', CKEDITOR.instances['DSC'].getData());

        $.ajax({
            url: "{{ url('administrator/post_berita_baru') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data == "success") {
                    const alpineStore = document.querySelector('[x-data]').__x.$data;
                    alpineStore.view = 'table';
                    window.ambil_berita(alpineStore.activeCategory);
                }
            }
        });
    }

    jQuery(document).ready(function() {
        jQuery('textarea[name="DSC"]').ckeditor();
    });
</script>

@stop
