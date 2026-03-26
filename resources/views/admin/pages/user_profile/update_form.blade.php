@extends($base_layout)

@section('isi_menu')

@php
    $level = Session::get('level');
    $isMobile = in_array($level, [2, 3, '2', '3']);
    $roleName = match((string)$level) {
        '1' => 'Bendesa Adat',
        '2' => 'Kelian Adat',
        '3' => 'Unit Usaha',
        '4' => 'Admin Sistem',
        default => 'Pengguna',
    };
@endphp

<div class="{{ $isMobile ? 'px-6 py-4' : '' }} space-y-6">
    
    @if($isMobile)
    <!-- Mobile Profile Header -->
    <div class="flex items-center gap-4">
        <div class="h-16 w-16 bg-[#00a6eb]/10 rounded-2xl flex items-center justify-center border border-[#00a6eb]/20">
            <i class="bi bi-person-fill text-[#00a6eb] text-3xl"></i>
        </div>
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">{{ $datas->name }}</h1>
            <p class="text-[10px] font-bold text-[#00a6eb] uppercase tracking-widest">{{ $roleName }}</p>
        </div>
    </div>
    @else
    <!-- Desktop Profile Header -->
    <div class="flex items-center gap-5 mb-6">
        <div class="h-16 w-16 rounded-2xl bg-primary-light/10 text-primary-light flex items-center justify-center shadow-xl shadow-blue-100">
            <i class="bi bi-person-fill text-3xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Profil Saya</h1>
            <p class="text-slate-500 font-semibold text-sm">{{ $roleName }}</p>
        </div>
    </div>
    @endif

    <!-- Profile Edit Form -->
    <form method="POST" action="{{ url('administrator/update_user') }}" class="space-y-{{ $isMobile ? '5' : '6' }}">
        @csrf
        <input type="hidden" name="iduserinput_edit" value="{{ $datas->id }}">

        <!-- Account Info -->
        <div class="{{ $isMobile ? 'bg-white border border-slate-100 rounded-3xl p-5 shadow-sm' : 'glass-card p-6 bg-white shadow-sm' }} space-y-4">
            <h3 class="text-{{ $isMobile ? 'xs' : 'sm' }} font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i class="bi bi-shield-lock text-[#00a6eb]"></i> Kredensial Akun
            </h3>
            
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Email</label>
                <input type="email" name="emailinput" value="{{ $datas->email }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Password Baru</label>
                <input type="password" name="passwordinput" placeholder="Kosongkan jika tidak diubah"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all placeholder-slate-300">
            </div>
        </div>

        <!-- Personal Info -->
        <div class="{{ $isMobile ? 'bg-white border border-slate-100 rounded-3xl p-5 shadow-sm' : 'glass-card p-6 bg-white shadow-sm' }} space-y-4">
            <h3 class="text-{{ $isMobile ? 'xs' : 'sm' }} font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                <i class="bi bi-person-vcard text-[#00a6eb]"></i> Informasi Pribadi
            </h3>
            
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                <input type="text" name="textinput" value="{{ $datas->name }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">No. WhatsApp</label>
                <input type="text" name="nowainput" value="{{ $datas->no_wa }}"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
            Simpan Perubahan
        </button>
    </form>

    <!-- Logout -->
    <form method="POST" action="{{ url('logoutadmin') }}">
        @csrf
        <button type="submit" class="w-full bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 font-bold py-3.5 rounded-2xl transition-all text-sm uppercase tracking-widest">
            <i class="bi bi-box-arrow-right mr-2"></i> Keluar Sistem
        </button>
    </form>
</div>

@stop
