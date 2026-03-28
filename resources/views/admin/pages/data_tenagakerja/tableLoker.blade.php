@extends('index')

@section('isi_menu')

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Monitoring Lowongan Kerja</h1>
            <p class="text-slate-500 font-medium text-sm">Monitor seluruh publikasi lowongan kerja dari unit usaha.</p>
        </div>
        <div class="glass-card px-5 py-2.5 flex items-center gap-3 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-briefcase-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Loker</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($lokers) }} Publikasi</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Lowongan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Usaha</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Posting</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $no = 1; @endphp
                    @foreach($lokers as $loker)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 w-16">#{{ $no++ }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-slate-700 tracking-tight group-hover:text-primary-light transition-colors leading-snug">
                                {{ $loker->judul }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-[10px] overflow-hidden">
                                    @if($loker->usaha && $loker->usaha->detail && $loker->usaha->detail->logo)
                                        @php
                                            $logoPath = file_exists(public_path('usaha/icon/'.$loker->usaha->detail->logo)) 
                                                ? 'usaha/icon/'.$loker->usaha->detail->logo 
                                                : 'storage/usaha/icon/'.$loker->usaha->detail->logo;
                                        @endphp
                                        <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="bi bi-building"></i>
                                    @endif
                                </div>
                                <span class="text-[11px] font-black text-slate-700 truncate max-w-[150px]">
                                    {{ $loker->usaha->detail->nama_usaha ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[10px] font-bold text-slate-500 uppercase">{{ date('d M Y', strtotime($loker->created_at)) }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php $isBuka = $loker->status == 'Buka'; @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border {{ $isBuka ? 'border-emerald-110 bg-emerald-50 text-emerald-600' : 'border-slate-100 bg-slate-50 text-slate-400' }}">
                                <span class="h-1 w-1 rounded-full {{ $isBuka ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                {{ $loker->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="h-8 w-8 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                <i class="bi bi-eye-fill text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($lokers) == 0)
        <div class="p-16 text-center space-y-3">
            <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
                <i class="bi bi-briefcase text-2xl text-slate-200"></i>
            </div>
            <p class="text-slate-400 font-bold italic text-xs tracking-tight">Belum ada lowongan pekerjaan yang diposting.</p>
        </div>
        @endif
    </div>
</div>

@stop
