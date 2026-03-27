@extends('index')
@section('isi_menu')

<div class="space-y-6" x-data="{ 
        show: false, 
        isEdit: false,
        form: {
            id_kategori_punia: '',
            nama_kategori: '',
            ikon: 'bi-wallet2',
            deskripsi_singkat: ''
        }
    }"
    @open-modal.window="show = true; isEdit = false; form = { id_kategori_punia: '', nama_kategori: '', ikon: 'bi-wallet2', deskripsi_singkat: '' }"
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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kategori Alokasi Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Manajemen pengelompokan penggunaan dana punia di ekosistem.</p>
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
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Ikon</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategori as $k => $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16">#{{ $k + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="h-10 w-10 bg-blue-50 text-[#00a6eb] rounded-xl flex items-center justify-center shadow-sm">
                                    <i class="bi {{ $item->ikon }} text-xl"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors">{{ $item->nama_kategori }}</span>
                            </td>
                            <td class="px-6 py-4 text-[10px] font-medium text-slate-500 italic max-w-xs truncate">
                                {{ $item->deskripsi_singkat ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="$dispatch('edit-modal', { id: '{{ $item->id_kategori_punia }}', nama: '{{ $item->nama_kategori }}', ikon: '{{ $item->ikon }}', deskripsi: '{{ $item->deskripsi_singkat }}' })"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ url('administrator/kategori_punia/hapus/'.$item->id_kategori_punia) }}" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')"
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center">
                                <i class="bi bi-folder-x text-4xl text-slate-300 mb-3 block"></i>
                                <span class="text-sm font-semibold text-slate-500">Belum ada Kategori Punia.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Form (Tambah & Edit) -->
    <template x-teleport="body">
        <div x-show="show" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="show = false">
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
                    <button @click="show = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/kategori_punia/update') }}' : '{{ url('administrator/kategori_punia/post') }}'" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_kategori_punia" x-model="form.id_kategori_punia">
                    
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Kategori</label>
                            <input type="text" name="nama_kategori" required x-model="form.nama_kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Ikon Kategori</label>
                            <select name="ikon" required x-model="form.ikon" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                <option value="bi-wallet2">Dompet / Keuangan (bi-wallet2)</option>
                                <option value="bi-cash-coin">Uang Tunai (bi-cash-coin)</option>
                                <option value="bi-building">Bangunan / Infrastruktur (bi-building)</option>
                                <option value="bi-house-heart">Kesejahteraan (bi-house-heart)</option>
                                <option value="bi-people">Masyarakat / Sosial (bi-people)</option>
                                <option value="bi-book">Pendidikan (bi-book)</option>
                                <option value="bi-heart-pulse">Kesehatan (bi-heart-pulse)</option>
                                <option value="bi-shield-check">Keamanan / Pacalang (bi-shield-check)</option>
                                <option value="bi-tree">Lingkungan / Kebersihan (bi-tree)</option>
                                <option value="bi-tools">Pemeliharaan / Alat (bi-tools)</option>
                                <option value="bi-basket2">Konsumsi / Yadnya (bi-basket2)</option>
                                <option value="bi-box-seam">Logistik / Barang (bi-box-seam)</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi Singkat</label>
                            <textarea name="deskripsi_singkat" rows="3" x-model="form.deskripsi_singkat" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="show = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                            Simpan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@endsection
