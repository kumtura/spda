@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-6 pb-24 space-y-6" x-data="{ 
    showDetailModal: false, 
    showInterviewModal: false, 
    showAcceptModal: false, 
    showRejectModal: false, 
    selectedApplicant: null,
    selectedCandidate: null 
}">

    <!-- Back -->
    <a href="{{ url('administrator/usaha/loker') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-medium transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Company Header -->
    <div class="flex items-start gap-4">
        @if($loker->usaha && $loker->usaha->detail)
        <div class="h-16 w-16 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
            @if($loker->usaha->detail->logo)
                @php
                    $logoPath = file_exists(public_path('usaha/icon/'.$loker->usaha->detail->logo)) 
                        ? 'usaha/icon/'.$loker->usaha->detail->logo 
                        : 'storage/usaha/icon/'.$loker->usaha->detail->logo;
                @endphp
                <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
            @else
                <i class="bi bi-building text-slate-300 text-2xl"></i>
            @endif
        </div>
        @endif
        <div class="flex-1">
            <h1 class="text-lg font-bold text-slate-800 leading-tight mb-1">{{ $loker->judul }}</h1>
            <p class="text-xs text-slate-500 mb-2">{{ $loker->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                <span class="flex items-center gap-1">
                    <i class="bi bi-clock text-[9px]"></i>
                    {{ \Carbon\Carbon::parse($loker->created_at)->diffForHumans() }}
                </span>
                <span>•</span>
                <span class="text-[8px] font-medium {{ $loker->status == 'Buka' ? 'text-slate-700 bg-slate-100 border-slate-200' : 'text-slate-400 bg-slate-50 border-slate-100' }} px-2 py-0.5 rounded border">{{ $loker->status }}</span>
            </div>
        </div>
    </div>

    <!-- Job Description -->
    <div>
        <h3 class="text-sm font-semibold text-slate-800 mb-3">Deskripsi Pekerjaan</h3>
        <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
            <p class="whitespace-pre-line text-xs">{{ $loker->deskripsi }}</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-2">
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            <div class="flex items-center gap-1.5 mb-1">
                <div class="h-6 w-6 bg-white rounded-lg flex items-center justify-center border border-slate-300">
                    <i class="bi bi-people text-slate-600 text-xs"></i>
                </div>
                <p class="text-[8px] font-medium text-slate-500 uppercase">Pelamar</p>
            </div>
            <p class="text-xl font-bold text-slate-800">{{ $applicants->where('status_interview', '!=', '1')->count() }}</p>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            <div class="flex items-center gap-1.5 mb-1">
                <div class="h-6 w-6 bg-white rounded-lg flex items-center justify-center border border-slate-300">
                    <i class="bi bi-calendar-check text-slate-600 text-xs"></i>
                </div>
                <p class="text-[8px] font-medium text-slate-500 uppercase">Interview</p>
            </div>
            <p class="text-xl font-bold text-slate-800">{{ $applicants->where('status_interview', '1')->where('status_diterima', '0')->count() }}</p>
        </div>
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
            <div class="flex items-center gap-1.5 mb-1">
                <div class="h-6 w-6 bg-white rounded-lg flex items-center justify-center border border-slate-300">
                    <i class="bi bi-check-circle text-slate-600 text-xs"></i>
                </div>
                <p class="text-[8px] font-medium text-slate-500 uppercase">Diterima</p>
            </div>
            <p class="text-xl font-bold text-slate-800">{{ $applicants->where('status_diterima', '1')->count() }}</p>
        </div>
    </div>

    <!-- Applicants Section -->
    <div>
        <h3 class="text-sm font-semibold text-slate-800 mb-3">Daftar Pelamar</h3>
        
        @php
            $pendingApplicants = $applicants->where('status_interview', '!=', '1')->where('status_diterima', '0');
            $interviewApplicants = $applicants->where('status_interview', '1')->where('status_diterima', '0');
            $acceptedApplicants = $applicants->where('status_diterima', '1');
        @endphp

        @if($pendingApplicants->count() > 0)
        <div class="space-y-2.5 mb-4">
            <p class="text-[9px] font-medium text-slate-500 uppercase">Pelamar Baru:</p>
            @foreach($pendingApplicants as $app)
            @php
                $candidate = App\Models\Karyawan::find($app->id_karyawan);
            @endphp
            @if($candidate)
            <div @click="selectedApplicant = {{ $app->id_jadwal_interview }}; selectedCandidate = {{ json_encode($candidate) }}; showDetailModal = true" 
                 class="bg-white border border-slate-100 rounded-xl p-3.5 hover:border-[#00a6eb] transition-colors cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-slate-50 text-slate-600 flex items-center justify-center font-semibold text-sm border border-slate-200">
                        {{ substr($candidate->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 mb-0.5">{{ $candidate->nama }}</p>
                        <p class="text-[10px] text-slate-500">{{ $candidate->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki' }} · {{ $candidate->umur }} tahun</p>
                    </div>
                    <i class="bi bi-chevron-right text-slate-400"></i>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if($interviewApplicants->count() > 0)
        <div class="space-y-2.5 mb-4">
            <p class="text-[9px] font-medium text-slate-500 uppercase">Sedang Interview:</p>
            @foreach($interviewApplicants as $app)
            @php
                $candidate = App\Models\Karyawan::find($app->id_karyawan);
            @endphp
            @if($candidate)
            <div @click="selectedApplicant = {{ $app->id_jadwal_interview }}; selectedCandidate = {{ json_encode($candidate) }}; showDetailModal = true" 
                 class="bg-amber-50 border border-amber-100 rounded-xl p-3.5 hover:border-amber-200 transition-colors cursor-pointer">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-white text-amber-600 flex items-center justify-center font-semibold text-sm border border-amber-200">
                        {{ substr($candidate->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 mb-0.5">{{ $candidate->nama }}</p>
                        <p class="text-[10px] text-amber-600 flex items-center gap-1">
                            <i class="bi bi-clock-history"></i> Sedang Interview
                        </p>
                    </div>
                    <i class="bi bi-chevron-right text-slate-400"></i>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if($acceptedApplicants->count() > 0)
        <div class="space-y-2.5">
            <p class="text-[9px] font-medium text-slate-500 uppercase">Diterima:</p>
            @foreach($acceptedApplicants as $app)
            @php
                $candidate = App\Models\Karyawan::find($app->id_karyawan);
            @endphp
            @if($candidate)
            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3.5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-lg bg-white text-emerald-600 flex items-center justify-center font-medium text-sm border border-emerald-200">
                        {{ substr($candidate->nama, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 mb-0.5">{{ $candidate->nama }}</p>
                        <p class="text-[10px] text-emerald-600 flex items-center gap-1">
                            <i class="bi bi-check-circle"></i> Diterima
                        </p>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif

        @if($applicants->count() == 0)
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-8 text-center">
            <i class="bi bi-inbox text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada pelamar</p>
            <p class="text-[10px] text-slate-400 mt-1">Tunggu hingga ada yang melamar posisi ini</p>
        </div>
        @endif
    </div>

    <!-- Detail Modal -->
    <div x-show="showDetailModal" 
         x-cloak
         @click.self="showDetailModal = false"
         @keydown.escape.window="showDetailModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop 
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden sticky top-0 z-10">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showDetailModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Detail Pelamar</h3>
                    <p class="text-white/80 text-xs mt-1">Informasi lengkap kandidat</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                @foreach($applicants as $app)
                @php
                    $candidate = App\Models\Karyawan::find($app->id_karyawan);
                @endphp
                @if($candidate)
                <template x-if="selectedApplicant === {{ $app->id_jadwal_interview }}">
                    <div>
                        <!-- Candidate Info -->
                        <div class="flex items-start gap-3 mb-4">
                            <div class="h-16 w-16 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center font-bold text-xl border border-slate-200">
                                {{ substr($candidate->nama, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-800 mb-1">{{ $candidate->nama }}</p>
                                <p class="text-xs text-slate-500 mb-1">{{ $candidate->jenis_kelamin == 'P' ? 'Perempuan' : 'Laki-laki' }} · {{ $candidate->umur }} tahun</p>
                                @if($candidate->alamat)
                                <p class="text-[10px] text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-geo-alt"></i> {{ $candidate->alamat }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="mb-4 bg-slate-50 rounded-xl p-3 border border-slate-200">
                            <p class="text-[10px] font-medium text-slate-500 uppercase mb-2">Kontak:</p>
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-xs text-slate-700">
                                    <i class="bi bi-telephone text-slate-400"></i>
                                    <span>{{ $candidate->no_telp ?? $candidate->no_wa ?? '-' }}</span>
                                </div>
                                @if($candidate->email_karyawan)
                                <div class="flex items-center gap-2 text-xs text-slate-700">
                                    <i class="bi bi-envelope text-slate-400"></i>
                                    <span>{{ $candidate->email_karyawan }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Uploaded Documents -->
                        @if($app->dokumen_lamaran)
                        @php
                            $documents = json_decode($app->dokumen_lamaran, true);
                        @endphp
                        @if(is_array($documents) && count($documents) > 0)
                        <div class="mb-4">
                            <p class="text-[10px] font-medium text-slate-500 uppercase mb-2">Dokumen:</p>
                            <div class="space-y-2">
                                @foreach($documents as $doc)
                                <a href="{{ asset($doc) }}" target="_blank" class="flex items-center gap-2 bg-slate-50 rounded-lg p-2.5 border border-slate-200 hover:border-[#00a6eb] transition-colors">
                                    <i class="bi bi-file-earmark-pdf text-slate-400 text-lg"></i>
                                    <span class="text-[10px] text-slate-600 flex-1 truncate">{{ basename($doc) }}</span>
                                    <i class="bi bi-download text-slate-400"></i>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif

                        <!-- Action Buttons -->
                        @if($app->status_interview != '1')
                        <button @click="showDetailModal = false; showInterviewModal = true" 
                                class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm mb-2">
                            <i class="bi bi-calendar-check mr-2"></i> Undang Interview
                        </button>
                        @else
                        <div class="flex items-center gap-2">
                            <button @click="showDetailModal = false; showAcceptModal = true" 
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                                <i class="bi bi-check-circle mr-1"></i> Terima
                            </button>
                            <button @click="showDetailModal = false; showRejectModal = true" 
                                    class="flex-1 bg-slate-600 hover:bg-slate-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                                <i class="bi bi-x-circle mr-1"></i> Tolak
                            </button>
                        </div>
                        @endif
                    </div>
                </template>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <!-- Interview Modal -->
    <div x-show="showInterviewModal" 
         x-cloak
         @click.self="showInterviewModal = false"
         @keydown.escape.window="showInterviewModal = false"
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
            
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showInterviewModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Undang Interview</h3>
                    <p class="text-white/80 text-xs mt-1">Pindahkan ke tahap interview</p>
                </div>
            </div>

            <form action="{{ route('administrator.usaha.loker.interview') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_jadwal_interview" x-model="selectedApplicant">

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Informasi</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Pelamar akan dipindahkan ke tahap interview. Hubungi kandidat melalui kontak yang tersedia untuk mengatur jadwal interview.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-calendar-check mr-2"></i> Undang Interview
                </button>
            </form>
        </div>
    </div>

    <!-- Accept Modal -->
    <div x-show="showAcceptModal" 
         x-cloak
         @click.self="showAcceptModal = false"
         @keydown.escape.window="showAcceptModal = false"
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
            
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showAcceptModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Terima Pelamar</h3>
                    <p class="text-white/80 text-xs mt-1">Konfirmasi penerimaan tenaga kerja</p>
                </div>
            </div>

            <form action="{{ route('administrator.usaha.loker.accept') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_jadwal_interview" x-model="selectedApplicant">

                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-check-circle text-emerald-600 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Konfirmasi Penerimaan</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Pelamar akan menjadi tenaga kerja aktif dan dapat mulai bekerja.</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-check-circle mr-2"></i> Terima Pelamar
                </button>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="showRejectModal" 
         x-cloak
         @click.self="showRejectModal = false"
         @keydown.escape.window="showRejectModal = false"
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
            
            <div class="bg-gradient-to-br from-slate-600 to-slate-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showRejectModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Tolak Pelamar</h3>
                    <p class="text-white/80 text-xs mt-1">Berikan alasan penolakan</p>
                </div>
            </div>

            <form action="{{ route('administrator.usaha.loker.reject') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_jadwal_interview" x-model="selectedApplicant">

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-slate-500 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-medium text-slate-700 mb-1">Informasi Penolakan</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Berikan alasan penolakan agar pelamar memahami keputusan Anda.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-medium text-slate-600 mb-1.5">Alasan Penolakan (Opsional)</label>
                    <textarea name="alasan" rows="3" placeholder="Contoh: Kualifikasi belum sesuai, posisi sudah terisi, dll"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-slate-600 hover:bg-slate-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-x-circle mr-2"></i> Tolak Pelamar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
