@extends($base_layout ?? 'index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="{ 
    showModal: false,
    isEdit: false,
    userId: '', userName: '', userEmail: '', userPassword: '', userPhone: '', userLevel: '', userBanjar: '',
    openAdd() { this.isEdit = false; this.userId = ''; this.userName = ''; this.userEmail = ''; this.userPassword = ''; this.userPhone = ''; this.userLevel = ''; this.userBanjar = ''; this.showModal = true; },
    openEdit(id) {
        fetch('{{ url('administrator/ambil_user') }}/' + id).then(r => r.json()).then(data => {
            this.isEdit = true; this.userId = data.id; this.userName = data.name; this.userEmail = data.email;
            this.userPassword = ''; this.userLevel = data.id_level; this.userPhone = data.no_wa; this.userBanjar = data.id_banjar || '';
            this.showModal = true;
        });
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Pengguna</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola akses administrator dan kewenangan sistem.</p>
        </div>
        <button @click="openAdd()" class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-person-plus-fill text-lg"></i>
            Tambah Pengguna
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table id="ikantable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama / Email</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Level</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8" x-transition x-cloak>
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center">
                            <i :class="isEdit ? 'bi-person-gear' : 'bi-person-plus'" class="bi text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Data' : 'Pengguna Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Pengguna' : 'Tambah Pengguna'"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form id="form_multi_user" method="POST" onsubmit="window.saveUser(); return false;" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="iduserinput_edit" x-model="userId">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email</label>
                        <input type="email" name="emailinput_edit" x-model="userEmail" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1" x-text="isEdit ? 'Password Baru (Opsional)' : 'Password'"></label>
                        <input type="password" name="passwordinput_edit" placeholder="••••••••" :required="!isEdit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                        <input type="text" name="textinput_edit" x-model="userName" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. WhatsApp</label>
                        <input type="number" name="nowainput_edit" x-model="userPhone" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Level</label>
                            <select name="levelinput_edit" x-model="userLevel" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                <option value="">Pilih</option>
                                @if(Session::get('level') == '1' || Session::get('level') == '4')
                                <option value="4">Admin Sistem</option><option value="1">Bendesa Adat</option>
                                @endif
                                <option value="2">Kelian Adat</option><option value="3">Unit Usaha</option>
                            </select>
                        </div>
                        <div class="space-y-1.5" x-show="userLevel == 2 || userLevel == 3" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar</label>
                            <select name="banjarinput_edit" x-model="userBanjar" :required="userLevel == 2 || userLevel == 3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                <option value="">Pilih</option>
                                @foreach($banjar as $b)<option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light hover:bg-primary-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all transform hover:-translate-y-0.5">
                            Simpan Data <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var table = jQuery('#ikantable').DataTable({
            "bFilter": false,
            "bLengthChange": false,
            "bInfo": false,
            "bSort": false,
            "pageLength": 10,
            "ajax": { "url": "{{ url('administrator/ambil_listuser') }}", "dataSrc": "" },
            "columns": [
                { "data": 'no', "className": "px-6 py-4 text-xs font-bold text-slate-400" },
                { "data": 'name', "className": "px-6 py-4", "render": function(data,t,row) { 
                    return '<div class="flex items-center gap-4"><div class="h-10 w-10 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-sm uppercase">' + data.charAt(0) + '</div><div><p class="text-xs font-black text-slate-800">' + data + '</p><p class="text-[10px] font-bold text-slate-400">' + row.email + '</p></div></div>'; 
                } },
                { "data": 'level', "className": "px-6 py-4 text-center", "render": function(data) { 
                    var c = 'bg-slate-50 text-slate-500 border border-slate-200'; 
                    if(data.toLowerCase().includes('admin')) c='bg-blue-50 text-blue-600 border border-blue-100'; 
                    if(data.toLowerCase().includes('bendesa')) c='bg-emerald-50 text-emerald-600 border border-emerald-100'; 
                    if(data.toLowerCase().includes('kelian')) c='bg-amber-50 text-amber-600 border border-amber-100'; 
                    if(data.toLowerCase().includes('usaha')) c='bg-rose-50 text-rose-600 border border-rose-100'; 
                    return '<span class="text-[9px] font-bold px-2.5 py-1 rounded bg-slate-50 border uppercase tracking-widest ' + c + '">' + data + '</span>'; 
                } },
                { "data": 'nama_banjar', "className": "px-6 py-4", "render": function(data) { 
                    return '<span class="text-xs font-black text-slate-600">' + data + '</span>'; 
                } },
                { "data": 'aksi', "className": "px-6 py-4 text-right", "render": function(d,t,row) { 
                    return '<div class="flex items-center justify-end gap-1.5"><button onclick="window.dispatchEdit(' + row.id + ')" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm"><i class="bi bi-pencil-square"></i></button><button onclick="window.dispatchDelete(' + row.id + ')" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm"><i class="bi bi-trash3"></i></button></div>'; 
                } }
            ],
            "language": { "paginate": { "previous": '<i class="bi bi-chevron-left"></i>', "next": '<i class="bi bi-chevron-right"></i>' } }
        });
        window.tableRef = table;
    });

    window.dispatchEdit = (id) => {
        var el = document.getElementById('admin-page-container');
        if(el.__x) el.__x.$data.openEdit(id);
        else if(el._x_dataStack) el._x_dataStack[0].openEdit(id);
    }
    window.dispatchDelete = (id) => {
        if(confirm('Hapus pengguna ini? Semua data terkait (usaha, loker) juga akan dihapus.')) {
            fetch('{{ url('administrator/hapususer') }}?id=' + id)
                .then(() => location.reload());
        }
    }
    window.saveUser = () => {
        var el = document.getElementById('admin-page-container');
        var data = el.__x ? el.__x.$data : el._x_dataStack[0];
        var url = data.isEdit ? '{{ url('administrator/updateuser') }}' : '{{ url('administrator/post_user') }}';
        var formData = new FormData(document.getElementById('form_multi_user'));
        fetch(url, { method: 'POST', body: formData })
            .then(() => { data.showModal = false; location.reload(); });
    }
</script>

@stop
