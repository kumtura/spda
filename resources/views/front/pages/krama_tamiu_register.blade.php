@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>
<div class="bg-white pb-24" x-data="{ lang: 'id', belumYakin: false }">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="flex items-center justify-between relative z-10 mb-6">
            <a href="{{ route('public.krama_tamiu') }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs transition-colors">
                <i class="bi bi-arrow-left"></i> <span x-text="lang==='id' ? 'Kembali' : 'Back'"></span>
            </a>
            <!-- Language Toggle -->
            <div class="flex items-center gap-1 bg-white/20 rounded-full p-0.5">
                <button type="button" @click="lang='id'" :class="lang==='id' ? 'bg-white text-[#00a6eb]' : 'text-white'" class="text-[10px] font-bold px-3 py-1 rounded-full transition-all">ID</button>
                <button type="button" @click="lang='en'" :class="lang==='en' ? 'bg-white text-[#00a6eb]' : 'text-white'" class="text-[10px] font-bold px-3 py-1 rounded-full transition-all">EN</button>
            </div>
        </div>
        
        <div class="relative z-10">
            <h1 class="text-lg font-black" x-text="lang==='id' ? 'Daftar Krama Tamiu' : 'Resident Registration'"></h1>
            <p class="text-white/80 text-[10px] mt-1" x-text="lang==='id' ? 'Lengkapi data diri untuk mendaftar sebagai warga pendatang' : 'Complete your details to register as a migrant resident'"></p>
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
                <h3 class="text-xs font-semibold text-slate-700" x-text="lang==='id' ? 'Informasi Pribadi' : 'Personal Information'"></h3>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span x-text="lang==='id' ? 'Nama Lengkap' : 'Full Name'"></span> <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           :placeholder="lang==='id' ? 'Nama lengkap sesuai KTP' : 'Full name as on ID card'">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span x-text="lang==='id' ? 'NIK' : 'ID Number (NIK)'"></span> <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="nik" value="{{ old('nik') }}" required maxlength="20"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           :placeholder="lang==='id' ? '16 digit NIK' : '16-digit National ID Number'">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span x-text="lang==='id' ? 'Asal Daerah' : 'Place of Origin'"></span> <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="asal" value="{{ old('asal') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           :placeholder="lang==='id' ? 'Kota/Kabupaten asal' : 'City/District of origin'">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span x-text="lang==='id' ? 'No. HP' : 'Phone Number'"></span> <span class="text-rose-400">*</span>
                    </label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all"
                           placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <!-- Domicile Info -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-slate-700" x-text="lang==='id' ? 'Tempat Tinggal' : 'Residence'"></h3>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span>Banjar</span> <span class="text-rose-400">*</span>
                    </label>
                    <select name="id_data_banjar" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                        <option value="">— <span x-text="lang==='id' ? 'Pilih Banjar' : 'Select Banjar'"></span> —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}" {{ old('id_data_banjar') == $banjar->id_data_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium text-slate-500 px-1">
                        <span x-text="lang==='id' ? 'Alamat Tinggal' : 'Residential Address'"></span> <span class="text-rose-400">*</span>
                    </label>
                    <textarea name="alamat_tinggal" rows="3" required
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all resize-none"
                              :placeholder="lang==='id' ? 'Alamat lengkap tempat tinggal saat ini' : 'Full current residential address'">{{ old('alamat_tinggal') }}</textarea>
                </div>
            </div>

            <!-- Duration of Stay -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-slate-700" x-text="lang==='id' ? 'Lama Tinggal' : 'Duration of Stay'"></h3>

                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-xs font-medium text-slate-700" x-text="lang==='id' ? 'Belum yakin' : 'Not sure yet'"></p>
                        <p class="text-[9px] text-slate-400" x-text="lang==='id' ? 'Centang jika belum menentukan durasi' : 'Check if duration is not decided yet'"></p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tinggal_belum_yakin" value="1" x-model="belumYakin" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                    </label>
                </div>

                <div x-show="!belumYakin" x-transition class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-medium text-slate-500 px-1" x-text="lang==='id' ? 'Dari Tanggal' : 'From Date'"></label>
                        <input type="date" name="tinggal_dari" value="{{ old('tinggal_dari') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-medium text-slate-500 px-1" x-text="lang==='id' ? 'Sampai Tanggal' : 'Until Date'"></label>
                        <input type="date" name="tinggal_sampai" value="{{ old('tinggal_sampai') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed" x-text="lang==='id' ? 'Dengan mendaftar, Anda menyetujui kewajiban punia (iuran) bulanan sesuai ketentuan desa adat. Data Anda akan diverifikasi oleh Kelian Banjar terkait.' : 'By registering, you agree to the monthly punia (contribution) obligation as per the traditional village regulations. Your data will be verified by the respective Kelian Banjar.'"></p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-3.5 rounded-xl font-medium text-sm shadow-md transition-all active:scale-[0.98]">
                <i class="bi bi-send mr-2"></i><span x-text="lang==='id' ? 'Kirim Pendaftaran' : 'Submit Registration'"></span>
            </button>
        </form>
    </div>
</div>
@endsection
