@extends($base_layout ?? 'index')

@section('isi_menu')
@php
    $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
    $puniaUsahaGlobal = $settings['punia_usaha_global'] ?? 0;
    $totalUsaha = App\Models\Usaha::where('aktif', '1')->where('aktif_status', '1')->count();
@endphp

<div class="space-y-6" x-data="{ 
    keDesa: {{ json_encode($settings['punia_usaha_ke_desa'] ?? false) }},
    tipeKeDesa: '{{ $settings['punia_usaha_tipe_ke_desa'] ?? 'persentase' }}',
    nilaiKeDesa: {{ $settings['punia_usaha_nilai_ke_desa'] ?? 0 }}
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ url('administrator/data_usaha') }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Data Usaha
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Punia Unit Usaha</h1>
            <p class="text-slate-500 font-medium text-sm">Atur nominal punia bulanan global untuk seluruh unit usaha.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-emerald-600"></i>
            <p class="text-sm text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="max-w-2xl">
        <form method="POST" action="{{ url('administrator/data_usaha/setting/update') }}" class="space-y-6">
            @csrf
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-primary-light text-lg shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Tentang Punia Usaha Global</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Nominal ini akan digunakan sebagai standar minimal bayar punia bulanan untuk unit usaha.
                                @if($totalUsaha > 0)
                                Saat ini ada <span class="font-bold">{{ $totalUsaha }} unit usaha</span> aktif yang terdaftar.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nominal Punia Bulanan Global Usaha <span class="text-rose-500">*</span></label>
                    <div class="relative max-w-md">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="punia_usaha_global" required min="0" step="1000" value="{{ $puniaUsahaGlobal }}"
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="0">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5">Nominal punia yang harus dibayar setiap bulan oleh unit usaha</p>
                </div>
            </div>

            <!-- Pengaturan Persentase Ke Desa -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <div>
                    <h3 class="text-sm font-black text-slate-800 mb-1">Pengaturan Bagi Hasil ke Desa</h3>
                    <p class="text-xs text-slate-500">Atur pembagian dana punia unit usaha yang disetor ke kas desa.</p>
                </div>

                <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="punia_usaha_ke_desa" value="1" x-model="keDesa" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-light/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-light"></div>
                    </label>
                    <div>
                        <p class="text-xs font-bold text-slate-700">Aktifkan Bagi Hasil ke Desa</p>
                        <p class="text-[10px] text-slate-400">Jika aktif, sebagian dari punia usaha akan dialokasikan ke kas desa</p>
                    </div>
                </div>

                <div x-show="keDesa" x-transition class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tipe Perhitungan</label>
                        <div class="grid grid-cols-2 gap-3 max-w-md">
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="tipeKeDesa === 'persentase' ? 'bg-blue-50 border-blue-300' : 'bg-slate-50 border-slate-200 hover:border-blue-200'">
                                <input type="radio" name="punia_usaha_tipe_ke_desa" value="persentase" x-model="tipeKeDesa" class="w-4 h-4 text-primary-light focus:ring-primary-light">
                                <div>
                                    <span class="text-xs font-bold text-slate-700">Persentase (%)</span>
                                    <p class="text-[9px] text-slate-400">Dari nominal punia</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-all"
                                   :class="tipeKeDesa === 'fix' ? 'bg-blue-50 border-blue-300' : 'bg-slate-50 border-slate-200 hover:border-blue-200'">
                                <input type="radio" name="punia_usaha_tipe_ke_desa" value="fix" x-model="tipeKeDesa" class="w-4 h-4 text-primary-light focus:ring-primary-light">
                                <div>
                                    <span class="text-xs font-bold text-slate-700">Nominal Fix (Rp)</span>
                                    <p class="text-[9px] text-slate-400">Jumlah tetap</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2" x-text="tipeKeDesa === 'persentase' ? 'Persentase ke Desa (%)' : 'Nominal Fix ke Desa (Rp)'"></label>
                        <div class="relative max-w-md">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400" x-text="tipeKeDesa === 'persentase' ? '%' : 'Rp'"></span>
                            <input type="number" name="punia_usaha_nilai_ke_desa" x-model="nilaiKeDesa"
                                   :min="0" :max="tipeKeDesa === 'persentase' ? 100 : 999999999" :step="tipeKeDesa === 'persentase' ? 0.5 : 1000"
                                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                                   placeholder="0">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5" x-show="tipeKeDesa === 'persentase'">Contoh: 10% dari Rp 500.000 = Rp 50.000 ke kas desa</p>
                        <p class="text-[10px] text-slate-400 mt-1.5" x-show="tipeKeDesa === 'fix'">Nominal tetap yang disetorkan ke kas desa dari setiap pembayaran punia</p>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-exclamation-triangle text-amber-600 text-lg shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-slate-700 mb-1">Perhatian</p>
                                <p class="text-[10px] text-slate-600 leading-relaxed">Perubahan pengaturan ini akan berlaku untuk pembayaran baru mulai saat disimpan. Data pembayaran yang sudah tercatat sebelumnya tidak akan berubah.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
