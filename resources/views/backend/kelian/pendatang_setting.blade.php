@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/pendatang') }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <h1 class="text-lg font-black">Pengaturan Punia Pendatang</h1>
            <p class="text-white/80 text-[10px] mt-1">Atur nominal punia bulanan global</p>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
                <p class="text-xs text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @php
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
        @endphp

        <form method="POST" action="{{ url('administrator/kelian/pendatang/setting/update') }}" class="space-y-4">
            @csrf
            
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Tentang Punia Global</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Nominal ini akan digunakan sebagai default untuk semua pendatang yang memilih opsi "Gunakan Setting Global" saat pendaftaran. 
                            @php
                                $jumlahPendatangGlobal = App\Models\Pendatang::where('use_global_punia', true)
                                    ->where('status', 'aktif')
                                    ->where('aktif', '1')
                                    ->count();
                            @endphp
                            @if($jumlahPendatangGlobal > 0)
                            Saat ini ada <span class="font-bold">{{ $jumlahPendatangGlobal }} pendatang</span> yang menggunakan setting global. Perubahan nominal akan mempengaruhi tagihan mereka di bulan berikutnya.
                            @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nominal Punia Bulanan Global <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">Rp</span>
                        <input type="number" name="punia_pendatang_global" required min="0" step="1000" value="{{ $puniaGlobal }}"
                               class="w-full pl-10 pr-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                               placeholder="0">
                    </div>
                    <p class="text-[9px] text-slate-400 mt-1">Nominal punia yang harus dibayar setiap bulan oleh pendatang</p>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
