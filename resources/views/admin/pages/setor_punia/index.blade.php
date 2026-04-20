@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showModal: false,
    showDeleteAlokasiModal: false,
    jenisAlur: '',
    activeAlokasiTab: '{{ $activeAlokasiTab }}',
    deleteAlokasiId: null,
    deleteAlokasiLabel: '',
    deleteAlokasiReason: '',
    openDeleteAlokasi(id, label, tab) {
        this.deleteAlokasiId = id;
        this.deleteAlokasiLabel = label;
        this.deleteAlokasiReason = '';
        this.activeAlokasiTab = tab;
        this.showDeleteAlokasiModal = true;
    },
    filterJenis: '{{ $filterJenis }}',
    filterBanjar: '{{ $filterBanjar }}',
    openModal(alur) {
        this.jenisAlur = alur;
        this.showModal = true;
    },
    applyFilter() {
        let url = '{{ url('administrator/setor_punia') }}';
        let params = [];
        if(this.filterJenis) params.push('jenis=' + this.filterJenis);
        if(this.filterBanjar) params.push('banjar=' + this.filterBanjar);
        if(this.activeAlokasiTab) params.push('tab=' + this.activeAlokasiTab);
        if(params.length > 0) url += '?' + params.join('&');
        window.location = url;
    },
    getAlurLabel(alur) {
        const labels = {
            'penagih_ke_banjar': 'Penagih → Banjar',
            'banjar_ke_desa': 'Banjar → Desa',
            'desa_tarik_pg': 'Desa Tarik PG',
            'desa_ke_banjar': 'Desa → Banjar'
        };
        return labels[alur] || alur;
    },
    getModalTitle(alur) {
        const titles = {
            'penagih_ke_banjar': 'Setor Cash Penagih ke Banjar',
            'banjar_ke_desa': 'Setor Bagian Desa dari Banjar',
            'desa_tarik_pg': 'Tarik Dana dari Payment Gateway',
            'desa_ke_banjar': 'Setor Bagian Banjar dari Desa'
        };
        return titles[alur] || '';
    },
    getModalDesc(alur) {
        const descs = {
            'penagih_ke_banjar': 'Penagih menyerahkan uang tunai hasil penagihan punia ke kas banjar.',
            'banjar_ke_desa': 'Banjar menyetorkan bagian persentase Desa Adat dari punia cash yang terkumpul.',
            'desa_tarik_pg': 'Desa Adat menarik dana dari akun Payment Gateway (Xendit) ke rekening bank.',
            'desa_ke_banjar': 'Desa Adat menyerahkan bagian persentase banjar dari punia online yang sudah ditarik.'
        };
        return descs[alur] || '';
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Setor & Tarik Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola alur kas punia antara penagih, banjar, dan desa adat.</p>
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

    @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
        <div class="flex items-center gap-2">
            <i class="bi bi-exclamation-circle text-rose-500"></i>
            <p class="text-sm text-rose-700 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 rounded-xl p-4">
        <div class="flex items-start gap-2">
            <i class="bi bi-exclamation-circle text-rose-500 mt-0.5"></i>
            <p class="text-sm text-rose-700 font-medium">{{ $errors->first() }}</p>
        </div>
    </div>
    @endif

    <!-- 4 Flow Action Buttons -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <button @click="openModal('penagih_ke_banjar')" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:border-primary-light hover:shadow-md transition-all group text-left">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-primary-light group-hover:text-white transition-all">
                <i class="bi bi-person-check text-lg"></i>
            </div>
            <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Penagih → Banjar</p>
            <p class="text-[9px] text-slate-400 mt-1">Setor cash penagihan</p>
        </button>
        <button @click="openModal('banjar_ke_desa')" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:border-primary-light hover:shadow-md transition-all group text-left">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-primary-light group-hover:text-white transition-all">
                <i class="bi bi-arrow-right-circle text-lg"></i>
            </div>
            <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Banjar → Desa</p>
            <p class="text-[9px] text-slate-400 mt-1">Setor bagian desa</p>
        </button>
        <button @click="openModal('desa_tarik_pg')" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:border-primary-light hover:shadow-md transition-all group text-left">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-primary-light group-hover:text-white transition-all">
                <i class="bi bi-bank text-lg"></i>
            </div>
            <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Desa Tarik PG</p>
            <p class="text-[9px] text-slate-400 mt-1">Tarik dari Xendit</p>
        </button>
        <button @click="openModal('desa_ke_banjar')" class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:border-primary-light hover:shadow-md transition-all group text-left">
            <div class="h-10 w-10 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center mb-3 group-hover:bg-primary-light group-hover:text-white transition-all">
                <i class="bi bi-arrow-left-circle text-lg"></i>
            </div>
            <p class="text-[10px] font-black text-slate-700 uppercase tracking-widest">Desa → Banjar</p>
            <p class="text-[9px] text-slate-400 mt-1">Setor bagian banjar</p>
        </button>
    </div>

    <!-- Saldo Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Saldo Desa -->
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-bank2 text-lg text-white/70"></i>
                <p class="text-[10px] font-black text-white/80 uppercase tracking-widest">Saldo Kas Desa Adat</p>
            </div>
            <p class="text-2xl font-black tracking-tight mb-2">Rp {{ number_format($saldoDesa->saldo_cash + $saldoDesa->saldo_online, 0, ',', '.') }}</p>
            <div class="flex gap-4 text-[9px] text-white/60 font-medium">
                <span>Cash: Rp {{ number_format($saldoDesa->saldo_cash, 0, ',', '.') }}</span>
                <span>Online: Rp {{ number_format($saldoDesa->saldo_online, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Total Saldo Banjar -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-buildings text-lg text-primary-light"></i>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Saldo Semua Banjar</p>
            </div>
            <p class="text-2xl font-black text-slate-800 tracking-tight mb-2">Rp {{ number_format($totalSaldoBanjar, 0, ',', '.') }}</p>
            <span class="text-[9px] text-slate-400 font-medium">{{ count($banjarSaldos) }} banjar terdaftar</span>
        </div>

        <!-- Pending -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <i class="bi bi-clock text-lg text-primary-light"></i>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Menunggu Verifikasi</p>
            </div>
            <p class="text-2xl font-black text-primary-light tracking-tight mb-2">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
            <span class="text-[9px] text-slate-400 font-medium">Diterima: Rp {{ number_format($totalSetorDiterima, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Saldo Per Banjar -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center">
                <i class="bi bi-buildings text-sm"></i>
            </div>
            <h3 class="text-sm font-black text-slate-800 tracking-tight">Saldo Per Banjar</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Saldo Cash</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Saldo Online</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total Saldo</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Hutang ke Desa</th>
                        <th class="px-5 py-3 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Hak dari Desa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($banjarSaldos as $bs)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-3 text-xs font-bold text-slate-700">{{ $bs['banjar']->nama_banjar }}</td>
                        <td class="px-5 py-3 text-right text-xs font-bold text-slate-600">Rp {{ number_format($bs['saldo']->saldo_cash, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-xs font-bold text-slate-600">Rp {{ number_format($bs['saldo']->saldo_online, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-sm font-black text-primary-light">Rp {{ number_format($bs['saldo']->total_saldo, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-xs font-bold {{ $bs['hutang_ke_desa'] > 0 ? 'text-rose-500' : 'text-slate-300' }}">
                            {{ $bs['hutang_ke_desa'] > 0 ? 'Rp ' . number_format($bs['hutang_ke_desa'], 0, ',', '.') : '-' }}
                        </td>
                        <td class="px-5 py-3 text-right text-xs font-bold {{ $bs['hak_dari_desa'] > 0 ? 'text-emerald-500' : 'text-slate-300' }}">
                            {{ $bs['hak_dari_desa'] > 0 ? 'Rp ' . number_format($bs['hak_dari_desa'], 0, ',', '.') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Riwayat Alokasi Transaksi Punia -->
    @php
        $alokasiTabs = [
            'tamiu' => [
                'title' => 'Krama Tamiu',
                'items' => $alokasiHistoryTamiu,
                'activeClass' => 'bg-blue-50 text-primary-light border-blue-200',
            ],
            'usaha' => [
                'title' => 'Unit Usaha',
                'items' => $alokasiHistoryUsaha,
                'activeClass' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
            ],
            'hapus' => [
                'title' => 'History Hapus',
                'items' => $riwayatHapus,
                'activeClass' => 'bg-rose-50 text-rose-600 border-rose-200',
            ],
        ];
    @endphp
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Alokasi Transaksi Punia</h3>
                <p class="text-[11px] text-slate-400 mt-1">Riwayat dipisahkan per jenis punia agar lebih mudah dicek, mendukung bulk edit status, dan punya history penghapusan data.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach($alokasiTabs as $tabKey => $tab)
                <button type="button"
                        @click="activeAlokasiTab = '{{ $tabKey }}'"
                        :class="activeAlokasiTab === '{{ $tabKey }}' ? '{{ $tab['activeClass'] }}' : 'bg-white text-slate-500 border-slate-200'"
                        class="px-4 py-2 rounded-xl border text-xs font-black uppercase tracking-widest transition-all">
                    {{ $tab['title'] }}
                    <span class="ml-1.5 text-[10px]">{{ $tab['items']->count() }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div class="px-5 py-4 bg-slate-50/70 border-b border-slate-100">
            <p class="text-[11px] text-slate-500 font-medium">Bulk edit akan mengubah status global sesuai jalur pembagian. Hapus transaksi akan mengeluarkan data dari riwayat aktif dan perhitungan keuangan aktif.</p>
        </div>

        @foreach(['tamiu', 'usaha'] as $tabKey)
        @php
            $tab = $alokasiTabs[$tabKey];
            $pendingCount = $tab['items']->where('status_global', 'pending')->count();
        @endphp
        <div x-show="activeAlokasiTab === '{{ $tabKey }}'" x-cloak>
            <form action="{{ url('administrator/setor_punia/alokasi/bulk-status') }}" method="POST">
                @csrf
                <input type="hidden" name="jenis_punia_tab" value="{{ $tabKey }}">
                <input type="hidden" name="tab" value="{{ $tabKey }}">
                <input type="hidden" name="filter_banjar" value="{{ $filterBanjar }}">
                <input type="hidden" name="filter_jenis" value="{{ $filterJenis }}">

                <div class="p-5 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-sm font-black text-slate-800">Daftar {{ $tab['title'] }}</h4>
                        <p class="text-[11px] text-slate-400 mt-1">{{ $tab['items']->count() }} transaksi, {{ $pendingCount }} transaksi masih menunggu setor sesuai status terbaru yang ditetapkan.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
                        <select name="bulk_status_action" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                            <option value="follow_global_selesai">Ikuti Pembagian Global → Tandai Selesai</option>
                            <option value="follow_global_pending">Ikuti Pembagian Global → Tandai Menunggu</option>
                        </select>
                        <button type="submit" onclick="return confirm('Terapkan bulk edit ke riwayat terpilih?')" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md">
                            <i class="bi bi-check2-square mr-1"></i>Terapkan Bulk Edit
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-5 py-4 w-12">
                                    <input type="checkbox" onclick="this.form.querySelectorAll('input[name=&quot;riwayat_ids[]&quot;]').forEach(cb => cb.checked = this.checked)" class="rounded border-slate-300 text-primary-light focus:ring-primary-light/20">
                                </th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Metode</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Total</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Bagian Desa</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Bagian Banjar</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status Global</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($tab['items'] as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors {{ $item->status_global === 'pending' ? 'bg-amber-50/30' : '' }}">
                                <td class="px-5 py-4">
                                    <input type="checkbox" name="riwayat_ids[]" value="{{ $item->id_riwayat }}" class="rounded border-slate-300 text-primary-light focus:ring-primary-light/20">
                                </td>
                                <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ optional($item->tanggal_transaksi)->format('d M Y H:i') ?: optional($item->tanggal)->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-xs font-bold text-slate-700">{{ $item->subjek_nama }}</p>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $item->subjek_label }}</p>
                                </td>
                                <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $item->banjar->nama_banjar ?? '-' }}</td>
                                <td class="px-5 py-4 text-[10px] font-bold text-slate-500 uppercase">{{ $item->metode_pembayaran ?: '-' }}</td>
                                <td class="px-5 py-4 text-right text-xs font-bold text-slate-700">Rp {{ number_format($item->nominal_total, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right text-xs font-bold text-primary-light">Rp {{ number_format($item->nominal_desa, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-right text-xs font-bold text-emerald-600">Rp {{ number_format($item->nominal_banjar, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg {{ $item->status_global === 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">{{ $item->status_text }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button type="button" @click="openDeleteAlokasi({{ $item->id_riwayat }}, '{{ addslashes($item->subjek_nama) }}', '{{ $tabKey }}')" class="h-8 w-8 inline-flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition-all" title="Hapus transaksi dari perhitungan">
                                        <i class="bi bi-trash text-[11px]"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <i class="bi bi-diagram-3 text-4xl text-slate-200"></i>
                                        <p class="text-sm text-slate-400 font-medium">Belum ada riwayat alokasi {{ strtolower($tab['title']) }} yang bisa ditampilkan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
        @endforeach

        <div x-show="activeAlokasiTab === 'hapus'" x-cloak>
            <div class="p-5 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-black text-slate-800">History Penghapusan Data Punia</h4>
                    <p class="text-[11px] text-slate-400 mt-1">Semua transaksi yang dihapus akan tercatat di sini bersama alasan penghapusan dan petugasnya.</p>
                </div>
                <div class="text-xs font-bold text-slate-400">{{ $riwayatHapus->count() }} data terhapus</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Transaksi</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dihapus Pada</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dihapus Oleh</th>
                            <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($riwayatHapus as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ optional($item->tanggal)->format('d M Y H:i') ?: '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-lg {{ $item->jenis_punia === 'tamiu' ? 'bg-blue-50 text-primary-light border border-blue-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100' }} inline-flex items-center gap-1">{{ $item->subjek_label }}</span>
                            </td>
                            <td class="px-5 py-4 text-xs font-bold text-slate-700">{{ $item->subjek_nama }}</td>
                            <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $item->banjar->nama_banjar ?? '-' }}</td>
                            <td class="px-5 py-4 text-right text-xs font-bold text-slate-700">Rp {{ number_format($item->nominal_total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ optional($item->tanggal_hapus)->format('d M Y H:i') ?: '-' }}</td>
                            <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $item->deleted_by_name }}</td>
                            <td class="px-5 py-4 text-xs text-slate-600">{{ $item->catatan_hapus }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="bi bi-archive text-4xl text-slate-200"></i>
                                    <p class="text-sm text-slate-400 font-medium">Belum ada histori penghapusan transaksi punia.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <select x-model="filterJenis" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Alur</option>
                <option value="penagih_ke_banjar">Penagih → Banjar</option>
                <option value="banjar_ke_desa">Banjar → Desa</option>
                <option value="desa_tarik_pg">Desa Tarik PG</option>
                <option value="desa_ke_banjar">Desa → Banjar</option>
            </select>
            <select x-model="filterBanjar" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Banjar</option>
                @foreach($banjarList as $b)
                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                @endforeach
            </select>
            <button @click="applyFilter()" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-primary-light transition-all shadow-md">
                <i class="bi bi-funnel mr-1"></i> Filter
            </button>
            @if($filterBanjar || $filterJenis)
            <a href="{{ url('administrator/setor_punia') }}" class="text-xs text-slate-500 hover:text-rose-500 font-bold transition-colors">
                <i class="bi bi-x-circle mr-1"></i>Reset
            </a>
            @endif
        </div>
    </div>

    <!-- Riwayat Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Setor & Penarikan</h3>
            <span class="text-xs font-bold text-slate-400">{{ $riwayat->count() }} record</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest w-12">No</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Alur</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Dari / Ke</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penyerah</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penerima (TTD)</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $i => $r)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ $r->tanggal_setor->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            @php
                                $alurLabels = [
                                    'penagih_ke_banjar' => ['Penagih → Banjar', 'bi-person-check'],
                                    'banjar_ke_desa' => ['Banjar → Desa', 'bi-arrow-right-circle'],
                                    'desa_tarik_pg' => ['Desa Tarik PG', 'bi-bank'],
                                    'desa_ke_banjar' => ['Desa → Banjar', 'bi-arrow-left-circle'],
                                ];
                                $label = $alurLabels[$r->jenis_alur] ?? [$r->jenis_alur ?? $r->jenis_setor, 'bi-arrow-right'];
                            @endphp
                            <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-lg bg-blue-50 text-primary-light border border-blue-100 inline-flex items-center gap-1">
                                <i class="{{ $label[1] }} text-[8px]"></i> {{ $label[0] }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-[10px] text-slate-600">
                            @if($r->banjar)
                                <span class="font-bold">{{ $r->banjar->nama_banjar }}</span>
                            @endif
                            @if($r->banjarTujuan)
                                → <span class="font-bold">{{ $r->banjarTujuan->nama_banjar }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[10px] font-medium text-slate-600">{{ $r->nama_penyerah ?: '-' }}</span>
                            @if($r->jabatan_penyerah)
                            <br><span class="text-[9px] text-slate-400">{{ $r->jabatan_penyerah }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[10px] font-medium text-slate-600">{{ $r->nama_penerima_ttd ?: '-' }}</span>
                            @if($r->jabatan_penerima)
                            <br><span class="text-[9px] text-slate-400">{{ $r->jabatan_penerima }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($r->status === 'diterima')
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600">Diterima</span>
                            @elseif($r->status === 'pending')
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-blue-50 text-primary-light">Pending</span>
                            @else
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold text-primary-light">Rp {{ number_format($r->nominal, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if($r->status === 'pending')
                                <form action="{{ url('administrator/setor_punia/verify/'.$r->id_setor_punia) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="diterima">
                                    <button type="submit" onclick="return confirm('Verifikasi dan terima?')" class="h-7 w-7 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-100 transition-all" title="Terima"><i class="bi bi-check-lg text-xs"></i></button>
                                </form>
                                <form action="{{ url('administrator/setor_punia/verify/'.$r->id_setor_punia) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="ditolak">
                                    <button type="submit" onclick="return confirm('Tolak?')" class="h-7 w-7 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-100 transition-all" title="Tolak"><i class="bi bi-x-lg text-[9px]"></i></button>
                                </form>
                                @endif
                                @if($r->bukti || $r->tanda_tangan)
                                <a href="{{ asset('bukti_keuangan/'.($r->bukti ?: $r->tanda_tangan)) }}" target="_blank" class="h-7 w-7 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Lihat Bukti"><i class="bi bi-image text-xs"></i></a>
                                @endif
                                <a href="{{ url('administrator/setor_punia/hapus/'.$r->id_setor_punia) }}" onclick="return confirm('Hapus?')" class="h-7 w-7 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all" title="Hapus"><i class="bi bi-trash text-[9px]"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-cash-stack text-4xl text-slate-200"></i>
                                <p class="text-sm text-slate-400 font-medium">Belum ada catatan setor atau penarikan punia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="showDeleteAlokasiModal" x-cloak
             class="fixed inset-0 z-[110] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8">
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showDeleteAlokasiModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-rose-50/60">
                    <div>
                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest block">Hapus Transaksi Punia</span>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight" x-text="deleteAlokasiLabel || 'Transaksi' "></h3>
                    </div>
                    <button @click="showDeleteAlokasiModal = false" class="h-8 w-8 bg-white hover:bg-rose-100 text-slate-400 hover:text-rose-500 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>

                <form action="{{ url('administrator/setor_punia/alokasi/hapus') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="id_riwayat" :value="deleteAlokasiId">
                    <input type="hidden" name="tab" :value="activeAlokasiTab">
                    <input type="hidden" name="filter_banjar" value="{{ $filterBanjar }}">
                    <input type="hidden" name="filter_jenis" value="{{ $filterJenis }}">

                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-xs text-rose-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        Data ini akan dikeluarkan dari transaksi aktif, riwayat alokasi aktif, dan sinkronisasi saldo aktif. Alasan penghapusan wajib dicatat.
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alasan Penghapusan *</label>
                        <textarea name="catatan_hapus" x-model="deleteAlokasiReason" rows="4" required minlength="5" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-300" placeholder="Contoh: salah input nominal, pembayaran duplikat, transaksi tidak valid"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showDeleteAlokasiModal = false" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-500 text-white text-xs font-black uppercase tracking-widest hover:bg-rose-600 transition-all">Hapus dari Perhitungan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Universal Modal -->
    <template x-teleport="body">
        <div x-show="showModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8">
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-lg shadow-blue-100">
                            <i class="bi bi-cash-coin text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest block">Catat Setoran</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight" x-text="getModalTitle(jenisAlur)"></h3>
                        </div>
                    </div>
                    <button @click="showModal = false" class="h-8 w-8 bg-slate-100 hover:bg-blue-100 text-slate-400 hover:text-primary-light rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/setor_punia/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <input type="hidden" name="jenis_alur" :value="jenisAlur">

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-xs text-blue-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        <span x-text="getModalDesc(jenisAlur)"></span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="0">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal *</label>
                            <input type="date" name="tanggal_setor" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                        </div>
                    </div>

                    <!-- Banjar Asal (for penagih_ke_banjar, banjar_ke_desa) -->
                    <div x-show="jenisAlur === 'penagih_ke_banjar' || jenisAlur === 'banjar_ke_desa'" class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar Asal *</label>
                        <select name="id_data_banjar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                            <option value="">— Pilih Banjar —</option>
                            @foreach($banjarList as $b)
                            <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Banjar Tujuan (for desa_ke_banjar) -->
                    <div x-show="jenisAlur === 'desa_ke_banjar'" class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar Tujuan *</label>
                        <select name="id_data_banjar_tujuan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                            <option value="">— Pilih Banjar —</option>
                            @foreach($banjarList as $b)
                            <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sumber Punia -->
                    <div x-show="jenisAlur !== 'desa_tarik_pg'" class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sumber Punia</label>
                        <select name="sumber_punia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                            <option value="campuran">Campuran</option>
                            <option value="tamiu">Krama Tamiu</option>
                            <option value="usaha">Unit Usaha</option>
                            <option value="umum">Umum / Lainnya</option>
                        </select>
                    </div>

                    <!-- Penyerah & Penerima TTD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Penyerah *</label>
                            <input type="text" name="nama_penyerah" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Nama yang menyerahkan">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan Penyerah</label>
                            <input type="text" name="jabatan_penyerah" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Penagih / Kelian / dll">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Penerima (TTD) *</label>
                            <input type="text" name="nama_penerima_ttd" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Nama yang menandatangani">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Jabatan Penerima</label>
                            <input type="text" name="jabatan_penerima" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Bendahara / Kelian Adat / dll">
                        </div>
                    </div>

                    <!-- Bank info for desa_tarik_pg -->
                    <div x-show="jenisAlur === 'desa_tarik_pg'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bank Tujuan</label>
                            <input type="text" name="nama_bank" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="BCA, BNI, BRI">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. Rekening</label>
                            <input type="text" name="no_rekening" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Nomor rekening">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan *</label>
                        <textarea name="keterangan" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5" placeholder="Keterangan setoran..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti Setor</label>
                            <input type="file" name="bukti" accept="image/*,.pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Foto Tanda Tangan</label>
                            <input type="file" name="tanda_tangan" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-600 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>
@endsection
