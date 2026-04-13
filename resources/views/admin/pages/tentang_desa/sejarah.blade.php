@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1"><i class="bi bi-arrow-left mr-1"></i> <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa</p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Sejarah Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola konten sejarah desa adat yang ditampilkan di halaman publik.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
        <form action="{{ url('administrator/tentang-desa/sejarah/update') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Konten Sejarah Desa Adat</label>
                    <textarea name="konten_sejarah" rows="16" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-4 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-y"
                        placeholder="Tulis sejarah desa adat di sini...">{{ $settings['sejarah_desa'] ?? '' }}</textarea>
                    <p class="text-[10px] text-slate-400 mt-1.5">Konten ini akan ditampilkan di halaman Tentang Desa pada website publik.</p>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all">
                        <i class="bi bi-save mr-2"></i>Simpan Sejarah
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
