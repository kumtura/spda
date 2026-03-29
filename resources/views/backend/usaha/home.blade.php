@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <!-- Page Title -->
    <div>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Dashboard</h2>
        <p class="text-[10px] text-slate-400 mt-1">{{ Auth::user()->name }}</p>
    </div>

    @php
        $currentMonth = date('m');
        $currentYear = date('Y');
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        
        $totalKontribusi = 0;
        $paidThisMonth = null;
        $tenagaKerjaCount = 0;
        
        if($myUsaha) {
            $totalKontribusi = App\Models\Danapunia::where('id_usaha', $myUsaha->id_usaha)
                ->where('aktif','1')
                ->where('status_pembayaran', 'completed')
                ->sum('jumlah_dana');
                
            $paidThisMonth = App\Models\Danapunia::where('id_usaha', $myUsaha->id_usaha)
                ->where('aktif','1')
                ->where('status_pembayaran', 'completed')
                ->where('bulan_punia', (int)$currentMonth)
                ->where('tahun_punia', $currentYear)
                ->first();
                
            $tenagaKerjaCount = App\Models\Jadwal_Interview::where('id_usaha', $myUsaha->id_usaha)
                ->where('status_diterima', '1')
                ->where('aktif', '1')
                ->count();
        }
    @endphp

    <!-- Stats Card - General -->
    @if($myUsaha)
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-graph-up text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $paidThisMonth ? 'Lunas' : 'Belum Bayar' }}</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Kontribusi Punia</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalKontribusi, 0, ',', '.') }}</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Punia Bulan Ini</p>
                    <p class="font-bold">{{ $paidThisMonth ? 'Lunas' : 'Belum Bayar' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Tenaga Kerja</p>
                    <p class="font-bold">{{ $tenagaKerjaCount }} Orang</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ url('administrator/usaha/punia') }}" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-3 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-wallet2 text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-0.5">Punia</h3>
            <p class="text-slate-400 text-[10px]">Kartu punia bulanan</p>
        </a>
        <a href="{{ url('administrator/usaha/loker') }}" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center mb-3 transition-colors group-hover:bg-[#00a6eb] group-hover:text-white border border-slate-100">
                <i class="bi bi-people text-xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-0.5">Tenaga Kerja</h3>
            <p class="text-slate-400 text-[10px]">Kelola karyawan</p>
        </a>
    </div>

    <!-- Program Donasi Section -->
    @php
        $programs = App\Models\ProgramDonasi::where('aktif', '1')->orderBy('id_program_donasi', 'desc')->take(5)->get();
    @endphp
    @if($programs->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-slate-800">Program Donasi</h4>
            <a href="{{ url('administrator/usaha/donasi') }}" class="text-[10px] font-bold text-[#00a6eb]">Lihat Semua</a>
        </div>
        <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
            @foreach($programs as $prog)
            <a href="{{ url('administrator/usaha/donasi/detail/'.$prog->id_program_donasi) }}" class="flex-shrink-0 w-64 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="h-32 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                    @if($prog->foto)
                        <img src="{{ asset('storage/program_donasi/'.$prog->foto) }}" class="w-full h-full object-cover" alt="{{ $prog->nama_program }}">
                    @else
                        <i class="bi bi-image text-3xl text-slate-200"></i>
                    @endif
                    @if($prog->kategori)
                    <span class="absolute top-2 right-2 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded-sm uppercase">{{ $prog->kategori->nama_kategori }}</span>
                    @endif
                </div>
                <div class="p-3">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $prog->nama_program }}</h4>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[9px] text-slate-400 mb-0.5">Terkumpul</p>
                            <p class="text-[11px] font-bold text-[#00a6eb]">Rp {{ number_format($prog->terkumpul, 0, ',', '.') }}</p>
                        </div>
                        <span class="bg-slate-50 text-slate-600 group-hover:bg-[#00a6eb] group-hover:text-white transition-colors px-3 py-1.5 rounded-full text-[10px] font-bold">Donasi</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Berita Desa Section -->
    @php
        $berita = App\Models\Berita::where('aktif', '1')->orderBy('id_berita', 'desc')->take(5)->get();
    @endphp
    @if($berita->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-slate-800">Berita Terkini</h4>
            <a href="{{ url('administrator/usaha/berita') }}" class="text-[10px] font-bold text-[#00a6eb]">Lihat Semua</a>
        </div>
        <div class="flex gap-3 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
            @foreach($berita as $news)
            <a href="{{ url('administrator/usaha/berita/detail/'.$news->id_berita) }}" class="flex-shrink-0 w-72 bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden hover:shadow-md transition-all group">
                <div class="h-40 bg-slate-50 relative flex items-center justify-center overflow-hidden">
                    @if($news->foto)
                        <img src="{{ asset('storage/berita/foto/'.$news->foto) }}" class="w-full h-full object-cover" alt="{{ $news->judul }}">
                    @else
                        <i class="bi bi-newspaper text-3xl text-slate-200"></i>
                    @endif
                </div>
                <div class="p-3">
                    <h4 class="text-xs font-bold text-slate-800 leading-tight mb-2 group-hover:text-[#00a6eb] transition-colors line-clamp-2">{{ $news->judul }}</h4>
                    <p class="text-[10px] text-slate-500 line-clamp-2 mb-2">{{ strip_tags($news->isi_berita) }}</p>
                    <div class="flex items-center justify-between text-[9px] text-slate-400">
                        <span><i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('d M Y') }}</span>
                        <span class="text-[#00a6eb] font-bold">Baca →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Loker yang Dibuat (Preview for Unit Usaha) -->
    @if($myUsaha)
    @php
        $myLokers = App\Models\Loker::where('id_usaha', $myUsaha->id_usaha)->orderBy('created_at', 'desc')->take(3)->get();
    @endphp
    @if($myLokers->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-sm font-bold text-slate-800">Lowongan Anda</h4>
            <a href="{{ url('administrator/usaha/loker') }}" class="text-[10px] font-bold text-[#00a6eb]">Kelola</a>
        </div>
        <div class="space-y-2.5">
            @foreach($myLokers as $loker)
            @php
                $applicants = App\Models\Jadwal_Interview::where('id_loker', $loker->id_loker)->where('aktif', '1')->count();
                $hired = App\Models\Jadwal_Interview::where('id_loker', $loker->id_loker)->where('status_diterima', '1')->where('aktif', '1')->count();
            @endphp
            <div class="bg-white rounded-xl border border-slate-100 p-3.5">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 line-clamp-1 mb-1">{{ $loker->posisi ?? 'Posisi Tidak Disebutkan' }}</p>
                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($loker->created_at)->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-[10px]">
                    <div class="flex items-center gap-1.5">
                        <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                            <i class="bi bi-people text-[#00a6eb] text-xs"></i>
                        </div>
                        <span class="text-slate-600 font-bold">{{ $applicants }} Pelamar</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="h-6 w-6 bg-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100">
                            <i class="bi bi-check-circle text-emerald-500 text-xs"></i>
                        </div>
                        <span class="text-slate-600 font-bold">{{ $hired }} Diterima</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif
</div>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
