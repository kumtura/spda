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
@endphp

<div id="admin-page-container" class="space-y-6 max-w-3xl mx-auto">
    
    <!-- Profile Header -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-5 bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="h-16 w-16 rounded-2xl bg-primary-light/10 text-primary-light flex items-center justify-center shadow-inner border border-blue-100 shrink-0">
            <i class="bi bi-person-fill text-3xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $datas->name }}</h1>
            <p class="text-[10px] font-black text-primary-light uppercase tracking-widest mt-0.5"><i class="bi bi-shield-check mr-1"></i> {{ $roleName }}</p>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <form method="POST" action="{{ url('administrator/update_user') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="iduserinput_edit" value="{{ $datas->id }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Account Info -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2 border-b border-slate-100 pb-3">
                    <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary-light flex items-center justify-center border border-blue-100"><i class="bi bi-shield-lock"></i></div>
                    Kredensial Akun
                </h3>
                
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email</label>
                    <input type="email" name="emailinput" value="{{ $datas->email }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/5 outline-none transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Password Baru</label>
                    <input type="password" name="passwordinput" placeholder="Kosongkan jika tidak diubah"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/5 outline-none transition-all placeholder-slate-400">
                </div>
            </div>

            <!-- Personal Info -->
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2 border-b border-slate-100 pb-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-100"><i class="bi bi-person-vcard"></i></div>
                    Informasi Pribadi
                </h3>
                
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap</label>
                    <input type="text" name="textinput" value="{{ $datas->name }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/5 outline-none transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. WhatsApp</label>
                    <input type="text" name="nowainput" value="{{ $datas->no_wa }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-4 focus:ring-primary-light/5 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- Submit & Logout Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-4">
            <button type="submit" class="flex-1 bg-primary-light hover:bg-primary-dark text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-100 transition-all text-xs uppercase tracking-widest transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                Simpan Perubahan <i class="bi bi-check-lg text-lg"></i>
            </button>
        </div>
    </form>
    
    <!-- Logout -->
    <form method="POST" action="{{ url('logoutadmin') }}">
        @csrf
        <button type="submit" class="w-full bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 hover:border-rose-300 font-black py-4 rounded-2xl transition-all shadow-sm text-xs uppercase tracking-widest flex items-center justify-center gap-2">
            Keluar Sistem <i class="bi bi-box-arrow-right text-lg"></i>
        </button>
    </form>

</div>

@stop
