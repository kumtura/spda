@extends('index')

@section('isi_menu')
<div class="px-6 space-y-6" x-data="{ 
    activeTab: 'punia',
    showBuktiModal: false,
    selectedBukti: '',
    showRejectModal: false,
    rejectId: null,
    rejectType: ''
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Pembayaran</h1>
            <p class="text-slate-500 font-medium text-sm">Konfirmasi pembayaran transfer manual dari masyarakat dan unit usaha.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200" role="alert">
        <span class="font-bold">Berhasil!</span> {{ session('success') }}
    </div>
    @endif

    <!-- Tabs with Underline Style -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="flex border-b border-slate-200">
            <button @click="activeTab = 'punia'" 
                    :class="activeTab === 'punia' ? 'border-b-2 border-primary-light text-primary-light' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-4 text-sm font-bold transition-colors flex items-center justify-center gap-2 -mb-px">
                <i class="bi bi-wallet2"></i>
                <span>Punia</span>
                @if($pending_punia->count() > 0)
                <span class="bg-primary-light text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pending_punia->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'donasi'" 
                    :class="activeTab === 'donasi' ? 'border-b-2 border-primary-light text-primary-light' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-4 text-sm font-bold transition-colors flex items-center justify-center gap-2 -mb-px">
                <i class="bi bi-heart-pulse"></i>
                <span>Donasi</span>
                @if($pending_donasi->count() > 0)
                <span class="bg-primary-light text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pending_donasi->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'tiket'" 
                    :class="activeTab === 'tiket' ? 'border-b-2 border-primary-light text-primary-light' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-4 text-sm font-bold transition-colors flex items-center justify-center gap-2 -mb-px">
                <i class="bi bi-ticket-perforated"></i>
                <span>Tiket</span>
                @if($pending_tiket->count() > 0)
                <span class="bg-primary-light text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pending_tiket->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'riwayat'" 
                    :class="activeTab === 'riwayat' ? 'border-b-2 border-primary-light text-primary-light' : 'text-slate-400 hover:text-slate-600'"
                    class="flex-1 py-4 text-sm font-bold transition-colors flex items-center justify-center gap-2 -mb-px">
                <i class="bi bi-archive"></i>
                <span>Riwayat</span>
            </button>
        </div>

        <!-- Punia Tab Content -->
        <div x-show="activeTab === 'punia'" class="p-6">
            @if($pending_punia->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Donatur</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Kategori Donatur</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Bukti</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_punia as $punia)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($punia->created_at)->translatedFormat('d M Y') }}</span>
                                <br>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($punia->created_at)->translatedFormat('H:i') }} WITA</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-bold text-slate-800">{{ $punia->nama_donatur }}</span>
                                @if($punia->email)
                                <br><span class="text-[10px] text-slate-400">{{ $punia->email }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($punia->usaha && $punia->usaha->detail)
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-building text-slate-400 text-xs"></i>
                                    <span class="text-xs text-slate-600">{{ $punia->usaha->detail->nama_usaha }}</span>
                                </div>
                                @else
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-person text-slate-400 text-xs"></i>
                                    <span class="text-xs text-slate-400">Masyarakat</span>
                                </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($punia->jumlah_dana, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="selectedBukti = '{{ asset($punia->bukti_transfer) }}'; showBuktiModal = true" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-colors">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ url('administrator/verifikasi_pembayaran/approve') }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $punia->id_dana_punia }}">
                                        <input type="hidden" name="type" value="punia">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition-colors">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </button>
                                    </form>
                                    <button @click="rejectId = {{ $punia->id_dana_punia }}; rejectType = 'punia'; showRejectModal = true" 
                                            class="px-3 py-1.5 bg-slate-500 text-white rounded-lg text-xs font-bold hover:bg-slate-600 transition-colors">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="bi bi-inbox text-5xl text-slate-300 mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Tidak ada pembayaran punia yang menunggu verifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Semua pembayaran sudah diproses</p>
            </div>
            @endif
        </div>

        <!-- Donasi Tab Content -->
        <div x-show="activeTab === 'donasi'" class="p-6">
            @if($pending_donasi->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Donatur</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Program</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Bukti</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_donasi as $donasi)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('d M Y') }}</span>
                                <br>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($donasi->created_at)->translatedFormat('H:i') }} WITA</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-bold text-slate-800">{{ $donasi->nama }}</span>
                            </td>
                            <td class="px-4 py-4">
                                @if($donasi->programDonasi)
                                <span class="text-xs text-slate-600">{{ $donasi->programDonasi->nama_program }}</span>
                                @else
                                <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($donasi->nominal, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="selectedBukti = '{{ asset($donasi->bukti_transfer) }}'; showBuktiModal = true" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-colors">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ url('administrator/verifikasi_pembayaran/approve') }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $donasi->id_sumbangan_sukarela }}">
                                        <input type="hidden" name="type" value="donasi">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition-colors">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </button>
                                    </form>
                                    <button @click="rejectId = {{ $donasi->id_sumbangan_sukarela }}; rejectType = 'donasi'; showRejectModal = true" 
                                            class="px-3 py-1.5 bg-slate-500 text-white rounded-lg text-xs font-bold hover:bg-slate-600 transition-colors">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="bi bi-inbox text-5xl text-slate-300 mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Tidak ada pembayaran donasi yang menunggu verifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Semua pembayaran sudah diproses</p>
            </div>
            @endif
        </div>

        <!-- Tiket Tab Content -->
        <div x-show="activeTab === 'tiket'" class="p-6">
            @if($pending_tiket->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Pengunjung</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Objek Wisata</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Jumlah</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Bukti</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_tiket as $tiket)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($tiket->created_at)->translatedFormat('d M Y') }}</span>
                                <br>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($tiket->created_at)->translatedFormat('H:i') }} WITA</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-bold text-slate-800">{{ $tiket->nama_pengunjung }}</span>
                                @if($tiket->email)
                                <br><span class="text-[10px] text-slate-400">{{ $tiket->email }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs text-slate-600">{{ $tiket->objekWisata->nama_objek }}</span>
                                <br>
                                <span class="text-[10px] text-slate-400">{{ $tiket->tanggal_kunjungan->translatedFormat('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-xs font-bold text-slate-800">{{ $tiket->details->sum('jumlah') }} Tiket</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="selectedBukti = '{{ asset('bukti_transfer/'.$tiket->bukti_transfer) }}'; showBuktiModal = true" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-colors">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ url('administrator/verifikasi_pembayaran/approve') }}" method="POST" onsubmit="return confirm('Setujui pembayaran ini?')">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $tiket->id_tiket }}">
                                        <input type="hidden" name="type" value="tiket">
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition-colors">
                                            <i class="bi bi-check-circle"></i> Setujui
                                        </button>
                                    </form>
                                    <button @click="rejectId = {{ $tiket->id_tiket }}; rejectType = 'tiket'; showRejectModal = true" 
                                            class="px-3 py-1.5 bg-slate-500 text-white rounded-lg text-xs font-bold hover:bg-slate-600 transition-colors">
                                        <i class="bi bi-x-circle"></i> Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="bi bi-inbox text-5xl text-slate-300 mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Tidak ada pembayaran tiket yang menunggu verifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Semua pembayaran sudah diproses</p>
            </div>
            @endif
        </div>

        <!-- Riwayat Tab Content -->
        <div x-show="activeTab === 'riwayat'" class="p-6">
            @php
                // Gabungkan punia, donasi, dan tiket yang sudah diverifikasi
                $riwayat_punia = App\Models\Danapunia::where('metode_pembayaran', 'transfer_manual')
                    ->whereIn('status_verifikasi', ['approved', 'rejected'])
                    ->with('usaha.detail')
                    ->get()
                    ->map(function($item) {
                        $item->tipe = 'punia';
                        return $item;
                    });
                
                $riwayat_donasi = App\Models\Sumbangan::where('metode_pembayaran', 'transfer_manual')
                    ->whereIn('status_verifikasi', ['approved', 'rejected'])
                    ->with('programDonasi')
                    ->get()
                    ->map(function($item) {
                        $item->tipe = 'donasi';
                        return $item;
                    });
                
                $riwayat_tiket = App\Models\TiketWisata::where('metode_pembayaran', 'transfer_manual')
                    ->whereIn('status_verifikasi', ['approved', 'rejected'])
                    ->with('objekWisata')
                    ->get()
                    ->map(function($item) {
                        $item->tipe = 'tiket';
                        return $item;
                    });
                
                // Gabungkan dan sort by updated_at
                $riwayat = $riwayat_punia->concat($riwayat_donasi)->concat($riwayat_tiket)->sortByDesc('updated_at');
            @endphp
            
            @if($riwayat->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Tipe</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Donatur</th>
                            <th class="text-left px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Detail</th>
                            <th class="text-right px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-4">
                                <span class="text-xs text-slate-600">{{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}</span>
                                <br>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('H:i') }} WITA</span>
                            </td>
                            <td class="px-4 py-4">
                                @if($item->tipe === 'punia')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-600 rounded text-[10px] font-bold border border-blue-100">
                                    <i class="bi bi-wallet2"></i> Punia
                                </span>
                                @elseif($item->tipe === 'donasi')
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 text-rose-600 rounded text-[10px] font-bold border border-rose-100">
                                    <i class="bi bi-heart-pulse"></i> Donasi
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-600 rounded text-[10px] font-bold border border-amber-100">
                                    <i class="bi bi-ticket-perforated"></i> Tiket
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($item->tipe === 'punia')
                                <span class="text-xs font-bold text-slate-800">{{ $item->nama_donatur }}</span>
                                @elseif($item->tipe === 'donasi')
                                <span class="text-xs font-bold text-slate-800">{{ $item->nama }}</span>
                                @else
                                <span class="text-xs font-bold text-slate-800">{{ $item->nama_pengunjung }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($item->tipe === 'punia')
                                    @if($item->usaha && $item->usaha->detail)
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-building text-slate-400 text-xs"></i>
                                        <div>
                                            <p class="text-xs text-slate-600">{{ $item->usaha->detail->nama_usaha }}</p>
                                            @if($item->bulan_punia && $item->tahun_punia)
                                            <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::createFromDate($item->tahun_punia, $item->bulan_punia, 1)->translatedFormat('F Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @else
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-person text-slate-400 text-xs"></i>
                                        <span class="text-xs text-slate-400">Masyarakat</span>
                                    </div>
                                    @endif
                                @elseif($item->tipe === 'donasi')
                                    @if($item->programDonasi)
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-heart text-slate-400 text-xs"></i>
                                        <span class="text-xs text-slate-600">{{ $item->programDonasi->nama_program }}</span>
                                    </div>
                                    @else
                                    <span class="text-xs text-slate-400">-</span>
                                    @endif
                                @else
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-geo-alt text-slate-400 text-xs"></i>
                                        <div>
                                            <p class="text-xs text-slate-600">{{ $item->objekWisata->nama_objek }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $item->details->sum('jumlah') }} Tiket - {{ $item->tanggal_kunjungan->translatedFormat('d M Y') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                @if($item->tipe === 'punia')
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($item->jumlah_dana, 0, ',', '.') }}</span>
                                @elseif($item->tipe === 'donasi')
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                @else
                                <span class="text-sm font-bold text-slate-800">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if($item->status_verifikasi === 'approved')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100">
                                    <i class="bi bi-check-circle-fill"></i> Disetujui
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold border border-slate-200">
                                    <i class="bi bi-x-circle-fill"></i> Ditolak
                                </span>
                                @if($item->catatan_verifikasi)
                                <br><span class="text-[10px] text-slate-400 mt-1">{{ $item->catatan_verifikasi }}</span>
                                @endif
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="selectedBukti = '{{ asset($item->bukti_transfer) }}'; showBuktiModal = true" 
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">
                                    <i class="bi bi-eye"></i> Lihat
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <i class="bi bi-archive text-5xl text-slate-300 mb-3"></i>
                <p class="text-sm font-bold text-slate-600">Belum ada riwayat verifikasi</p>
                <p class="text-xs text-slate-400 mt-1">Data yang sudah diverifikasi akan muncul di sini</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Bukti Transfer Modal -->
    <div x-show="showBuktiModal" 
         x-cloak
         @click.self="showBuktiModal = false"
         @keydown.escape.window="showBuktiModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop 
             class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-br from-primary-light to-primary-dark p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showBuktiModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Bukti Transfer</h3>
                    <p class="text-white/80 text-xs font-medium mt-1">Preview dokumen pembayaran</p>
                </div>
            </div>

            <div class="p-6">
                <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden">
                    <template x-if="selectedBukti.endsWith('.pdf')">
                        <a :href="selectedBukti" target="_blank" class="flex flex-col items-center justify-center py-12 hover:bg-slate-100 transition-colors">
                            <i class="bi bi-file-earmark-pdf text-6xl text-rose-500 mb-3"></i>
                            <p class="text-sm font-bold text-slate-700">Bukti Transfer (PDF)</p>
                            <p class="text-xs text-slate-500 mt-1">Klik untuk membuka</p>
                        </a>
                    </template>
                    <template x-if="!selectedBukti.endsWith('.pdf')">
                        <img :src="selectedBukti" class="w-full h-auto" alt="Bukti Transfer">
                    </template>
                </div>
                <div class="mt-4 flex justify-end">
                    <a :href="selectedBukti" download class="px-4 py-2 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-colors">
                        <i class="bi bi-download mr-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="showRejectModal" 
         x-cloak
         @click.self="showRejectModal = false"
         @keydown.escape.window="showRejectModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop 
             class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-br from-slate-600 to-slate-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showRejectModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Tolak Pembayaran</h3>
                    <p class="text-white/80 text-xs font-medium mt-1">Berikan alasan penolakan</p>
                </div>
            </div>

            <form action="{{ url('administrator/verifikasi_pembayaran/reject') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id" x-model="rejectId">
                <input type="hidden" name="type" x-model="rejectType">

                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-slate-500 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Informasi Penolakan</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Berikan alasan penolakan agar donatur dapat memperbaiki bukti transfer.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-2">Alasan Penolakan</label>
                    <textarea name="alasan" rows="3" required placeholder="Contoh: Bukti transfer tidak jelas, nominal tidak sesuai, dll"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-slate-600 hover:bg-slate-700 text-white font-bold py-3 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-x-circle mr-2"></i> Tolak Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
