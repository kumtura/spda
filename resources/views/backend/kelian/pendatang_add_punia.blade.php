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
            
            <h1 class="text-lg font-black">Tambah Punia Rutin</h1>
            <p class="text-white/80 text-[10px] mt-1">{{ $pendatang->nama }}</p>
        </div>
    </div>

    <div class="px-4 pt-4">
        <form method="POST" action="{{ url('administrator/kelian/pendatang/punia/store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
            <input type="hidden" name="jenis_punia" value="rutin">
            
            <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Periode <span class="text-rose-500">*</span></label>
                    <select name="periode_rutin" id="periode_rutin" required onchange="updatePeriodeInput()"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>
                
                <div id="bulan-input">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Bulan & Tahun <span class="text-rose-500">*</span></label>
                    <input type="month" name="bulan_tahun" id="bulan_tahun" value="{{ date('Y-m') }}" required
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div id="tahun-input" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 mb-2">Tahun <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun" id="tahun" value="{{ date('Y') }}" min="2020" max="2100"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Nominal <span class="text-rose-500">*</span></label>
                    <input type="number" name="nominal" required min="0" step="1000"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                           placeholder="50000">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb] resize-none"
                              placeholder="Catatan tambahan"></textarea>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-plus-lg mr-2"></i>Tambah Tagihan
            </button>
        </form>
    </div>
</div>

<script>
function updatePeriodeInput() {
    const periode = document.getElementById('periode_rutin').value;
    const bulanInput = document.getElementById('bulan-input');
    const tahunInput = document.getElementById('tahun-input');
    const bulanField = document.getElementById('bulan_tahun');
    const tahunField = document.getElementById('tahun');
    
    if (periode === 'bulanan') {
        bulanInput.classList.remove('hidden');
        tahunInput.classList.add('hidden');
        bulanField.required = true;
        tahunField.required = false;
        bulanField.name = 'bulan_tahun';
        tahunField.name = '';
    } else {
        bulanInput.classList.add('hidden');
        tahunInput.classList.remove('hidden');
        bulanField.required = false;
        tahunField.required = true;
        bulanField.name = '';
        tahunField.name = 'bulan_tahun';
    }
}
</script>
@endsection
