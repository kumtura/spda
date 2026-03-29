@extends($base_layout ?? 'index')

@section('isi_menu')

@php
    $level = Session::get('level');
    $roleName = match((string)$level) {
        '1' => 'Bendesa Adat',
        '2' => 'Kelian Adat',
        '3' => 'Unit Usaha',
        '4' => 'Admin Sistem',
        default => 'Pengguna',
    };
    $isMobile = in_array($level, [2, 3]);
@endphp

<div id="admin-page-container" class="{{ $isMobile ? 'px-4 pt-8 pb-24' : '' }} space-y-5 {{ $isMobile ? '' : 'max-w-3xl mx-auto' }}">
    
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 p-3.5 rounded-xl flex items-center gap-2.5">
        <i class="bi bi-check-circle-fill text-emerald-600 text-lg"></i>
        <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif
    
    <!-- Profile Header -->
    <div class="flex items-center gap-4 bg-white border border-slate-200 rounded-xl p-5">
        @if($level == 3 && isset($usaha) && $usaha->logo)
        <div class="h-14 w-14 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 shrink-0 overflow-hidden">
            <img src="{{ asset('storage/usaha/icon/'.$usaha->logo) }}" class="h-full w-full object-cover" alt="Logo">
        </div>
        @else
        <div class="h-14 w-14 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center border border-slate-200 shrink-0">
            <i class="bi bi-{{ $level == 3 ? 'shop' : 'person' }} text-2xl"></i>
        </div>
        @endif
        <div>
            <h1 class="text-lg font-bold text-slate-800">{{ $level == 3 && isset($usaha) ? $usaha->nama_usaha : $datas->name }}</h1>
            <p class="text-[10px] font-bold text-[#00a6eb] uppercase tracking-wide mt-0.5">{{ $roleName }}</p>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <form method="POST" action="{{ url('administrator/update_user') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <input type="hidden" name="iduserinput_edit" value="{{ $datas->id }}">
        @if($level == 3 && isset($usaha))
        <input type="hidden" name="id_detail_usaha" value="{{ $usaha->id_detail_usaha }}">
        @endif

        @if($level == 3 && isset($usaha))
        <!-- Unit Usaha Specific Fields -->
        
        <!-- Business Info -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Informasi Usaha</h3>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Nama Usaha</label>
                <input type="text" name="nama_usaha" value="{{ $usaha->nama_usaha }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Email Usaha</label>
                <input type="email" name="email_usaha" value="{{ $usaha->email_usaha }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Logo Usaha</label>
                @if($usaha->logo)
                <div class="mb-2">
                    <img src="{{ asset('storage/usaha/icon/'.$usaha->logo) }}" class="h-16 w-16 object-cover rounded-lg border border-slate-200" alt="Logo">
                </div>
                @endif
                <input type="file" name="logo_usaha" accept="image/*"
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2 text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-[10px] file:font-bold file:bg-[#00a6eb] file:text-white hover:file:bg-[#0090d0]">
                <p class="text-[9px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah</p>
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Kategori Usaha</label>
                <input type="text" value="{{ $usaha->nama_kategori_usaha ?? '-' }}" disabled
                       class="w-full bg-slate-100 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-400">
                <p class="text-[9px] text-slate-400">Hubungi admin untuk mengubah kategori</p>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Kontak & Lokasi</h3>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ $usaha->no_telp }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">No. WhatsApp</label>
                <input type="text" name="no_wa_usaha" value="{{ $usaha->no_wa }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Banjar</label>
                <select name="id_banjar" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                    @foreach($banjar as $b)
                    <option value="{{ $b->id_data_banjar }}" {{ $usaha->id_banjar == $b->id_data_banjar ? 'selected' : '' }}>{{ $b->nama_banjar }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Alamat</label>
                <textarea name="alamat_banjar" rows="3"
                          class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">{{ $usaha->alamat_banjar }}</textarea>
            </div>
        </div>

        <!-- Social Media & Maps -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Media Sosial & Lokasi</h3>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Facebook URL</label>
                <input type="url" name="facebook_url" value="{{ $usaha->facebook_url }}" placeholder="https://facebook.com/..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Twitter URL</label>
                <input type="url" name="twitter_url" value="{{ $usaha->twitter_url }}" placeholder="https://twitter.com/..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Website URL</label>
                <input type="url" name="website_url" value="{{ $usaha->website_url }}" placeholder="https://..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Google Maps URL</label>
                <input type="url" name="google_maps" value="{{ $usaha->google_maps }}" placeholder="https://maps.google.com/..."
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
            </div>
        </div>

        <!-- Account Credentials -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
            <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Kredensial Akun</h3>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Email Login</label>
                <input type="email" name="emailinput" value="{{ $datas->email }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Nama Penanggung Jawab</label>
                <input type="text" name="textinput" value="{{ $datas->name }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">No. WhatsApp Penanggung Jawab</label>
                <input type="text" name="nowainput" value="{{ $datas->no_wa }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
            </div>
            
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500">Password Baru</label>
                <input type="password" name="passwordinput" placeholder="Kosongkan jika tidak diubah"
                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
                <p class="text-[9px] text-slate-400">Kosongkan jika tidak ingin mengubah password</p>
            </div>
        </div>
        @else
        <!-- Regular User Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Account Info -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Kredensial Akun</h3>
                
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-500">Email</label>
                    <input type="email" name="emailinput" value="{{ $datas->email }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-500">Password Baru</label>
                    <input type="password" name="passwordinput" placeholder="Kosongkan jika tidak diubah"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none placeholder-slate-400">
                </div>
            </div>

            <!-- Personal Info -->
            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-700 pb-2.5 border-b border-slate-100">Informasi Pribadi</h3>
                
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-500">Nama Lengkap</label>
                    <input type="text" name="textinput" value="{{ $datas->name }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-500">No. WhatsApp</label>
                    <input type="text" name="nowainput" value="{{ $datas->no_wa }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                </div>
            </div>
        </div>
        @endif

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3 rounded-xl transition-all text-sm flex items-center justify-center gap-2">
            Simpan Perubahan <i class="bi bi-check-lg"></i>
        </button>
    </form>
    
    <!-- Logout -->
    <form method="POST" action="{{ url('logoutadmin') }}">
        @csrf
        <button type="submit" class="w-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 font-bold py-3 rounded-xl transition-all text-sm flex items-center justify-center gap-2">
            Keluar Sistem <i class="bi bi-box-arrow-right"></i>
        </button>
    </form>

</div>

@stop
