@extends($base_layout ?? 'index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="newsComponent()">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4" x-show="view === 'table'">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Konten</h1>
            <p class="text-slate-500 font-medium text-sm">Publikasikan informasi terbaru dan dikelola di ekosistem desa.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Posting Berita
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm" x-show="view === 'table'">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Berita</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penulis</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center w-24">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template x-for="(row, index) in newsList" :key="row.id_berita">
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-400" x-text="'#' + (index + 1)"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-14 rounded-lg overflow-hidden bg-slate-50 border border-slate-100 shrink-0 flex items-center justify-center">
                                        <template x-if="row.foto">
                                            <img :src="row.urlfoto" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!row.foto">
                                            <i class="bi bi-image text-slate-300 text-lg"></i>
                                        </template>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-700 leading-tight" x-text="row.judul_berita"></p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5" x-text="row.tanggal"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-500 text-[9px] font-black uppercase tracking-widest border border-slate-200" x-text="row.nama_kategori_berita"></span>
                            </td>
                            <td class="px-6 py-4">
                                <template x-if="row.kode_wartawan">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-md bg-blue-50 text-blue-500 flex items-center justify-center font-black text-[9px] uppercase border border-blue-100" x-text="row.kode_wartawan.charAt(0)"></div>
                                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest" x-text="row.kode_wartawan"></span>
                                    </div>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <template x-if="row.approved == '1'">
                                    <span class="px-2 py-1 rounded bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest border border-emerald-100">Verified</span>
                                </template>
                                <template x-if="row.approved != '1'">
                                    <span class="px-2 py-1 rounded bg-amber-50 text-amber-600 text-[9px] font-bold uppercase tracking-widest border border-amber-100">Draft</span>
                                </template>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button @click="openDetail(row.id_berita)" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm"><i class="bi bi-eye"></i></button>
                                    <button @click="openEdit(row.id_berita)" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-emerald-500 hover:border-emerald-200 transition-all shadow-sm"><i class="bi bi-pencil-square"></i></button>
                                    <button @click="confirmDelete(row.id_berita)" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm"><i class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="newsList.length === 0">
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium text-xs">Belum ada berita.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail View -->
    <div class="max-w-4xl mx-auto space-y-6" x-show="view === 'detail'" x-transition x-cloak>
        <button @click="view = 'table'" class="flex items-center gap-2 text-slate-400 hover:text-primary-light font-black text-[10px] uppercase tracking-widest transition-all">
            <i class="bi bi-arrow-left text-lg"></i> Kembali
        </button>
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="h-64 sm:h-96 w-full relative bg-slate-100 flex items-center justify-center">
                <template x-if="detail.image && detail.image.includes('storage')">
                    <img :src="detail.image" class="w-full h-full object-cover">
                </template>
                <template x-if="!detail.image || !detail.image.includes('storage')">
                    <i class="bi bi-image text-slate-300 text-6xl"></i>
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/20 to-transparent flex flex-col justify-end p-8">
                    <h2 class="text-2xl sm:text-4xl font-black text-white leading-tight tracking-tight" x-text="detail.title"></h2>
                </div>
            </div>
            <div class="p-8 prose prose-slate max-w-none prose-sm sm:prose-base leading-relaxed">
                <div x-html="detail.content"></div>
            </div>
        </div>
    </div>

    <!-- Form View -->
    <div class="max-w-4xl mx-auto space-y-6" x-show="view === 'form'" x-transition x-cloak>
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Artikel' : 'Artikel Baru'"></h2>
            <button @click="view = 'table'" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
            <form id="frm_berita" onsubmit="window.tambahdata(); return false;" class="space-y-6">
                @csrf
                <input type="hidden" name="t_idberita" x-model="newsId">
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Judul Artikel</label>
                    <input type="text" name="judul" x-model="newsTitle" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Berita</label>
                    <select name="kategori" x-model="newsCategory" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $k)<option value="{{ $k->id_kategori_berita }}">{{ $k->nama_kategori_berita }}</option>@endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Cover Image</label>
                    <input type="file" name="uploadinput" id="uploadinput" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                </div>

                <div class="space-y-1.5 pt-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Konten Artikel</label>
                    <textarea name="DSC" id="DSC" rows="12" class="w-full bg-slate-50 border-2 border-slate-200 rounded-xl px-5 py-4 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all leading-relaxed" placeholder="Tulis isi berita di sini..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                    <button type="button" @click="view = 'table'" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                    <button type="submit" class="px-8 py-2.5 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                        Simpan Artikel <i class="bi bi-check-lg ml-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('newsComponent', () => ({
            view: 'table',
            newsList: [],
            newsId: '',
            newsTitle: '',
            newsCategory: '',
            isEdit: false,
            detail: { title: '', content: '', image: '' },

            init() {
                this.fetchNews();
            },

            fetchNews() {
                $.ajax({
                    url: "{{ url('administrator/ambil_listberita_kategori') }}",
                    data: { id: '', status_update: '2' }, // Fetch all initially
                    success: (data) => {
                        this.newsList = data;
                    }
                });
            },

            openAdd() {
                this.isEdit = false;
                this.newsId = '';
                this.newsTitle = '';
                this.newsCategory = '';
                this.view = 'form';
                document.getElementById('DSC').value = '';
            },

            openEdit(id) {
                $.ajax({
                    url: '{{ url('administrator/ambil_berita') }}/' + id,
                    dataType: 'json',
                    success: (data) => {
                        this.isEdit = true;
                        this.newsId = data.id_berita;
                        this.newsTitle = data.judul_berita;
                        this.newsCategory = data.id_kategori_berita;
                        this.view = 'form';
                        setTimeout(() => {
                           document.getElementById('DSC').value = data.isi_berita;
                        }, 50);
                    }
                });
            },

            openDetail(id) {
                $.ajax({
                    url: '{{ url('administrator/ambil_berita') }}/' + id,
                    dataType: 'json',
                    success: (data) => {
                        this.detail = { 
                            title: data.judul_berita, 
                            content: data.isi_berita, 
                            image: data.foto ? '{{ url('storage/berita/foto/') }}/' + data.foto : null
                        };
                        this.view = 'detail';
                    }
                });
            },

            confirmDelete(id) {
                if(confirm('Hapus berita ini?')) {
                    $.get("{{ url('administrator/hapusberita/') }}", {id: id}, () => {
                        this.fetchNews();
                    });
                }
            }
        }));
    });

    window.tambahdata = function() {
        const formData = new FormData(document.getElementById('frm_berita'));
        const url = formData.get('t_idberita') ? "{{ url('administrator/updateberita') }}" : "{{ url('administrator/post_berita_baru') }}";
        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                location.reload();
            }
        });
    }
</script>

@stop
