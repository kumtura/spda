@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-purple-500 text-white flex items-center justify-center shadow-xl shadow-purple-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">Staff Counter</h1>
                <p class="text-slate-500 font-semibold text-sm">Monitor staff ticket counter, absensi dan performa penjualan.</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Staff</p>
                    <p class="text-2xl font-black text-slate-800">{{ $totalStaff }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masuk Hari Ini</p>
                    <p class="text-2xl font-black text-slate-800">{{ $staffAktifHariIni }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penjualan Hari Ini</p>
                    <p class="text-2xl font-black text-slate-800">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <select name="id_data_banjar" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none">
                <option value="">Semua Banjar</option>
                @foreach($banjarList as $b)
                    <option value="{{ $b->id_data_banjar }}" {{ request('id_data_banjar') == $b->id_data_banjar ? 'selected' : '' }}>{{ $b->nama_banjar }}</option>
                @endforeach
            </select>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none w-64">
            <button type="submit" class="px-5 py-2.5 bg-purple-600 text-white rounded-xl text-sm font-bold hover:bg-purple-700 transition-all">Filter</button>
            @if(request()->hasAny(['id_data_banjar', 'search']))
                <a href="{{ url('administrator/staff_counter') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">Reset</a>
            @endif
        </form>
    </div>

    <!-- Staff List -->
    <div class="space-y-4">
        @forelse($staffList as $staff)
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:border-purple-200 transition-all">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 font-black text-lg uppercase flex-shrink-0">
                        {{ substr($staff->name, 0, 1) }}
                    </div>
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <h3 class="text-sm font-bold text-slate-800">{{ $staff->name }}</h3>
                            @if($staff->activeShift)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> On Duty
                                </span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-bold">Off Duty</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 text-[10px] text-slate-400 font-medium">
                            <span>{{ $staff->email }}</span>
                            <span>{{ $staff->no_wa }}</span>
                            <span>Banjar: {{ $staff->nama_banjar ?? '-' }}</span>
                        </div>
                        @if($staff->assignments->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($staff->assignments as $a)
                                @if($a->objekWisata)
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold">{{ $a->objekWisata->nama_objek }}</span>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-5 flex-shrink-0">
                    <div class="text-center">
                        <p class="text-lg font-black text-slate-800">{{ $staff->absensi_bulan_ini }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Absensi</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-slate-800">{{ $staff->tiket_hari_ini }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Tiket Hari Ini</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-emerald-600">Rp {{ number_format($staff->penjualan_hari_ini, 0, ',', '.') }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Hari Ini</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-black text-blue-600">Rp {{ number_format($staff->penjualan_bulan_ini, 0, ',', '.') }}</p>
                        <p class="text-[9px] font-bold text-slate-400 uppercase">Bulan Ini</p>
                    </div>
                    <a href="{{ url('administrator/staff_counter/detail/' . $staff->id) }}" class="px-4 py-2.5 bg-purple-100 text-purple-700 rounded-xl text-xs font-bold hover:bg-purple-200 transition-all flex-shrink-0">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white border border-slate-200 rounded-2xl p-12 shadow-sm text-center">
            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <p class="text-sm font-semibold text-slate-400">Belum ada staff ticket counter.</p>
            <p class="text-xs text-slate-400 mt-1">Tambahkan pengguna dengan role "Ticket Counter" di halaman Data User.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
