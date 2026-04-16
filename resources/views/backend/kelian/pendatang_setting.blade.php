@extends('mobile_layout')

@section('isi_menu')
@php
    $dbSettingTamiu = App\Models\PengaturanBagiHasil::where('jenis_punia', 'tamiu')
        ->whereNull('id_data_banjar')
        ->where('aktif', 1)
        ->orderBy('berlaku_sejak', 'desc')
        ->first();
    
    if ($dbSettingTamiu) {
        $keDesa = true;
        $tipeKeDesa = 'persentase';
        $nilaiKeDesa = $dbSettingTamiu->persen_desa;
    } else {
        $keDesa = $settings['punia_pendatang_ke_desa'] ?? false;
        $tipeKeDesa = $settings['punia_pendatang_tipe_ke_desa'] ?? 'persentase';
        $nilaiKeDesa = $settings['punia_pendatang_nilai_ke_desa'] ?? 0;
    }
@endphp
<div class="bg-white pb-24" x-data="{ 
    keDesa: {{ json_encode($keDesa) }},
    tipeKeDesa: '{{ $tipeKeDesa }}',
    nilaiKeDesa: {{ $nilaiKeDesa }}
}">
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

            <!-- Pengaturan Bagi Hasil ke Desa -->
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800 mb-1">Bagi Hasil ke Desa</h3>
                        <p class="text-[10px] text-slate-500">Atur pembagian dana punia pendatang ke kas desa</p>
                    </div>
                    <a href="{{ url('administrator/pengaturan_bagi_hasil') }}" class="text-[9px] font-bold text-[#00a6eb] hover:underline flex items-center gap-1">
                        <i class="bi bi-gear"></i> Detail
                    </a>
                </div>

                @if($dbSettingTamiu)
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
                    <div class="flex items-start gap-2">
                        <i class="bi bi-check-circle-fill text-emerald-600 text-sm shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[10px] font-bold text-emerald-700">Tersinkronisasi</p>
                            <p class="text-[9px] text-emerald-600">{{ number_format($dbSettingTamiu->persen_desa, 1) }}% ke Desa, {{ number_format($dbSettingTamiu->persen_banjar, 1) }}% ke Banjar — sejak {{ $dbSettingTamiu->berlaku_sejak->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="punia_pendatang_ke_desa" value="1" x-model="keDesa" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#00a6eb]/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                    </label>
                    <div>
                        <p class="text-xs font-bold text-slate-700">Aktifkan Bagi Hasil</p>
                        <p class="text-[9px] text-slate-400">Sebagian punia akan dialokasikan ke kas desa</p>
                    </div>
                </div>

                <div x-show="keDesa" x-transition class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Tipe Perhitungan</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-center"
                                   :class="tipeKeDesa === 'persentase' ? 'bg-blue-50 border-blue-300' : 'bg-slate-50 border-slate-200'">
                                <input type="radio" name="punia_pendatang_tipe_ke_desa" value="persentase" x-model="tipeKeDesa" class="w-4 h-4 text-[#00a6eb] focus:ring-[#00a6eb]">
                                <span class="text-xs font-bold text-slate-700">Persentase (%)</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all text-center"
                                   :class="tipeKeDesa === 'fix' ? 'bg-blue-50 border-blue-300' : 'bg-slate-50 border-slate-200'">
                                <input type="radio" name="punia_pendatang_tipe_ke_desa" value="fix" x-model="tipeKeDesa" class="w-4 h-4 text-[#00a6eb] focus:ring-[#00a6eb]">
                                <span class="text-xs font-bold text-slate-700">Nominal Fix (Rp)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2" x-text="tipeKeDesa === 'persentase' ? 'Persentase ke Desa (%)' : 'Nominal Fix ke Desa (Rp)'"></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500" x-text="tipeKeDesa === 'persentase' ? '%' : 'Rp'"></span>
                            <input type="number" name="punia_pendatang_nilai_ke_desa" x-model="nilaiKeDesa"
                                   :min="0" :max="tipeKeDesa === 'persentase' ? 100 : 999999999" :step="tipeKeDesa === 'persentase' ? 0.5 : 1000"
                                   class="w-full pl-10 pr-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                   placeholder="0">
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-exclamation-triangle text-amber-600 text-sm shrink-0 mt-0.5"></i>
                            <p class="text-[10px] text-slate-600">Perubahan ini berlaku untuk pembayaran baru. Data lama tidak terpengaruh.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
