@extends('index')

@section('isi_menu')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tenaga Kerja Aktif</h1>
            <p class="text-slate-500 font-medium text-sm">Monitoring database tenaga kerja dan status penempatan unit usaha.</p>
        </div>
        <div class="glass-card px-5 py-2.5 flex items-center gap-3 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total SDM</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($karyawan) }} Orang</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="get" action="{{ url('administrator/data_tenagakerja_aktif') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama tenaga kerja..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
            </div>
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-100 transform hover:-translate-y-0.5">
                Filter Data
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tenaga Kerja</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak & Alamat</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penempatan Usaha</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $no = 1; @endphp
                    @foreach($karyawan as $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16">#{{ $no++ }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs overflow-hidden border border-slate-200">
                                    @if($rows->foto_profile)
                                        <img src="{{ asset('storage/karyawan/'.$rows->foto_profile) }}" class="h-full w-full object-cover">
                                    @else
                                        {{ substr($rows->nama, 0, 1) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-700 tracking-tight leading-none mb-1">{{ $rows->nama }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $rows->jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan' }} • {{ $rows->umur }} Thn</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-slate-600 mb-0.5">{{ $rows->no_wa }}</p>
                            <p class="text-[10px] text-slate-400 font-medium italic truncate max-w-[200px]" title="{{ $rows->alamat }}">
                                {{ $rows->alamat }}
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            @if($rows->nama_usaha)
                                <div class="flex items-center gap-2">
                                    <div class="h-7 w-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-[10px] overflow-hidden">
                                        @if($rows->logo)
                                            <img src="{{ asset('storage/usaha/icon/'.$rows->logo) }}" class="h-full w-full object-cover">
                                        @else
                                            <i class="bi bi-briefcase"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-slate-700 leading-none mb-0.5">{{ $rows->nama_usaha }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase">{{ $rows->jabatan ?? 'Karyawan' }}</p>
                                    </div>
                                </div>
                            @else
                                <span class="text-[10px] font-bold text-slate-300 italic">Belum Ditempatkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $status = $rows->status_diterima;
                                $label = $status == '1' ? 'AKTIF BEKERJA' : ($rows->id_jadwal_interview ? 'INTERVIEW' : 'MENCARI KERJA');
                                $color = $status == '1' ? 'emerald' : ($rows->id_jadwal_interview ? 'amber' : 'slate');
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-{{ $color }}-100 bg-{{ $color }}-50 text-{{ $color }}-600">
                                <span class="h-1 w-1 bg-{{ $color == 'slate' ? 'slate-400' : ($color.'-500') }} rounded-full {{ $status == '1' ? 'animate-pulse' : '' }}"></span>
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ url('administrator/detail_tenaga_kerja/'.$rows->id_tenaga_kerja) }}" 
                               class="h-8 w-8 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                <i class="bi bi-eye-fill text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop
