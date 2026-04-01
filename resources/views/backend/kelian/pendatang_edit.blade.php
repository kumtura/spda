@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/pendatang/detail/'.$pendatang->id_pendatang) }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <h1 class="text-lg font-black">Edit Data Pendatang</h1>
            <p class="text-white/80 text-[10px] mt-1">Update informasi pendatang</p>
        </div>
    </div>

    <div class="px-4 pt-4">
        <form method="POST" action="{{ url('administrator/kelian/pendatang/update/'.$pendatang->id_pendatang) }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ $pendatang->nama }}" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">NIK <span class="text-rose-500">*</span></label>
                    <input type="text" name="nik" value="{{ $pendatang->nik }}" required maxlength="20"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Asal <span class="text-rose-500">*</span></label>
                    <input type="text" name="asal" value="{{ $pendatang->asal }}" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">No. HP <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ $pendatang->no_hp }}" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Alamat Tinggal</label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] resize-none">{{ $pendatang->alamat_tinggal }}</textarea>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Banjar</label>
                    <select name="id_data_banjar" class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] bg-white">
                        <option value="">— Pilih Banjar —</option>
                        @foreach($banjarList as $banjar)
                        <option value="{{ $banjar->id_data_banjar }}" {{ $pendatang->id_data_banjar == $banjar->id_data_banjar ? 'selected' : '' }}>{{ $banjar->nama_banjar }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Punia Rutin Bulanan <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-500">Rp</span>
                        <input type="number" name="punia_rutin_bulanan" required min="0" step="1000" value="{{ $pendatang->punia_rutin_bulanan }}"
                               id="punia-amount-input"
                               class="w-full pl-10 pr-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                               placeholder="0"
                               {{ $pendatang->use_global_punia ? 'disabled' : '' }}>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Nominal punia yang harus dibayar setiap bulan</p>
                </div>
            </div>

            <!-- Pengaturan Punia -->
            @php
                $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
            @endphp
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
                <h4 class="text-xs font-bold text-slate-700">Pengaturan Punia</h4>
                
                <div class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-3">
                    <div>
                        <p class="text-xs text-slate-700">Gunakan tarif global</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Rp {{ number_format($puniaGlobal, 0, ',', '.') }} / bulan</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="use_global_punia" value="1" {{ $pendatang->use_global_punia ? 'checked' : '' }} class="sr-only peer" onchange="toggleGlobalPunia(this)">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                    </label>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-save mr-2"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<script>
function toggleGlobalPunia(checkbox) {
    const input = document.getElementById('punia-amount-input');
    if (checkbox.checked) {
        input.disabled = true;
        input.classList.add('opacity-50');
    } else {
        input.disabled = false;
        input.classList.remove('opacity-50');
    }
}
</script>
@endsection
