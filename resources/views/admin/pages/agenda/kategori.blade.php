@extends('index')
@section('isi_menu')

<div class="space-y-6" x-data="{ 
        show: false, 
        isEdit: false,
        form: {
            id_kategori_agenda: '',
            nama_kategori: '',
            keterangan: ''
        }
    }"
    @open-modal.window="show = true; isEdit = false; form = { id_kategori_agenda: '', nama_kategori: '', keterangan: '' }"
    @edit-modal.window="show = true; isEdit = true; form = $event.detail">

    @if (session('success'))
        <div class="relative w-full p-4 mb-6 bg-emerald-50 text-emerald-600 rounded-2xl border border-emerald-100/50 shadow-sm flex items-center gap-3">
            <div class="h-8 w-8 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="bi bi-check-circle-fill text-lg"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold tracking-tight">Sukses</h4>
                <p class="text-xs font-medium opacity-80 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Agenda</h1>
            <p class="text-slate-500 font-medium text-sm">Manajemen kategori untuk pengelompokan agenda desa adat.</p>
        </div>
        <button type="button" @click="$dispatch('open-modal')" 
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
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategori as $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors block">{{ $item->nama_kategori }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-medium text-slate-500 italic max-w-xs truncate block">{{ $item->keterangan ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="$dispatch('edit-modal', { id_kategori_agenda: '{{ $item->id_kategori_agenda }}', nama_kategori: '{{ addslashes($item->nama_kategori) }}', keterangan: '{{ addslashes($item->keterangan) }}' })"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ url('administrator/kategori_agenda/hapus/'.$item->id_kategori_agenda) }}" 
                                       onclick="return confirm('Hapus kategori ini?')"
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center">
                                <i class="bi bi-tag text-4xl text-slate-300 mb-3 block"></i>
                                <span class="text-sm font-semibold text-slate-500">Belum ada kategori agenda.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form -->
    <template x-teleport="body">
        <div x-show="show" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="show = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center">
                            <i class="bi bi-tag-fill text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Data' : 'Kategori Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/kategori_agenda/update') }}' : '{{ url('administrator/kategori_agenda/post') }}'" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_kategori_agenda" x-model="form.id_kategori_agenda">
                    
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kategori <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_kategori" required x-model="form.nama_kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan</label>
                            <textarea name="keterangan" rows="3" x-model="form.keterangan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="show = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light hover:bg-primary-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all transform hover:-translate-y-0.5">
                            Simpan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
