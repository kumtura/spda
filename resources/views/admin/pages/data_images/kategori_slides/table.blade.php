@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showModal: false,
    isEdit: false,
    
    // Form Data
    kategoriId: '',
    kategoriName: '',
    
    openAdd() {
        this.isEdit = false;
        this.kategoriId = '';
        this.kategoriName = '';
        this.showModal = true;
    },

    openEdit(id, nama) {
        this.isEdit = true;
        this.kategoriId = id;
        this.kategoriName = nama;
        this.showModal = true;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Slide Beranda</h1>
            <p class="text-slate-500 font-semibold text-sm">Kelola pengelompokan gambar untuk slider halaman utama.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-tag-fill text-lg"></i>
            Tambah Kategori
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1">
            <table id="ikantable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($datalist as $index => $values)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-black text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $values->nama_kategori }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openEdit('{{ $values->id_kategori_slides }}', '{{ $values->nama_kategori }}')" 
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button onclick="window.dispatchDelete('{{ $values->id_kategori_slides }}')" 
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform rotate-2">
                            <i :class="isEdit ? 'bi-tag-fill' : 'bi-plus-circle-dotted'" class="bi text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest block" x-text="isEdit ? 'Update Category' : 'New Category'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                        </div>
                    </div>
                </div>

                <form id="form_kategori" onsubmit="window.saveKategori(); return false;" class="p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="t_id_banjar" x-model="kategoriId">
                    
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kategori</label>
                        <input type="text" name="t_nama_banjar" x-model="kategoriName" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/5 transition-all outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600">Batal</button>
                        <button type="submit" 
                                class="px-8 py-2.5 bg-primary-light hover:bg-primary-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
                            Simpan Data <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#ikantable').DataTable({
            "language": {
                "paginate": {
                    "previous": `<i class="bi bi-chevron-left"></i>`,
                    "next": `<i class="bi bi-chevron-right"></i>`
                }
            },
            "drawCallback": function() {
                jQuery(".dataTables_paginate").addClass("flex justify-end gap-1.5 mt-4 p-4");
                jQuery(".paginate_button").addClass("h-8 px-3 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all scale-95");
                jQuery(".paginate_button.current").addClass("!bg-primary-light !text-white !border-primary-light !scale-100 shadow-sm");
            }
        });
    });

    window.saveKategori = () => {
        $.ajax({
            type: "POST",
            url: "{{ url('administrator/post_data_banjar') }}",
            data: $("#form_kategori").serialize(),
            success: function() {
                location.reload();
            }
        });
    }

    window.dispatchDelete = (id) => {
        if(confirm('Hapus kategori ini?')) {
            $.ajax({
                type: "POST",
                url: "{{ url('administrator/hapusbanjar') }}",
                data: { id: id, _token: '{{ csrf_token() }}' },
                success: function() {
                    location.reload();
                }
            });
        }
    }
</script>

<style>
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
        @apply p-4 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest;
    }
    .dataTables_wrapper .dataTables_filter input {
        @apply ml-2 bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all;
    }
    .dataTables_wrapper .dataTables_info {
        @apply p-4 text-[10px] font-black text-slate-400 uppercase tracking-widest;
    }
</style>

@stop
