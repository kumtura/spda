@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-28">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/pendatang') }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <div class="flex items-start gap-4">
                <div class="h-16 w-16 bg-white/20 rounded-xl flex items-center justify-center shrink-0 text-white font-bold text-xl">
                    {{ strtoupper(substr($pendatang->nama, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-lg font-black">{{ $pendatang->nama }}</h1>
                        @if($pendatang->status === 'aktif')
                        <span class="text-[8px] font-bold bg-white/20 px-2 py-0.5 rounded border border-white/20">Aktif</span>
                        @endif
                    </div>
                    <p class="text-white/80 text-[10px]">NIK: {{ $pendatang->nik }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-5 relative z-10 space-y-5">
        @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                <p class="text-xs text-blue-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Info Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Informasi Pendatang</h4>
            
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-geo-alt text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Asal:</span>
                    <span class="text-slate-700">{{ $pendatang->asal }}</span>
                </div>
                @if($pendatang->banjar)
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-house-door text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Banjar:</span>
                    <span class="text-slate-700">{{ $pendatang->banjar->nama_banjar }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-telephone text-slate-400 text-xs"></i>
                    <span class="text-slate-500">No. HP:</span>
                    <span class="text-slate-700">{{ $pendatang->no_hp }}</span>
                </div>
                @if($pendatang->alamat_tinggal)
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-house text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Alamat:</span>
                    <span class="text-slate-700">{{ $pendatang->alamat_tinggal }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-calendar-range text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Lama Tinggal:</span>
                    @if($pendatang->tinggal_belum_yakin)
                    <span class="text-amber-600 italic">Belum ditentukan</span>
                    @elseif($pendatang->tinggal_dari || $pendatang->tinggal_sampai)
                    <span class="text-slate-700">
                        {{ $pendatang->tinggal_dari ? $pendatang->tinggal_dari->format('d M Y') : '?' }}
                        — {{ $pendatang->tinggal_sampai ? $pendatang->tinggal_sampai->format('d M Y') : '?' }}
                    </span>
                    @else
                    <span class="text-slate-400 italic">Belum diisi</span>
                    @endif
                </div>
            </div>
            
            <div class="flex gap-2 pt-3 border-t border-slate-100">
                <a href="{{ url('administrator/kelian/pendatang/edit/'.$pendatang->id_pendatang) }}" class="flex-1 py-2 bg-slate-50 text-slate-600 rounded-lg text-[10px] font-medium text-center border border-slate-100">
                    <i class="bi bi-pencil mr-1"></i>Edit
                </a>
                <a href="{{ url('administrator/kelian/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}" class="flex-1 py-2 bg-blue-50 text-[#00a6eb] rounded-lg text-[10px] font-medium text-center border border-blue-100">
                    <i class="bi bi-wallet2 mr-1"></i>Kartu Iuran
                </a>
                <button onclick="toggleStatus()" class="flex-1 py-2 bg-slate-50 text-slate-400 rounded-lg text-[10px] font-medium text-center border border-slate-200">
                    <i class="bi bi-toggle-{{ $pendatang->status === 'aktif' ? 'on' : 'off' }} mr-1"></i>{{ $pendatang->status === 'aktif' ? 'Nonaktif' : 'Aktifkan' }}
                </button>
            </div>
        </div>

        <!-- Stats Card -->
        @php
            $totalTagihan = $pendatang->puniaPendatang->where('aktif', '1')->count();
            $belumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->count();
            $sudahBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'lunas')->where('aktif', '1')->count();
            $totalNominalBelumBayar = $pendatang->puniaPendatang->where('status_pembayaran', 'belum_bayar')->where('aktif', '1')->sum('nominal');
            
            // Check if tagihan bulan ini sudah ada
            $bulanIni = now()->format('Y-m');
            $tagihanBulanIni = $pendatang->puniaPendatang()
                ->where('jenis_punia', 'rutin')
                ->where('bulan_tahun', $bulanIni)
                ->where('aktif', '1')
                ->exists();
                
            $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
            $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
        @endphp
        <!-- Ringkasan Punia -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Ringkasan Punia</h4>
            
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-slate-700">{{ $totalTagihan }}</p>
                    <p class="text-[9px] text-slate-400">Total</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-[#00a6eb]">{{ $sudahBayar }}</p>
                    <p class="text-[9px] text-slate-400">Lunas</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-slate-700">{{ $belumBayar }}</p>
                    <p class="text-[9px] text-slate-400">Belum</p>
                </div>
            </div>
            
            @if($totalNominalBelumBayar > 0)
            <div class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2">
                <span class="text-[10px] text-slate-500">Tagihan terutang</span>
                <span class="text-sm font-medium text-slate-700">Rp {{ number_format($totalNominalBelumBayar, 0, ',', '.') }}</span>
            </div>
            @endif
            
            <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                <div class="text-[10px] text-slate-500">
                    Punia: <span class="font-medium text-slate-700">Rp {{ number_format($pendatang->effective_punia_nominal, 0, ',', '.') }}/bln</span>
                    @if($pendatang->use_global_punia)
                    <span class="text-[9px] text-slate-400 ml-1">(global)</span>
                    @endif
                </div>
                <a href="{{ url('administrator/kelian/pendatang/edit/'.$pendatang->id_pendatang) }}" class="text-[10px] text-[#00a6eb] font-medium">
                    <i class="bi bi-gear text-[9px] mr-0.5"></i>Atur
                </a>
            </div>
        </div>

        <!-- Generate Tagihan Bulan Ini -->
        @if(!$tagihanBulanIni && $pendatang->effective_punia_nominal > 0 && $pendatang->status === 'aktif')
        <a href="{{ url('administrator/kelian/pendatang/generate-tagihan/'.$pendatang->id_pendatang) }}" 
           onclick="return confirm('Generate tagihan punia untuk bulan {{ now()->translatedFormat('F Y') }}?')"
           class="block w-full bg-[#00a6eb] text-white py-3 rounded-xl font-bold text-sm shadow-lg text-center">
            <i class="bi bi-plus-lg mr-2"></i>Generate Tagihan Bulan Ini
        </a>
        @endif

        <!-- Punia Acara Section (Unpaid Only) -->
        @php
            $acaraBelumBayar = $pendatang->puniaPendatang
                ->where('aktif', '1')
                ->where('jenis_punia', 'acara')
                ->where('status_pembayaran', 'belum_bayar');
        @endphp
        @if($acaraBelumBayar->count() > 0)
        <div>
            <h4 class="text-xs font-bold text-slate-800 mb-2">Punia Acara</h4>
            <div class="space-y-1.5">
                @foreach($acaraBelumBayar as $punia)
                <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-800">{{ $punia->nama_acara }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-[11px] text-slate-600">Rp {{ number_format($punia->nominal, 0, ',', '.') }}</span>
                                @if($punia->acaraPunia && $punia->acaraPunia->tanggal_acara)
                                <span class="text-[9px] text-slate-400">&middot; {{ $punia->acaraPunia->tanggal_acara->format('d M Y') }}</span>
                                @endif
                            </div>
                        </div>
                        <button onclick="showPaymentModal({{ $punia->id_punia_pendatang }}, '{{ $punia->nama_acara }}', {{ $punia->nominal }})" 
                                class="px-3 py-1.5 bg-[#00a6eb] text-white rounded-lg text-[10px] font-medium shrink-0">
                            Bayar
                        </button>
                    </div>
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
        @if($puniaLunas->count() > 0)
        <div>
            <h4 class="text-xs font-bold text-slate-800 mb-2">Riwayat Punia</h4>
            <div class="space-y-1.5">
                @foreach($puniaLunas as $punia)
                <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            @if($punia->jenis_punia === 'rutin')
                            <p class="text-xs font-medium text-slate-700">
                                Punia {{ ucfirst($punia->periode_rutin) }}
                                <span class="text-[10px] text-slate-400 font-normal ml-1">{{ $punia->bulan_tahun }}</span>
                            </p>
                            @else
                            <p class="text-xs font-medium text-slate-700">{{ $punia->nama_acara }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                                <span>{{ $punia->tanggal_bayar->format('d M Y') }}</span>
                                @if($punia->metode_pembayaran)
                                <span>&middot; {{ strtoupper($punia->metode_pembayaran) }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="text-[11px] font-medium text-slate-600 shrink-0">
                            Rp {{ number_format($punia->nominal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat punia</p>
        </div>
        @endif
    </div>
</div>

<script>
function toggleStatus() {
    if (confirm('Ubah status pendatang ini?')) {
        window.location.href = '{{ url("administrator/kelian/pendatang/toggle/".$pendatang->id_pendatang) }}';
    }
}

function deletePunia(id) {
    if (confirm('Hapus tagihan punia ini?')) {
        window.location.href = '{{ url("administrator/kelian/pendatang/punia/delete") }}/' + id;
    }
}
</script>
@endsection

<!-- Modal Pembayaran Punia -->
<div id="payment-modal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <button onclick="closePaymentModal()" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                <i class="bi bi-x text-xl"></i>
            </button>
            <div class="relative">
                <h3 class="text-xl font-black">Bayar Punia</h3>
                <p class="text-white/80 text-xs font-medium mt-1" id="payment-modal-title"></p>
            </div>
        </div>

        <!-- Content -->
        <form id="payment-form" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <div>
                        <p class="text-xs font-bold text-slate-700 mb-1">Pilih Metode Pembayaran</p>
                        <p class="text-[10px] text-slate-600 leading-relaxed">Pilih metode pembayaran yang digunakan oleh pendatang.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Jumlah Pembayaran (Rp)</label>
                <input type="text" id="payment-amount" readonly
                       class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 cursor-not-allowed">
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative">
                        <input type="radio" name="metode_pembayaran" value="cash" required class="peer sr-only">
                        <div class="border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 hover:border-slate-300">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-cash-coin text-2xl text-slate-400"></i>
                                <span class="text-xs font-bold text-slate-700">Cash</span>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" name="metode_pembayaran" value="qris" required class="peer sr-only">
                        <div class="border-2 border-slate-200 rounded-xl p-4 cursor-pointer transition-all peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 hover:border-slate-300">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-qr-code text-2xl text-slate-400"></i>
                                <span class="text-xs font-bold text-slate-700">QRIS</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm">
                <i class="bi bi-check-circle mr-2"></i> Konfirmasi Pembayaran
            </button>
        </form>
    </div>
</div>

<script>
let currentPuniaId = null;

function togglePuniaField(checkbox) {
    // Settings moved to edit page
}

function showPaymentModal(puniaId, title, amount) {
    currentPuniaId = puniaId;
    document.getElementById('payment-modal-title').textContent = title;
    document.getElementById('payment-amount').value = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    document.getElementById('payment-form').action = '{{ url("administrator/kelian/pendatang/punia/bayar") }}/' + puniaId;
    document.getElementById('payment-modal').classList.remove('hidden');
    document.getElementById('payment-modal').classList.add('flex');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
    document.getElementById('payment-modal').classList.remove('flex');
    currentPuniaId = null;
}
</script>

