@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ showModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1"><i class="bi bi-arrow-left mr-1"></i> <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa</p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Badan Usaha Milik Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola data badan usaha milik desa adat (BUMDes).</p>
        </div>
        <button @click="showModal = true" class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all">
            <i class="bi bi-shop text-lg"></i> Tambah BUMDes
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    <!-- Grid BUMDes -->
    @if(count($bumdesList) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach($bumdesList as $bumdes)
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-start gap-4 mb-4">
                <div class="h-16 w-16 rounded-xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center">
                    @if($bumdes['logo'])
                        <img src="{{ asset('storage/tentang_desa/bumdes/' . $bumdes['logo']) }}" class="h-full w-full object-cover" alt="{{ $bumdes['nama_bumdes'] }}">
                    @else
                        <i class="bi bi-shop text-2xl text-slate-400"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-800 leading-tight">{{ $bumdes['nama_bumdes'] }}</p>
                    @if($bumdes['ketua'])
                    <p class="text-[10px] font-bold text-primary-light mt-0.5">Ketua: {{ $bumdes['ketua'] }}</p>
                    @endif
                    @if($bumdes['tahun_berdiri'])
                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="bi bi-calendar3 mr-1"></i>Berdiri: {{ $bumdes['tahun_berdiri'] }}</p>
                    @endif
                </div>
            </div>
            @if($bumdes['deskripsi'])
            <p class="text-xs text-slate-500 leading-relaxed mb-4 line-clamp-3">{{ $bumdes['deskripsi'] }}</p>
            @endif
            <div class="flex justify-end pt-3 border-t border-slate-100">
                <form action="{{ route('tentang_desa.bumdes.delete') }}" method="POST" onsubmit="return confirm('Hapus BUMDes ini?')">
                    @csrf
                    <input type="hidden" name="id" value="{{ $bumdes['id'] }}">
                    <button type="submit" class="flex items-center gap-1.5 text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors">
                        <i class="bi bi-trash3"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center shadow-sm">
        <i class="bi bi-shop text-4xl text-slate-300 block mb-3"></i>
        <p class="text-sm font-bold text-slate-400">Belum ada data BUMDes. Klik "Tambah BUMDes" untuk memulai.</p>
    </div>
    @endif

    <!-- Modal Tambah -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4" x-transition x-cloak>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800">Tambah BUMDes</h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('tentang_desa.bumdes.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama BUMDes <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_bumdes" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Ketua</label>
                            <input type="text" name="ketua" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tahun Berdiri</label>
                            <input type="text" name="tahun_berdiri" placeholder="2020" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Logo (Opsional)</label>
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/jpg" class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 text-slate-400 font-bold text-sm">Batal</button>
                        <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
