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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Keahlian</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola daftar kompetensi dan skill tenaga kerja.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-indigo-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Skill Baru
        </button>
    </div>

    <!-- Stats / Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-6 rounded-4xl flex items-center gap-4">
            <div class="h-12 w-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                <i class="bi bi-award text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Skill</p>
                <p class="text-xl font-black text-slate-800">{{ count($skill_kerja) }} Kategori</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="glass-card rounded-4xl overflow-hidden shadow-2xl border-white/60">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">No</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Kompetensi / Keahlian</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $no = 1; @endphp
                    @foreach($skill_kerja as $rows)
                    <tr class="group hover:bg-slate-50/30 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-sm font-black text-slate-400 group-hover:text-indigo-600 transition-colors">{{ str_pad($no++, 2, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-check2-circle text-lg"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-900 transition-colors">{{ $rows->nama_skill }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="openEdit('{{ $rows->id_skill_tenaga_kerja }}', '{{ $rows->nama_skill }}')"
                                        class="h-10 px-4 flex items-center gap-2 bg-white border border-slate-200 rounded-xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-indigo-600 hover:border-indigo-100 hover:shadow-md transition-all">
                                    <i class="bi bi-pencil-square text-sm"></i> Edit
                                </button>
                                <button @click="confirmDelete('{{ $rows->id_skill_tenaga_kerja }}')"
                                        class="h-10 w-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-rose-400 hover:text-rose-600 hover:border-rose-100 hover:shadow-md transition-all">
                                    <i class="bi bi-trash3 text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($skill_kerja) == 0)
        <div class="p-20 text-center space-y-4">
            <div class="h-20 w-20 bg-slate-50 rounded-4xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
                <i class="bi bi-clipboard-x text-3xl text-slate-200"></i>
            </div>
            <p class="text-slate-400 font-bold italic text-sm">Belum ada data skill yang terdaftar.</p>
        </div>
        @endif
    </div>

    <!-- Add/Edit Modal -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="glass-card w-full max-w-lg rounded-4xl overflow-hidden shadow-2xl relative border-white/20" @click.away="showModal = false">
                <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-linear-to-br from-indigo-50/50 to-white">
                    <div class="flex items-center gap-5">
                        <div class="h-16 w-16 rounded-3xl bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-100 transform -rotate-3">
                            <i class="bi bi-award text-3xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1 block" x-text="isEdit ? 'Update Entri' : 'Entri Baru'"></span>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Data Skill' : 'Tambah Skill Baru'"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="h-12 w-12 flex items-center justify-center hover:bg-slate-100 rounded-2xl transition-all duration-300 text-slate-400">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/post_data_edit_skill') }}' : '{{ url('administrator/post_data_skill') }}'" 
                      method="POST" class="p-10 bg-white/30 space-y-8">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="put">
                    </template>
                    <input type="hidden" name="edit_hidden_textfield" x-model="skillId">
                    <input type="hidden" name="t_id_menu" x-model="skillId">

                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-2">Nama Kompetensi Karyawan</label>
                        <div class="relative group">
                            <i class="bi bi-lightning-charge absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            <input type="text" :name="isEdit ? 'edit_text_title_new' : 't_nama_menu'" required 
                                   x-model="skillName"
                                   placeholder="Contoh: Welding, Accounting, Coding..." 
                                   class="w-full bg-slate-100/50 border-2 border-transparent rounded-3xl pl-14 pr-6 py-5 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-100 focus:bg-white transition-all shadow-inner">
                        </div>
                        <p class="text-[11px] font-medium text-slate-400 px-2 leading-relaxed">Masukkan nama keahlian yang spesifik agar mudah dikategorikan sistem.</p>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-100/50">
                        <button type="button" @click="showModal = false" 
                                class="px-8 py-5 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 hover:bg-slate-100 transition-all">Batal</button>
                        <button type="submit" 
                                class="px-12 py-5 bg-indigo-600 hover:bg-slate-900 text-white rounded-4xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-indigo-100 transition-all transform hover:-translate-y-1 active:scale-95">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Konfirmasi & Simpan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

@stop
