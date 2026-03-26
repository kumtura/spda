@extends($base_layout)

@section('isi_menu')

<div class="space-y-8" x-data="{ 
    showApproveModal: false,
    selectedInterviewId: '',
    selectedKaryawanName: '',
    jabatan: '',

    openApprove(id, name) {
        this.selectedInterviewId = id;
        this.selectedKaryawanName = name;
        this.jabatan = '';
        this.showApproveModal = true;
    },

    async submitApprove() {
        if (!this.jabatan) return;
        
        try {
            const formData = new FormData();
            formData.append('edit_hidden_textfield', this.selectedInterviewId);
            formData.append('edit_text_title_new', this.jabatan);
            formData.append('_token', '{{ csrf_token() }}');

            const response = await fetch('{{ url('administrator/approve_data_karyawan') }}', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();
            if (result === 'success') {
                window.location = '{{ url('administrator/data_tenagakerja_approve') }}';
            }
        } catch (error) {
            console.error('Approval failed:', error);
        }
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Interview Karyawan</h1>
            <p class="text-slate-500 font-medium text-sm">Monitor dan kelola proses rekrutmen tenaga kerja aktif.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
            <div class="h-10 w-10 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <div class="pr-2">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Pipeline</p>
                <p class="text-lg font-black text-slate-800">{{ count($karyawan) }} Kandidat</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="glass-card p-4 rounded-4xl border-white/40 shadow-xl">
        <form method="get" action="{{ url('administrator/data_tenagakerja_interview') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari kandidat berdasarkan nama lengkap..." 
                       class="w-full bg-slate-100/50 border-none rounded-2xl pl-12 pr-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400">
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-indigo-100 transform hover:-translate-y-1">
                Filter Data
            </button>
        </form>
    </div>

    <!-- Grid View -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @foreach($karyawan as $rows)
        <div class="glass-card group rounded-4xl overflow-hidden hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-500 border-white/60">
            <div class="p-8 space-y-6">
                <!-- Header Card -->
                <div class="flex items-center justify-between">
                    <div class="h-16 w-16 rounded-3xl bg-slate-100 p-1 border-2 border-white shadow-sm overflow-hidden group-hover:scale-110 transition-transform duration-500">
                        @if($rows->foto_profile)
                            <img src="{{ asset('storage/karyawan/'.$rows->foto_profile) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-indigo-50 text-indigo-400 text-xl font-black">
                                {{ substr($rows->nama, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ url('administrator/detail_tenaga_kerja/'.$rows->id_karyawan) }}" target="_blank" 
                           class="h-10 w-10 flex items-center justify-center bg-white border border-slate-100 rounded-xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm" title="Lihat Profil">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </div>
                </div>

                <!-- Info -->
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight leading-tight group-hover:text-indigo-600 transition-colors">{{ $rows->nama }}</h3>
                    <p class="text-xs font-bold text-slate-400 tracking-widest uppercase">Kandidat Interview</p>
                </div>

                <!-- Recruitment Details -->
                <div class="grid grid-cols-1 gap-3">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 flex items-center gap-4">
                        <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500 border border-emerald-50">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Target Usaha</p>
                            <p class="text-[13px] font-black text-slate-700 truncate max-w-[150px]">{{ $rows->nama_usaha }}</p>
                        </div>
                        <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" target="_blank" class="text-indigo-400 hover:text-indigo-600 transition-colors">
                            <i class="bi bi-arrow-up-right-square text-lg"></i>
                        </a>
                    </div>
                    
                    <div class="p-4 bg-indigo-50/30 rounded-2xl border border-indigo-100/30 flex items-center gap-4">
                        <div class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-500 border border-indigo-50">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Jadwal Interview</p>
                            <p class="text-[13px] font-black text-slate-700">{{ tgl_indo($rows->tanggal_interview) }} <span class="text-indigo-400 ml-1">@ {{ $rows->jam }}</span></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button @click="openApprove('{{ $rows->id_jadwal_interview }}', '{{ $rows->nama }}')"
                            class="flex-1 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-100 transition-all transform hover:-translate-y-1">
                        <i class="bi bi-check-lg mr-1 text-sm"></i> Terima
                    </button>
                    <button class="flex-1 py-4 bg-rose-50 hover:bg-rose-100 text-rose-500 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all">
                        <i class="bi bi-x-lg mr-1 text-sm"></i> Tolak
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if(count($karyawan) == 0)
    <div class="glass-card p-20 rounded-[3rem] text-center space-y-6">
        <div class="h-24 w-24 bg-slate-50 rounded-4xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
            <i class="bi bi-person-slash text-4xl text-slate-200"></i>
        </div>
        <div class="max-w-md mx-auto">
            <h3 class="text-2xl font-black text-slate-800">Tidak Ada Data Interview</h3>
            <p class="text-slate-500 font-medium">Saat ini tidak ada kandidat dalam tahap interview atau pencarian Anda tidak membuahkan hasil.</p>
        </div>
        <a href="{{ url('administrator/data_tenagakerja_interview') }}" class="inline-block px-8 py-4 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-100 transition-all">
            Reset Pencarian
        </a>
    </div>
    @endif

    <!-- Acceptance Modal -->
    <template x-teleport="body">
        <div x-show="showApproveModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="glass-card w-full max-w-lg rounded-4xl overflow-hidden shadow-2xl relative border-white/20" @click.away="showApproveModal = false">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-linear-to-br from-emerald-50/50 to-white">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] mb-1 block">Konfirmasi Rekrutmen</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Terima Karyawan</h3>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-8">
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg" x-text="selectedKaryawanName.charAt(0)"></div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Membuka Posisi Untuk</p>
                            <p class="font-black text-slate-800 text-lg" x-text="selectedKaryawanName"></p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan / Posisi Kerja</label>
                            <input type="text" x-model="jabatan" required placeholder="Contoh: Staff Operasional"
                                   class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-300">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Catatan/Alasan (Opsional)</label>
                            <textarea name="alasan" placeholder="Berikan catatan singkat jika perlu..."
                                      class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-300 resize-none" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showApproveModal = false" 
                                class="px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 hover:bg-slate-100 transition-all">Batalkan</button>
                        <button type="button" @click="submitApprove()" :disabled="!jabatan"
                                :class="!jabatan ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-emerald-600 hover:bg-emerald-700 shadow-xl shadow-emerald-100 hover:-translate-y-1'"
                                class="px-10 py-4 text-white rounded-4xl font-black text-xs uppercase tracking-widest shadow-lg transition-all transform duration-300">
                                Konfirmasi & Aktifkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>

@stop
