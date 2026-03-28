@extends('index')

@section('isi_menu')

<div class="space-y-8" x-data="{ 
    showModal: false,
    isEdit: false,
    skillId: '',
    skillName: '',

    openAdd() {
        this.isEdit = false;
        this.skillId = '';
        this.skillName = '';
        this.showModal = true;
    },

    openEdit(id, name) {
        this.isEdit = true;
        this.skillId = id;
        this.skillName = name;
        this.showModal = true;
    },

    confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data skill ini?')) {
            window.location = '{{ url('administrator/hapus_skill') }}/' + id;
        }
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Keahlian</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola daftar kompetensi dan skill tenaga kerja desa.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Keahlian
        </button>
    </div>

    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-award-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Kompetensi</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($skill_kerja) }} Kategori</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kompetensi / Keahlian</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $no = 1; @endphp
                    @foreach($skill_kerja as $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16">
                            #{{ str_pad($no++, 2, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center text-primary-light group-hover:scale-110 transition-transform">
                                    <i class="bi bi-patch-check"></i>
                                </div>
                                <span class="text-xs font-black text-slate-700 group-hover:text-primary-light transition-colors">{{ $rows->nama_skill }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openEdit('{{ $rows->id_skill_tenaga_kerja }}', '{{ $rows->nama_skill }}')"
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button @click="confirmDelete('{{ $rows->id_skill_tenaga_kerja }}')"
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

        @if(count($skill_kerja) == 0)
        <div class="p-16 text-center space-y-3">
            <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
                <i class="bi bi-clipboard-x text-2xl text-slate-200"></i>
            </div>
            <p class="text-slate-400 font-bold italic text-xs tracking-tight">Belum ada data keahlian yang terdaftar.</p>
        </div>
        @endif
    </div>

    <!-- Add/Edit Modal -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-12"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-award text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Update Entri' : 'Entri Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Keahlian' : 'Tambah Keahlian'"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/post_data_edit_skill') }}' : '{{ url('administrator/post_data_skill') }}'" 
                      method="POST" class="p-6 space-y-6">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="put">
                    </template>
                    <input type="hidden" name="edit_hidden_textfield" x-model="skillId">
                    <input type="hidden" name="t_id_menu" x-model="skillId">

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kompetensi</label>
                        <input type="text" :name="isEdit ? 'edit_text_title_new' : 't_nama_menu'" required 
                               x-model="skillName"
                               placeholder="Contoh: Welding, Accounting, Coding..." 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        <p class="text-[10px] font-medium text-slate-400 px-1 italic">Gunakan nama yang spesifik untuk mempermudah kategorisasi.</p>
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

@stop
