@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <a href="{{ route('public.krama_tamiu') }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-lg font-black">Daftar Krama Tamiu</h1>
            <p class="text-white/80 text-[10px] mt-1">Lengkapi data diri untuk mendaftar sebagai warga pendatang</p>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10">
        @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 mb-4">
            <div class="flex items-start gap-2">
                <i class="bi bi-exclamation-circle text-rose-600 text-sm shrink-0 mt-0.5"></i>
                <div>
                    @foreach($errors->all() as $error)
                    <p class="text-xs text-rose-700">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('public.krama_tamiu.register.submit') }}" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            
            <!-- Personal Info -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-slate-700">Informasi Pribadi</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">Nama Lengkap <span class="text-rose-400">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="Nama lengkap sesuai KTP">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">NIK <span class="text-rose-400">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="20"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="16 digit NIK">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">Asal Daerah <span class="text-rose-400">*</span></label>
                    <input type="text" name="asal" value="{{ old('asal') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="Kota/Kabupaten asal">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">No. HP <span class="text-rose-400">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <!-- Domicile Info -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-slate-700">Tempat Tinggal</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">Banjar <span class="text-rose-400">*</span></label>
                    <select name="id_data_banjar" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                        <option value="">— Pilih Banjar —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}" {{ old('id_data_banjar') == $banjar->id_data_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">Alamat Tinggal <span class="text-rose-400">*</span></label>
                    <textarea name="alamat_tinggal" rows="3" required
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all resize-none"
                              placeholder="Alamat lengkap tempat tinggal saat ini">{{ old('alamat_tinggal') }}</textarea>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Dengan mendaftar, Anda menyetujui kewajiban punia (iuran) bulanan sesuai ketentuan desa adat. Data Anda akan diverifikasi oleh Kelian Banjar terkait.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-3.5 rounded-xl font-medium text-sm shadow-md transition-all active:scale-[0.98]">
                <i class="bi bi-send mr-2"></i>Kirim Pendaftaran
            </button>
        </form>
    </div>
</div>
@endsection
