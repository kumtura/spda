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
            
            <h1 class="text-lg font-black">Tambah Pendatang</h1>
            <p class="text-white/80 text-[10px] mt-1">Daftarkan pendatang baru</p>
        </div>
    </div>

    <div class="px-4 pt-4">
        <form method="POST" action="{{ url('administrator/kelian/pendatang/store') }}" class="space-y-4">
            @csrf
            
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="Nama lengkap">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">NIK <span class="text-rose-500">*</span></label>
                    <input type="text" name="nik" required maxlength="20"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="16 digit NIK">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Asal <span class="text-rose-500">*</span></label>
                    <input type="text" name="asal" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="Kota/Kabupaten asal">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">No. HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_hp" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="08xxxxxxxxxx">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Alamat Tinggal</label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] resize-none"
                              placeholder="Alamat lengkap di banjar"></textarea>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Banjar</label>
                    <select name="id_data_banjar" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] bg-white">
                        <option value="">— Pilih Banjar —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}">{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-3">Pengaturan Punia Bulanan <span class="text-rose-500">*</span></label>
                    @php
                        $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                        $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
                    @endphp
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-[#00a6eb] transition-colors has-[:checked]:border-[#00a6eb] has-[:checked]:bg-blue-50">
                            <input type="radio" name="use_global_punia" value="1" checked class="mt-0.5" onchange="togglePuniaInput(this)">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-800">Gunakan Setting Global</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Rp {{ number_format($puniaGlobal, 0, ',', '.') }} per bulan</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-[#00a6eb] transition-colors has-[:checked]:border-[#00a6eb] has-[:checked]:bg-blue-50">
                            <input type="radio" name="use_global_punia" value="0" class="mt-0.5" onchange="togglePuniaInput(this)">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-slate-800">Atur Manual</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">Tentukan nominal punia khusus untuk pendatang ini</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div id="customPuniaField" style="display: none;">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nominal Punia Bulanan <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">Rp</span>
                        <input type="number" name="punia_rutin_bulanan" min="0" step="1000" value="0"
                               class="w-full pl-10 pr-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                               placeholder="0">
                    </div>
                    <p class="text-[9px] text-slate-400 mt-1">Nominal punia yang harus dibayar setiap bulan</p>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-save mr-2"></i>Simpan Data
            </button>
        </form>
    </div>
</div>

<script>
function togglePuniaInput(radio) {
    const customField = document.getElementById('customPuniaField');
    const puniaInput = customField.querySelector('input[name="punia_rutin_bulanan"]');
    
    if (radio.value === '0') {
        customField.style.display = 'block';
        puniaInput.required = true;
    } else {
        customField.style.display = 'none';
        puniaInput.required = false;
        puniaInput.value = '0';
    }
}
</script>
@endsection
