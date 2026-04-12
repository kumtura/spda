@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showOverrideModal: false,
    overrideJenis: 'tamiu',
    overrideBanjar: '',
    overridePersen: '',
    overrideBerlaku: '{{ date('Y-m-d') }}',
    overrideKeterangan: ''
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Pengaturan Bagi Hasil</h1>
            <p class="text-slate-500 font-medium text-sm">Atur persentase pembagian punia antara Desa Adat dan Banjar.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-center gap-2">
            <i class="bi bi-check-circle text-primary-light"></i>
            <p class="text-sm text-blue-700 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <div class="flex gap-3">
            <div class="h-10 w-10 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-md shadow-blue-100 flex-shrink-0">
                <i class="bi bi-info-lg text-lg"></i>
            </div>
            <div class="text-xs text-blue-700 space-y-1">
                <p class="font-bold">Cara kerja pengaturan ini:</p>
                <ul class="list-disc ml-4 space-y-0.5 text-blue-600">
                    <li><strong>Pengaturan Global</strong> berlaku untuk semua banjar yang tidak punya pengaturan khusus.</li>
                    <li><strong>Override per Banjar</strong> menggantikan pengaturan global untuk banjar tertentu.</li>
                    <li>Persentase Desa + Banjar harus selalu = 100%.</li>
                    <li>Berlaku sejak tanggal yang ditentukan.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Global Settings -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-blue-50/50 border-b border-slate-100 px-6 py-4 flex items-center gap-3">
            <div class="h-10 w-10 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-md shadow-blue-100">
                <i class="bi bi-globe2 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 tracking-tight">Pengaturan Global</h3>
                <p class="text-[10px] text-slate-500 font-medium">Berlaku untuk semua banjar kecuali yang punya pengaturan khusus</p>
            </div>
        </div>

        <form action="{{ url('administrator/pengaturan_bagi_hasil/global') }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Punia Tamiu -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-people text-primary-light"></i>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest">Punia Krama Tamiu</h4>
                    </div>
                    <div class="space-y-4" x-data="{ desaTamiu: {{ $globalTamiu ? $globalTamiu->persen_desa : 0 }} }">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">% Masuk ke Desa Adat</label>
                            <input type="number" name="persen_desa_tamiu" x-model="desaTamiu" step="0.01" min="0" max="100" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5"
                                placeholder="0">
                        </div>
                        <div class="flex items-center justify-between bg-blue-50 rounded-xl p-3 border border-blue-100">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">% Masuk ke Banjar</span>
                            <span class="text-sm font-black text-blue-700" x-text="(100 - desaTamiu).toFixed(2) + '%'"></span>
                        </div>
                        @if($globalTamiu)
                        <p class="text-[9px] text-slate-400 font-medium">
                            <i class="bi bi-clock mr-1"></i>Aktif sejak {{ $globalTamiu->berlaku_sejak->format('d M Y') }}
                        </p>
                        @else
                        <p class="text-[9px] text-slate-400 font-medium italic">Belum ada pengaturan (default: 100% ke Desa)</p>
                        @endif
                    </div>
                </div>

                <!-- Punia Usaha -->
                <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-building text-primary-light"></i>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest">Punia Unit Usaha</h4>
                    </div>
                    <div class="space-y-4" x-data="{ desaUsaha: {{ $globalUsaha ? $globalUsaha->persen_desa : 0 }} }">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">% Masuk ke Desa Adat</label>
                            <input type="number" name="persen_desa_usaha" x-model="desaUsaha" step="0.01" min="0" max="100" required
                                class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5"
                                placeholder="0">
                        </div>
                        <div class="flex items-center justify-between bg-blue-50 rounded-xl p-3 border border-blue-100">
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">% Masuk ke Banjar</span>
                            <span class="text-sm font-black text-blue-700" x-text="(100 - desaUsaha).toFixed(2) + '%'"></span>
                        </div>
                        @if($globalUsaha)
                        <p class="text-[9px] text-slate-400 font-medium">
                            <i class="bi bi-clock mr-1"></i>Aktif sejak {{ $globalUsaha->berlaku_sejak->format('d M Y') }}
                        </p>
                        @else
                        <p class="text-[9px] text-slate-400 font-medium italic">Belum ada pengaturan (default: 100% ke Desa)</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                <div class="flex-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1 block mb-1.5">Berlaku Sejak *</label>
                    <input type="date" name="berlaku_sejak" required value="{{ date('Y-m-d') }}"
                        class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 w-full md:w-auto">
                </div>
                <button type="submit" class="px-8 py-3 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-600 transition-all mt-auto">
                    <i class="bi bi-check-lg mr-1"></i> Simpan Global
                </button>
            </div>
        </form>
    </div>

    <!-- Per-Banjar Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-slate-900 text-white rounded-xl flex items-center justify-center shadow-md">
                    <i class="bi bi-buildings text-lg"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 tracking-tight">Pengaturan Per Banjar</h3>
                    <p class="text-[10px] text-slate-500 font-medium">Override pengaturan global untuk banjar tertentu</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <form action="{{ url('administrator/pengaturan_bagi_hasil/terapkan_semua') }}" method="POST" class="inline"
                      onsubmit="return confirm('Hapus semua override khusus banjar? Semua banjar akan kembali menggunakan pengaturan global.')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                        <i class="bi bi-arrow-repeat mr-1"></i> Reset Semua ke Global
                    </button>
                </form>
                <button @click="showOverrideModal = true" class="px-4 py-2 bg-primary-light text-white rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md shadow-blue-100 hover:bg-blue-600 transition-all">
                    <i class="bi bi-plus-lg mr-1"></i> Override Banjar
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center" colspan="2">Punia Tamiu</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center" colspan="2">Punia Usaha</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-24">Aksi</th>
                    </tr>
                    <tr class="bg-slate-50/30 border-b border-slate-100">
                        <th class="px-6 py-2"></th>
                        <th class="px-3 py-2 text-[9px] font-bold text-slate-400 text-center">% Desa</th>
                        <th class="px-3 py-2 text-[9px] font-bold text-slate-400 text-center">% Banjar</th>
                        <th class="px-3 py-2 text-[9px] font-bold text-slate-400 text-center">% Desa</th>
                        <th class="px-3 py-2 text-[9px] font-bold text-slate-400 text-center">% Banjar</th>
                        <th class="px-6 py-2"></th>
                        <th class="px-6 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($banjarSettings as $bs)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold text-slate-700">{{ $bs['banjar']->nama_banjar }}</span>
                        </td>
                        <td class="px-3 py-4 text-center">
                            <span class="text-sm font-black {{ $bs['tamiu'] ? 'text-primary-light' : 'text-slate-400' }}">
                                {{ $bs['tamiu_effective'] ? number_format($bs['tamiu_effective']->persen_desa, 1) . '%' : '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-center">
                            <span class="text-sm font-black {{ $bs['tamiu'] ? 'text-primary-light' : 'text-slate-400' }}">
                                {{ $bs['tamiu_effective'] ? number_format($bs['tamiu_effective']->persen_banjar, 1) . '%' : '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-center">
                            <span class="text-sm font-black {{ $bs['usaha'] ? 'text-primary-light' : 'text-slate-400' }}">
                                {{ $bs['usaha_effective'] ? number_format($bs['usaha_effective']->persen_desa, 1) . '%' : '-' }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-center">
                            <span class="text-sm font-black {{ $bs['usaha'] ? 'text-primary-light' : 'text-slate-400' }}">
                                {{ $bs['usaha_effective'] ? number_format($bs['usaha_effective']->persen_banjar, 1) . '%' : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($bs['has_override'])
                            <span class="text-[9px] font-black px-2.5 py-1 rounded-lg bg-blue-50 text-primary-light border border-blue-100 uppercase">Custom</span>
                            @else
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-slate-50 text-slate-400 uppercase">Global</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($bs['has_override'])
                            <div class="flex items-center justify-end gap-1">
                                @if($bs['tamiu'])
                                <a href="{{ url('administrator/pengaturan_bagi_hasil/hapus/'.$bs['tamiu']->id_pengaturan) }}" 
                                   onclick="return confirm('Hapus override tamiu untuk {{ $bs['banjar']->nama_banjar }}?')"
                                   class="h-7 w-7 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all text-[9px]" title="Hapus override Tamiu">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                                @endif
                                @if($bs['usaha'])
                                <a href="{{ url('administrator/pengaturan_bagi_hasil/hapus/'.$bs['usaha']->id_pengaturan) }}" 
                                   onclick="return confirm('Hapus override usaha untuk {{ $bs['banjar']->nama_banjar }}?')"
                                   class="h-7 w-7 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all text-[9px]" title="Hapus override Usaha">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-[9px] text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-black text-slate-800 tracking-tight">Riwayat Pengaturan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Scope</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">% Desa</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">% Banjar</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Berlaku</th>
                        <th class="px-6 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-slate-50/50 transition-colors {{ !$r->aktif ? 'opacity-40' : '' }}">
                        <td class="px-6 py-3 text-xs font-medium text-slate-500">{{ $r->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-3">
                            <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-blue-50 text-primary-light">{{ ucfirst($r->jenis_punia) }}</span>
                        </td>
                        <td class="px-6 py-3 text-xs font-medium text-slate-600">{{ $r->banjar ? $r->banjar->nama_banjar : 'Global' }}</td>
                        <td class="px-6 py-3 text-center text-xs font-bold text-slate-600">{{ number_format($r->persen_desa, 1) }}%</td>
                        <td class="px-6 py-3 text-center text-xs font-bold text-slate-600">{{ number_format($r->persen_banjar, 1) }}%</td>
                        <td class="px-6 py-3 text-xs font-medium text-slate-500">{{ $r->berlaku_sejak->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            @if($r->aktif)
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-emerald-50 text-emerald-600">Aktif</span>
                            @else
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-400">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-sliders text-3xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">Belum ada pengaturan bagi hasil.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Override Banjar -->
    <template x-teleport="body">
        <div x-show="showOverrideModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8">
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showOverrideModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-100">
                            <i class="bi bi-buildings text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest block">Override</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Pengaturan Khusus Banjar</h3>
                        </div>
                    </div>
                    <button @click="showOverrideModal = false" class="h-8 w-8 bg-slate-100 hover:bg-blue-100 text-slate-400 hover:text-primary-light rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/pengaturan_bagi_hasil/banjar') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        Pengaturan ini akan menggantikan pengaturan global hanya untuk banjar yang dipilih.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar *</label>
                            <select name="id_data_banjar" x-model="overrideBanjar" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                                <option value="">— Pilih Banjar —</option>
                                @foreach($banjarList as $b)
                                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jenis Punia *</label>
                            <select name="jenis_punia" x-model="overrideJenis" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                                <option value="tamiu">Krama Tamiu</option>
                                <option value="usaha">Unit Usaha</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4" x-data="{ pDesa: 0 }">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">% Desa Adat *</label>
                                <input type="number" name="persen_desa" x-model="pDesa" step="0.01" min="0" max="100" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5"
                                    placeholder="0">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">% Banjar (otomatis)</label>
                                <div class="w-full bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-sm font-black text-primary-light" x-text="(100 - pDesa).toFixed(2) + '%'"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Berlaku Sejak *</label>
                            <input type="date" name="berlaku_sejak" x-model="overrideBerlaku" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan</label>
                            <input type="text" name="keterangan" x-model="overrideKeterangan"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5"
                                placeholder="Alasan override...">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showOverrideModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-600 transition-all">Simpan Override</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
