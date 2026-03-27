@extends($base_layout ?? 'index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="{ 
    showModal: false,
    isEdit: false,
    categoryId: '',
    categoryName: '',

    openAdd() { 
        this.isEdit = false; 
        this.categoryId = ''; 
        this.categoryName = ''; 
        this.showModal = true; 
    },
    openEdit(id, name) {
        this.isEdit = true;
        this.categoryId = id;
        this.categoryName = name;
        this.showModal = true;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Berita</h1>
            <p class="text-slate-500 font-medium text-sm">Manajemen pengelompokan konten berita dan publikasi desa.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Kategori
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategori as $index => $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16 text-center">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors">{{ $rows->nama_kategori_berita }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openEdit('{{ $rows->id_kategori_berita }}', '{{ $rows->nama_kategori_berita }}')"
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button onclick="deletedata('{{ $rows->id_kategori_berita }}')"
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium text-xs tracking-wide uppercase">Belum ada kategori terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-tags text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Data' : 'Kategori Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :id="isEdit ? 'form_multi_edit' : 'form_multi'" @submit.prevent="isEdit ? submit_edit_form() : submit_form()" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="iduserinput_edit" x-model="categoryId">
                    
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kategori</label>
                            <input type="text" :name="isEdit ? 'emailinput_edit' : 'emailinput'" required x-model="categoryName"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                            Simpan Data <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script type="text/javascript">
    function deletedata(value){
        if(confirm("Hapus kategori ini? Berita yang terkait mungkin akan kehilangan kategorinya.")){
            jQuery.ajax({
                type:"GET",
                url: "{{ url('administrator/hapus_kategori_berita') }}",
                data:"id="+value,
                success:function(data){
                    location.reload();
                }
            })
        }
    }

    function submit_edit_form(){
        var serial = jQuery("#form_multi_edit").serialize();
        jQuery.ajax({
            type:"POST",
            url: "{{ url('administrator/post_user_kategori_berita') }}",
            data:serial,
            success:function(data){
                location.reload();
            }
        });
    }

    function submit_form(){
        var serial = jQuery("#form_multi").serialize();
        jQuery.ajax({
            type:"POST",
            url: "{{ url('administrator/post_kategori_berita') }}",
            data:serial,
            success:function(data){
                location.reload();
            }
        });
    }
</script>

@stop
