@extends('index')
@section('isi_menu')

<div class="space-y-6" x-data="{ 
        show: false, 
        isEdit: false,
        form: {
            id_agenda: '',
            id_kategori_agenda: '',
            judul_agenda: '',
            deskripsi_agenda: '',
            tanggal_agenda: '{{ date('Y-m-d') }}',
            waktu_agenda: '',
            waktu_selesai_data: '',
            status_selesai: 'fixed',
            lokasi_agenda: '',
            status_agenda: 'Publish'
        }
    }"
    @open-modal.window="show = true; isEdit = false; form = { id_agenda: '', id_kategori_agenda: '', judul_agenda: '', deskripsi_agenda: '', tanggal_agenda: '{{ date('Y-m-d') }}', waktu_agenda: '', waktu_selesai_data: '', status_selesai: 'fixed', lokasi_agenda: '', status_agenda: 'Publish' }"
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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Agenda Desa</h1>
            <p class="text-slate-500 font-medium text-sm">Manajemen agenda kegiatan, upacara adat, dan event desa.</p>
        </div>
        <button type="button" @click="$dispatch('open-modal')" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Agenda
        </button>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Agenda & Lokasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($agenda as $item)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                        @if($item->foto_agenda)
                                            <img src="{{ asset('storage/agenda/'.$item->foto_agenda) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-slate-300">
                                                <i class="bi bi-image text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="text-sm font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors block">{{ $item->judul_agenda }}</span>
                                        <div class="flex items-center gap-1 text-[10px] text-slate-400 font-bold mt-0.5">
                                            <i class="bi bi-geo-alt-fill"></i>
                                            <span>{{ $item->lokasi_agenda }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_agenda)->translatedFormat('d F Y') }}</div>
                                <div class="text-[10px] font-medium text-slate-400 mt-0.5 tracking-wider">{{ $item->waktu_agenda ? date('H:i', strtotime($item->waktu_agenda)).' WITA' : 'Waktu belum diatur' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="inline-flex items-center gap-1.5 bg-blue-50 text-primary-light px-2 py-1 rounded text-[10px] font-bold uppercase tracking-widest">
                                    <i class="bi bi-tag-fill"></i>
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ url('administrator/agenda/toggle/'.$item->id_agenda) }}" 
                                   class="inline-flex items-center gap-1.5 {{ $item->status_agenda == 'Publish' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest hover:scale-105 transition-transform">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $item->status_agenda == 'Publish' ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                    {{ $item->status_agenda }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" 
                                            @click="$dispatch('edit-modal', { id_agenda: '{{ $item->id_agenda }}', id_kategori_agenda: '{{ $item->id_kategori_agenda }}', judul_agenda: '{{ addslashes($item->judul_agenda) }}', deskripsi_agenda: '{{ addslashes($item->deskripsi_agenda) }}', tanggal_agenda: '{{ $item->tanggal_agenda }}', waktu_agenda: '{{ $item->waktu_agenda }}', waktu_selesai_data: '{{ $item->waktu_selesai_data }}', status_selesai: '{{ $item->status_selesai }}', lokasi_agenda: '{{ addslashes($item->lokasi_agenda) }}', status_agenda: '{{ $item->status_agenda }}' })"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ url('administrator/agenda/hapus/'.$item->id_agenda) }}" 
                                       onclick="return confirm('Hapus agenda ini?')"
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center">
                                <i class="bi bi-calendar-x text-4xl text-slate-300 mb-3 block"></i>
                                <span class="text-sm font-semibold text-slate-500">Belum ada agenda desa.</span>
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
            
            <div class="bg-white w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="show = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center">
                            <i class="bi bi-calendar-plus text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block" x-text="isEdit ? 'Perbarui Agenda' : 'Agenda Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="isEdit ? 'Edit Agenda Desa' : 'Tambah Agenda Desa'"></h3>
                        </div>
                    </div>
                    <button @click="show = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form :action="isEdit ? '{{ url('administrator/agenda/update') }}' : '{{ url('administrator/agenda/post') }}'" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="id_agenda" x-model="form.id_agenda">
                    
                    <div class="max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Judul Agenda <span class="text-rose-500">*</span></label>
                                <input type="text" name="judul_agenda" required x-model="form.judul_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Agenda <span class="text-rose-500">*</span></label>
                                <select name="id_kategori_agenda" required x-model="form.id_kategori_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                                    <option value="">- Pilih Kategori -</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id_kategori_agenda }}">{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Status Publikasi</label>
                                <select name="status_agenda" x-model="form.status_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                                    <option value="Publish">Publish (Terlihat Publik)</option>
                                    <option value="Draft">Draft (Hanya Admin)</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal Agenda <span class="text-rose-500">*</span></label>
                                <input type="date" name="tanggal_agenda" required x-model="form.tanggal_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                                <input type="time" name="waktu_agenda" required x-model="form.waktu_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Opsi Berakhir</label>
                                <select name="status_selesai" x-model="form.status_selesai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                                    <option value="selesai">Sampai Selesai</option>
                                    <option value="fixed">Jam Tertentu</option>
                                </select>
                            </div>

                            <div class="space-y-1.5" x-show="form.status_selesai == 'fixed'" x-transition>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Waktu Berakhir</label>
                                <input type="time" name="waktu_selesai_data" :required="form.status_selesai == 'fixed'" x-model="form.waktu_selesai_data" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Lokasi Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="lokasi_agenda" required x-model="form.lokasi_agenda" placeholder="Contoh: Pura Desa, Balai Banjar, dll" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Deskripsi Agenda <span class="text-rose-500">*</span></label>
                                <textarea name="deskripsi_agenda" rows="4" required x-model="form.deskripsi_agenda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"></textarea>
                            </div>

                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto/Flyer Agenda</label>
                                <input type="file" name="foto_agenda" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-light/10 file:text-primary-light hover:file:bg-primary-light/20">
                                <p class="text-[9px] text-slate-400 mt-1 px-1">Upload gambar format JPG/PNG (Max 2MB)</p>
                            </div>
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
