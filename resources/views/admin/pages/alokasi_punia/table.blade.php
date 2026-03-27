@extends('index')
@section('isi_menu')

<div class="space-y-6" x-data="{ 
        show: false, 
        isEdit: false,
        form: {
            id_alokasi_punia: '',
            id_kategori_punia: '',
            judul: '',
            deskripsi: '',
            nominal: '',
            tanggal_alokasi: '{{ date('Y-m-d') }}'
        }
    }"
    @open-modal.window="show = true; isEdit = false; form = { id_alokasi_punia: '', id_kategori_punia: '', judul: '', deskripsi: '', nominal: '', tanggal_alokasi: '{{ date('Y-m-d') }}' }"
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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Alokasi Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Pencatatan penggunaan pengeluaran dana punia di ekosistem.</p>
        </div>
        <button type="button" @click="$dispatch('open-modal')" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Catat Alokasi
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Judul Keperluan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal (Rp)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($alokasi as $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal_alokasi)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 bg-blue-50 text-[#00a6eb] px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest">
                                    <i class="bi {{ $item->kategori->ikon ?? 'bi-wallet2' }}"></i>
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors block">{{ $item->judul }}</span>
                                <span class="text-[10px] font-medium text-slate-500 italic max-w-xs truncate block mt-0.5">{{ Str::limit($item->deskripsi, 50) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-rose-500 text-right">-{{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="$dispatch('edit-modal', { id: '{{ $item->id_alokasi_punia }}', kategori: '{{ $item->id_kategori_punia }}', judul: '{{ addslashes($item->judul) }}', deskripsi: '{{ addslashes($item->deskripsi) }}', nominal: '{{ $item->nominal }}', tanggal: '{{ $item->tanggal_alokasi }}' })"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ url('administrator/alokasi_punia/hapus/'.$item->id_alokasi_punia) }}" 
                                       onclick="return confirm('Hapus data alokasi ini?')"
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center">
                                <i class="bi bi-file-earmark-x text-4xl text-slate-300 mb-3 block"></i>
                                <span class="text-sm font-semibold text-slate-500">Belum ada data alokasi dana punia.</span>
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
                        <div class="h-12 w-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-cash-stack text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Data' : 'Alokasi Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Alokasi Punia' : 'Catat Alokasi Punia'"></h3>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/alokasi_punia/update') }}' : '{{ url('administrator/alokasi_punia/post') }}'" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_alokasi_punia" x-model="form.id_alokasi_punia">
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Alokasi <span class="text-rose-500">*</span></label>
                                <select name="id_kategori_punia" required x-model="form.id_kategori_punia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                    <option value="">- Pilih Kategori -</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id_kategori_punia }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal Alokasi <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_alokasi" required x-model="form.tanggal_alokasi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Item/Judul Keperluan <span class="text-rose-500">*</span></label>
                            <input type="text" name="judul" required x-model="form.judul" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="nominal" required min="0" x-model="form.nominal" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Catatan/Keterangan</label>
                            <textarea name="deskripsi" rows="3" x-model="form.deskripsi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all"></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto Dokumentasi (Multiple)</label>
                            <input type="file" name="foto[]" multiple accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100">
                            <p class="text-[9px] text-slate-400 mt-1 px-1">Upload multiple gambar (JPG, PNG, GIF, max 2MB per file)</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="show = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-slate-900 hover:bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                            Simpan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
