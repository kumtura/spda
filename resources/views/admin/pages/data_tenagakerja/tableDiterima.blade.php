@extends('index')

@section('isi_menu')

<div class="space-y-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tenaga Kerja Aktif</h1>
            <p class="text-slate-500 font-medium text-sm">Daftar tenaga kerja yang telah berhasil ditempatkan.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
            <div class="h-10 w-10 flex items-center justify-center bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <div class="pr-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Aktif</p>
                <p class="text-lg font-black text-slate-800">{{ count($karyawan) }} Personil</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="glass-card p-4 rounded-4xl border-white/40 shadow-xl">
        <form method="get" action="{{ url('administrator/data_tenagakerja_diterima') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari personil aktif..." 
                       class="w-full bg-slate-100/50 border-none rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-400">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-emerald-100 transform hover:-translate-y-1">
                Filter Data
            </button>
        </form>
    </div>

    <!-- Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($karyawan as $rows)
        <div class="glass-card group rounded-4xl overflow-hidden hover:shadow-2xl hover:shadow-emerald-100/50 transition-all duration-500 border-white/60">
            <!-- Decorative Status Badge -->
            <div class="absolute -top-1 -right-1 z-10">
                <div class="bg-emerald-500 text-white px-6 py-2 rounded-bl-3xl font-black text-[10px] uppercase tracking-widest shadow-xl">
                    Active
                </div>
            </div>

            <div class="p-8 space-y-6">
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div class="h-16 w-16 rounded-3xl bg-slate-100 p-1 border-2 border-white shadow-sm overflow-hidden group-hover:scale-110 transition-transform duration-500">
                        @if($rows->foto_profile)
                            <img src="{{ asset('storage/karyawan/'.$rows->foto_profile) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-emerald-50 text-emerald-400 text-xl font-black">
                                {{ substr($rows->nama, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight leading-tight group-hover:text-emerald-600 transition-colors">{{ $rows->nama }}</h3>
                    <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">Tenaga Kerja Terverifikasi</p>
                </div>

                <!-- Employment Details -->
                <div class="grid grid-cols-1 gap-3">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 flex items-center gap-4 group/item hover:bg-white hover:border-emerald-100 transition-all duration-300">
                        <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500 border border-emerald-50 group-hover/item:scale-110 transition-transform">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Penempatan</p>
                            <p class="text-[13px] font-black text-slate-700 truncate max-w-[150px]">{{ $rows->nama_usaha }}</p>
                        </div>
                        <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" target="_blank" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                            <i class="bi bi-arrow-up-right-square text-lg"></i>
                        </a>
                    </div>
                    
                    <div class="p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100/30 flex items-center gap-4">
                        <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500 border border-emerald-50">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Tanggal Mulai</p>
                            <p class="text-[13px] font-black text-slate-700">{{ tgl_indo($rows->tanggal_interview) }} <span class="text-emerald-400 ml-1">Approved</span></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <a href="{{ url('administrator/detail_tenaga_kerja/'.$rows->id_karyawan) }}" 
                       class="flex-1 py-4 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-slate-200 transition-all transform hover:-translate-y-1 text-center">
                        <i class="bi bi-person-badge mr-1 text-sm"></i> Profil Lengkap
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(count($karyawan) == 0)
    <div class="glass-card p-20 rounded-[3rem] text-center space-y-6">
        <div class="h-24 w-24 bg-slate-50 rounded-4xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
            <i class="bi bi-people text-4xl text-slate-200"></i>
        </div>
        <div class="max-w-md mx-auto">
            <h3 class="text-2xl font-black text-slate-800">Personil Belum Tersedia</h3>
            <p class="text-slate-500 font-medium">Belum ada kandidat yang mencapai status 'Diterima' untuk ditampilkan di halaman ini.</p>
        </div>
    </div>
    @endif

</div>

@stop
