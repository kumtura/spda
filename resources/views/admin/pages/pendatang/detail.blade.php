@extends('index')

@section('isi_menu')
@php
    $totalTagihan = $pendatang->puniaPendatang->where('aktif', '1')->count();
    $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
    $sudahBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'lunas')->where('aktif', '1')->count();
    $totalNominalBelumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->sum('nominal');
    
    $bulanIni = now()->format('Y-m');
    $tagihanBulanIni = $pendatang->puniaPendatang()
        ->where('jenis_punia', 'rutin')
        ->where('bulan_tahun', $bulanIni)
        ->where('aktif', '1')
        ->exists();
        
    $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
    $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
    $fromPunia = request('from') === 'punia';
    $baseUrl = 'administrator/pendatang';
    $backUrl = $fromPunia ? url('administrator/datapunia_pendatang') : url('administrator/pendatang');
    $backLabel = $fromPunia ? 'Kembali ke Iuran Pendatang' : 'Kembali ke Data Pendatang';
    $fromParam = $fromPunia ? '?from=punia' : '';
@endphp

<div class="space-y-6" x-data="{ showPaymentModal: false, paymentId: null, paymentTitle: '', paymentAmount: 0 }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ $backUrl }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
                <i class="bi bi-arrow-left mr-1"></i> {{ $backLabel }}
            </a>
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 bg-primary-light/10 rounded-xl flex items-center justify-center text-primary-light font-bold text-lg">
                    {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 tracking-tight">{{ $pendatang->nama }}</h1>
                    <p class="text-slate-500 font-medium text-sm">NIK: {{ $pendatang->nik }} &middot;
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded {{ $pendatang->status === 'aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">{{ $pendatang->status }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url($baseUrl.'/edit/'.$pendatang->id_pendatang.$fromParam) }}" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ url($baseUrl.'/kartu-punia/'.$pendatang->id_pendatang.$fromParam) }}" class="flex items-center gap-2 bg-white border border-slate-200 text-primary-light px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-blue-50 transition-all">
                <i class="bi bi-wallet2"></i> Kartu Iuran
            </a>
            <a href="{{ url($baseUrl.'/toggle/'.$pendatang->id_pendatang) }}" onclick="return confirm('Ubah status pendatang ini?')" class="flex items-center gap-2 bg-white border border-slate-200 text-slate-500 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-50 transition-all">
                <i class="bi bi-toggle-{{ $pendatang->status === 'aktif' ? 'on' : 'off' }}"></i> {{ $pendatang->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
            </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Info -->
        <div class="space-y-6">
            <!-- Info Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Pendatang</h3>
                <div class="space-y-2.5">
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-geo-alt text-slate-400"></i>
                        <span class="text-slate-500">Asal:</span>
                        <span class="text-slate-700 font-medium">{{ $pendatang->asal }}</span>
                    </div>
                    @if($pendatang->banjar)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-house-door text-slate-400"></i>
                        <span class="text-slate-500">Banjar:</span>
                        <span class="text-slate-700 font-medium">{{ $pendatang->banjar->nama_banjar }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-telephone text-slate-400"></i>
                        <span class="text-slate-500">No. HP:</span>
                        <span class="text-slate-700 font-medium">{{ $pendatang->no_hp }}</span>
                    </div>
                    @if($pendatang->alamat_tinggal)
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-house text-slate-400"></i>
                        <span class="text-slate-500">Alamat:</span>
                        <span class="text-slate-700 font-medium">{{ $pendatang->alamat_tinggal }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2 text-sm">
                        <i class="bi bi-calendar-range text-slate-400"></i>
                        <span class="text-slate-500">Lama Tinggal:</span>
                        @if($pendatang->tinggal_belum_yakin)
                        <span class="text-amber-600 font-medium italic">Belum ditentukan</span>
                        @elseif($pendatang->tinggal_dari || $pendatang->tinggal_sampai)
                        <span class="text-slate-700 font-medium">
                            {{ $pendatang->tinggal_dari ? $pendatang->tinggal_dari->format('d M Y') : '?' }}
                            — {{ $pendatang->tinggal_sampai ? $pendatang->tinggal_sampai->format('d M Y') : '?' }}
                        </span>
                        @else
                        <span class="text-slate-400 italic">Belum diisi</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Punia Setting -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Iuran Punia</h3>
                    <a href="{{ url($baseUrl.'/edit/'.$pendatang->id_pendatang) }}" class="text-xs text-primary-light font-bold"><i class="bi bi-gear mr-1"></i>Atur</a>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest mb-1">Nominal per bulan</p>
                    <p class="text-xl font-black text-slate-800">Rp {{ number_format($pendatang->use_global_punia ? $puniaGlobal : $pendatang->punia_rutin_bulanan, 0, ',', '.') }}</p>
                    @if($pendatang->use_global_punia)
                    <span class="text-[9px] font-bold text-primary-light bg-blue-50 px-2 py-0.5 rounded mt-1 inline-block">Global</span>
                    @else
                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded mt-1 inline-block">Kustom</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Stats & Punia -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-primary-light rounded-2xl p-5 text-white shadow-md shadow-blue-100">
                    <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1">Total</p>
                    <p class="text-2xl font-black">{{ $totalTagihan }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lunas</p>
                    <p class="text-2xl font-black text-emerald-500">{{ $sudahBayar }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Belum Bayar</p>
                    <p class="text-2xl font-black text-rose-500">{{ $belumBayar }}</p>
                </div>
                <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Terutang</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($totalNominalBelumBayar, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Generate Tagihan -->
            @if(!$tagihanBulanIni && $pendatang->punia_rutin_bulanan > 0 && $pendatang->status === 'aktif')
            <a href="{{ url($baseUrl.'/generate-tagihan/'.$pendatang->id_pendatang) }}" 
               onclick="return confirm('Generate tagihan punia untuk bulan {{ now()->translatedFormat('F Y') }}?')"
               class="block bg-primary-light hover:bg-primary-dark text-white py-3 rounded-xl font-bold text-sm shadow-lg text-center transition-all">
                <i class="bi bi-plus-lg mr-2"></i>Generate Tagihan Bulan Ini
            </a>
            @endif

            <!-- Punia Acara Section (Unpaid) -->
            @php
                $acaraBelumBayar = $pendatang->puniaPendatang
                    ->where('aktif', '1')
                    ->where('jenis_punia', 'acara')
                    ->where('status_pembayaran', 'belum_bayar');
            @endphp
            @if($acaraBelumBayar->count() > 0)
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Punia Acara (Belum Bayar)</h3>
                <div class="space-y-2">
                    @foreach($acaraBelumBayar as $punia)
                    <div class="bg-slate-50 rounded-xl px-4 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $punia->nama_acara }}</p>
                            <div class="flex items-center gap-2 mt-0.5 text-xs text-slate-500">
                                <span>Rp {{ number_format($punia->nominal, 0, ',', '.') }}</span>
                                @if($punia->acaraPunia && $punia->acaraPunia->tanggal_acara)
                                <span>&middot; {{ $punia->acaraPunia->tanggal_acara->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <button @click="paymentId = {{ $punia->id_punia_pendatang }}; paymentTitle = '{{ $punia->nama_acara }}'; paymentAmount = {{ $punia->nominal }}; showPaymentModal = true" 
                                class="px-4 py-2 bg-primary-light text-white rounded-lg text-xs font-bold hover:bg-primary-dark transition-all">
                            Bayar
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Riwayat Punia -->
            @php
                $puniaLunas = $pendatang->puniaPendatang
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'lunas')
                    ->sortByDesc('created_at');
            @endphp
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Punia (Lunas)</h3>
                </div>
                @if($puniaLunas->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($puniaLunas as $punia)
                    <div class="px-6 py-3 flex items-center justify-between hover:bg-slate-50/50">
                        <div>
                            @if($punia->jenis_punia === 'rutin')
                            <p class="text-sm font-medium text-slate-700">Punia {{ ucfirst($punia->periode_rutin) }} <span class="text-xs text-slate-400 ml-1">{{ $punia->bulan_tahun }}</span></p>
                            @else
                            <p class="text-sm font-medium text-slate-700">{{ $punia->nama_acara }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-0.5 text-[10px] text-slate-400">
                                <span>{{ $punia->tanggal_bayar ? $punia->tanggal_bayar->format('d M Y') : '-' }}</span>
                                @if($punia->metode_pembayaran)
                                <span>&middot; {{ strtoupper($punia->metode_pembayaran) }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-sm font-bold text-slate-700">Rp {{ number_format($punia->nominal, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center">
                    <p class="text-sm text-slate-400">Belum ada riwayat punia</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div @click.away="showPaymentModal = false" class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-primary-light p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black">Bayar Punia</h3>
                        <p class="text-white/80 text-xs mt-1" x-text="paymentTitle"></p>
                    </div>
                    <button @click="showPaymentModal = false" class="h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center">
                        <i class="bi bi-x text-xl"></i>
                    </button>
                </div>
            </div>
            <form :action="'{{ url($baseUrl.'/punia/bayar') }}/' + paymentId" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="bg-slate-50 rounded-xl p-4 flex items-center justify-between">
                    <span class="text-xs text-slate-500">Nominal</span>
                    <span class="text-lg font-black text-slate-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(paymentAmount)"></span>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-600 mb-2">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative">
                            <input type="radio" name="metode_pembayaran" value="cash" required class="peer sr-only">
                            <div class="border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all peer-checked:border-primary-light peer-checked:bg-blue-50 hover:border-slate-300 text-center">
                                <i class="bi bi-cash-coin text-2xl text-slate-400"></i>
                                <p class="text-xs font-bold text-slate-700 mt-1">Cash</p>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="metode_pembayaran" value="qris" required class="peer sr-only">
                            <div class="border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all peer-checked:border-primary-light peer-checked:bg-blue-50 hover:border-slate-300 text-center">
                                <i class="bi bi-qr-code text-2xl text-slate-400"></i>
                                <p class="text-xs font-bold text-slate-700 mt-1">QRIS</p>
                            </div>
                        </label>
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary-light hover:bg-primary-dark text-white py-3 rounded-xl font-bold text-sm transition-all">
                    <i class="bi bi-check-circle mr-2"></i>Konfirmasi Lunas
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
