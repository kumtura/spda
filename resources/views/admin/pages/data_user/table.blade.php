@extends($base_layout)

@section('isi_menu')
@php
    $level = Session::get('level');
    $isMobile = in_array($level, [2, 3, '2', '3']);
@endphp

<div class="{{ $isMobile ? 'px-6 py-4' : '' }} space-y-6" x-data="{ 
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
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-{{ $isMobile ? 'xl' : '2xl' }} font-black text-slate-800 tracking-tight">Data Pengguna</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Kelola akses administrator</p>
        </div>
        <button @click="openAdd()" class="h-10 {{ $isMobile ? 'w-10' : 'px-5' }} bg-[#00a6eb] text-white rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-[#00a6eb]/20 text-sm font-bold">
            <i class="bi bi-person-plus-fill"></i>
            @if(!$isMobile)<span>Tambah</span>@endif
        </button>
    </div>

    <!-- User List (Card based for mobile, table fallback for desktop) -->
    @if($isMobile)
    <div class="space-y-3" id="userListCards">
        <div class="bg-slate-50 rounded-2xl p-6 text-center" id="loadingUsers">
            <div class="animate-pulse"><i class="bi bi-arrow-repeat text-2xl text-slate-300"></i></div>
            <p class="text-xs text-slate-400 mt-2">Memuat data...</p>
        </div>
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto p-1 text-slate-700">
            <table id="ikantable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama / Email</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Level</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100"></tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Add/Edit Modal -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto {{ $isMobile ? '' : 'px-4 py-12 flex items-center justify-center' }} bg-slate-900/60 backdrop-blur-md" x-transition x-cloak>
            <div class="bg-white {{ $isMobile ? 'min-h-screen' : 'w-full max-w-lg rounded-3xl' }} overflow-hidden shadow-2xl" @click.away="showModal = false">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-[#00a6eb] text-white flex items-center justify-center shadow-lg">
                            <i :class="isEdit ? 'bi-person-gear' : 'bi-person-plus'" class="bi text-xl"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-800" x-text="isEdit ? 'Edit Pengguna' : 'Tambah Pengguna'"></h3>
                    </div>
                    <button @click="showModal = false" class="h-10 w-10 flex items-center justify-center hover:bg-slate-200 rounded-xl text-slate-400"><i class="bi bi-x-lg"></i></button>
                </div>

                <form id="form_multi_user" method="POST" onsubmit="window.saveUser(); return false;" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="iduserinput_edit" x-model="userId">
                    <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label><input type="email" name="emailinput_edit" x-model="userEmail" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all"></div>
                    <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5" x-text="isEdit ? 'Password Baru (Opsional)' : 'Password'"></label><input type="password" name="passwordinput_edit" placeholder="••••••••" :required="!isEdit" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all"></div>
                    <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label><input type="text" name="textinput_edit" x-model="userName" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all"></div>
                    <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">No. WhatsApp</label><input type="number" name="nowainput_edit" x-model="userPhone" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all"></div>
                    <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Level</label>
                        <select name="levelinput_edit" x-model="userLevel" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all">
                            <option value="">Pilih</option>
                            @if(Session::get('level') == '1' || Session::get('level') == '4')
                            <option value="4">Admin Sistem</option><option value="1">Bendesa Adat</option>
                            @endif
                            <option value="2">Kelian Adat</option><option value="3">Unit Usaha</option>
                        </select>
                    </div>
                    <div x-show="userLevel == 2 || userLevel == 3" x-transition>
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Banjar</label>
                        <select name="banjarinput_edit" x-model="userBanjar" :required="userLevel == 2 || userLevel == 3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-[#00a6eb]/20 outline-none transition-all">
                            <option value="">Pilih</option>
                            @foreach($banjar as $b)<option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>@endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
                        Simpan <i class="bi bi-check-lg ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>

<script type="text/javascript">
@if($isMobile)
    // Load user cards via native fetch for mobile
    document.addEventListener('DOMContentLoaded', function() {
        fetch('{{ url('administrator/ambil_listuser') }}')
            .then(r => r.json())
            .then(function(json) {
                var html = '';
                json.forEach(function(u) {
                    var levelColor = 'bg-slate-100 text-slate-500';
                    var l = u.level.toLowerCase();
                    if(l.includes('admin')) levelColor = 'bg-blue-50 text-[#00a6eb]';
                    if(l.includes('bendesa')) levelColor = 'bg-emerald-50 text-emerald-600';
                    if(l.includes('kelian')) levelColor = 'bg-amber-50 text-amber-600';
                    if(l.includes('usaha')) levelColor = 'bg-rose-50 text-rose-600';

                    html += '<div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">' +
                        '<div class="flex items-center justify-between mb-2">' +
                        '<div class="flex items-center gap-3">' +
                        '<div class="h-10 w-10 rounded-xl bg-blue-50 text-[#00a6eb] flex items-center justify-center font-black text-sm uppercase border border-blue-100">' + u.name.charAt(0) + '</div>' +
                        '<div><p class="text-sm font-bold text-slate-800">' + u.name + '</p><p class="text-[10px] text-slate-400">' + u.email + '</p></div></div>' +
                        '<span class="text-[9px] font-bold px-2 py-1 rounded-md uppercase tracking-widest ' + levelColor + '">' + u.level + '</span></div>' +
                        '<div class="flex items-center gap-3 text-[10px] text-slate-400 font-medium mb-3"><span><i class="bi bi-geo-alt mr-1"></i>' + u.nama_banjar + '</span></div>' +
                        '<div class="flex gap-2">' +
                        '<button onclick="window.dispatchEdit(' + u.id + ')" class="flex-1 text-center bg-slate-50 border border-slate-200 text-slate-500 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-[#00a6eb] hover:text-white transition-all"><i class="bi bi-pencil mr-1"></i>Edit</button>' +
                        '<button onclick="window.dispatchDelete(' + u.id + ')" class="text-center bg-white border border-rose-200 text-rose-400 py-2 px-3 rounded-xl text-[10px] font-bold hover:bg-rose-50 transition-all"><i class="bi bi-trash3"></i></button>' +
                        '</div></div>';
                });
                document.getElementById('userListCards').innerHTML = html || '<div class="bg-slate-50 rounded-2xl p-6 text-center"><p class="text-xs text-slate-400">Tidak ada data pengguna</p></div>';
            });
    });
@else
    document.addEventListener('DOMContentLoaded', function() {
        var table = jQuery('#ikantable').DataTable({
            "ajax": { "url": "{{ url('administrator/ambil_listuser') }}", "dataSrc": "" },
            "columns": [
                { "data": 'no', "className": "px-6 py-4 text-xs font-bold text-slate-400" },
                { "data": 'name', "className": "px-6 py-4", "render": function(data,t,row) { return '<div class="flex items-center gap-3"><div class="h-9 w-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-[#00a6eb] font-black text-xs uppercase">' + data.charAt(0) + '</div><div><p class="text-xs font-bold text-slate-800">' + data + '</p><p class="text-[10px] text-slate-400">' + row.email + '</p></div></div>'; } },
                { "data": 'level', "className": "px-6 py-4", "render": function(data) { var c = 'bg-slate-100 text-slate-500'; if(data.toLowerCase().includes('admin')) c='bg-blue-50 text-[#00a6eb]'; if(data.toLowerCase().includes('bendesa')) c='bg-emerald-50 text-emerald-600'; if(data.toLowerCase().includes('kelian')) c='bg-amber-50 text-amber-600'; if(data.toLowerCase().includes('usaha')) c='bg-rose-50 text-rose-600'; return '<span class="text-[9px] font-bold px-2 py-1 rounded-md uppercase tracking-widest ' + c + '">' + data + '</span>'; } },
                { "data": 'nama_banjar', "className": "px-6 py-4", "render": function(data) { return '<span class="text-xs font-bold text-slate-600"><i class="bi bi-geo-alt text-slate-400 mr-1"></i>' + data + '</span>'; } },
                { "data": 'aksi', "className": "px-6 py-4 text-right", "render": function(d,t,row) { return '<div class="flex justify-end gap-1"><button onclick="window.dispatchEdit(' + row.id + ')" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-[#00a6eb] transition-all"><i class="bi bi-pencil-square"></i></button><button onclick="window.dispatchDelete(' + row.id + ')" class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 transition-all"><i class="bi bi-trash3"></i></button></div>'; } }
            ],
            "language": { "paginate": { "previous": '<i class="bi bi-chevron-left"></i>', "next": '<i class="bi bi-chevron-right"></i>' } }
        });
        window.tableRef = table;
    });
@endif

    window.dispatchEdit = (id) => {
        var el = document.querySelector('[x-data]');
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
        var el = document.querySelector('[x-data]');
        var data = el.__x ? el.__x.$data : el._x_dataStack[0];
        var url = data.isEdit ? '{{ url('administrator/updateuser') }}' : '{{ url('administrator/post_user') }}';
        var formData = new FormData(document.getElementById('form_multi_user'));
        fetch(url, { method: 'POST', body: formData })
            .then(() => { data.showModal = false; location.reload(); });
    }
</script>

@if(!$isMobile)
<style>
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
    .dataTables_wrapper .dataTables_filter input { margin-left: 0.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.375rem 0.75rem; font-size: 12px; font-weight: 700; color: #334155; outline: none; }
    .dataTables_wrapper .dataTables_info { padding: 1rem; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
</style>
@endif

@stop
