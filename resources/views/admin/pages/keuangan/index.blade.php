@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="keuanganPage()">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Keuangan</h1>
            <p class="text-slate-500 font-medium text-sm">Catatan lengkap pemasukan, pengeluaran, dan penarikan dana desa adat.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="glass-card p-5 h-28 flex flex-col justify-between bg-emerald-500 text-white border-none shadow-lg shadow-emerald-100">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black text-white/70 uppercase tracking-widest leading-none">Total Pemasukan</span>
                <i class="bi bi-arrow-down-circle text-white/50 text-lg"></i>
            </div>
            <p class="text-lg font-black tracking-tight">Rp {{ format_rupiah($totalPemasukan) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between border border-slate-200">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Pengeluaran</span>
                <i class="bi bi-arrow-up-circle text-slate-300 text-lg"></i>
            </div>
            <p class="text-lg font-black text-rose-600 tracking-tight">Rp {{ format_rupiah($totalPengeluaran) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between border border-slate-200">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Total Tarik Dana</span>
                <i class="bi bi-cash-stack text-slate-300 text-lg"></i>
            </div>
            <p class="text-lg font-black text-amber-600 tracking-tight">Rp {{ format_rupiah($totalTarik) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between bg-primary-light text-white border-none shadow-lg shadow-blue-100">
            <div class="flex items-center justify-between">
                <span class="text-[9px] font-black text-white/70 uppercase tracking-widest leading-none">Saldo Bersih</span>
                <i class="bi bi-wallet2 text-white/50 text-lg"></i>
            </div>
            <p class="text-lg font-black tracking-tight">Rp {{ format_rupiah($saldo) }}</p>
        </div>
    </div>

    <!-- Method Breakdown -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
            <div class="h-10 w-10 bg-blue-50 border border-blue-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-globe text-blue-500"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Online</span>
                <p class="text-sm font-black text-slate-800">Rp {{ format_rupiah($pemasukanOnline) }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
            <div class="h-10 w-10 bg-indigo-50 border border-indigo-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-credit-card text-indigo-500"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Transfer</span>
                <p class="text-sm font-black text-slate-800">Rp {{ format_rupiah($pemasukanTransfer) }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3">
            <div class="h-10 w-10 bg-emerald-50 border border-emerald-100 rounded-lg flex items-center justify-center">
                <i class="bi bi-cash-coin text-emerald-500"></i>
            </div>
            <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Cash</span>
                <p class="text-sm font-black text-slate-800">Rp {{ format_rupiah($pemasukanCash) }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Dari Tanggal</label>
                    <input type="date" x-model="dateAwal"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sampai Tanggal</label>
                    <input type="date" x-model="dateAkhir"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                </div>
            </div>
            <div class="flex gap-2 w-full lg:w-auto">
                <button @click="applyFilter()"
                        class="flex-1 lg:flex-none h-10 px-6 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                    Filter
                </button>
                <a href="{{ url('administrator/keuangan') }}" 
                   class="flex-1 lg:flex-none h-10 px-6 bg-white border border-slate-200 text-slate-500 hover:text-slate-700 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all flex items-center justify-center">
                    Reset
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex border-b border-slate-200">
            <button @click="switchTab('pemasukan')" 
                    :class="activeTab == 'pemasukan' ? 'border-b-2 border-emerald-500 text-emerald-600 bg-emerald-50/50' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'"
                    class="flex-1 px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                <i class="bi bi-arrow-down-circle"></i> Pemasukan
            </button>
            <button @click="switchTab('pengeluaran')" 
                    :class="activeTab == 'pengeluaran' ? 'border-b-2 border-rose-500 text-rose-600 bg-rose-50/50' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'"
                    class="flex-1 px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                <i class="bi bi-arrow-up-circle"></i> Pengeluaran
            </button>
            <button @click="switchTab('tarik')" 
                    :class="activeTab == 'tarik' ? 'border-b-2 border-amber-500 text-amber-600 bg-amber-50/50' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-50'"
                    class="flex-1 px-6 py-4 text-[11px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                <i class="bi bi-cash-stack"></i> Tarik Dana
            </button>
        </div>

        <!-- Tab Content: Pemasukan -->
        <div x-show="activeTab == 'pemasukan'" x-transition>
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <span class="text-xs font-bold text-slate-500">{{ $pemasukan->count() }} transaksi pemasukan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pemasukan', 'no')">No</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pemasukan', 'tanggal')">
                                Tanggal <i class="bi" :class="getSortIcon('pemasukan', 'tanggal')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pemasukan', 'sumber')">
                                Sumber <i class="bi" :class="getSortIcon('pemasukan', 'sumber')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pemasukan', 'nama')">
                                Nama <i class="bi" :class="getSortIcon('pemasukan', 'nama')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pemasukan', 'metode')">
                                Metode <i class="bi" :class="getSortIcon('pemasukan', 'metode')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none text-right" @click="sortBy('pemasukan', 'nominal')">
                                Nominal <i class="bi" :class="getSortIcon('pemasukan', 'nominal')"></i>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, idx) in sortedPemasukan" :key="idx">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 text-xs font-bold text-slate-400" x-text="idx + 1"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.tanggal_fmt"></td>
                                <td class="px-5 py-3">
                                    <span class="text-[9px] font-black uppercase px-2 py-1 rounded-lg"
                                          :class="row.sumber == 'Sumbangan' ? 'bg-pink-50 text-pink-600' : (row.sumber == 'Punia Usaha' ? 'bg-blue-50 text-blue-600' : 'bg-violet-50 text-violet-600')"
                                          x-text="row.sumber"></span>
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-700" x-text="row.nama"></td>
                                <td class="px-5 py-3 text-[10px] text-slate-500 max-w-[200px] truncate" x-text="row.keterangan"></td>
                                <td class="px-5 py-3">
                                    <span class="text-[9px] font-bold flex items-center gap-1 text-slate-500">
                                        <i class="bi" :class="row.metode == 'Online' ? 'bi-globe' : (row.metode == 'Transfer' ? 'bi-credit-card' : 'bi-cash-coin')"></i>
                                        <span x-text="row.metode"></span>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-xs font-black text-emerald-600" x-text="'Rp ' + formatRupiah(row.nominal)"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <template x-if="sortedPemasukan.length === 0">
                    <div class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-16 w-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <i class="bi bi-inbox text-3xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400">Belum ada data pemasukan</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Tab Content: Pengeluaran -->
        <div x-show="activeTab == 'pengeluaran'" x-transition>
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <span class="text-xs font-bold text-slate-500">{{ $pengeluaran->count() }} transaksi pengeluaran</span>
                <button @click="showPengeluaranModal = true"
                        class="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                    <i class="bi bi-plus-lg"></i> Tambah Pengeluaran
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pengeluaran', 'tanggal')">
                                Tanggal <i class="bi" :class="getSortIcon('pengeluaran', 'tanggal')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pengeluaran', 'kategori')">
                                Kategori <i class="bi" :class="getSortIcon('pengeluaran', 'kategori')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pengeluaran', 'penerima')">
                                Penerima <i class="bi" :class="getSortIcon('pengeluaran', 'penerima')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('pengeluaran', 'metode_pembayaran')">
                                Metode <i class="bi" :class="getSortIcon('pengeluaran', 'metode_pembayaran')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none text-right" @click="sortBy('pengeluaran', 'nominal')">
                                Nominal <i class="bi" :class="getSortIcon('pengeluaran', 'nominal')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, idx) in sortedPengeluaran" :key="row.id_keuangan">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 text-xs font-bold text-slate-400" x-text="idx + 1"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.tanggal"></td>
                                <td class="px-5 py-3">
                                    <span class="text-[9px] font-black uppercase px-2 py-1 rounded-lg bg-slate-100 text-slate-600" x-text="row.kategori || '-'"></span>
                                </td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-700" x-text="row.penerima || '-'"></td>
                                <td class="px-5 py-3 text-[10px] text-slate-500 max-w-[200px] truncate" x-text="row.keterangan"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.metode_pembayaran || 'Cash'"></td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-xs font-black text-rose-600" x-text="'Rp ' + formatRupiah(row.nominal)"></span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <button @click="if(confirm('Hapus record ini?')) window.location = '{{ url('administrator/keuangan/hapus') }}/' + row.id_keuangan"
                                            class="h-7 w-7 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-300 transition-all">
                                        <i class="bi bi-trash text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <template x-if="sortedPengeluaran.length === 0">
                    <div class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-16 w-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <i class="bi bi-inbox text-3xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400">Belum ada data pengeluaran</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Tab Content: Tarik Dana -->
        <div x-show="activeTab == 'tarik'" x-transition>
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <span class="text-xs font-bold text-slate-500">{{ $tarik->count() }} transaksi penarikan</span>
                <button @click="showTarikModal = true"
                        class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                    <i class="bi bi-plus-lg"></i> Tarik Dana
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/30">
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('tarik', 'tanggal')">
                                Tanggal <i class="bi" :class="getSortIcon('tarik', 'tanggal')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('tarik', 'penerima')">
                                Penerima <i class="bi" :class="getSortIcon('tarik', 'penerima')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none" @click="sortBy('tarik', 'nama_bank')">
                                Bank / Tujuan <i class="bi" :class="getSortIcon('tarik', 'nama_bank')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Rekening</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest cursor-pointer select-none text-right" @click="sortBy('tarik', 'nominal')">
                                Nominal <i class="bi" :class="getSortIcon('tarik', 'nominal')"></i>
                            </th>
                            <th class="px-5 py-3.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(row, idx) in sortedTarik" :key="row.id_keuangan">
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3 text-xs font-bold text-slate-400" x-text="idx + 1"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.tanggal"></td>
                                <td class="px-5 py-3 text-xs font-bold text-slate-700" x-text="row.penerima || '-'"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.nama_bank || '-'"></td>
                                <td class="px-5 py-3 text-[10px] font-bold text-slate-500" x-text="row.no_rekening || '-'"></td>
                                <td class="px-5 py-3 text-[10px] text-slate-500 max-w-[200px] truncate" x-text="row.keterangan"></td>
                                <td class="px-5 py-3 text-right">
                                    <span class="text-xs font-black text-amber-600" x-text="'Rp ' + formatRupiah(row.nominal)"></span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <button @click="if(confirm('Hapus record ini?')) window.location = '{{ url('administrator/keuangan/hapus') }}/' + row.id_keuangan"
                                            class="h-7 w-7 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-rose-500 hover:border-rose-300 transition-all">
                                        <i class="bi bi-trash text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <template x-if="sortedTarik.length === 0">
                    <div class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="h-16 w-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                <i class="bi bi-inbox text-3xl text-slate-300"></i>
                            </div>
                            <p class="text-sm font-bold text-slate-400">Belum ada data penarikan dana</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Modal: Tambah Pengeluaran -->
    <template x-teleport="body">
        <div x-show="showPengeluaranModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showPengeluaranModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-rose-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-rose-500 text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-arrow-up-circle text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-rose-600 uppercase tracking-widest block">Catat Transaksi</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Tambah Pengeluaran</h3>
                        </div>
                    </div>
                    <button @click="showPengeluaranModal = false" class="h-8 w-8 bg-slate-100 hover:bg-rose-100 text-slate-400 hover:text-rose-500 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/keuangan/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <input type="hidden" name="jenis" value="pengeluaran">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal *</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori</label>
                            <select name="kategori" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5">
                                <option value="operasional">Operasional</option>
                                <option value="pembangunan">Pembangunan</option>
                                <option value="upacara">Upacara / Keagamaan</option>
                                <option value="sosial">Sosial / Bantuan</option>
                                <option value="gaji">Gaji / Honor</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5">
                                <option value="cash">Cash / Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="online">Online</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Penerima Dana</label>
                        <input type="text" name="penerima" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5" placeholder="Nama penerima">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan *</label>
                        <textarea name="keterangan" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-rose-500/5" placeholder="Deskripsi pengeluaran"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti (Opsional)</label>
                        <input type="file" name="bukti" accept="image/*,.pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showPengeluaranModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-rose-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-rose-50 hover:bg-rose-600 transition-all">Simpan Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Modal: Tarik Dana -->
    <template x-teleport="body">
        <div x-show="showTarikModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showTarikModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-amber-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-cash-stack text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest block">Catat Transaksi</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Tarik Dana</h3>
                        </div>
                    </div>
                    <button @click="showTarikModal = false" class="h-8 w-8 bg-slate-100 hover:bg-amber-100 text-slate-400 hover:text-amber-500 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/keuangan/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <input type="hidden" name="jenis" value="tarik">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal *</label>
                            <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Penerima / Atas Nama *</label>
                        <input type="text" name="penerima" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="Nama penerima penarikan">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bank Tujuan</label>
                            <input type="text" name="nama_bank" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="BCA, BNI, BRI, dll">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. Rekening</label>
                            <input type="text" name="no_rekening" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="Nomor rekening tujuan">
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5">
                            <option value="transfer">Transfer Bank</option>
                            <option value="cash">Cash / Tunai</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan *</label>
                        <textarea name="keterangan" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="Alasan / tujuan penarikan dana"></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti (Opsional)</label>
                        <input type="file" name="bukti" accept="image/*,.pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showTarikModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-amber-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-amber-50 hover:bg-amber-600 transition-all">Simpan Penarikan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Simple flash notification
        const flash = document.createElement('div');
        flash.className = 'fixed top-6 right-6 z-[200] bg-emerald-500 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg';
        flash.textContent = '{{ session('success') }}';
        document.body.appendChild(flash);
        setTimeout(() => flash.remove(), 3000);
    });
</script>
@endif

<script>
function keuanganPage() {
    return {
        activeTab: '{{ $tab }}',
        dateAwal: '{{ $dateAwal }}',
        dateAkhir: '{{ $dateAkhir }}',
        showPengeluaranModal: false,
        showTarikModal: false,

        // Sort state per tab
        sorts: {
            pemasukan: { field: null, dir: 'asc' },
            pengeluaran: { field: null, dir: 'asc' },
            tarik: { field: null, dir: 'asc' }
        },

        // Raw data from server
        rawPemasukan: @json($pemasukan->map(function($row) {
            return [
                'tanggal' => $row['tanggal'],
                'tanggal_fmt' => \Carbon\Carbon::parse($row['tanggal'])->format('d M Y'),
                'sumber' => $row['sumber'],
                'nama' => $row['nama'],
                'keterangan' => $row['keterangan'],
                'metode' => $row['metode'],
                'nominal' => (float)$row['nominal'],
            ];
        })->values()),

        rawPengeluaran: @json($pengeluaran->map(function($row) {
            return [
                'id_keuangan' => $row->id_keuangan,
                'tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d'),
                'kategori' => $row->kategori,
                'penerima' => $row->penerima,
                'keterangan' => $row->keterangan,
                'metode_pembayaran' => $row->metode_pembayaran,
                'nominal' => (float)$row->nominal,
            ];
        })->values()),

        rawTarik: @json($tarik->map(function($row) {
            return [
                'id_keuangan' => $row->id_keuangan,
                'tanggal' => \Carbon\Carbon::parse($row->tanggal)->format('Y-m-d'),
                'penerima' => $row->penerima,
                'nama_bank' => $row->nama_bank,
                'no_rekening' => $row->no_rekening,
                'keterangan' => $row->keterangan,
                'nominal' => (float)$row->nominal,
            ];
        })->values()),

        get sortedPemasukan() {
            return this.applySorting('pemasukan', this.rawPemasukan);
        },
        get sortedPengeluaran() {
            return this.applySorting('pengeluaran', this.rawPengeluaran);
        },
        get sortedTarik() {
            return this.applySorting('tarik', this.rawTarik);
        },

        sortBy(tab, field) {
            if (this.sorts[tab].field === field) {
                this.sorts[tab].dir = this.sorts[tab].dir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sorts[tab].field = field;
                this.sorts[tab].dir = 'asc';
            }
        },

        getSortIcon(tab, field) {
            if (this.sorts[tab].field !== field) return 'bi-chevron-expand';
            return this.sorts[tab].dir === 'asc' ? 'bi-chevron-up' : 'bi-chevron-down';
        },

        applySorting(tab, data) {
            const s = this.sorts[tab];
            if (!s.field) return [...data];
            return [...data].sort((a, b) => {
                let va = a[s.field];
                let vb = b[s.field];
                if (typeof va === 'number') return s.dir === 'asc' ? va - vb : vb - va;
                va = (va || '').toString().toLowerCase();
                vb = (vb || '').toString().toLowerCase();
                if (va < vb) return s.dir === 'asc' ? -1 : 1;
                if (va > vb) return s.dir === 'asc' ? 1 : -1;
                return 0;
            });
        },

        switchTab(tab) {
            this.activeTab = tab;
        },

        applyFilter() {
            let url = '{{ url('administrator/keuangan') }}?tab=' + this.activeTab;
            if (this.dateAwal) url += '&dateawal=' + this.dateAwal;
            if (this.dateAkhir) url += '&dateakhir=' + this.dateAkhir;
            window.location = url;
        },

        formatRupiah(num) {
            if (!num) return '0';
            return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    };
}
</script>

@stop
