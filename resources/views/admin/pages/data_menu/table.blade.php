@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showModal: false,
    editMode: false,
    
    // Form Data
    menuId: '',
    menuName: '',
    menuUrl: '',
    menuOrder: '1',
    isSlide: false,

    openAdd() {
        this.editMode = false;
        this.menuId = '';
        this.menuName = '';
        this.menuUrl = '';
        this.menuOrder = '1';
        this.isSlide = false;
        this.showModal = true;
    },

    openEdit(id, name, url, slide, order) {
        this.editMode = true;
        this.menuId = id;
        this.menuName = name;
        this.menuUrl = url;
        this.menuOrder = order;
        this.isSlide = parseInt(slide) === 1;
        this.showModal = true;
    },

    confirmDelete(id) {
        if(confirm('Hapus menu ini?')) {
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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Navigasi Sistem</h1>
            <p class="text-slate-500 font-medium text-sm">Konfigurasi struktur menu dan aksesibilitas fitur utama platform.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-grid-fill text-lg"></i>
            Tambah Menu
        </button>
    </div>

    <!-- Menu Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="ikantable">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Urutan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Icon & Nama</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">URL Context</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Slide</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($datalist as $values)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="h-6 w-6 rounded bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">{{ $values->urutan }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-lg bg-white border border-slate-100 p-1.5 shadow-sm transform group-hover:scale-105 transition-transform">
                                    <img src="{{ url('storage/menu/icon/thumbnail/'.$values->foto) }}" class="h-full w-full object-contain">
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-700 tracking-tight leading-none mb-1">{{ $values->menu }}</p>
                                    <p class="text-[9px] font-bold text-primary-light uppercase tracking-widest">ID: #{{ $values->id_menu_member }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded border border-slate-100">{{ $values->url }}</code>
                        </td>
                        <td class="px-6 py-4">
                            @if($values->is_slide == 1)
                                <span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-black uppercase tracking-tight border border-emerald-100">
                                    Slide Aktif
                                </span>
                            @else
                                <span class="inline-flex px-2 py-0.5 bg-slate-50 text-slate-400 rounded text-[9px] font-black uppercase tracking-tight border border-slate-200">
                                    Static
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openEdit('{{ $values->id_menu_member }}','{{ $values->menu }}','{{ $values->url }}','{{ $values->is_slide }}','{{ $values->urutan }}')"
                                        class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button @click="confirmDelete('{{ $values->id_menu_member }}')"
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

    <!-- Modal Layout -->
    <template x-teleport="body">
        <div x-show="showModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform -rotate-1">
                            <i class="bi bi-cpu text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="editMode ? 'Arsitektur Sistem' : 'Navigasi Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Konfigurasi' : 'Tambah Menu'"></h3>
                        </div>
                    </div>
                </div>

                <form action="{{ url('administrator/post_data_menu') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="t_id_menu" x-model="menuId">
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Label Menu</label>
                                <input type="text" name="t_nama_menu" required x-model="menuName"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Order Index</label>
                                <input type="number" name="t_urutan_menu" required x-model="menuOrder"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Target URL Path</label>
                            <input type="text" name="t_url_menu" required x-model="menuUrl" placeholder="administrator/view-name"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs font-mono font-bold text-slate-600 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Icon Asset (IMG/PNG)</label>
                            <input type="file" name="f_upload_menu" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold">
                        </div>

                        <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100 cursor-pointer group">
                            <input type="checkbox" name="chk_is_slide" x-model="isSlide" value="1" class="w-4 h-4 rounded border-slate-300 text-primary-light">
                            <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest group-hover:text-primary-light transition-colors">Tampilkan di Slideshow Home</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Tutup</button>
                        <button type="submit" class="px-8 py-2.5 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                            Deploy Navigasi <i class="bi bi-chevron-right ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@stop
