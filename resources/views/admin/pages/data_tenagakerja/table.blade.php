@extends($base_layout)

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showAddModal: false, 
    showInterviewModal: false,
    pencarianUsaha: '',
    searchResult: [],
    selectedKaryawanName: '',
    selectedKaryawanId: '',
    selectedUsahaId: '',
    selectedUsahaName: '',

    init() {
        if (typeof jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
            $('#ikantable').DataTable({
                responsive: true,
                language: {
                    search: '_INPUT_',
                    searchPlaceholder: 'Cari data...',
                    lengthMenu: '_MENU_',
                },
                drawCallback: function() {
                    $('.dataTables_filter input').addClass('bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 text-xs focus:ring-4 focus:ring-primary-light/5 w-64 transition-all outline-none');
                    $('.dataTables_length select').addClass('bg-slate-50 border border-slate-200 rounded-lg px-2 py-1.5 text-xs focus:ring-4 focus:ring-primary-light/5 transition-all ml-2 mr-2 outline-none');
                }
            });
        }
    },

    openInterview(id, nama) {
        this.selectedKaryawanId = id;
        this.selectedKaryawanName = nama;
        this.selectedUsahaId = '';
        this.selectedUsahaName = '';
        this.searchResult = [];
        this.pencarianUsaha = '';
        this.showInterviewModal = true;
    },

    async searchUsaha() {
        if (!this.pencarianUsaha) return;
        try {
            const response = await fetch(`${window.url_menu_apis}/post_search_usaha`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: `val=${encodeURIComponent(this.pencarianUsaha)}`
            });
            const result = await response.json();
            this.searchResult = result.data;
        } catch (error) {
            console.error('Search failed:', error);
        }
    },

    pickUsaha(id, name) {
        this.selectedUsahaId = id;
        this.selectedUsahaName = name;
    }
}">

    <!-- Header & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Tenaga Kerja</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola dan pantau seluruh basis data tenaga kerja sistem.</p>
        </div>
        <button @click="showAddModal = true" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Karyawan
        </button>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-people-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Karyawan</p>
                <p class="text-lg font-black text-slate-800">{{ count($karyawan) }}</p>
            </div>
        </div>
        
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200 border-l-4 border-l-amber-400">
            <div class="h-10 w-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-person-badge-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Butuh Skill</p>
                <p class="text-lg font-black text-slate-800">{{ count($tenaga_kerja) }}</p>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-4 sm:p-6">
            <div class="overflow-x-auto">
                <table id="ikantable" class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Karyawan</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Umur</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gender</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($karyawan as $index => $rows)
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-400 text-xs">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black uppercase text-xs overflow-hidden">
                                        @if($rows->foto)
                                            <img src="{{ asset('storage/karyawan/foto/'.$rows->foto) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($rows->nama, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-slate-800 tracking-tight leading-none mb-1">{{ $rows->nama }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase">ID: #{{ str_pad($rows->id_tenaga_kerja, 4, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs font-medium text-slate-500 truncate max-w-[150px]" title="{{ $rows->alamat }}">{{ $rows->alamat }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-black border border-slate-200">{{ $rows->umur }} Thn</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5">
                                    <i class="bi {{ $rows->jenis_kelamin == 1 ? 'bi-gender-male text-blue-500' : 'bi-gender-female text-rose-500' }}"></i>
                                    <span class="text-slate-500 font-bold text-[10px] uppercase">{{ Config::get('myconfig.jk_tenaga')[$rows->jenis_kelamin] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $status = $rows->status;
                                    $statusLabel = Config::get('myconfig.status_tenaga')[$status];
                                    $statusClass = match($status) {
                                        0 => 'bg-amber-50 text-amber-600 border-amber-100',
                                        1 => 'bg-blue-50 text-primary-light border-blue-100',
                                        2 => 'bg-emerald-50 text-emerald-600 border-emerald-110',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex px-2 py-1 rounded text-[9px] font-black uppercase tracking-tight border ${statusClass}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ url('administrator/detail_tenaga_kerja/'.$rows->id_tenaga_kerja) }}" 
                                       class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </a>
                                    <button @click="openInterview('{{ $rows->id_tenaga_kerja }}', '{{ $rows->nama }}')"
                                            class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-emerald-500 hover:border-emerald-100 transition-all shadow-sm">
                                        <i class="bi bi-calendar-check-fill"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-8 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-cloak>
            
            <div class="bg-white w-full max-w-4xl rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block">Administrasi Personalia</span>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Tambah Tenaga Kerja Baru</h3>
                    </div>
                    <button @click="showAddModal = false" class="h-10 w-10 flex items-center justify-center hover:bg-slate-200 rounded-xl transition-all text-slate-400">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/submit_post_add_tenagakerja') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div x-data="{ tab: 'personal' }">
                        <div class="flex gap-6 border-b border-slate-100 mb-6 overflow-x-auto no-scrollbar">
                            <button type="button" @click="tab = 'personal'" 
                                    :class="tab === 'personal' ? 'text-primary-light border-primary-light' : 'text-slate-400 border-transparent'"
                                    class="pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all">Details</button>
                            <button type="button" @click="tab = 'skill'" 
                                    :class="tab === 'skill' ? 'text-primary-light border-primary-light' : 'text-slate-400 border-transparent'"
                                    class="pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all">Skills</button>
                            <button type="button" @click="tab = 'sosmed'" 
                                    :class="tab === 'sosmed' ? 'text-primary-light border-primary-light' : 'text-slate-400 border-transparent'"
                                    class="pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 transition-all">Links</button>
                        </div>

                        <div class="space-y-6 min-h-[350px]">
                            <div x-show="tab === 'personal'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                                    <input type="text" name="text_title_new" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar</label>
                                    <select name="cmb_banjar_karyawan" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                        <option value="">Pilih Banjar</option>
                                        @foreach($banjar as $b) <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option> @endforeach
                                    </select>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email</label>
                                    <input type="email" name="text_email_usaha_new" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">WhatsApp</label>
                                    <input type="text" name="text_telpkantor_new" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jenis Kelamin</label>
                                    <div class="flex gap-6 py-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="rdb_jk_karyawan" value="1" checked class="w-4 h-4 text-primary-light">
                                            <span class="text-xs font-bold text-slate-600">Pria</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="rdb_jk_karyawan" value="0" class="w-4 h-4 text-rose-500">
                                            <span class="text-xs font-bold text-slate-600">Wanita</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Umur</label>
                                    <input type="number" name="text_notelp_was" required
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                                <div class="md:col-span-2 space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Domisili</label>
                                    <textarea name="t_alamat_usaha" rows="2" required
                                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></textarea>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto Profile</label>
                                    <input type="file" name="f_upload_gambar_mobile" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Scan Ijazah</label>
                                    <input type="file" name="f_foto_ijasah" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                                </div>
                            </div>

                            <div x-show="tab === 'skill'" x-transition class="space-y-4">
                                <div class="p-5 bg-slate-50 rounded-xl border border-slate-100">
                                    <label class="text-[10px] font-black text-primary-light uppercase tracking-widest mb-4 block">Keahlian Terdaftar</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($tenaga_kerja as $tek)
                                        <label class="flex items-center gap-2.5 p-3 bg-white border border-slate-200 rounded-lg hover:border-primary-light transition-all cursor-pointer">
                                            <input type="checkbox" name="chk_tenaga_kerja[]" value="{{ $tek->id_skill_tenaga_kerja }}" class="w-4 h-4 rounded border-slate-300 text-primary-light">
                                            <span class="text-[11px] font-bold text-slate-600 truncate">{{ $tek->nama_skill }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div x-show="tab === 'sosmed'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5 py-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Facebook URL</label>
                                    <input type="text" name="text_facebook_new" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Twitter / X</label>
                                    <input type="text" name="text_twitter_new" placeholder="@..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 mt-4">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">Daftarkan Karyawan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Interview Modal -->
    <template x-teleport="body">
        <div x-show="showInterviewModal" class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8" x-cloak>
            <div class="bg-white w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showInterviewModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg"><i class="bi bi-calendar-check text-2xl"></i></div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest block">Recruitment Node</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Form Interview</h3>
                        </div>
                    </div>
                    <button @click="showInterviewModal = false" class="text-slate-400 hover:text-emerald-600"><i class="bi bi-x-lg text-lg"></i></button>
                </div>
                <form action="{{ url('administrator/submit_hire_tenaga') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="text_index_karyawan_pilihan" :value="selectedKaryawanId">
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-white border border-emerald-200 flex items-center justify-center text-emerald-600 font-black" x-text="selectedKaryawanName.charAt(0)"></div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase leading-none mb-1">Kandidat</p>
                            <p class="font-black text-slate-800 text-sm" x-text="selectedKaryawanName"></p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal</label>
                            <input type="date" name="text_tanggal_interview" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jam</label>
                            <input type="time" name="text_jam_interview" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Cari Penempatan (Usaha)</label>
                            <div class="flex gap-2 p-1.5 bg-slate-100 rounded-xl border border-slate-200">
                                <input type="text" x-model="pencarianUsaha" @keyup.enter="searchUsaha()" placeholder="Nama usaha..." class="flex-1 bg-white border-none rounded-lg px-4 py-2 text-xs font-bold outline-none">
                                <button type="button" @click="searchUsaha()" class="h-9 w-9 bg-emerald-600 text-white rounded-lg flex items-center justify-center shadow-lg"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                        <div x-show="searchResult.length > 0" class="max-h-52 overflow-y-auto space-y-2 custom-scrollbar p-1">
                            <template x-for="item in searchResult" :key="item.id_usaha">
                                <div @click="pickUsaha(item.id_usaha, item.nama_usaha)" :class="selectedUsahaId == item.id_usaha ? 'border-emerald-500 bg-emerald-50' : 'bg-white border-slate-100'" class="p-3 rounded-xl border transition-all cursor-pointer flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 bg-slate-50 rounded-lg border border-slate-100 overflow-hidden"><img :src="`${window.url_menu_asset}/usaha/icon/thumbnail/${item.logo}`" class="w-full h-full object-cover"></div>
                                        <div><p class="font-black text-slate-800 text-xs tracking-tight" x-text="item.nama_usaha"></p><p class="text-[9px] font-bold text-slate-400 uppercase" x-text="item.alamat_banjar"></p></div>
                                    </div>
                                    <i x-show="selectedUsahaId == item.id_usaha" class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
                                </div>
                            </template>
                        </div>
                        <div class="p-4 bg-emerald-600 rounded-xl shadow-lg flex items-center justify-between text-white" x-show="selectedUsahaName">
                            <div><p class="text-[8px] font-black uppercase text-emerald-200 mb-1">Penempatan</p><p class="font-black text-sm" x-text="selectedUsahaName"></p></div>
                            <input type="hidden" name="text_index_usaha_pilihan" :value="selectedUsahaId">
                            <input type="hidden" name="text_usaha_pilihan" :value="selectedUsahaName">
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showInterviewModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" :disabled="!selectedUsahaId" class="px-8 py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg disabled:opacity-50">Jadwalkan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script>
    window.url_menu_apis = "{{ url('administrator') }}";
    window.url_menu_asset = "{{ asset('storage') }}";
</script>

@stop
