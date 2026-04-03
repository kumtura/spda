@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/pendatang/detail/'.$pendatang->id_pendatang) }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke Detail
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Tambah Punia Rutin</h1>
        <p class="text-slate-500 font-medium text-sm">{{ $pendatang->nama }}</p>
    </div>

    <div class="max-w-lg">
        <form method="POST" action="{{ url('administrator/pendatang/punia/store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
            <input type="hidden" name="jenis_punia" value="rutin">

            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Periode <span class="text-rose-500">*</span></label>
                    <select name="periode_rutin" id="periode_rutin" required onchange="updatePeriodeInput()"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                        <option value="bulanan">Bulanan</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>

                <div id="bulan-input">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Bulan & Tahun <span class="text-rose-500">*</span></label>
                    <input type="month" name="bulan_tahun" id="bulan_tahun" value="{{ date('Y-m') }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                </div>

                <div id="tahun-input" class="hidden">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tahun <span class="text-rose-500">*</span></label>
                    <input type="number" name="tahun" id="tahun" value="{{ date('Y') }}" min="2020" max="2100"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nominal <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input type="number" name="nominal" required min="0" step="1000"
                               class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                               placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all resize-none"
                              placeholder="Catatan tambahan"></textarea>
                </div>
            </div>

            <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-plus-lg mr-2"></i> Tambah Tagihan
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
