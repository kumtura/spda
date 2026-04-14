@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <p class="text-sm text-primary-light font-medium mb-1">
                <i class="bi bi-arrow-left mr-1"></i>
                <a href="{{ url('administrator/') }}">Dashboard</a> / Tentang Desa Adat
            </p>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Lembaga Desa Adat</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola data lembaga yang ada di Desa Adat.</p>
        </div>
        <a href="{{ url('administrator/tentang-desa/lembaga/create') }}"
           class="flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all">
            <i class="bi bi-building-add text-lg"></i> Tambah Lembaga
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-2">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(count($lembagaList) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($lembagaList as $lembaga)
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col">
            {{-- Logo / Header --}}
            <div class="p-5 flex items-start gap-4">
                <div class="h-14 w-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                    @if(!empty($lembaga['logo']))
                        <img src="{{ asset('storage/tentang_desa/lembaga/' . $lembaga['logo']) }}" class="h-full w-full object-cover" alt="{{ $lembaga['nama_lembaga'] }}">
                    @else
                        <i class="bi bi-building text-2xl text-slate-300"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-black text-slate-800 leading-tight">{{ $lembaga['nama_lembaga'] }}</p>
                    @if(!empty($lembaga['ketua']))
                    <p class="text-[10px] font-bold text-primary-light mt-0.5">Ketua: {{ $lembaga['ketua'] }}</p>
                    @endif
                    @if(!empty($lembaga['pengurus']))
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ count($lembaga['pengurus']) }} pengurus</p>
                    @endif
                </div>
            </div>

            @if(!empty($lembaga['deskripsi']))
            <div class="px-5 pb-3">
                <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">{!! strip_tags($lembaga['deskripsi']) !!}</p>
            </div>
            @endif

            {{-- Gallery preview --}}
            @if(!empty($lembaga['gallery']) && count($lembaga['gallery']) > 0)
            <div class="px-5 pb-3 flex gap-1.5 overflow-x-auto">
                @foreach(array_slice($lembaga['gallery'], 0, 4) as $gi => $gfoto)
                <div class="h-14 w-14 rounded-lg overflow-hidden shrink-0 bg-slate-100 border border-slate-200">
                    <img src="{{ asset('storage/tentang_desa/lembaga/' . $gfoto) }}" class="h-full w-full object-cover" alt="Gallery">
                </div>
                @endforeach
                @if(count($lembaga['gallery']) > 4)
                <div class="h-14 w-14 rounded-lg shrink-0 bg-slate-100 border border-slate-200 flex items-center justify-center">
                    <span class="text-[10px] font-black text-slate-400">+{{ count($lembaga['gallery']) - 4 }}</span>
                </div>
                @endif
            </div>
            @endif

            <div class="mt-auto px-5 py-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ url('administrator/tentang-desa/lembaga/' . $lembaga['id'] . '/edit') }}"
                   class="flex items-center gap-1.5 text-xs font-bold text-primary-light hover:text-primary-dark transition-colors">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
                <form action="{{ url('administrator/tentang-desa/lembaga/delete') }}" method="POST" onsubmit="return confirm('Hapus lembaga ini?')">
                    @csrf
                    <input type="hidden" name="id" value="{{ $lembaga['id'] }}">
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
        <i class="bi bi-building text-4xl text-slate-300 block mb-3"></i>
        <p class="text-sm font-bold text-slate-400">Belum ada data lembaga.</p>
        <a href="{{ url('administrator/tentang-desa/lembaga/create') }}" class="inline-flex items-center gap-2 mt-4 bg-primary-light text-white px-5 py-2.5 rounded-xl font-bold text-sm">
            <i class="bi bi-plus-lg"></i> Tambah Lembaga Pertama
        </a>
    </div>
    @endif
</div>
@endsection
