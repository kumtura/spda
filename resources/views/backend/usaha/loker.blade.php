@extends('mobile_layout')

@section('isi_menu')
<div class="px-6 py-4 space-y-6" x-data="{ showForm: false }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Lowongan Kerja</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Cari karyawan lokal banjar</p>
        </div>
        <button @click="showForm = !showForm" class="h-10 w-10 bg-[#00a6eb] text-white rounded-xl flex items-center justify-center shadow-lg shadow-[#00a6eb]/20">
            <i class="bi text-lg" :class="showForm ? 'bi-x-lg' : 'bi-plus-lg'"></i>
        </button>
    </div>

    @php
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        $tenagaKerja = collect();
        if($myUsaha) {
            $tenagaKerja = App\Models\Jadwal_Interview::join('tb_tenaga_kerja','tb_tenaga_kerja.id_tenaga_kerja','tb_jadwal_interview.id_karyawan')
                ->where('tb_jadwal_interview.id_usaha', $myUsaha->id_usaha)
                ->where('tb_jadwal_interview.aktif','1')
                ->orderBy('tb_jadwal_interview.id_jadwal_interview','desc')
                ->get();
        }
        $allCandidates = App\Models\Karyawan::where('aktif','1')->where('status','0')->orderBy('id_tenaga_kerja','desc')->get();
    @endphp

    <!-- Post New Job / Hire Form -->
    <div x-show="showForm" x-transition class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
            <i class="bi bi-megaphone text-[#00a6eb]"></i> Rekrut Tenaga Kerja
        </h3>
        @if($myUsaha)
        <form action="{{ url('administrator/submit_hire_tenaga') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="text_index_usaha_pilihan" value="{{ $myUsaha->id_usaha }}">
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Kandidat</label>
                <select name="text_index_karyawan_pilihan" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
                    <option value="">-- Pilih Tenaga Kerja --</option>
                    @foreach($allCandidates as $c)
                    <option value="{{ $c->id_tenaga_kerja }}">{{ $c->nama }} ({{ $c->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $c->umur }} thn)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal Interview</label>
                <input type="date" name="text_tanggal_interview" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Interview</label>
                <input type="time" name="text_jam_interview" value="09:00" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
                <i class="bi bi-send-fill mr-2"></i> Jadwalkan Interview
            </button>
        </form>
        @else
        <div class="bg-amber-50 rounded-2xl p-4 text-center">
            <p class="text-xs text-amber-600 font-bold">Akun usaha belum terdaftar.</p>
        </div>
        @endif
    </div>

    <!-- Active Recruitment List -->
    <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 px-1">Rekrutmen Aktif</h3>
        <div class="space-y-3">
            @forelse($tenagaKerja as $tk)
            <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-blue-50 text-[#00a6eb] flex items-center justify-center font-black text-sm uppercase">
                            {{ substr($tk->nama, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $tk->nama }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $tk->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} · {{ $tk->umur }} thn</p>
                        </div>
                    </div>
                    @if($tk->status_diterima == '1')
                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md uppercase tracking-widest border border-emerald-100">Diterima</span>
                    @else
                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-md uppercase tracking-widest border border-amber-100">Interview</span>
                    @endif
                </div>
                <div class="flex items-center gap-4 text-[10px] text-slate-400 font-medium">
                    <span><i class="bi bi-calendar-event mr-1"></i>{{ $tk->tanggal_interview }}</span>
                    <span><i class="bi bi-clock mr-1"></i>{{ $tk->jam }}</span>
                    @if($tk->jabatan)
                    <span><i class="bi bi-briefcase mr-1"></i>{{ $tk->jabatan }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="bg-slate-50 rounded-2xl p-6 text-center">
                <i class="bi bi-person-plus text-3xl text-slate-300 mb-2"></i>
                <p class="text-xs text-slate-400 font-medium">Belum ada rekrutmen aktif</p>
                <p class="text-[10px] text-slate-300 mt-1">Tap + untuk mulai merekrut</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
