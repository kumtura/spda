@extends('mobile_layout_public')

@section('content')
<div class="min-h-screen bg-slate-50 pt-20 pb-10" x-data="{ 
    activeTab: 'detail',
    usahaEmail: '',
    username: '',
    updateUsername() { this.username = this.usahaEmail; }
}">

    <!-- Fixed Header -->
    <div class="fixed top-0 inset-x-0 h-16 bg-white border-b border-slate-100 px-4 flex items-center justify-between z-50 shadow-sm">
        <a href="{{ url('/') }}" class="h-10 w-10 flex items-center justify-center bg-slate-50 rounded-xl text-slate-500 hover:text-[#00a6eb] transition-colors">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pendaftaran Usaha</h1>
        <div class="w-10"></div>
    </div>

    <div class="px-4 space-y-6">
        <!-- Progress Indicator -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center justify-between relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-50 to-transparent"></div>
            <div class="relative z-10 space-y-1">
                <span class="text-[9px] font-black text-[#00a6eb] uppercase tracking-widest block" x-text="activeTab === 'detail' ? 'Langkah 1 dari 3' : (activeTab === 'pj' ? 'Langkah 2 dari 3' : 'Langkah Akhir')"></span>
                <h2 class="text-lg font-black text-slate-800 tracking-tight" x-text="activeTab === 'detail' ? 'Detail Usaha' : (activeTab === 'pj' ? 'Data Penanggung Jawab' : 'Akun Login')"></h2>
            </div>
            <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-100 relative z-10 text-[#00a6eb]">
                <i class="bi text-2xl" :class="activeTab === 'detail' ? 'bi-building' : (activeTab === 'pj' ? 'bi-person-badge' : 'bi-shield-check')"></i>
            </div>
        </div>

        <form action="{{ route('public.register_usaha.submit') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-5 shadow-lg shadow-slate-200/50 border border-slate-100">
            @csrf

            <!-- Step 1: Detail Usaha -->
            <div x-show="activeTab === 'detail'" x-transition class="space-y-4">
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Usaha/Toko <span class="text-rose-500">*</span></label><input type="text" name="text_title_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Kategori <span class="text-rose-500">*</span></label><select name="cmb_kategori_usaha" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20"><option value="">Pilih Kategori</option>@foreach($kategori as $k)<option value="{{ $k->id_kategori_usaha }}">{{ $k->nama_kategori_usaha }}</option>@endforeach</select></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email Bisnis <span class="text-rose-500">*</span></label><input type="email" name="text_email_usaha_new" required x-model="usahaEmail" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">No. WhatsApp <span class="text-rose-500">*</span></label><input type="text" name="text_notelp_was" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Banjar Domisili Usaha <span class="text-rose-500">*</span></label><select name="text_desc_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20"><option value="">Pilih Banjar</option>@foreach($banjar as $b)<option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>@endforeach</select></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Logo Usaha <span class="text-rose-500">*</span></label><input type="file" name="f_upload_gambar_mobile" required accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500"></div>
                
                <div class="pt-4 border-t border-slate-100">
                    <button type="button" @click="if(document.querySelector('input[name=text_title_new]').checkValidity() && document.querySelector('select[name=cmb_kategori_usaha]').checkValidity() && document.querySelector('input[name=text_email_usaha_new]').checkValidity() && document.querySelector('select[name=text_desc_new]').checkValidity()) { activeTab = 'pj' } else { document.querySelector('input[name=text_title_new]').reportValidity() }" class="w-full bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
                        Lanjut ke Tahap 2 <i class="bi bi-arrow-right ml-1.5"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: PJ -->
            <div x-show="activeTab === 'pj'" x-transition x-cloak class="space-y-4">
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Penanggung Jawab <span class="text-rose-500">*</span></label><input type="text" name="text_namapngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jabatan <span class="text-rose-500">*</span></label><input type="text" name="text_statuspngg_new" required placeholder="Cth: Pemilik / Manajer" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">No. Telp Penanggung Jawab <span class="text-rose-500">*</span></label><input type="text" name="text_notelp_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email Pribadi <span class="text-rose-500">*</span></label><input type="email" name="text_email_pngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Alamat Tempat Tinggal <span class="text-rose-500">*</span></label><textarea name="text_alamat_pngg_new" required rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></textarea></div>
                
                <!-- Default Hidden Field for the DB Structure -->
                <input type="hidden" name="text_minimal_pembayaran" value="0">

                <div class="pt-4 border-t border-slate-100 flex gap-3">
                    <button type="button" @click="activeTab = 'detail'" class="w-1/3 bg-slate-100 text-slate-500 font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-200">Kembali</button>
                    <button type="button" @click="if(document.querySelector('input[name=text_namapngg_new]').checkValidity() && document.querySelector('input[name=text_notelp_pngg_new]').checkValidity()) { activeTab = 'auth'; updateUsername(); } else { document.querySelector('input[name=text_namapngg_new]').reportValidity() }" class="w-2/3 bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
                        Tahap Akhir <i class="bi bi-arrow-right ml-1.5"></i>
                    </button>
                </div>
            </div>

            <!-- Step 3: Auth -->
            <div x-show="activeTab === 'auth'" x-transition x-cloak class="space-y-4">
                <div class="bg-slate-900 rounded-2xl p-5 text-white relative overflow-hidden mb-6">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    <i class="bi bi-shield-lock text-4xl opacity-20 absolute top-4 right-4"></i>
                    <h4 class="text-xs font-black uppercase tracking-widest mb-1">Akun Sistem Anda</h4>
                    <p class="text-[10px] text-slate-400 leading-relaxed max-w-[80%]">Buat password untuk login ke portal Unit Usaha SPDA. Gunakan email bisnis Anda sebagai username utama.</p>
                </div>

                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Username (Email Anda)</label><input type="text" name="text_username_new" x-model="username" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold italic text-slate-400"></div>
                <div><label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password <span class="text-rose-500">*</span></label><input type="password" name="text_password_new" required placeholder="Minimal 8 karakter" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-2 focus:ring-[#00a6eb]/20 transition-all"></div>
                
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mt-6">
                    <p class="text-[9px] font-bold text-[#00a6eb] uppercase tracking-widest mb-1">Catatan</p>
                    <p class="text-[10px] text-slate-600 font-medium">Dengan menekan tombol kirim, Anda menyetujui syarat & ketentuan sistem punia desa adat.</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex gap-3">
                    <button type="button" @click="activeTab = 'pj'" class="w-1/3 bg-slate-100 text-slate-500 font-bold py-3.5 rounded-2xl text-xs uppercase tracking-widest hover:bg-slate-200">Kembali</button>
                    <button type="submit" class="w-2/3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all text-sm uppercase tracking-widest">
                        Kirim Pendaftaran <i class="bi bi-check-circle-fill ml-1.5"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
