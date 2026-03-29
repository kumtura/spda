@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ showLokerForm: false, showApplicantModal: false, selectedLoker: null, selectedApplicant: null }">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Tenaga Kerja</h1>
        <p class="text-slate-400 text-[10px] mt-1">Kelola rekrutmen karyawan</p>
    </div>

    @php
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        $tenagaKerjaAktif = collect();
        $myLokers = collect();
        $totalApplicants = 0;
        
        if($myUsaha) {
            $tenagaKerjaAktif = App\Models\Jadwal_Interview::join('tb_tenaga_kerja','tb_tenaga_kerja.id_tenaga_kerja','tb_jadwal_interview.id_karyawan')
                ->where('tb_jadwal_interview.id_usaha', $myUsaha->id_usaha)
                ->where('tb_jadwal_interview.status_diterima', '1')
                ->where('tb_jadwal_interview.aktif','1')
                ->orderBy('tb_jadwal_interview.id_jadwal_interview','desc')
                ->get();
                
            $myLokers = App\Models\Loker::where('id_usaha', $myUsaha->id_usaha)->orderBy('created_at', 'desc')->get();
            
            $totalApplicants = App\Models\Jadwal_Interview::where('id_usaha', $myUsaha->id_usaha)
                ->where('status_diterima', '0')
                ->where('aktif','1')
                ->count();
        }
    @endphp

    @if($myUsaha)
    <!-- Stats Card -->
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
                    <p class="font-bold">{{ $myLokers->where('status', 'Buka')->count() }} Posisi</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Proses Interview</p>
                    <p class="font-bold">{{ $totalApplicants }} Orang</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Lowongan Pekerjaan Section -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Lowongan Pekerjaan</h3>
            <button @click="showLokerForm = true" class="h-9 px-3 bg-[#00a6eb] text-white rounded-xl flex items-center gap-1.5 shadow-lg shadow-[#00a6eb]/20 transition-all active:scale-95 text-[10px] font-bold">
                <i class="bi bi-plus-lg"></i>
                <span>Buat Lowongan</span>
            </button>
        </div>

        <!-- Lowongan List -->
        @if($myLokers->count() > 0)
        <div class="space-y-2.5">
            @foreach($myLokers as $loker)
            @php
                $applicants = App\Models\Jadwal_Interview::where('id_loker', $loker->id_loker)->where('aktif', '1')->get();
                $hired = $applicants->where('status_diterima', '1')->count();
                $pending = $applicants->where('status_diterima', '0')->count();
            @endphp
            <a href="{{ route('administrator.usaha.loker.detail', $loker->id_loker) }}" class="block bg-white border border-slate-100 rounded-xl p-3.5 hover:border-slate-200 hover:shadow-sm transition-all">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 line-clamp-1 mb-1">{{ $loker->judul }}</p>
                        <p class="text-[10px] text-slate-400 line-clamp-2 mb-1.5">{{ $loker->deskripsi }}</p>
                        <p class="text-[9px] text-slate-400">{{ \Carbon\Carbon::parse($loker->created_at)->translatedFormat('d M Y') }}</p>
                    </div>
                    <span class="text-[8px] font-bold {{ $loker->status == 'Buka' ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-slate-400 bg-slate-50 border-slate-100' }} px-2 py-1 rounded border shrink-0">{{ $loker->status }}</span>
                </div>
                <div class="flex items-center gap-3 text-[10px]">
                    <div class="flex items-center gap-1.5">
                        <div class="h-6 w-6 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100">
                            <i class="bi bi-people text-[#00a6eb] text-xs"></i>
                        </div>
                        <span class="text-slate-600 font-bold">{{ $pending }} Pelamar</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="h-6 w-6 bg-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100">
                            <i class="bi bi-check-circle text-emerald-500 text-xs"></i>
                        </div>
                        <span class="text-slate-600 font-bold">{{ $hired }} Diterima</span>
                    </div>
                    <i class="bi bi-chevron-right text-slate-300 ml-auto"></i>
                </div>
            </a>
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

    <!-- Create Loker Modal -->
    <div x-show="showLokerForm" 
         x-cloak
         @click.self="showLokerForm = false"
         @keydown.escape.window="showLokerForm = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop 
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <!-- Header with Gradient -->
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showLokerForm = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Buat Lowongan</h3>
                    <p class="text-white/80 text-xs font-medium mt-1">Posting lowongan pekerjaan baru</p>
                </div>
            </div>

            <!-- Form Content -->
            @if($myUsaha)
            <form action="{{ url('administrator/usaha/loker/create') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $myUsaha->id_usaha }}">
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Informasi Lowongan</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Jelaskan posisi yang dibutuhkan dengan detail agar pelamar memahami persyaratan pekerjaan.</p>
                        </div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Judul Posisi</label>
                    <input type="text" name="judul" required placeholder="Contoh: Staff Admin, Kasir, Barista"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Deskripsi Pekerjaan</label>
                    <textarea name="deskripsi" rows="5" required placeholder="Jelaskan tugas, kualifikasi yang dibutuhkan, dan benefit yang ditawarkan..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all resize-none"></textarea>
                    <p class="text-[9px] text-slate-400 mt-1.5">Sertakan detail tugas, kualifikasi, dan benefit</p>
                </div>
                
                <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-megaphone-fill mr-2"></i> Posting Lowongan
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Accept Applicant Modal -->
    <div x-show="showApplicantModal" 
         x-cloak
         @click.self="showApplicantModal = false"
         @keydown.escape.window="showApplicantModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop 
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showApplicantModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Terima Pelamar</h3>
                    <p class="text-white/80 text-xs font-medium mt-1">Konfirmasi penerimaan tenaga kerja</p>
                </div>
            </div>

            <form action="{{ url('administrator/usaha/loker/accept') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_jadwal_interview" x-model="selectedApplicant">

                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-emerald-600 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Konfirmasi Penerimaan</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Pelamar akan menjadi tenaga kerja aktif setelah dikonfirmasi.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-check-circle mr-2"></i> Terima Pelamar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
