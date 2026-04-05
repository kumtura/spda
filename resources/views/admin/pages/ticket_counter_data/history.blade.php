@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Pembelian Tiket</h1>
            <p class="text-slate-500 font-medium text-sm">History pembelian tiket online dan offline.</p>
        </div>
        <a href="{{ url('administrator/ticket_counter_data') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl shadow-lg shadow-slate-200/50 transition-all text-xs font-black uppercase tracking-widest active:scale-95">
            <i class="bi bi-arrow-left"></i> Kembali Dashboard
        </a>
    </div>


    <!-- Filters -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Dari Tanggal</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Sampai Tanggal</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Metode</label>
                <select name="metode_pembelian" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
                    <option value="">Semua</option>
                    <option value="online" {{ request('metode_pembelian') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="offline" {{ request('metode_pembelian') == 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Status</label>
                <select name="status" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
                    <option value="">Semua</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1 block">Objek Wisata</label>
                <select name="id_objek_wisata" class="w-full border border-slate-200 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
                    <option value="">Semua</option>
                    @foreach($objekWisataList as $obj)
                    <option value="{{ $obj->id_objek_wisata }}" {{ request('id_objek_wisata') == $obj->id_objek_wisata ? 'selected' : '' }}>{{ $obj->nama_objek }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-colors">
                    <i class="bi bi-search mr-1"></i>Filter
                </button>
                <a href="{{ url('administrator/ticket_counter_data/history') }}" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">Reset</a>
            </div>
        </form>
    </div>

    <!-- Search -->
    <form method="GET" action="{{ url('administrator/ticket_counter_data/history') }}" class="flex gap-2">
        @foreach(request()->except('search', 'page') as $key => $val)
        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
        @endforeach
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode tiket, nama pengunjung, email..." class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 text-xs focus:ring-2 focus:ring-blue-200 focus:border-primary-light outline-none">
        <button type="submit" class="px-5 py-2.5 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 transition-colors">Cari</button>
    </form>

    <!-- Transactions Table -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead><tr class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider text-[10px]">
                    <th class="text-left px-5 py-3">Kode Tiket</th>
                    <th class="text-left px-3 py-3">Pengunjung</th>
                    <th class="text-left px-3 py-3">Objek Wisata</th>
                    <th class="text-left px-3 py-3">Detail Tiket</th>
                    <th class="text-center px-3 py-3">Metode</th>
                    <th class="text-center px-3 py-3">Bayar</th>
                    <th class="text-center px-3 py-3">Status</th>
                    <th class="text-center px-3 py-3">Petugas</th>
                    <th class="text-right px-5 py-3">Total</th>
                    <th class="text-right px-5 py-3">Tanggal</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tikets as $trx)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3 font-mono font-bold text-slate-800 whitespace-nowrap">{{ $trx->kode_tiket }}</td>
                        <td class="px-3 py-3">
                            <p class="font-bold text-slate-700">{{ $trx->nama_pengunjung ?: '-' }}</p>
                            @if($trx->email)<p class="text-[9px] text-slate-400">{{ $trx->email }}</p>@endif
                        </td>
                        <td class="px-3 py-3 font-medium text-slate-700">{{ $trx->objekWisata->nama_objek ?? '-' }}</td>
                        <td class="px-3 py-3">
                            @foreach($trx->details as $d)
                            <div class="text-[10px] text-slate-500">
                                {{ $d->kategoriTiket->nama_kategori ?? '-' }}
                                @if($d->kategoriTiket && $d->kategoriTiket->market_type != 'all')
                                <span class="font-bold {{ $d->kategoriTiket->market_type == 'local' ? 'text-emerald-600' : 'text-amber-600' }}">({{ strtoupper($d->kategoriTiket->market_type) }})</span>
                                @endif
                                × {{ $d->jumlah }}
                            </div>
                            @endforeach
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded font-bold {{ $trx->metode_pembelian == 'offline' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-primary-light' }}">
                                {{ ucfirst($trx->metode_pembelian) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center font-medium text-slate-600 uppercase">{{ $trx->metode_pembayaran }}</td>
                        <td class="px-3 py-3 text-center">
                            @php $statusColors = ['completed' => 'bg-emerald-50 text-emerald-700', 'pending' => 'bg-amber-50 text-amber-700', 'failed' => 'bg-rose-50 text-rose-700']; @endphp
                            <span class="px-2 py-0.5 rounded font-bold text-[9px] {{ $statusColors[$trx->status_pembayaran] ?? 'bg-slate-50 text-slate-500' }}">{{ ucfirst($trx->status_pembayaran) }}</span>
                        </td>
                        <td class="px-3 py-3 text-center text-slate-500">{{ $trx->petugas->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-right font-black text-slate-800 whitespace-nowrap">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-right text-slate-400 whitespace-nowrap">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-12 text-center text-slate-400">
                            <i class="bi bi-inbox text-3xl mb-2 block"></i>
                            <p class="text-xs font-medium">Tidak ada data transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tikets->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $tikets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
