@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ showPaymentModal: false, showReceiptModal: false, selectedMonth: null, selectedYear: {{ request()->get('year', date('Y')) }}, receipt: null }">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Kartu Punia</h1>
            <p class="text-slate-400 text-[10px] mt-1">Pembayaran punia bulanan</p>
        </div>
        
        <div class="flex items-center gap-2">
            <!-- Print Button -->
            <a :href="'{{ route('administrator.usaha.punia.print') }}?year=' + selectedYear" 
               class="h-8 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center gap-1.5 transition-colors">
                <i class="bi bi-printer text-sm"></i>
                <span class="text-[10px] font-bold">Cetak</span>
            </a>
            
            <!-- Year Selector -->
            <button @click="window.location.href = '{{ url('administrator/usaha/punia') }}?year=' + (selectedYear - 1)" 
                    class="h-8 w-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors">
                <i class="bi bi-chevron-left text-sm"></i>
            </button>
            <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                <span class="text-xs font-bold text-slate-700" x-text="selectedYear"></span>
            </div>
            <button @click="if(selectedYear < {{ date('Y') }}) window.location.href = '{{ url('administrator/usaha/punia') }}?year=' + (selectedYear + 1)" 
                    :disabled="selectedYear >= {{ date('Y') }}"
                    :class="selectedYear >= {{ date('Y') }} ? 'h-8 w-8 bg-slate-50 text-slate-300 rounded-lg flex items-center justify-center cursor-not-allowed' : 'h-8 w-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors'">
                <i class="bi bi-chevron-right text-sm"></i>
            </button>
        </div>
    </div>

    @php
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        
        $selectedYear = (int)request()->get('year', date('Y'));
        $currentYear = date('Y');
        $currentMonth = date('m');
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Get all payments for selected year
        $payments = [];
        $totalKontribusi = 0;
        if($myUsaha) {
            $paymentsData = App\Models\Danapunia::where('id_usaha', $myUsaha->id_usaha)
                ->where('aktif','1')
                ->where('status_pembayaran', 'completed')
                ->where('tahun_punia', $selectedYear)
                ->get();
            
            foreach($paymentsData as $p) {
                $month = (int)$p->bulan_punia; // Use bulan_punia instead of extracting from tanggal_pembayaran
                $payments[$month] = $p;
                $totalKontribusi += $p->jumlah_dana;
            }
        }
        
        $paidThisMonth = $payments[(int)$currentMonth] ?? null;
    @endphp

    @if($myUsaha)
    <!-- Stats Card - Total Kontribusi -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-wallet2 text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Total Kontribusi</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Tahun <span x-text="selectedYear"></span></p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalKontribusi, 0, ',', '.') }}</h3>
            
            @if($selectedYear == $currentYear)
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Bulan Ini</p>
                    <p class="font-bold">{{ $paidThisMonth ? 'Lunas' : 'Belum Bayar' }}</p>
                </div>
                <div class="text-right">
                    @if(!$paidThisMonth)
                    <button @click="selectedMonth = {{ (int)$currentMonth }}; showPaymentModal = true" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-[10px] font-bold transition-all border border-white/30">
                        <i class="bi bi-wallet2 mr-1"></i> Bayar
                    </button>
                    @else
                    <span class="text-[10px] text-white/80">{{ \Carbon\Carbon::parse($paidThisMonth->tanggal_pembayaran)->translatedFormat('d M') }}</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabel Kartu Punia -->
    <div class="bg-white rounded-2xl border-2 border-slate-200 overflow-hidden shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b-2 border-slate-200">
                    <th class="text-left px-4 py-3 text-[10px] font-black text-slate-600 uppercase tracking-wider">Bulan</th>
                    <th class="text-center px-2 py-3 text-[10px] font-black text-slate-600 uppercase tracking-wider">Nominal</th>
                    <th class="text-center px-2 py-3 text-[10px] font-black text-slate-600 uppercase tracking-wider">Tgl</th>
                    <th class="text-center px-4 py-3 text-[10px] font-black text-slate-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $monthNum => $monthName)
                @php
                    $isPaid = isset($payments[$monthNum]);
                    $payment = $payments[$monthNum] ?? null;
                    $isPastMonth = $selectedYear < $currentYear || ($selectedYear == $currentYear && $monthNum < (int)$currentMonth);
                @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors {{ !$isPaid && $isPastMonth ? 'bg-rose-50/30' : '' }}">
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-800">{{ $monthName }}</span>
                            @if(!$isPaid && $isPastMonth)
                            <span class="text-[7px] font-bold text-rose-500 bg-rose-100 px-1.5 py-0.5 rounded uppercase">Terlewat</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-2 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-[10px] font-bold text-slate-700">{{ number_format($payment->jumlah_dana, 0, ',', '.') }}</span>
                        @else
                        <span class="text-[10px] text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-2 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-[10px] text-slate-500">{{ date('d/m', strtotime($payment->tanggal_pembayaran)) }}</span>
                        @else
                        <span class="text-[10px] text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($isPaid)
                            <button @click="receipt = { id: {{ $payment->id_dana_punia }}, bulan: '{{ $monthName }}', tahun: {{ $selectedYear }}, nominal: '{{ number_format($payment->jumlah_dana, 0, ',', '.') }}', tgl: '{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y') }}', metode: '{{ ucfirst($payment->metode_pembayaran ?? $payment->metode ?? 'Online') }}', status: '{{ $payment->status_verifikasi ?? '-' }}', bukti: '{{ $payment->bukti_pembayaran }}' }; showReceiptModal = true"
                                    class="h-7 px-2.5 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center gap-1 border border-slate-200 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all text-[10px] font-bold">
                                <i class="bi bi-eye text-xs"></i>
                            </button>
                            <a :href="'{{ url('administrator/usaha/punia/receipt') }}?id={{ $payment->id_dana_punia }}'"
                               class="h-7 px-2.5 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center gap-1 border border-slate-200 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all text-[10px] font-bold">
                                <i class="bi bi-download text-xs"></i>
                            </a>
                            @else
                            <button @click="selectedMonth = {{ $monthNum }}; showPaymentModal = true" class="h-7 px-2.5 bg-[#00a6eb] text-white rounded-lg flex items-center justify-center gap-1 hover:bg-[#0090d0] transition-all shadow-sm text-[10px] font-bold">
                                <i class="bi bi-wallet2 text-xs"></i> Bayar
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @else
    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6 text-center">
        <i class="bi bi-exclamation-triangle text-3xl text-amber-400 mb-2"></i>
        <p class="text-sm font-bold text-slate-700">Akun usaha belum terdaftar</p>
        <p class="text-xs text-slate-400 mt-1">Hubungi Kelian Adat untuk mendaftarkan usaha Anda.</p>
    </div>
    @endif

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" 
         x-cloak
         @click.self="showPaymentModal = false"
         @keydown.escape.window="showPaymentModal = false"
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
            
            <!-- Header -->
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showPaymentModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Bayar Punia</h3>
                    <p class="text-white/80 text-xs font-medium mt-1" x-text="'Bulan ' + (selectedMonth ? ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][selectedMonth] : '') + ' ' + selectedYear"></p>
                </div>
            </div>

            <!-- Content -->
            @if($myUsaha)
            <form action="{{ route('administrator.usaha.punia.bayar') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $myUsaha->id_usaha }}">
                <input type="hidden" name="bulan" x-model="selectedMonth">
                <input type="hidden" name="tahun" x-model="selectedYear">

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Informasi Pembayaran</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">Nominal punia sudah ditentukan sesuai ketentuan. Anda akan memilih metode pembayaran di halaman berikutnya.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Jumlah Pembayaran (Rp)</label>
                    <input type="text" value="Rp {{ number_format($myUsaha->minimal_bayar ?? 0, 0, ',', '.') }}" readonly
                           class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 cursor-not-allowed">
                    <p class="text-[9px] text-slate-400">Nominal sudah ditentukan oleh sistem</p>
                </div>

                <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-credit-card mr-2"></i> Pilih Metode Pembayaran
                </button>
            </form>
            @endif
        </div>
    </div>
    <!-- Receipt Modal -->
    <div x-show="showReceiptModal" x-cloak
         @click.self="showReceiptModal = false"
         @keydown.escape.window="showReceiptModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">

        <div @click.stop
             class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden border border-slate-200"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">

            <!-- Header -->
            <div class="bg-[#00a6eb] p-5 text-white relative">
                <button @click="showReceiptModal = false" type="button" class="absolute top-3.5 right-3.5 h-7 w-7 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-lg"></i>
                </button>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="bi bi-receipt-cutoff text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black">Bukti Pembayaran</h3>
                        <p class="text-white/80 text-[11px] font-medium mt-0.5" x-text="receipt ? receipt.bulan + ' ' + receipt.tahun : ''"></p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 space-y-3">
                <div class="bg-slate-50 rounded-lg p-4 space-y-2.5 border border-slate-100">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Nominal</span>
                        <span class="text-sm font-black text-slate-800" x-text="receipt ? 'Rp ' + receipt.nominal : ''"></span>
                    </div>
                    <div class="border-t border-slate-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Tanggal Bayar</span>
                        <span class="text-xs font-bold text-slate-700" x-text="receipt ? receipt.tgl : ''"></span>
                    </div>
                    <div class="border-t border-slate-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Metode</span>
                        <span class="text-xs font-bold text-slate-700" x-text="receipt ? receipt.metode : ''"></span>
                    </div>
                    <div class="border-t border-slate-200"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase">Status</span>
                        <span class="text-[10px] font-bold text-[#00a6eb] bg-blue-50 px-2 py-1 rounded border border-blue-100">Lunas</span>
                    </div>
                </div>

                {{-- Bukti Transfer Image --}}
                <template x-if="receipt && receipt.bukti">
                    <div class="rounded-lg overflow-hidden border border-slate-200">
                        <p class="text-[10px] font-bold text-slate-400 uppercase px-3 pt-2">Bukti Transfer</p>
                        <img :src="'{{ asset('bukti_pembayaran') }}/' + receipt.bukti" class="w-full max-h-48 object-contain p-2" alt="Bukti">
                    </div>
                </template>

                <!-- Download Button -->
                <a :href="receipt ? '{{ url('administrator/usaha/punia/receipt') }}?id=' + receipt.id : '#'"
                   class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3 rounded-lg shadow-sm transition-all text-sm flex items-center justify-center gap-2">
                    <i class="bi bi-download"></i> Download Receipt
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
