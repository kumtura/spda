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
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Wawancara Tenaga Kerja</h1>
            <p class="text-slate-500 font-medium text-sm">Monitor dan kelola proses seleksi kandidat tenaga kerja.</p>
        </div>
        <div class="glass-card px-5 py-2.5 flex items-center gap-3 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-person-check-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Pipeline</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($karyawan) }} Kandidat</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form method="get" action="{{ url('administrator/data_tenagakerja_interview') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="nama" value="{{ request('nama') }}" placeholder="Cari nama kandidat..." 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-12 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
            </div>
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-blue-100 transform hover:-translate-y-0.5">
                Filter Kandidat
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
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kandidat</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penempatan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Jadwal Interview</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi Manajemen</th>
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
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">{{ $rows->no_wa }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-7 w-7 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-[10px] overflow-hidden">
                                    @if($rows->logo)
                                        <img src="{{ asset('storage/usaha/icon/'.$rows->logo) }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="bi bi-briefcase"></i>
                                    @endif
                                </div>
                                <span class="text-[11px] font-black text-slate-700 leading-none truncate max-w-[150px]">{{ $rows->nama_usaha }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <p class="text-[11px] font-black text-slate-700 leading-none mb-1">{{ tgl_indo($rows->tanggal_interview) }}</p>
                            <p class="text-[10px] text-primary-light font-bold uppercase tracking-widest">{{ $rows->jam }} WITA</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button @click="openApprove('{{ $rows->id_jadwal_interview }}', '{{ $rows->nama }}')"
                                        class="h-8 px-3 flex items-center gap-1.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                    <i class="bi bi-check-lg text-xs"></i> Terima
                                </button>
                                <a href="{{ url('administrator/detail_tenaga_kerja/'.$rows->id_karyawan) }}" 
                                   class="h-8 w-8 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(count($karyawan) == 0)
        <div class="p-16 text-center space-y-3">
            <div class="h-14 w-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto border-2 border-dashed border-slate-200">
                <i class="bi bi-person-slash text-2xl text-slate-200"></i>
            </div>
            <div class="max-w-xs mx-auto">
                <p class="text-slate-500 font-bold text-xs tracking-tight">Tidak ada jadwal interview aktif.</p>
                <p class="text-slate-400 font-medium text-[10px] mt-1 italic">Silakan periksa kembali filter pencarian Anda atau tambahkan jadwal baru.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Acceptance Modal -->
    <template x-teleport="body">
        <div x-show="showApproveModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showApproveModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-emerald-500 text-white flex items-center justify-center shadow-lg transform -rotate-2">
                            <i class="bi bi-shield-check text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-0.5 block">Konfirmasi Rekrutmen</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Terima Karyawan</h3>
                        </div>
                    </div>
                    <button @click="showApproveModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center text-white font-black text-sm shadow-md" x-text="selectedKaryawanName.charAt(0)"></div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Mendaftarkan</p>
                            <p class="font-black text-slate-800 text-sm" x-text="selectedKaryawanName"></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan / Posisi Kerja</label>
                            <input type="text" x-model="jabatan" required placeholder="Contoh: Staff Operasional"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showApproveModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="button" @click="submitApprove()" :disabled="!jabatan"
                                :class="!jabatan ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-slate-900 hover:bg-emerald-600 shadow-lg hover:-translate-y-0.5'"
                                class="px-8 py-2.5 text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all transform duration-300">
                                Konfirmasi & Aktifkan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

</div>

@stop
