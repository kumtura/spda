@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ showForm: false }">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Tenaga Kerja</h1>
        <p class="text-slate-400 text-[10px] mt-1">Kelola rekrutmen karyawan</p>
    </div>

    @php
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        $tenagaKerja = collect();
        $tenagaKerjaAktif = collect();
        $myLokers = collect();
        
        if($myUsaha) {
            $tenagaKerja = App\Models\Jadwal_Interview::join('tb_tenaga_kerja','tb_tenaga_kerja.id_tenaga_kerja','tb_jadwal_interview.id_karyawan')
                ->where('tb_jadwal_interview.id_usaha', $myUsaha->id_usaha)
                ->where('tb_jadwal_interview.aktif','1')
                ->orderBy('tb_jadwal_interview.id_jadwal_interview','desc')
                ->get();
                
            $tenagaKerjaAktif = App\Models\Jadwal_Interview::join('tb_tenaga_kerja','tb_tenaga_kerja.id_tenaga_kerja','tb_jadwal_interview.id_karyawan')
                ->where('tb_jadwal_interview.id_usaha', $myUsaha->id_usaha)
                ->where('tb_jadwal_interview.status_diterima', '1')
                ->where('tb_jadwal_interview.aktif','1')
                ->orderBy('tb_jadwal_interview.id_jadwal_interview','desc')
                ->get();
                
            $myLokers = App\Models\Loker::where('id_usaha', $myUsaha->id_usaha)->orderBy('created_at', 'desc')->get();
        }
        $allCandidates = App\Models\Karyawan::where('aktif','1')->where('status','0')->orderBy('id_tenaga_kerja','desc')->get();
    @endphp

    @if($myUsaha)
    <!-- Stats Card - Tenaga Kerja Info -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-people text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Rekrutmen</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Tenaga Kerja Aktif</p>
            <h3 class="text-3xl font-black mb-3">{{ $tenagaKerjaAktif->count() }} Orang</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Lowongan Aktif</p>
                    <p class="font-bold">{{ $myLokers->count() }} Posisi</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Proses Interview</p>
                    <p class="font-bold">{{ $tenagaKerja->where('status_diterima', '0')->count() }} Orang</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Lowongan Pekerjaan Section -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Lowongan Pekerjaan</h3>
            <button @click="showForm = !showForm" class="h-9 px-3 bg-[#00a6eb] text-white rounded-xl flex items-center gap-1.5 shadow-lg shadow-[#00a6eb]/20 transition-all active:scale-95 text-[10px] font-bold">
                <i class="bi" :class="showForm ? 'bi-x-lg' : 'bi-plus-lg'"></i>
                <span x-text="showForm ? 'Tutup' : 'Buat Lowongan'"></span>
            </button>
        </div>

        <!-- Post New Job Form -->
        <div x-show="showForm" x-transition class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm space-y-4 mb-4">
            <h4 class="text-xs font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-megaphone text-[#00a6eb]"></i> Buat Lowongan Baru
            </h4>
            @if($myUsaha)
            <form action="{{ url('administrator/submit_hire_tenaga') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="text_index_usaha_pilihan" value="{{ $myUsaha->id_usaha }}">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Pilih Kandidat</label>
                    <select name="text_index_karyawan_pilihan" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
                        <option value="">-- Pilih Tenaga Kerja --</option>
                        @foreach($allCandidates as $c)
                        <option value="{{ $c->id_tenaga_kerja }}">{{ $c->nama }} ({{ $c->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $c->umur }} thn)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Tanggal Interview</label>
                    <input type="date" name="text_tanggal_interview" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Jam Interview</label>
                    <input type="time" name="text_jam_interview" value="09:00" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
                </div>
                <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-send-fill mr-2"></i> Jadwalkan Interview
                </button>
            </form>
            @else
            <div class="bg-amber-50 rounded-xl p-4 text-center border border-amber-100">
                <p class="text-xs text-amber-600 font-bold">Akun usaha belum terdaftar.</p>
            </div>
            @endif
        </div>

        <!-- Lowongan List -->
        @if($myLokers->count() > 0)
        <div class="space-y-2.5">
            @foreach($myLokers as $loker)
            @php
                $applicants = App\Models\Jadwal_Interview::where('id_loker', $loker->id_loker)->where('aktif', '1')->count();
                $hired = App\Models\Jadwal_Interview::where('id_loker', $loker->id_loker)->where('status_diterima', '1')->where('aktif', '1')->count();
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl p-3.5">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 line-clamp-1 mb-1">{{ $loker->posisi ?? $loker->judul ?? 'Posisi Tidak Disebutkan' }}</p>
                        <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($loker->created_at)->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="text-[8px] font-bold {{ $loker->status == 'Buka' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-slate-400 bg-slate-50 border-slate-100' }} px-2 py-1 rounded border shrink-0">{{ $loker->status }}</span>
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
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-megaphone text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada lowongan pekerjaan</p>
            <p class="text-[10px] text-slate-400 mt-1">Klik tombol "Buat Lowongan" untuk memulai</p>
        </div>
        @endif
    </div>

    <!-- Tenaga Kerja Aktif Section -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Tenaga Kerja Aktif</h3>
        @if($tenagaKerjaAktif->count() > 0)
        <div class="space-y-2.5">
            @foreach($tenagaKerjaAktif as $tk)
            <div class="bg-white border border-slate-100 rounded-xl p-3.5">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center font-black text-sm border border-slate-100">
                        {{ substr($tk->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5">{{ $tk->nama }}</p>
                        <p class="text-[10px] text-slate-400">{{ $tk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $tk->umur }} thn</p>
                        @if($tk->jabatan)
                        <span class="inline-block mt-1.5 text-[8px] font-bold text-[#00a6eb] bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $tk->jabatan }}</span>
                        @endif
                    </div>
                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 shrink-0">Aktif</span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-person-plus text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada tenaga kerja aktif</p>
            <p class="text-[10px] text-slate-400 mt-1">Rekrut karyawan untuk memulai</p>
        </div>
        @endif
    </div>
</div>
@endsection
