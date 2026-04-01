@extends('index')

@section('isi_menu')

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Objek Wisata</h1>
            <p class="text-slate-500 font-medium text-sm">Daftar destinasi wisata yang dikelola oleh Kelian Adat dan Bendesa.</p>
        </div>
        <div>
            <a href="{{ url('administrator/objek_wisata/create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary-light hover:bg-primary-dark text-white rounded-xl shadow-lg shadow-primary-light/30 transition-all text-xs font-black uppercase tracking-widest active:scale-95">
                <i class="bi bi-plus-lg"></i>
                Tambah Objek Wisata
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-map-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Destinasi</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($objekWisata) }} Objek</p>
            </div>
        </div>
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-check-circle-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Aktif</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ $objekWisata->where('status', 'aktif')->count() }} Objek</p>
            </div>
        </div>
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-cash-stack text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Pemasukan</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">Rp {{ number_format($objekWisata->sum('total_pemasukan'), 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Objek</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tiket Terjual</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Pemasukan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jam Operasional</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($objekWisata as $objek)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            @if($objek->foto)
                            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" alt="{{ $objek->nama_objek }}" 
                                 class="w-14 h-14 object-cover rounded-xl ring-1 ring-slate-100 shadow-sm">
                            @else
                            <div class="w-14 h-14 bg-slate-50 rounded-xl flex items-center justify-center border border-dashed border-slate-200">
                                <i class="bi bi-image text-slate-300 text-xl"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors block">{{ $objek->nama_objek }}</span>
                            <span class="text-[10px] font-medium text-slate-400 italic mt-0.5 block max-w-[200px] truncate">{{ $objek->alamat }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($objek->banjar)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 text-primary-light text-[10px] font-bold">
                                <i class="bi bi-geo-alt-fill"></i>
                                {{ $objek->banjar->nama_banjar }}
                            </span>
                            @else
                            <span class="text-[10px] text-slate-300 italic">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-700">{{ number_format($objek->total_tiket_terjual ?? 0) }}</span>
                            <span class="text-[9px] text-slate-400 font-medium ml-0.5">tiket</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-primary-light">Rp {{ number_format($objek->total_pemasukan ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-6 py-4 text-[10px] font-medium text-slate-500">
                            @if($objek->jam_buka && $objek->jam_tutup)
                            <div class="flex items-center gap-1">
                                <i class="bi bi-clock text-amber-500"></i>
                                {{ $objek->jam_buka }} - {{ $objek->jam_tutup }}
                            </div>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($objek->status === 'aktif')
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-green-100 text-green-700">Aktif</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-slate-100 text-slate-500">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ url('administrator/objek_wisata/edit/'.$objek->id_objek_wisata) }}" 
                                   class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm"
                                   title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="javascript:void(0)" onclick="if(confirm('Hapus objek wisata ini?')) window.location.href='{{ url('administrator/objek_wisata/delete/'.$objek->id_objek_wisata) }}'"
                                   class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-500 transition-all shadow-sm"
                                   title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="bi bi-map text-slate-300 text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-bold text-sm">Belum ada objek wisata</p>
                                <p class="text-[10px] text-slate-400 mt-1">Belum ada data destinasi wisata yang ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
