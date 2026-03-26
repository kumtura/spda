@extends('mobile_layout')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-slate-800">Kelian Adat,</h1>
            <p class="text-slate-500 text-sm font-medium">{{ Session::get('namapt') }}</p>
        </div>
        <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center border border-indigo-100 shadow-sm overflow-hidden">
            <i class="bi bi-person-badge-fill text-indigo-500 text-xl"></i>
        </div>
    </div>

    <!-- Stats Card (Kelian Style) -->
    <div class="bg-indigo-600 rounded-3xl p-6 text-white mb-8 shadow-lg shadow-indigo-600/20 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-xs font-bold text-white/80 uppercase tracking-widest mb-1">Manajemen Banjar</p>
        <h2 class="text-2xl font-black mb-4">{{ Auth::user()->banjar->nama_banjar ?? 'Banjar Adat' }}</h2>
        <div class="flex items-center gap-2 text-[10px] font-bold bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full w-fit">
            <i class="bi bi-people-fill"></i>
            Total Unit Usaha: {{ \App\Models\User::where('id_banjar', Auth::user()->id_banjar)->where('id_level', 3)->count() }}
        </div>
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-2 gap-4 mb-10">
        <a href="{{ url('administrator/datauser') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                <i class="bi bi-people text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Kelola Akun</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Buat akun untuk Unit Usaha</p>
        </a>
        <a href="{{ url('administrator/data_usaha') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-orange-500 group-hover:text-white">
                <i class="bi bi-briefcase text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Unit Usaha</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Liat daftar usaha Banjar</p>
        </a>
        <a href="{{ url('administrator/datapunia_wajib') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-blue-500 group-hover:text-white">
                <i class="bi bi-wallet2 text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Monitor Iuran</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Cek setoran iuran rutin</p>
        </a>
        <a href="{{ url('administrator/data_tenagakerja') }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm hover:shadow-md transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-4 transition-colors group-hover:bg-emerald-500 group-hover:text-white">
                <i class="bi bi-person-workspace text-2xl"></i>
            </div>
            <h3 class="font-bold text-slate-800 text-sm mb-1">Tenaga Kerja</h3>
            <p class="text-slate-400 text-[10px] leading-tight">Pantau penempatan kerja</p>
        </a>
    </div>

    <!-- Important Notice -->
    <div class="bg-slate-50 border border-slate-100 p-5 rounded-3xl">
        <h3 class="font-black text-slate-800 tracking-tight mb-3">Tugas Kelian Adat</h3>
        <ul class="space-y-3">
            <li class="flex items-start gap-3">
                <i class="bi bi-check-circle-fill text-[#00a6eb] text-sm flex-shrink-0 mt-0.5"></i>
                <p class="text-[11px] text-slate-600 font-medium">Memastikan semua unit usaha di Banjar terdaftar secara resmi di sistem.</p>
            </li>
             <li class="flex items-start gap-3">
                <i class="bi bi-check-circle-fill text-[#00a6eb] text-sm flex-shrink-0 mt-0.5"></i>
                <p class="text-[11px] text-slate-600 font-medium">Bekerjasama dengan Bendesa Adat dalam pengawasan dana punia wajib.</p>
            </li>
        </ul>
    </div>
</div>
@endsection
