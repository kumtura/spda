@extends('index')
@section('isi_menu')

<div class="space-y-6" x-data="{ 
        show: false, 
        isEdit: false,
        form: {
            id_program_donasi: '',
            id_kategori_donasi: '',
            nama_program: '',
            deskripsi: '',
            target_dana: '',
            tanggal_mulai: '{{ date('Y-m-d') }}'
        }
    }"
    @open-modal.window="show = true; isEdit = false; form = { id_program_donasi: '', id_kategori_donasi: '', nama_program: '', deskripsi: '', target_dana: '', tanggal_mulai: '{{ date('Y-m-d') }}' }"
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

    @if ($errors->any())
        <div class="relative w-full p-4 mb-6 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100/50 shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-8 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                </div>
                <h4 class="text-sm font-bold tracking-tight">Validasi Gagal</h4>
            </div>
            <ul class="text-xs font-medium opacity-80 list-disc pl-12 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Program Donasi</h1>
            <p class="text-slate-500 font-medium text-sm">Pencatatan target dan kampanye donasi sosial aktif.</p>
        </div>
        <button type="button" @click="$dispatch('open-modal')" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Program
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Foto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Mulai</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Program</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Target (Rp)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($program as $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="h-12 w-16 rounded-lg bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center">
                                    @if($item->foto)
                                        <img src="{{ asset('storage/program_donasi/'.$item->foto) }}" class="h-full w-full object-cover" alt="">
                                    @else
                                        <i class="bi bi-image text-slate-300"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-500 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors block">{{ $item->nama_program }}</span>
                                <span class="text-[10px] font-medium text-slate-500 italic max-w-xs truncate block mt-0.5">{{ Str::limit($item->deskripsi, 50) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-primary-light text-right">{{ number_format($item->target_dana, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="$dispatch('edit-modal', { id_program_donasi: '{{ $item->id_program_donasi }}', id_kategori_donasi: '{{ $item->id_kategori_donasi }}', nama_program: '{{ e(str_replace(["\r\n", "\r", "\n", "'"], ["\\n", "\\n", "\\n", "\\'"], $item->nama_program)) }}', deskripsi: '{{ e(str_replace(["\r\n", "\r", "\n", "'"], ["\\n", "\\n", "\\n", "\\'"], $item->deskripsi ?? "")) }}', target_dana: '{{ $item->target_dana }}', tanggal_mulai: '{{ $item->tanggal_mulai }}' })"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ url('administrator/program_donasi/hapus/'.$item->id_program_donasi) }}" 
                                       onclick="return confirm('Hapus program ini?')"
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center">
                                <i class="bi bi-file-earmark-x text-4xl text-slate-300 mb-3 block"></i>
                                <span class="text-sm font-semibold text-slate-500">Belum ada data program donasi.</span>
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
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-calendar-event text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Data' : 'Program Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Program Donasi' : 'Tambah Program Donasi'"></h3>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/program_donasi/update') }}' : '{{ url('administrator/program_donasi/post') }}'" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_program_donasi" x-model="form.id_program_donasi">
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Program <span class="text-rose-500">*</span></label>
                                <select name="id_kategori_donasi" required x-model="form.id_kategori_donasi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                                    <option value="">- Pilih Kategori -</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id_kategori_donasi }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_mulai" required x-model="form.tanggal_mulai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Program <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_program" required x-model="form.nama_program" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Target Dana (Rp) <span class="text-rose-500">*</span></label>
                            <input type="number" name="target_dana" required min="0" x-model="form.target_dana" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi Program</label>
                            <textarea name="deskripsi" rows="3" x-model="form.deskripsi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto Program</label>
                            <input type="file" name="foto" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-primary-light file:text-white">
                            <p class="text-[9px] text-slate-400 italic px-1">Format: JPG, PNG, WebP. Maks 2MB. Kosongkan jika tidak ingin mengubah.</p>
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
