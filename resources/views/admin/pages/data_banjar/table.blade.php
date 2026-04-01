@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showAddModal: false,
    editMode: false,
    
    // Form Data
    banjarId: '',
    banjarName: '',
    banjarAddress: '',
    kelianAdat: '',

    openAdd() {
        this.editMode = false;
        this.banjarId = '';
        this.banjarName = '';
        this.banjarAddress = '';
        this.kelianAdat = '';
        this.showAddModal = true;
    },

    openEdit(id, name, address, kelian) {
        this.editMode = true;
        this.banjarId = id;
        this.banjarName = name;
        this.banjarAddress = address;
        this.kelianAdat = kelian;
        this.showAddModal = true;
    },

    confirmDelete(id) {
        if(confirm('Hapus data banjar ini?')) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('administrator/hapusbanjar') }}';
            
            let idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;
            
            let csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            form.appendChild(idInput);
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Wilayah Banjar</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola database wilayah administrasi banjar di ekosistem Dana Punia.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Banjar
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-geo-alt-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Wilayah</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($datalist) }} Banjar</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="ikantable">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Wilayah Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelian Adat</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat / Lokasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $no = 1; @endphp
                    @foreach($datalist as $values)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16">#{{ $no++ }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors">{{ $values->nama_banjar }}</span>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-medium text-slate-500 max-w-xs truncate">
                            {{ $values->userKelian->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-[10px] font-medium text-slate-500 italic max-w-xs truncate">
                            {{ $values->alamat_banjar }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openEdit('{{ $values->id_data_banjar }}', '{{ $values->nama_banjar }}', '{{ $values->alamat_banjar }}', '{{ $values->id_user_kelian }}')"
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button @click="confirmDelete('{{ $values->id_data_banjar }}')"
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

    <!-- Modal Form -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-geo-alt text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="editMode ? 'Perbarui Data' : 'Banjar Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Wilayah' : 'Tambah Wilayah'"></h3>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/post_data_banjar') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="t_id_banjar" x-model="banjarId">
                    
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Banjar</label>
                            <input type="text" name="t_nama_banjar" required x-model="banjarName"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kelian Adat</label>
                            <select name="t_kelian_adat" x-model="kelianAdat"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                <option value="">-- Tidak ada Kelian --</option>
                                @foreach($kelianUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label>
                            <textarea name="t_alamat_banjar" required rows="3" x-model="banjarAddress"
                                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
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
