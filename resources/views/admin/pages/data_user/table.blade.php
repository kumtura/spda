@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showModal: false,
    isEdit: false,
    
    // Form Data
    userId: '',
    userName: '',
    userEmail: '',
    userPassword: '',
    userPhone: '',
    userLevel: '',
    userBanjar: '',
    
    openAdd() {
        this.isEdit = false;
        this.userId = '';
        this.userName = '';
        this.userEmail = '';
        this.userPassword = '';
        this.userPhone = '';
        this.userLevel = '';
        this.userBanjar = '';
        this.showModal = true;
    },

    openEdit(id) {
        $.ajax({
            type: 'GET',
            url: '{{ url('administrator/ambil_user') }}/' + id,
            dataType: 'json',
            success: (data) => {
                this.isEdit = true;
                this.userId = data.id;
                this.userName = data.name;
                this.userEmail = data.email;
                this.userPassword = ''; 
                this.userLevel = data.id_level;
                this.userPhone = data.no_wa;
                this.userBanjar = data.id_banjar || '';
                this.showModal = true;
            }
        });
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Manajemen Akses Pengguna</h1>
            <p class="text-slate-500 font-semibold text-sm">Kelola akun pimpinan adat dan operator unit usaha desa.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-person-plus-fill text-lg"></i>
            Tambah User Baru
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-shield-lock text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Admin</p>
                <p class="text-lg font-black text-slate-800" id="stat_admin">--</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table id="ikantable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">ID/Kode</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama / Email</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akses Level</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Wilayah/Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <!-- DataTables will populate this -->
                </tbody>
            </table>
        </div>
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
            
            <div class="bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg shadow-blue-100">
                            <i :class="isEdit ? 'bi-person-gear' : 'bi-person-plus'" class="bi text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Update Akun' : 'Create Account'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Data Pengguna' : 'Tambah Pengguna Baru'"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="h-10 w-10 flex items-center justify-center hover:bg-slate-200 rounded-xl transition-all text-slate-400">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form id="form_multi_user" onsubmit="window.saveUser(); return false;" class="p-8 space-y-6">
                    @csrf
                    <input type="hidden" name="iduserinput_edit" x-model="userId">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black text-primary-light uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Akun</h4>
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email</label>
                                    <input type="email" name="emailinput_edit" x-model="userEmail" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1" x-text="isEdit ? 'Ganti Password (Opsional)' : 'Password'"></label>
                                    <input type="password" name="passwordinput_edit" placeholder="••••••••" :required="!isEdit"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Akses Level</label>
                                    <select name="levelinput_edit" x-model="userLevel" required
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                        <option value="">Pilih Level</option>
                                        <option value="4">Admin Sistem</option>
                                        <option value="1">Bendesa Adat</option>
                                        <option value="2">Kelian Adat</option>
                                        <option value="3">Unit Usaha</option>
                                    </select>
                                </div>
                                <div class="space-y-1.5" x-show="userLevel == 2 || userLevel == 3" x-transition>
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Wilayah / Banjar</label>
                                    <select name="banjarinput_edit" x-model="userBanjar" :required="userLevel == 2 || userLevel == 3"
                                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                        <option value="">Pilih Banjar</option>
                                        @foreach($banjar as $b)
                                            <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-[10px] font-black text-primary-light uppercase tracking-widest border-b border-slate-100 pb-2">Detail Profil</h4>
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                                    <input type="text" name="textinput_edit" x-model="userName" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. WhatsApp</label>
                                    <input type="number" name="nowainput_edit" x-model="userPhone" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto Profil</label>
                                    <input type="file" name="uploadinput_edit" 
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500 transition-all outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600">Batal</button>
                        <button type="submit" 
                                class="px-8 py-2.5 bg-primary-light hover:bg-primary-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
                            Simpan Perubahan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script type="text/javascript">
    var table = "";

    jQuery(document).ready(function() {
        table = jQuery('#ikantable').DataTable({
            "ajax": {
                "url": "{{ url('administrator/ambil_listuser') }}",
                "dataSrc": (json) => {
                    const admins = json.filter(u => u.id_level == 4 || u.id_level == 1).length;
                    $("#stat_admin").text(admins + " Users");
                    return json;
                }
            },
            "columns": [
                { "data": 'no', "className": "px-6 py-4 text-xs font-black text-slate-400" },
                { "data": 'kode', "className": "px-6 py-4 text-xs font-bold text-slate-500 italic" },
                { 
                    "data": 'name', 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        return `
                        <div class="flex items-center gap-3">
                            <div class="h-9 w-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black uppercase text-xs">
                                ${data.charAt(0)}
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-800 tracking-tight">${data}</p>
                                <p class="text-[10px] font-bold text-slate-400">${row.email}</p>
                            </div>
                        </div>`;
                    }
                },
                { 
                    "data": 'level', 
                    "className": "px-6 py-4",
                    "render": function(data, type, row) {
                        let colorClass = 'bg-slate-100 text-slate-500 border-slate-200';
                        const lowerData = data.toLowerCase();
                        if(lowerData.includes('admin')) colorClass = 'bg-blue-50 text-primary-light border-blue-100';
                        if(lowerData.includes('bendesa')) colorClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        if(lowerData.includes('kelian')) colorClass = 'bg-amber-50 text-amber-600 border-amber-100';
                        if(lowerData.includes('usaha')) colorClass = 'bg-rose-50 text-rose-600 border-rose-100';
                        
                        return `<span class="inline-flex items-center px-3 py-1 rounded-md text-[9px] font-bold uppercase tracking-widest border ${colorClass}">${data}</span>`;
                    }
                },
                { 
                    "data": 'nama_banjar', 
                    "className": "px-6 py-4",
                    "render": (data) => `<span class="flex items-center gap-1.5 text-xs font-bold text-slate-600"><i class="bi bi-geo-alt text-slate-400"></i> ${data}</span>`
                },
                { 
                    "data": 'aksi', 
                    "className": "px-6 py-4 text-right",
                    "render": function(data, type, row) {
                        return `
                        <div class="flex items-center justify-end gap-1.5">
                            <button onclick="window.dispatchEdit(${row.id})" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button onclick="window.dispatchDelete(${row.id})" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>`;
                    }
                }
            ],
            "language": {
                "paginate": {
                    "previous": `<i class="bi bi-chevron-left"></i>`,
                    "next": `<i class="bi bi-chevron-right"></i>`
                }
            },
            "drawCallback": function() {
                $(".dataTables_paginate").addClass("flex justify-end gap-1.5 mt-4 p-4");
                $(".paginate_button").addClass("h-8 px-3 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all scale-95");
                $(".paginate_button.current").addClass("!bg-primary-light !text-white !border-primary-light !scale-100 shadow-sm");
            }
        });
    });

    window.dispatchEdit = (id) => {
        document.querySelector('[x-data]').__x.$data.openEdit(id);
    }

    window.dispatchDelete = (id) => {
        if(confirm('Hapus pengguna ini permanen? Akun tidak akan bisa login kembali.')) {
            $.ajax({
                type: "GET",
                url: "{{ url('administrator/hapususer/') }}",
                data: "id=" + id,
                success: function() { table.ajax.reload(); }
            });
        }
    }

    window.saveUser = () => {
        const alpineStore = document.querySelector('[x-data]').__x.$data;
        const url = alpineStore.isEdit ? "{{ url('administrator/updateuser') }}" : "{{ url('administrator/post_user') }}";
        $.ajax({
            type: "POST",
            url: url,
            data: $("#form_multi_user").serialize(),
            success: function() {
                alpineStore.showModal = false;
                table.ajax.reload();
            }
        });
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
