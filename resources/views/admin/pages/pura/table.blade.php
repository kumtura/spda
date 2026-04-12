@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ }">
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Punia Pura</h1>
            <p class="text-sm text-slate-400 mt-1">Kelola data pura dan donasi/punia pura</p>
        </div>
        <a href="{{ url('administrator/puniapura/create') }}" class="inline-flex items-center gap-2 bg-primary-light hover:bg-primary-dark text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-md shadow-blue-200/50 transition-all">
            <i class="bi bi-plus-circle"></i>
            <span>Tambah Pura</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center">
                    <i class="bi bi-building-fill text-amber-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Total Pura</p>
                    <p class="text-xl font-black text-slate-800">{{ count($pura) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">No</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Pura</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Banjar</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Ketua Pura</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Pemangku</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Wuku Odalan</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pura as $key => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 text-xs text-slate-500">{{ $key + 1 }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-3">
                                @if($item->gambar_pura)
                                <img src="{{ asset($item->gambar_pura) }}" class="h-10 w-10 rounded-lg object-cover" alt="{{ $item->nama_pura }}" onerror="this.outerHTML='<div class=\'h-10 w-10 bg-amber-50 rounded-lg flex items-center justify-center\'><i class=\'bi bi-building text-amber-400\'></i></div>'">
                                @else
                                <div class="h-10 w-10 bg-amber-50 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-building text-amber-400"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="text-xs font-bold text-slate-800">{{ $item->nama_pura }}</p>
                                    <p class="text-[10px] text-slate-400">{{ Str::limit($item->lokasi, 40) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-3 text-xs text-slate-600">{{ $item->nama_banjar ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <p class="text-xs text-slate-700">{{ $item->nama_ketua_pura ?? '-' }}</p>
                            @if($item->no_telp_ketua)
                            <p class="text-[10px] text-slate-400">{{ $item->no_telp_ketua }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-3 text-xs text-slate-600">{{ $item->nama_pemangku ?? '-' }}</td>
                        <td class="px-6 py-3 text-xs text-slate-600">{{ $item->wuku_odalan ?? '-' }}</td>
                        <td class="px-6 py-3">
                            <div class="flex items-center gap-1">
                                <a href="{{ url('administrator/puniapura/detail/'.$item->id_pura) }}" class="h-8 w-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-100 transition-colors" title="Detail">
                                    <i class="bi bi-eye text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/puniapura/edit/'.$item->id_pura) }}" class="h-8 w-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-100 transition-colors" title="Edit">
                                    <i class="bi bi-pencil text-sm"></i>
                                </a>
                                <a href="{{ url('administrator/puniapura/delete/'.$item->id_pura) }}" onclick="return confirm('Yakin hapus pura ini?')" class="h-8 w-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-100 transition-colors" title="Hapus">
                                    <i class="bi bi-trash text-sm"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-8 text-sm text-slate-400">Belum ada data pura</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
