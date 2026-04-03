@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ url('administrator/pendatang') }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Data Pendatang
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Punia Pendatang</h1>
            <p class="text-slate-500 font-medium text-sm">Atur nominal punia bulanan global untuk seluruh pendatang.</p>
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

    @php
        $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
        $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
        $jumlahPendatangGlobal = App\Models\Pendatang::where('use_global_punia', true)
            ->where('status', 'aktif')
            ->where('aktif', '1')
            ->count();
    @endphp

    <div class="max-w-2xl">
        <form method="POST" action="{{ url('administrator/pendatang/setting/update') }}" class="space-y-6">
            @csrf
            
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-primary-light text-lg shrink-0"></i>
                        <div>
                            <p class="text-sm font-bold text-slate-700 mb-1">Tentang Punia Global</p>
                            <p class="text-xs text-slate-600 leading-relaxed">
                                Nominal ini akan digunakan sebagai default untuk semua pendatang yang memilih opsi "Gunakan Setting Global" saat pendaftaran.
                                @if($jumlahPendatangGlobal > 0)
                                Saat ini ada <span class="font-bold">{{ $jumlahPendatangGlobal }} pendatang</span> yang menggunakan setting global. Perubahan nominal akan mempengaruhi tagihan mereka di bulan berikutnya.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nominal Punia Bulanan Global <span class="text-rose-500">*</span></label>
                    <div class="relative max-w-md">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="punia_pendatang_global" required min="0" step="1000" value="{{ $puniaGlobal }}"
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="0">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5">Nominal punia yang harus dibayar setiap bulan oleh pendatang</p>
                </div>
            </div>
            
            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-save mr-2"></i>Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection
