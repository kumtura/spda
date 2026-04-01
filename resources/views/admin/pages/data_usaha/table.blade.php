@extends($base_layout ?? 'index')

@section('isi_menu')

<div id="admin-page-container" class="space-y-6" x-data="{ 
    showAddModal: false,
    showEditModal: false,
    activeTab: 'detail',
    usahaEmail: '',
    username: '',
    editData: {},
    openAdd() { this.showAddModal = true; this.activeTab = 'detail'; },
    updateUsername() { this.username = this.usahaEmail; },
    openEdit(row) {
        this.editData = {
            id_usaha: row.id_usaha,
            id_detail_usaha: row.id_detail_usaha,
            nama_usaha: row.nama_usaha,
            id_banjar: row.id_banjar,
            id_jenis_usaha: row.id_jenis_usaha,
            no_wa: row.no_wa,
            email_usaha: row.email_usaha,
            alamat: row.alamat_banjar,
            minimal_bayar: row.minimal_bayar,
            facebook: row.facebook_url,
            instagram: row.twitter_url,
            website: row.website_url,
            maps: row.google_maps
        };
        this.showEditModal = true;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Data Unit Usaha</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola seluruh unit usaha dan mitra dalam ekosistem Dana Punia.</p>
        </div>
        <button @click="openAdd()" class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Usaha
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 flex items-center gap-4 border border-slate-200">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center">
                <i class="bi bi-briefcase-fill text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Unit Usaha</p>
                <p class="text-lg font-black text-slate-800 tracking-tight">{{ count($usaha) }} Mitra</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24">Logo</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Usaha</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wilayah</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($usaha as $index => $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-slate-400 text-center">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="h-12 w-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center p-1">
                               @if($rows->logo)
                                    @php
                                        $logoPath = file_exists(public_path('usaha/icon/'.$rows->logo)) 
                                            ? 'usaha/icon/'.$rows->logo 
                                            : 'storage/usaha/icon/'.$rows->logo;
                                    @endphp
                                    <img src="{{ asset($logoPath) }}" class="w-full h-full object-contain" alt="Logo" onerror="this.outerHTML='<i class=\'bi bi-building text-slate-300 text-xl\'></i>'">
                               @else
                                    <i class="bi bi-building text-slate-300 text-xl"></i>
                               @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-slate-800 tracking-tight leading-none mb-1">{{ $rows->nama_usaha }}</span>
                                <span class="text-[9px] font-bold text-primary-light uppercase tracking-widest">{{ $rows->nama_kategori_usaha ?? 'Umum' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-slate-500 flex items-center"><i class="bi bi-envelope mr-1.5 text-slate-400"></i>{{ $rows->email_usaha }}</span>
                                <span class="text-[10px] font-bold text-slate-500 flex items-center"><i class="bi bi-whatsapp mr-1.5 text-emerald-500"></i>{{ $rows->no_wa }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold text-slate-500 italic">{{ $rows->nama_banjar }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($rows->aktif_status == "1")
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest">
                                    <span class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest">
                                    <span class="h-1.5 w-1.5 bg-amber-400 rounded-full"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 transition-all duration-300">
                                <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" 
                                   class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm" title="Profil Lengkap">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button @click="openEdit({{ json_encode($rows) }})" 
                                   class="h-8 w-8 flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm" title="Edit Cepat">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                @if($rows->aktif_status == "0")
                                <a href="{{ url('administrator/approve_usaha/'.$rows->id_usaha) }}" 
                                   class="h-8 w-8 flex items-center justify-center bg-primary-light border border-primary-light rounded-lg text-white hover:bg-primary-dark transition-all shadow-sm" title="Konfirmasi">
                                    <i class="bi bi-check2"></i>
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

    <!-- Edit Modal -->
    <template x-teleport="body">
        <div x-show="showEditModal" class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8" x-cloak>
            <div class="bg-white w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showEditModal = false" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Koreksi Data Usaha</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Penyesuaian identitas dan administrasi unit</p>
                    </div>
                    <button @click="showEditModal = false" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <form action="{{ url('administrator/update_post_add_usaha') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                    @csrf @method('PUT')
                    <input type="hidden" name="tb_hidden_usaha" :value="editData.id_usaha">
                    <input type="hidden" name="tb_hidden_detail_usaha" :value="editData.id_detail_usaha">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Usaha</label>
                            <input type="text" name="text_title_new" x-model="editData.nama_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">E-mail Usaha</label>
                            <input type="email" name="text_email_usaha_new" x-model="editData.email_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar Wilayah</label>
                            <select name="text_desc_new" x-model="editData.id_banjar" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                                @foreach($banjar as $b)
                                    <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Usaha</label>
                            <select name="cmb_kategori_usaha" x-model="editData.id_jenis_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id_kategori_usaha }}">{{ $k->nama_kategori_usaha }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. WhatsApp (Aktif)</label>
                            <input type="text" name="text_notelp_was" x-model="editData.no_wa" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-emerald-600 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Min. Punia Wajib (Rp)</label>
                            <input type="number" name="text_minimal_pembayaran" x-model="editData.minimal_bayar" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-black text-slate-800 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none">
                        </div>

                        <!-- Logo Upload (Added) -->
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Logo Unit Usaha (Ganti)</label>
                            <div class="flex items-center gap-4 p-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl group hover:border-primary-light transition-all">
                                <i class="bi bi-image text-2xl text-slate-300 group-hover:text-primary-light"></i>
                                <input type="file" name="logo_usaha" accept="image/*" 
                                       class="text-xs font-bold text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-primary-light file:text-white hover:file:bg-primary-light/90 transition-all cursor-pointer">
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label>
                            <textarea name="t_alamat_usaha" x-model="editData.alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:bg-white focus:ring-4 focus:ring-primary-light/5 focus:border-primary-light transition-all outline-none resize-none"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-6 border-t border-slate-100">
                        <p class="text-[10px] text-slate-400 font-medium italic">* Perubahan akan berdampak pada akses login unit usaha terkait.</p>
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <button type="button" @click="showEditModal = false" class="flex-1 md:flex-none px-8 py-3 text-[10px] font-black uppercase text-slate-400 hover:text-slate-600 transition-colors">Batal</button>
                            <button type="submit" class="flex-1 md:flex-none px-10 py-3 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-900/20 transition-all transform hover:-translate-y-1 active:translate-y-0">Update Data</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
        <div x-show="showAddModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8 overflow-y-auto"
             x-transition x-cloak>
            
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl relative border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center">
                            <i class="bi bi-building-add text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest mb-0.5 block">Registrasi</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Usaha Baru</h3>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/submit_post_add_usaha') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Tabs -->
                    <div class="flex border-b border-slate-100 bg-slate-50/50 px-6">
                        <button type="button" @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'text-primary-light border-b-2 border-primary-light' : 'text-slate-400 hover:text-slate-600'" class="flex-1 px-4 py-4 font-black text-[10px] uppercase tracking-widest transition-colors"><i class="bi bi-info-circle mr-1"></i> 1. Detail Usaha</button>
                        <button type="button" @click="activeTab = 'pj'" :class="activeTab === 'pj' ? 'text-primary-light border-b-2 border-primary-light' : 'text-slate-400 hover:text-slate-600'" class="flex-1 px-4 py-4 font-black text-[10px] uppercase tracking-widest transition-colors"><i class="bi bi-person mr-1"></i> 2. Penanggung Jawab</button>
                        <button type="button" @click="activeTab = 'auth'; updateUsername()" :class="activeTab === 'auth' ? 'text-primary-light border-b-2 border-primary-light' : 'text-slate-400 hover:text-slate-600'" class="flex-1 px-4 py-4 font-black text-[10px] uppercase tracking-widest transition-colors"><i class="bi bi-shield-lock mr-1"></i> 3. Akun Login</button>
                    </div>

                    <div class="p-6">
                        <!-- Tab: Detail -->
                        <div x-show="activeTab === 'detail'" class="space-y-4" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Usaha</label><input type="text" name="text_title_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori</label><select name="cmb_kategori_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"><option value="">Pilih</option>@foreach($kategori as $k)<option value="{{ $k->id_kategori_usaha }}">{{ $k->nama_kategori_usaha }}</option>@endforeach</select></div>
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email Bisnis</label><input type="email" name="text_email_usaha_new" required x-model="usahaEmail" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">WhatsApp</label><input type="text" name="text_notelp_was" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Minimal Setoran (Punia)</label><input type="number" name="text_minimal_pembayaran" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                                
                                @if(Session::get('level') == '2' && Auth::user()->id_banjar)
                                    <input type="hidden" name="text_desc_new" value="{{ Auth::user()->id_banjar }}">
                                @else
                                    <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar</label><select name="text_desc_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"><option value="">Pilih</option>@foreach($banjar as $b)<option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>@endforeach</select></div>
                                @endif
                            </div>

                            <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Logo Usaha</label><input type="file" name="f_upload_gambar_mobile" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                        </div>

                        <!-- Tab: PJ -->
                        <div x-show="activeTab === 'pj'" class="space-y-4" x-transition>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama PJ</label><input type="text" name="text_namapngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan</label><input type="text" name="text_statuspngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                            </div>
                            <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label><textarea name="text_alamat_pngg_new" required rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></textarea></div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email PJ</label><input type="email" name="text_email_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                                <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. Telp / WA</label><input type="text" name="text_notelp_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                            </div>
                        </div>

                        <!-- Tab: Auth -->
                        <div x-show="activeTab === 'auth'" class="space-y-4" x-transition>
                            <div class="p-5 bg-slate-900 rounded-xl text-white relative overflow-hidden mb-6 shadow-inner">
                                <i class="bi bi-shield-lock text-5xl opacity-10 absolute -right-2 -bottom-2"></i>
                                <h4 class="text-sm font-black tracking-tight flex items-center gap-2"><i class="bi bi-info-circle text-primary-light"></i> Akun Login Usaha</h4>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest">Email bisnis akan digunakan sebagai username.</p>
                            </div>
                            <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Username (Email Usaha)</label><input type="text" name="text_username_new" x-model="username" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold italic text-slate-500 cursor-not-allowed"></div>
                            <div class="space-y-1.5"><label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Password</label><input type="password" name="text_password_new" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-b-2xl">
                        <template x-if="activeTab !== 'detail'">
                            <button type="button" @click="activeTab === 'auth' ? activeTab = 'pj' : activeTab = 'detail'" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors"><i class="bi bi-arrow-left mr-1.5"></i> Tahap Sebelumnya</button>
                        </template>
                        <div class="ml-auto flex gap-3">
                            <button type="button" @click="showAddModal = false" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-500 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm hover:bg-slate-50 transition-all">Batal</button>
                            <template x-if="activeTab !== 'auth'">
                                <button type="button" @click="activeTab === 'detail' ? activeTab = 'pj' : (activeTab = 'auth', updateUsername())" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest transition-all transform hover:-translate-y-0.5">Lanjut <i class="bi bi-arrow-right ml-1"></i></button>
                            </template>
                            <template x-if="activeTab === 'auth'">
                                <button type="submit" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-primary-dark transition-all transform hover:-translate-y-0.5">Selesaikan Pendaftaran <i class="bi bi-check-lg ml-1"></i></button>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@stop
