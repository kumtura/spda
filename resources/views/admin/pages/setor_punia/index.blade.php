@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showSetorModal: false, 
    showTarikModal: false,
    activeTab: 'semua',
    filterJenis: '{{ $filterJenis }}',
    filterBanjar: '{{ $filterBanjar }}',
    applyFilter() {
        let url = '{{ url('administrator/setor_punia') }}';
        let params = [];
        if(this.filterJenis) params.push('jenis=' + this.filterJenis);
        if(this.filterBanjar) params.push('banjar=' + this.filterBanjar);
        if(params.length > 0) url += '?' + params.join('&');
        window.location = url;
    }
}">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Setor & Tarik Punia</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola penyetoran uang cash dan penarikan dana dari payment gateway.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showSetorModal = true" class="flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                <i class="bi bi-cash-coin"></i> Setor Cash
            </button>
            <button @click="showTarikModal = true" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                <i class="bi bi-bank"></i> Tarik Online
            </button>
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

    <!-- Tracking Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Cash Tracking -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-emerald-50 border-b border-emerald-100 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-md">
                        <i class="bi bi-cash-stack text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-emerald-800 tracking-tight">Kas Cash (Tunai)</h3>
                        <p class="text-[10px] text-emerald-600/70 font-medium">Dari pembayaran cash kelian banjar</p>
                    </div>
                </div>
            </div>
            <div class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Cash Tamiu</p>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($totalCashTamiu, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Cash Usaha</p>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($totalCashUsaha, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Sudah Disetor</span>
                    <span class="text-sm font-black text-emerald-700">Rp {{ number_format($totalSudahSetorCash, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between bg-rose-50 rounded-xl p-3 border border-rose-100">
                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Belum Disetor</span>
                    <span class="text-lg font-black text-rose-700">Rp {{ number_format($cashBelumSetor, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Online Tracking -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-amber-50 border-b border-amber-100 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-md">
                        <i class="bi bi-globe text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-amber-800 tracking-tight">Kas Online (Payment Gateway)</h3>
                        <p class="text-[10px] text-amber-600/70 font-medium">Dari pembayaran QRIS / Xendit</p>
                    </div>
                </div>
            </div>
            <div class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Online Tamiu</p>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($totalOnlineTamiu, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Online Usaha</p>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($totalOnlineUsaha, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between bg-amber-50 rounded-xl p-3 border border-amber-100">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">Sudah Ditarik</span>
                    <span class="text-sm font-black text-amber-700">Rp {{ number_format($totalSudahTarikOnline, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between bg-rose-50 rounded-xl p-3 border border-rose-100">
                    <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Belum Ditarik</span>
                    <span class="text-lg font-black text-rose-700">Rp {{ number_format($onlineBelumTarik, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Bar -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
            <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1.5">Total Sudah Diterima</p>
            <p class="text-xl font-black tracking-tight">Rp {{ number_format($totalSetorDiterima, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Menunggu Verifikasi</p>
            <p class="text-xl font-black text-amber-500 tracking-tight">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm col-span-2 md:col-span-1">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Total Belum Diproses</p>
            <p class="text-xl font-black text-rose-500 tracking-tight">Rp {{ number_format($cashBelumSetor + $onlineBelumTarik, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-wrap items-center gap-3">
            <select x-model="filterJenis" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                <option value="">Semua Jenis</option>
                <option value="setor_cash">Setor Cash</option>
                <option value="tarik_online">Tarik Online</option>
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
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Sumber</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Banjar</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                        <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $i => $r)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 text-xs font-medium text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-5 py-4 text-xs font-bold text-slate-600">{{ $r->tanggal_setor->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            @if($r->jenis_setor === 'setor_cash')
                            <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">Setor Cash</span>
                            @else
                            <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 border border-amber-100">Tarik Online</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-[9px] font-bold uppercase px-2 py-0.5 rounded
                                {{ $r->sumber_punia === 'tamiu' ? 'bg-blue-50 text-blue-600' : ($r->sumber_punia === 'usaha' ? 'bg-violet-50 text-violet-600' : 'bg-slate-100 text-slate-600') }}">
                                {{ ucfirst($r->sumber_punia) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs font-medium text-slate-600">{{ $r->banjar->nama_banjar ?? '-' }}</td>
                        <td class="px-5 py-4 text-[10px] text-slate-500 max-w-[200px] truncate">{{ $r->keterangan }}</td>
                        <td class="px-5 py-4 text-center">
                            @if($r->status === 'diterima')
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600">Diterima</span>
                            @elseif($r->status === 'pending')
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600">Pending</span>
                            @else
                            <span class="text-[9px] font-bold px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <span class="text-sm font-bold {{ $r->jenis_setor === 'setor_cash' ? 'text-emerald-600' : 'text-amber-600' }}">
                                Rp {{ number_format($r->nominal, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if($r->status === 'pending')
                                <form action="{{ url('administrator/setor_punia/verify/'.$r->id_setor_punia) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="diterima">
                                    <button type="submit" onclick="return confirm('Verifikasi dan terima setoran ini?')" 
                                            class="h-8 w-8 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center hover:bg-emerald-100 transition-all" title="Terima">
                                        <i class="bi bi-check-lg text-sm"></i>
                                    </button>
                                </form>
                                <form action="{{ url('administrator/setor_punia/verify/'.$r->id_setor_punia) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="ditolak">
                                    <button type="submit" onclick="return confirm('Tolak setoran ini?')" 
                                            class="h-8 w-8 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center hover:bg-rose-100 transition-all" title="Tolak">
                                        <i class="bi bi-x-lg text-xs"></i>
                                    </button>
                                </form>
                                @endif
                                @if($r->bukti)
                                <a href="{{ asset('bukti_keuangan/'.$r->bukti) }}" target="_blank" class="h-8 w-8 bg-blue-50 text-primary-light rounded-lg flex items-center justify-center hover:bg-blue-100 transition-all" title="Lihat Bukti">
                                    <i class="bi bi-image text-sm"></i>
                                </a>
                                @endif
                                <a href="{{ url('administrator/setor_punia/hapus/'.$r->id_setor_punia) }}" onclick="return confirm('Hapus record ini?')"
                                   class="h-8 w-8 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center hover:bg-rose-50 hover:text-rose-500 transition-all" title="Hapus">
                                    <i class="bi bi-trash text-xs"></i>
                                </a>
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

    <!-- Modal: Setor Cash -->
    <template x-teleport="body">
        <div x-show="showSetorModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8">
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showSetorModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-cash-coin text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest block">Catat Penyetoran</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Setor Cash ke Kas Desa</h3>
                        </div>
                    </div>
                    <button @click="showSetorModal = false" class="h-8 w-8 bg-slate-100 hover:bg-emerald-100 text-slate-400 hover:text-emerald-500 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/setor_punia/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <input type="hidden" name="jenis_setor" value="setor_cash">
                    
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-xs text-emerald-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        Catat penyetoran uang tunai yang dikumpulkan oleh kelian dari pembayaran punia warga ke kas Desa Adat.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5" placeholder="0">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal Setor *</label>
                            <input type="date" name="tanggal_setor" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sumber Punia</label>
                            <select name="sumber_punia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5">
                                <option value="campuran">Campuran</option>
                                <option value="tamiu">Krama Tamiu</option>
                                <option value="usaha">Unit Usaha</option>
                                <option value="umum">Umum / Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Banjar Asal</label>
                            <select name="id_data_banjar" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5">
                                <option value="">— Semua / Umum —</option>
                                @foreach($banjarList as $b)
                                <option value="{{ $b->id_data_banjar }}">{{ $b->nama_banjar }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Penerima Setoran</label>
                        <input type="text" name="penerima" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5" placeholder="Nama bendahara / penerima">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan *</label>
                        <textarea name="keterangan" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5" placeholder="Setor punia bulan April 2026 dari kelian banjar..."></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti Setor (Opsional)</label>
                        <input type="file" name="bukti" accept="image/*,.pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showSetorModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="submit" class="px-8 py-2.5 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-50 hover:bg-emerald-600 transition-all">Simpan Setoran</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <!-- Modal: Tarik Online -->
    <template x-teleport="body">
        <div x-show="showTarikModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8">
            <div class="bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showTarikModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-amber-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-amber-500 text-white rounded-xl flex items-center justify-center shadow-lg">
                            <i class="bi bi-bank text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest block">Catat Penarikan</span>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Tarik Dana dari Payment Gateway</h3>
                        </div>
                    </div>
                    <button @click="showTarikModal = false" class="h-8 w-8 bg-slate-100 hover:bg-amber-100 text-slate-400 hover:text-amber-500 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
                <form action="{{ url('administrator/setor_punia/store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    @csrf
                    <input type="hidden" name="jenis_setor" value="tarik_online">

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-xs text-amber-700">
                        <i class="bi bi-info-circle mr-1"></i>
                        Catat penarikan dana dari akun payment gateway (Xendit) ke rekening Desa Adat. Pastikan jumlah sesuai dengan yang ditarik.
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp) *</label>
                            <input type="number" name="nominal" required min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="0">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tanggal Penarikan *</label>
                            <input type="date" name="tanggal_setor" required value="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sumber Punia</label>
                            <select name="sumber_punia" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5">
                                <option value="campuran">Campuran</option>
                                <option value="tamiu">Krama Tamiu</option>
                                <option value="usaha">Unit Usaha</option>
                                <option value="umum">Umum / Lainnya</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Penerima / Atas Nama *</label>
                            <input type="text" name="penerima" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="Nama penerima penarikan">
                        </div>
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
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan *</label>
                        <textarea name="keterangan" rows="2" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-amber-500/5" placeholder="Penarikan dana Xendit bulan..."></textarea>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti Penarikan (Opsional)</label>
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
@endsection
