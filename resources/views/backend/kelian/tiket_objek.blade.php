@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header with gradient and back button -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/kelian/tiket') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Kelola Objek Wisata</h1>
                <p class="text-[10px] text-white/80">Tambah dan edit objek wisata</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4">
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 mb-4">
            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle text-emerald-600"></i>
                <p class="text-xs font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Add Button -->
        <a href="{{ url('administrator/kelian/tiket/objek/create') }}" 
           class="block w-full bg-[#00a6eb] text-white text-center py-3 rounded-xl font-black text-sm shadow-lg mb-4">
            <i class="bi bi-plus-circle mr-2"></i>Tambah Objek Wisata
        </a>

        <!-- Objek Wisata List -->
        <div class="space-y-3">
            @forelse($objekWisata as $objek)
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-start gap-4 p-4">
                    <div class="h-20 w-20 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                        @if($objek->foto)
                            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="h-full w-full object-cover" alt="{{ $objek->nama_objek }}">
                        @else
                            <i class="bi bi-image text-slate-300 text-2xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-800 mb-1">{{ $objek->nama_objek }}</h3>
                        <p class="text-[10px] text-slate-500 mb-2 line-clamp-1">{{ $objek->alamat }}</p>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[9px] font-bold text-[#00a6eb] bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}
                            </span>
                            @if($objek->status === 'aktif')
                            <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">
                                Aktif
                            </span>
                            @else
                            <span class="text-[9px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                Nonaktif
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ url('administrator/kelian/tiket/objek/edit/'.$objek->id_objek_wisata) }}" 
                               class="text-[10px] font-bold text-[#00a6eb] bg-blue-50 px-3 py-1 rounded-lg border border-blue-100">
                                <i class="bi bi-pencil mr-1"></i>Edit
                            </a>
                            <a href="{{ url('administrator/kelian/tiket/objek/toggle/'.$objek->id_objek_wisata) }}" 
                               class="text-[10px] font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-lg border border-amber-100">
                                <i class="bi bi-toggle-on mr-1"></i>Toggle
                            </a>
                            <button onclick="confirmDelete({{ $objek->id_objek_wisata }})" 
                                    class="text-[10px] font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-lg border border-rose-100">
                                <i class="bi bi-trash mr-1"></i>Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-8 text-center">
                <i class="bi bi-ticket-perforated text-3xl text-slate-300 mb-2"></i>
                <p class="text-xs text-slate-400">Belum ada objek wisata</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus objek wisata ini?')) {
        window.location.href = '{{ url("administrator/kelian/tiket/objek/delete") }}/' + id;
    }
}
</script>
@endsection
