@extends('index')

@section('isi_menu')

<div class="space-y-8" x-data="{ 
    showEditModal: false,
    activeTab: 'detail',
    profileImg: '{{ asset('storage/karyawan/'.$rows->foto_profile) }}',
    isUploading: false,

    async uploadImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.isUploading = true;
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch('{{ url('administrator/upload_gambar_karyawan/'.$rows->id_tenaga_kerja) }}', {
                method: 'POST',
                body: formData
            });
            const imgUrl = await response.text();
            this.profileImg = imgUrl;
        } catch (error) {
            console.error('Upload failed:', error);
        } finally {
            this.isUploading = false;
        }
    }
}">

    <!-- Breadcrumbs / Top Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('administrator/data_tenagakerja') }}" class="h-12 w-12 flex items-center justify-center bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm">
                <i class="bi bi-arrow-left text-xl font-bold"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Profil Tenaga Kerja</h1>
                <p class="text-slate-500 font-medium text-sm">Informasi lengkap dan riwayat kompetensi karyawan.</p>
            </div>
        </div>
        <button @click="showEditModal = true" 
                class="flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 px-6 py-3 rounded-2xl font-bold shadow-sm transition-all duration-300">
            <i class="bi bi-pencil-square text-lg text-indigo-500"></i>
            Edit Data Profil
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sidebar: Profile Overview -->
        <div class="lg:col-span-4 space-y-6">
            <div class="glass-card rounded-[2.5rem] overflow-hidden p-8 text-center relative group">
                <!-- Avatar Upload -->
                <div class="relative inline-block mx-auto mb-6">
                    <div class="h-40 w-40 rounded-4xl bg-slate-100 p-1.5 border-4 border-white shadow-2xl overflow-hidden relative">
                        <img :src="profileImg" class="w-full h-full object-cover rounded-3xl" alt="Profile">
                        
                        <!-- Upload Overlay -->
                        <label class="absolute inset-0 bg-indigo-900/40 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-all cursor-pointer backdrop-blur-sm">
                            <input type="file" @change="uploadImage" class="hidden">
                            <i class="bi bi-camera-fill text-white text-3xl mb-1"></i>
                            <span class="text-white text-[10px] font-black uppercase tracking-widest">Ganti Foto</span>
                        </label>

                        <!-- Loader -->
                        <div x-show="isUploading" class="absolute inset-0 bg-white/80 flex items-center justify-center">
                            <div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 h-10 w-10 bg-emerald-500 border-4 border-white rounded-2xl flex items-center justify-center shadow-lg transform rotate-6">
                        <i class="bi bi-patch-check-fill text-white text-lg"></i>
                    </div>
                </div>

                <div class="space-y-1">
                    <h2 class="text-2xl font-black text-slate-800">{{ $rows->nama }}</h2>
                    <p class="text-indigo-600 font-bold text-sm bg-indigo-50 inline-block px-4 py-1 rounded-full uppercase tracking-tighter">
                        {{ Config::get('myconfig.status_tenaga')[$rows->status] }}
                    </p>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-4 text-left">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Email Address</p>
                        <p class="text-sm font-bold text-slate-700 break-all">{{ $rows->email_karyawan }}</p>
                    </div>
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">WhatsApp / Cell</p>
                        <p class="text-sm font-bold text-slate-700">{{ $rows->no_wa }}</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-center gap-3">
                    @if($rows->foto_ijazah)
                    <button onclick="window.open('{{ asset('storage/karyawan/'.$rows->foto_ijazah) }}')"
                            class="flex-1 flex items-center justify-center gap-2 bg-slate-900 text-white px-5 py-4 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl shadow-slate-200">
                        <i class="bi bi-file-earmark-pdf text-lg"></i>
                        Download CV
                    </button>
                    @endif
                </div>
            </div>

            <!-- Social Links -->
            <div class="glass-card rounded-4xl p-6 space-y-4">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest px-2">Koneksi Sosial</h3>
                <div class="flex flex-col gap-2">
                    <a href="#" class="flex items-center justify-between p-3 bg-blue-50/50 border border-blue-100/50 rounded-xl group hover:bg-blue-600 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-facebook text-xl text-blue-600 group-hover:text-white transition-colors"></i>
                            <span class="text-sm font-bold text-blue-700 group-hover:text-white transition-colors">Facebook Profile</span>
                        </div>
                        <i class="bi bi-chevron-right text-blue-400 group-hover:text-white transition-colors"></i>
                    </a>
                    <a href="#" class="flex items-center justify-between p-3 bg-slate-100/50 border border-slate-200/50 rounded-xl group hover:bg-slate-900 transition-all duration-300">
                        <div class="flex items-center gap-3">
                            <i class="bi bi-twitter-x text-xl text-slate-900 group-hover:text-white transition-colors"></i>
                            <span class="text-sm font-bold text-slate-700 group-hover:text-white transition-colors">Twitter Handle</span>
                        </div>
                        <i class="bi bi-chevron-right text-slate-400 group-hover:text-white transition-colors"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content: Detailed Info & Tabs -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-[2.5rem] min-h-[600px] flex flex-col overflow-hidden shadow-2xl">
                <!-- Tabs Nav -->
                <div class="flex border-b border-slate-100 p-2 bg-slate-50/30">
                    <button @click="activeTab = 'detail'" 
                            :class="activeTab === 'detail' ? 'bg-white shadow-md text-indigo-600' : 'text-slate-400 hover:bg-white/50'"
                            class="flex-1 px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all">
                        <i class="bi bi-grid-fill mr-2"></i>Detail Personal
                    </button>
                    <button @click="activeTab = 'skill'" 
                            :class="activeTab === 'skill' ? 'bg-white shadow-md text-indigo-600' : 'text-slate-400 hover:bg-white/50'"
                            class="flex-1 px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all">
                        <i class="bi bi-lightning-charge-fill mr-2"></i>Keahlian
                    </button>
                    <button @click="activeTab = 'history'" 
                            :class="activeTab === 'history' ? 'bg-white shadow-md text-indigo-600' : 'text-slate-400 hover:bg-white/50'"
                            class="flex-1 px-6 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] transition-all">
                        <i class="bi bi-clock-history mr-2"></i>Riwayat Status
                    </button>
                </div>

                <!-- Tab Panels -->
                <div class="flex-1 p-8">
                    <!-- Detail Personal Panel -->
                    <div x-show="activeTab === 'detail'" x-transition class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-1 group">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Alamat Domisili</p>
                                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 group-hover:bg-indigo-50/50 group-hover:border-indigo-100 transition-all duration-300 min-h-[100px]">
                                    <p class="text-slate-700 font-bold leading-relaxed">{{ $rows->alamat }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Gender / Jenis Kelamin</p>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <i class="bi {{ $rows->jenis_kelamin == 1 ? 'bi-gender-male text-blue-500' : 'bi-gender-female text-rose-500' }} text-xl"></i>
                                        <p class="text-slate-700 font-bold">{{ Config::get('myconfig.jk_tenaga')[$rows->jenis_kelamin] }}</p>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Umur Saat Ini</p>
                                    <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                        <i class="bi bi-hourglass-split text-xl text-amber-500"></i>
                                        <p class="text-slate-700 font-bold">{{ $rows->umur }} Tahun</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 rounded-4xl bg-indigo-900 text-white relative overflow-hidden shadow-2xl">
                            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                                <div class="space-y-2">
                                    <h4 class="text-lg font-black tracking-tight">Status Penempatan Sistem</h4>
                                    <p class="text-indigo-200 font-medium text-sm leading-relaxed max-w-md">Saat ini karyawan terdaftar dengan status {{ Config::get('myconfig.status_tenaga')[$rows->status] }}. Anda dapat melihat riwayat perubahan status di tab histori.</p>
                                </div>
                                <div class="px-8 py-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20">
                                    <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-1 block">Tingkat Prioritas</span>
                                    <p class="font-black text-2xl uppercase italic">Reguler</p>
                                </div>
                            </div>
                            <!-- Decorative Circle -->
                            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500 rounded-full opacity-20 blur-3xl"></div>
                        </div>
                    </div>

                    <!-- Skill Panel -->
                    <div x-show="activeTab === 'skill'" x-transition class="space-y-6">
                        <div class="flex items-center justify-between mb-2 px-2">
                            <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Matrik Kompetensi</h4>
                            <span class="text-[10px] bg-slate-100 text-slate-500 px-3 py-1 rounded-full font-black uppercase">{{ count($skill_kerja) }} Skill Terdeteksi</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($skill_kerja as $rows_tek)
                            <div class="flex items-center gap-4 p-5 bg-white border-2 border-slate-50 rounded-3xl shadow-sm hover:shadow-md hover:border-indigo-100 transition-all group">
                                <div class="h-12 w-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center transition-transform group-hover:rotate-12 group-hover:scale-110">
                                    <i class="bi bi-check2-circle text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-black text-slate-700 tracking-tight">{{ $rows_tek->nama_skill }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Verified Proficiency</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- History Panel -->
                    <div x-show="activeTab === 'history'" x-transition class="space-y-8">
                        <div class="relative py-8 px-4">
                            <!-- Timeline Line -->
                            <div class="absolute left-8 top-0 bottom-0 w-1 bg-slate-100 rounded-full"></div>

                            <div class="space-y-12 relative">
                                <!-- Example Timeline Item -->
                                <div class="relative pl-12 group">
                                    <div class="absolute left-[-4px] top-1 h-6 w-6 rounded-full bg-white border-4 border-indigo-600 shadow-sm z-10 transition-transform group-hover:scale-125"></div>
                                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 group-hover:bg-white group-hover:shadow-xl transition-all duration-300">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                                            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">Pendaftaran Awal</span>
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-200/50 px-2 py-1 rounded-lg italic">System Default</span>
                                        </div>
                                        <p class="text-sm font-bold text-slate-700">Data karyawan pertama kali dimasukkan ke dalam basis data SPDA Kumtura.</p>
                                        <p class="mt-4 text-[11px] font-black text-slate-400 uppercase tracking-tighter">
                                            <i class="bi bi-clock-history mr-1"></i> Data Historis Statis
                                        </p>
                                    </div>
                                </div>

                                <div class="text-center py-12">
                                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                        <i class="bi bi-inbox text-2xl text-slate-300"></i>
                                    </div>
                                    <p class="text-slate-400 font-bold text-sm tracking-tight italic">Belum ada riwayat aktivitas rekrutmen atau interview yang tercatat.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal (Legacy Content adapted to Premium UI) -->
    <template x-teleport="body">
        <div x-show="showEditModal" 
             class="fixed inset-0 z-100 overflow-y-auto px-4 py-12 flex items-center justify-center bg-slate-900/60 backdrop-blur-md"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             x-cloak>
            
            <div class="glass-card w-full max-w-4xl rounded-4xl overflow-hidden shadow-2xl relative border-white/20" @click.away="showEditModal = false">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between bg-linear-to-br from-amber-500/5 to-white">
                    <div>
                        <span class="text-[10px] font-black text-amber-500 uppercase tracking-[0.2em] mb-1 block">Update Informasi</span>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Edit Data Karyawan</h3>
                    </div>
                    <button @click="showEditModal = false" class="h-12 w-12 flex items-center justify-center hover:bg-slate-100 rounded-2xl transition-all duration-300 text-slate-400">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/update_post_add_tenagakerja') }}" method="POST" enctype="multipart/form-data" class="p-8 bg-white/30 space-y-8">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="t_hidden_idtext" value="{{ $rows->id_tenaga_kerja }}">

                    <div x-data="{ editTab: 'personal' }">
                        <div class="flex gap-8 border-b border-slate-100/50 mb-8 pb-1">
                            <button type="button" @click="editTab = 'personal'" 
                                    :class="editTab === 'personal' ? 'text-amber-600 border-amber-600' : 'text-slate-400 border-transparent'"
                                    class="pb-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                                <i class="bi bi-person mr-1"></i>Data Diri
                            </button>
                            <button type="button" @click="editTab = 'skill'" 
                                    :class="editTab === 'skill' ? 'text-amber-600 border-amber-600' : 'text-slate-400 border-transparent'"
                                    class="pb-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all">
                                <i class="bi bi-award mr-1"></i>Kompetensi
                            </button>
                        </div>

                        <div class="min-h-[400px]">
                            <div x-show="editTab === 'personal'" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Karyawan</label>
                                    <input type="text" name="text_title_new" required value="{{ $rows->nama }}"
                                           class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-amber-500/10 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email Address</label>
                                    <input type="email" name="text_email_usaha_new" required value="{{ $rows->email_karyawan }}"
                                           class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-amber-500/10 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. WhatsApp</label>
                                    <input type="text" name="text_telpkantor_new" required value="{{ $rows->no_wa }}"
                                           class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-amber-500/10 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Umur (Thn)</label>
                                    <input type="number" name="text_notelp_was" required value="{{ $rows->umur }}"
                                           class="w-full bg-slate-100/50 border-none rounded-2xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-amber-500/10 transition-all">
                                </div>
                                <div class="col-span-2 space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Tinggal</label>
                                    <textarea name="t_alamat_usaha" rows="3" required
                                              class="w-full bg-slate-100/50 border-none rounded-4xl px-6 py-4 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-amber-500/10 transition-all">{{ $rows->alamat }}</textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jenis Kelamin</label>
                                    <div class="flex items-center gap-8 py-3">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="rdb_jk_karyawan" value="1" {{ $rows->jenis_kelamin == 1 ? 'checked' : '' }} class="w-6 h-6 text-amber-600 focus:ring-amber-500/10 border-slate-200">
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-amber-600 transition-colors">Laki-Laki</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input type="radio" name="rdb_jk_karyawan" value="0" {{ $rows->jenis_kelamin == 0 ? 'checked' : '' }} class="w-6 h-6 text-amber-600 focus:ring-amber-500/10 border-slate-200">
                                            <span class="text-sm font-bold text-slate-600 group-hover:text-amber-600 transition-colors">Perempuan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div x-show="editTab === 'skill'" class="space-y-6">
                                <div class="bg-amber-50/20 p-8 rounded-[2.5rem] border border-amber-100/30">
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($tenaga_kerja as $rows_tek)
                                        @php
                                            $checked = "";
                                            foreach($skill_kerja as $values) {
                                                if($values->id_skill == $rows_tek->id_skill_tenaga_kerja) {
                                                    $checked = "checked";
                                                }
                                            }
                                        @endphp
                                        <label class="flex items-center p-4 bg-white rounded-2xl shadow-sm border-2 border-transparent hover:border-amber-200 cursor-pointer transition-all group">
                                            <input type="checkbox" name="chk_tenaga_kerja[]" value="{{ $rows_tek->id_skill_tenaga_kerja }}" {{ $checked }}
                                                   class="w-6 h-6 text-amber-600 rounded-lg focus:ring-amber-500/10 border-slate-200">
                                            <span class="ml-4 text-[13px] font-bold text-slate-600 group-hover:text-slate-900">{{ $rows_tek->nama_skill }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100/50">
                        <button type="button" @click="showEditModal = false" 
                                class="px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-400 hover:bg-slate-100 transition-all">Batalkan</button>
                        <button type="submit" 
                                class="px-10 py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-3xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-amber-100 transition-all transform hover:-translate-y-1 active:scale-95">
                                Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

@stop
