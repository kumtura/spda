@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ showModal: false }">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1"><i class="bi bi-arrow-left mr-1"></i> <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa</p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengurus Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola data pengurus desa adat yang ditampilkan di halaman publik.</p>
        </div>
        <button @click="showModal = true" class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all">
            <i class="bi bi-person-plus-fill text-lg"></i> Tambah Pengurus
        </button>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    <!-- List Pengurus -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        @if(count($pengurusList) > 0)
        <div class="divide-y divide-slate-100">
            @foreach($pengurusList as $pengurus)
            <div class="flex items-center gap-4 p-5">
                <div class="h-14 w-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center">
                    @if($pengurus['foto'])
                        <img src="{{ asset('storage/tentang_desa/pengurus/' . $pengurus['foto']) }}" class="h-full w-full object-cover" alt="{{ $pengurus['nama'] }}">
                    @else
                        <i class="bi bi-person-fill text-2xl text-slate-400"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-800">{{ $pengurus['nama'] }}</p>
                    <p class="text-xs font-bold text-primary-light">{{ $pengurus['jabatan'] }}</p>
                    @if($pengurus['no_hp'])
                    <p class="text-[10px] text-slate-400 mt-0.5"><i class="bi bi-telephone mr-1"></i>{{ $pengurus['no_hp'] }}</p>
                    @endif
                </div>
                <form action="{{ route('tentang_desa.pengurus.delete') }}" method="POST" onsubmit="return confirm('Hapus pengurus ini?')">
                    @csrf
                    <input type="hidden" name="id" value="{{ $pengurus['id'] }}">
                    <button type="submit" class="h-9 w-9 flex items-center justify-center bg-white border border-rose-200 text-rose-400 rounded-xl hover:bg-rose-50 transition-colors">
                        <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <i class="bi bi-people text-4xl text-slate-300 block mb-3"></i>
            <p class="text-sm font-bold text-slate-400">Belum ada data pengurus. Klik "Tambah Pengurus" untuk memulai.</p>
        </div>
        @endif
    </div>

    <!-- Modal Tambah -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4" x-transition x-cloak>
            <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-800">Tambah Pengurus</h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ route('tentang_desa.pengurus.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jabatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="jabatan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">No. HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Foto (Opsional)</label>
                        <input type="file" name="foto" accept="image/png,image/jpeg,image/jpg" class="block w-full text-sm text-slate-500 border border-slate-200 rounded-xl cursor-pointer bg-slate-50 file:mr-4 file:py-2.5 file:px-4 file:rounded-l-xl file:border-0 file:text-xs file:font-semibold file:bg-primary-light file:text-white">
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
