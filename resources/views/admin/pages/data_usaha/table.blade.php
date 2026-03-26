@extends($base_layout)

@section('isi_menu')
@php
    $level = Session::get('level');
    $isMobile = in_array($level, [2, 3, '2', '3']);
@endphp

<div class="{{ $isMobile ? 'px-6 py-4' : '' }} space-y-6" x-data="{ 
    showAddModal: false,
    activeTab: 'detail',
    usahaEmail: '',
    username: '',
    openAdd() { this.showAddModal = true; this.activeTab = 'detail'; },
    updateUsername() { this.username = this.usahaEmail; }
}">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-{{ $isMobile ? 'xl' : '2xl' }} font-black text-slate-800 tracking-tight">Data Usaha</h1>
            <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">{{ count($usaha) }} usaha terdaftar</p>
        </div>
        <button @click="openAdd()" class="h-10 {{ $isMobile ? 'w-10' : 'px-5' }} bg-[#00a6eb] text-white rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-[#00a6eb]/20 text-sm font-bold">
            <i class="bi bi-plus-lg"></i>
            @if(!$isMobile)<span>Tambah</span>@endif
        </button>
    </div>

    <!-- Card List -->
    <div class="space-y-3">
        @foreach($usaha as $rows)
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 text-[#00a6eb] flex items-center justify-center font-black text-sm uppercase border border-blue-100">
                        <i class="bi bi-building"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800 tracking-tight">{{ $rows->nama_usaha }}</p>
                        <p class="text-[10px] text-slate-400 font-medium truncate max-w-[180px]">{{ $rows->alamat_banjar }}</p>
                    </div>
                </div>
                @if($rows->aktif_status == "1")
                <span class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse"></span>
                @else
                <span class="h-2 w-2 bg-amber-400 rounded-full"></span>
                @endif
            </div>
            <div class="flex items-center gap-3 text-[10px] text-slate-400 font-medium mb-3">
                <span><i class="bi bi-envelope mr-1"></i>{{ $rows->email_usaha }}</span>
                <span><i class="bi bi-whatsapp mr-1 text-emerald-400"></i>{{ $rows->no_wa }}</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ url('administrator/detail_usaha/'.$rows->id_usaha) }}" 
                   class="flex-1 text-center bg-slate-50 border border-slate-200 text-slate-600 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all">
                    <i class="bi bi-gear mr-1"></i> Detail
                </a>
                @if($rows->aktif_status == "0")
                <a href="{{ url('administrator/approve_usaha/'.$rows->id_usaha) }}" 
                   class="flex-1 text-center bg-emerald-500 text-white py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-emerald-600 transition-all">
                    <i class="bi bi-check-lg mr-1"></i> Konfirmasi
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Add Modal (Full screen on mobile) -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-[100] overflow-y-auto {{ $isMobile ? 'bg-white' : 'px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md' }}"
             x-transition x-cloak>
            
            <div class="bg-white {{ $isMobile ? 'w-full min-h-screen' : 'w-full max-w-4xl rounded-3xl shadow-2xl' }} overflow-hidden relative" @click.away="showAddModal = false">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-[#00a6eb] text-white flex items-center justify-center shadow-lg">
                            <i class="bi bi-plus-square text-xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-widest block">Registrasi</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Usaha Baru</h3>
                        </div>
                    </div>
                    <button @click="showAddModal = false" class="h-10 w-10 flex items-center justify-center hover:bg-slate-200 rounded-xl text-slate-400"><i class="bi bi-x-lg text-lg"></i></button>
                </div>

                <form action="{{ url('administrator/submit_post_add_usaha') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Tabs -->
                    <div class="flex border-b border-slate-100 bg-slate-50/50">
                        <button type="button" @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'text-[#00a6eb] bg-white border-b-2 border-[#00a6eb]' : 'text-slate-400'" class="flex-1 px-4 py-3 font-bold text-[10px] uppercase tracking-widest">1. Detail</button>
                        <button type="button" @click="activeTab = 'pj'" :class="activeTab === 'pj' ? 'text-[#00a6eb] bg-white border-b-2 border-[#00a6eb]' : 'text-slate-400'" class="flex-1 px-4 py-3 font-bold text-[10px] uppercase tracking-widest">2. PJ</button>
                        <button type="button" @click="activeTab = 'auth'; updateUsername()" :class="activeTab === 'auth' ? 'text-[#00a6eb] bg-white border-b-2 border-[#00a6eb]' : 'text-slate-400'" class="flex-1 px-4 py-3 font-bold text-[10px] uppercase tracking-widest">3. Login</button>
                    </div>

                    <div class="p-5 space-y-4">
                        <!-- Tab: Detail -->
                        <div x-show="activeTab === 'detail'" class="space-y-4" x-transition>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Usaha</label><input type="text" name="text_title_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kategori</label><select name="cmb_kategori_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20"><option value="">Pilih</option>@foreach($kategori as $k)<option value="{{ $k->id_kategori_usaha }}">{{ $k->nama_kategori_usaha }}</option>@endforeach</select></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email Bisnis</label><input type="email" name="text_email_usaha_new" required x-model="usahaEmail" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">WhatsApp</label><input type="text" name="text_notelp_was" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Minimal Setoran</label><input type="number" name="text_minimal_pembayaran" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            
                            @if(Session::get('level') == '2' && Auth::user()->id_banjar)
                                <input type="hidden" name="text_desc_new" value="{{ Auth::user()->id_banjar }}">
                            @else
                                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Banjar</label><select name="text_desc_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20"><option value="">Pilih</option>@foreach($banjar as $b)<option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>@endforeach</select></div>
                            @endif

                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Logo</label><input type="file" name="f_upload_gambar_mobile" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500"></div>
                        </div>

                        <!-- Tab: PJ -->
                        <div x-show="activeTab === 'pj'" class="space-y-4" x-transition>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama PJ</label><input type="text" name="text_namapngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jabatan</label><input type="text" name="text_statuspngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Alamat</label><textarea name="text_alamat_pngg_new" required rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></textarea></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email PJ</label><input type="email" name="text_email_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">No. Telp</label><input type="text" name="text_notelp_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                        </div>

                        <!-- Tab: Auth -->
                        <div x-show="activeTab === 'auth'" class="space-y-4" x-transition>
                            <div class="p-4 bg-slate-900 rounded-2xl text-white relative overflow-hidden">
                                <i class="bi bi-shield-lock text-3xl opacity-10 absolute -right-1 -bottom-1"></i>
                                <h4 class="text-xs font-black uppercase tracking-wider">Akun Login Usaha</h4>
                                <p class="text-[10px] text-slate-400 mt-1">Email bisnis sebagai username.</p>
                            </div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Username</label><input type="text" name="text_username_new" x-model="username" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold italic text-slate-400"></div>
                            <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password</label><input type="password" name="text_password_new" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="p-5 border-t border-slate-100 flex items-center justify-between">
                        <template x-if="activeTab !== 'detail'">
                            <button type="button" @click="activeTab === 'auth' ? activeTab = 'pj' : activeTab = 'detail'" class="text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-slate-600"><i class="bi bi-chevron-left mr-1"></i> Kembali</button>
                        </template>
                        <div class="ml-auto flex gap-2">
                            <button type="button" @click="showAddModal = false" class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-widest text-slate-400">Batal</button>
                            <template x-if="activeTab !== 'auth'">
                                <button type="button" @click="activeTab === 'detail' ? activeTab = 'pj' : (activeTab = 'auth', updateUsername())" class="px-6 py-2.5 bg-[#00a6eb] text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg">Lanjut <i class="bi bi-chevron-right ml-1"></i></button>
                            </template>
                            <template x-if="activeTab === 'auth'">
                                <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-[10px] uppercase tracking-widest shadow-lg">Daftarkan <i class="bi bi-check-lg ml-1"></i></button>
                            </template>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

@stop
