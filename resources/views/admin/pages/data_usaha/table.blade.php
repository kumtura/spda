@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showAddModal: false,
    activeTab: 'detail',
    
    // Form Data
    usahaName: '',
    usahaEmail: '',
    usahaPhone: '',
    usahaWA: '',
    usahaAddress: '',
    usahaKategori: '',
    minPayment: '',
    banjarId: '',
    googleMaps: '',
    
    pjName: '',
    pjStatus: '',
    pjAddress: '',
    pjEmail: '',
    pjPanel: '',
    
    username: '',
    password: '',

    openAdd() {
        this.showAddModal = true;
        this.activeTab = 'detail';
    },

    updateUsername() {
        this.username = this.usahaEmail;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Data Usaha</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola profil usaha, verifikasi pendaftaran, dan data investor.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Usaha Baru
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="bi bi-building-check text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Terdaftar</p>
                <p class="text-lg font-black text-slate-800">{{ count($usaha) }} Usaha</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Profil Usaha</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak & Komunikasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Verifikasi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($usaha as $rows)
                    <tr class="group hover:bg-slate-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center text-primary-light border border-blue-100 shadow-sm transition-transform">
                                    <i class="bi bi-building text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-800 tracking-tight group-hover:text-primary-light transition-colors">{{ $rows->nama_usaha }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 truncate max-w-[200px]">{{ $rows->alamat_banjar }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-envelope text-slate-300"></i>
                                    <span class="text-xs font-bold text-slate-600">{{ $rows->email_usaha }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-whatsapp text-emerald-400"></i>
                                    <span class="text-xs font-bold text-slate-600">{{ $rows->no_wa }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($rows->aktif_status == "1")
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                                    <span class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest border border-amber-100">
                                    <span class="h-1.5 w-1.5 bg-amber-500 rounded-full"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" 
                                   class="h-9 px-4 flex items-center gap-2 bg-white border border-slate-200 rounded-lg text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-primary-light hover:border-primary-light transition-all">
                                    <i class="bi bi-gear-fill"></i> Detail
                                </a>
                                @if($rows->aktif_status == "0")
                                <a href="{{ url('administrator/approve_usaha/'.$rows->id_usaha) }}" 
                                   class="h-9 px-4 flex items-center gap-2 bg-emerald-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">
                                    <i class="bi bi-check-lg"></i> Konfirmasi
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="bg-white w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg shadow-blue-100">
                            <i class="bi bi-plus-square text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block">Registrasi Baru</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Form Pendaftaran Usaha</h3>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="h-10 w-10 flex items-center justify-center hover:bg-slate-200 rounded-xl transition-all text-slate-400">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/submit_post_add_usaha') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Tabs Navigation -->
                    <div class="flex border-b border-slate-100 bg-slate-50/50">
                        <button type="button" @click="activeTab = 'detail'" 
                                :class="activeTab === 'detail' ? 'text-primary-light bg-white border-b-2 border-primary-light' : 'text-slate-400'"
                                class="flex-1 px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">1. Detail Usaha</button>
                        <button type="button" @click="activeTab = 'pj'" 
                                :class="activeTab === 'pj' ? 'text-primary-light bg-white border-b-2 border-primary-light' : 'text-slate-400'"
                                class="flex-1 px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">2. Penanggung Jawab</button>
                        <button type="button" @click="activeTab = 'auth'; updateUsername()" 
                                :class="activeTab === 'auth' ? 'text-primary-light bg-white border-b-2 border-primary-light' : 'text-slate-400'"
                                class="flex-1 px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">3. Credentials</button>
                    </div>

                    <div class="p-8 min-h-[400px]">
                        <!-- Tab: Detail Usaha -->
                        <div x-show="activeTab === 'detail'" class="grid grid-cols-1 md:grid-cols-2 gap-6" x-transition>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Usaha</label>
                                <input type="text" name="text_title_new" required x-model="usahaName"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori</label>
                                <select name="cmb_kategori_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                        <option value="{{ $k->id_kategori_usaha }}">{{ $k->nama_kategori_usaha }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email Bisnis</label>
                                <input type="email" name="text_email_usaha_new" required x-model="usahaEmail"
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">WhatsApp</label>
                                <input type="text" name="text_notelp_was" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Minimal Setoran</label>
                                <input type="number" name="text_minimal_pembayaran" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar</label>
                                <select name="text_desc_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                    <option value="">Pilih Banjar</option>
                                    @foreach($banjar as $b)
                                        <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Upload Logo</label>
                                <input type="file" name="f_upload_gambar_mobile" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500 transition-all">
                            </div>
                        </div>

                        <!-- Tab: PJ -->
                        <div x-show="activeTab === 'pj'" class="grid grid-cols-1 md:grid-cols-2 gap-6" x-transition>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama PJ</label>
                                <input type="text" name="text_namapngg_new" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan</label>
                                <input type="text" name="text_statuspngg_new" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="md:col-span-2 space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label>
                                <textarea name="text_alamat_pngg_new" required rows="2"
                                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></textarea>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email PJ</label>
                                <input type="email" name="text_email_pngg_new" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. Telp</label>
                                <input type="text" name="text_notelp_pngg_new" required
                                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            </div>
                        </div>

                        <!-- Tab: Auth -->
                        <div x-show="activeTab === 'auth'" class="max-w-md mx-auto space-y-6 pt-6" x-transition>
                            <div class="p-6 bg-slate-900 rounded-2xl text-white space-y-2 relative overflow-hidden shadow-lg">
                                <i class="bi bi-shield-lock text-4xl opacity-10 absolute -right-2 -bottom-2"></i>
                                <h4 class="text-sm font-black uppercase tracking-wider">Access Node</h4>
                                <p class="text-xs font-medium text-slate-400 leading-relaxed">Gunakan email bisnis sebagai identitas sistem utama.</p>
                            </div>
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Username Login</label>
                                    <input type="text" name="text_username_new" x-model="username" readonly
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black italic text-slate-400">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Password Login</label>
                                    <input type="password" name="text_password_new" required placeholder="••••••••"
                                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <template x-if="activeTab !== 'detail'">
                                <button type="button" @click="activeTab === 'auth' ? activeTab = 'pj' : activeTab = 'detail'" 
                                        class="px-4 py-2.5 font-black text-[10px] uppercase tracking-widest text-slate-500 hover:bg-slate-200 rounded-lg transition-all">
                                    <i class="bi bi-chevron-left mr-1"></i> Kembali
                                </button>
                            </template>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="showAddModal = false"
                                    class="px-6 py-2.5 font-black text-[10px] uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-all">Batal</button>
                            <template x-if="activeTab !== 'auth'">
                                <button type="button" @click="activeTab === 'detail' ? activeTab = 'pj' : activeTab = 'auth'; if(activeTab === 'auth') updateUsername()"
                                        class="px-8 py-2.5 bg-primary-light hover:bg-primary-dark text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-1">
                                    Lanjut <i class="bi bi-chevron-right ml-1"></i>
                                </button>
                            </template>
                            <template x-if="activeTab === 'auth'">
                                <button type="submit"
                                        class="px-10 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-100 transition-all transform hover:-translate-y-1">
                                    Daftarkan Usaha <i class="bi bi-check-lg ml-1"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

@stop
