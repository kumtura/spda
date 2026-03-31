@extends('mobile_layout')

@section('isi_menu')
@php
    $currentYear = date('Y');
    $currentMonth = (int)date('m');
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $paidThisMonth = $payments[$currentMonth] ?? null;
@endphp

<div class="bg-slate-50 min-h-screen pb-24" x-data="{ 
    showPaymentModal: false, 
    selectedMonth: null, 
    selectedMonthName: '',
    selectedYear: {{ $selectedYear }},
    processing: false
}">
    <div class="bg-white px-4 pt-6 pb-6 space-y-4">
        <!-- Back Navigation -->
        <a href="{{ url('administrator/kelian/pendatang/detail/'.$pendatang->id_pendatang) }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-lg"></i>
            <span class="text-xs font-bold uppercase tracking-wider">Kembali ke Detail</span>
        </a>

        <!-- Header Row (Matches Image Exactly) -->
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kartu Punia</h1>
                <p class="text-[10px] text-slate-400 mt-1">Pembayaran punia bulanan</p>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Cetak Button -->
                <button class="h-9 px-3 bg-white border border-slate-200 rounded-xl flex items-center gap-2 shadow-sm text-slate-600 hover:bg-slate-50 transition-all">
                    <i class="bi bi-printer text-sm"></i>
                    <span class="text-[10px] font-bold">Cetak</span>
                </button>

                <!-- Year Navigation -->
                <div class="flex items-center gap-1 bg-white border border-slate-100 p-1 rounded-xl shadow-sm">
                    <button @click="window.location.href = '{{ url('administrator/kelian/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear - 1)" 
                            class="h-8 w-8 bg-slate-50 hover:bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-chevron-left text-xs"></i>
                    </button>
                    <div class="px-3 min-w-[50px] text-center">
                        <span class="text-xs font-black text-slate-700 tracking-wider" x-text="selectedYear"></span>
                    </div>
                    <button @click="if(selectedYear < {{ date('Y') }}) window.location.href = '{{ url('administrator/kelian/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear + 1)" 
                            :disabled="selectedYear >= {{ date('Y') }}"
                            class="h-8 w-8 disabled:opacity-30 bg-slate-50 hover:bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center transition-all">
                        <i class="bi bi-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contribution Summary Card (Exact Replication) -->
        <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-[32px] p-7 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
            
            <div class="relative z-10">
                <div class="flex items-start justify-between mb-8">
                    <div class="h-11 w-11 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20">
                        <i class="bi bi-wallet2 text-xl"></i>
                    </div>
                    <div class="bg-white/20 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                        <span class="text-[8px] font-black uppercase tracking-widest">Total Kontribusi</span>
                    </div>
                </div>
                
                <p class="text-[10px] uppercase text-white/60 font-bold mb-1 tracking-wider">Total Punia Tahun {{ $selectedYear }}</p>
                <h3 class="text-5xl font-black mb-10 tracking-tight">Rp {{ number_format($totalKontribusi, 0, ',', '.') }}</h3>
                
                <div class="flex items-end justify-between pt-5 border-t border-white/20">
                    <div>
                        <p class="text-white/60 text-[10px] mb-1">Bulan Ini</p>
                        <p class="text-base font-black">{{ $paidThisMonth ? 'Lunas' : 'Belum Lunas' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[11px] font-bold text-white/80 opacity-60 tracking-wider">{{ $currentDateFormatted }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Matrix Table (Exact Replication) -->
    <div class="px-4 mt-6">
        <div class="bg-white rounded-[24px] border border-slate-200 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bulan</th>
                        <th class="px-2 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-2 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl</th>
                        <th class="px-6 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($months as $num => $name)
                    @php
                        $payment = $payments[$num] ?? null;
                        $isPaid = !!$payment;
                        $isPast = ($selectedYear < $currentYear) || ($selectedYear == $currentYear && $num < $currentMonth);
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-slate-700">{{ $name }}</span>
                                @if(!$isPaid && $isPast)
                                <span class="text-[7px] font-black bg-rose-50 text-rose-400 border border-rose-100 px-2 py-0.5 rounded uppercase tracking-tighter">Terlewat</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-2 py-5 text-center">
                            @if($isPaid)
                            <span class="text-xs font-black text-slate-700">{{ number_format($payment->nominal, 0, ',', '.') }}</span>
                            @else
                            <span class="text-xs text-slate-200">-</span>
                            @endif
                        </td>
                        <td class="px-2 py-5 text-center">
                            @if($isPaid)
                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $payment->tanggal_bayar->translatedFormat('d/m') }}</span>
                            @else
                            <span class="text-xs text-slate-200">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex justify-center">
                                @if($isPaid)
                                <div class="h-8 w-11 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 text-slate-300">
                                    <i class="bi bi-eye text-sm"></i>
                                </div>
                                @else
                                <button @click="selectedMonth = {{ $num }}; selectedMonthName = '{{ $name }}'; showPaymentModal = true" 
                                        class="h-8 px-4 bg-[#00a6eb] text-white rounded-xl text-[10px] font-black flex items-center gap-1.5 shadow-md shadow-blue-500/10 active:scale-95 transition-all">
                                    <i class="bi bi-wallet2 text-xs"></i>
                                    <span>Bayar</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Selection-Based Payment Modal (Follows Front Punia Style) -->
    <div x-show="showPaymentModal" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm shadow-2xl"
         style="display: none;">
        
        <div @click.away="showPaymentModal = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-[32px] shadow-2xl max-w-sm w-full overflow-hidden">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showPaymentModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Bayar Iuran</h3>
                    <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest mt-1" x-text="'Bulan ' + selectedMonthName + ' ' + selectedYear"></p>
                </div>
            </div>

            <!-- Choice Content (Matches public punia choice style) -->
            <div class="p-6 space-y-3">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 mb-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nominal</span>
                        <span class="text-sm font-black text-slate-800">Rp {{ number_format($pendatang->punia_rutin_bulanan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tunai / Cash Choice Card -->
                <button type="button" @click="processing = true; $refs.paymentForm.metode_pembayaran.value = 'cash'; $refs.paymentForm.submit()"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-[24px] p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-cash-coin text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800 mb-0.5">Tunai</h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Catat Manual</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <!-- QRIS Choice Card -->
                <button type="button" @click="processing = true; $refs.paymentForm.metode_pembayaran.value = 'qris'; $refs.paymentForm.submit()"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-[24px] p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-qr-code text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-black text-slate-800 mb-0.5">Scan QRIS</h4>
                            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Metode QRIS</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <!-- Loading State Overlay -->
                <div x-show="processing" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50">
                    <i class="bi bi-arrow-repeat animate-spin text-4xl text-[#00a6eb] mb-3"></i>
                    <p class="text-xs font-bold text-slate-600">Sedang diproses...</p>
                </div>
            </div>

            <!-- Hidden Form -->
            <form x-ref="paymentForm" action="{{ url('administrator/kelian/pendatang/kartu-punia/bayar') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
                <input type="hidden" name="bulan" x-model="selectedMonth">
                <input type="hidden" name="tahun" x-model="selectedYear">
                <input type="hidden" name="metode_pembayaran">
            </form>

            <!-- Modal Footer -->
            <div class="px-6 pb-6 pt-2 text-center">
                <button @click="showPaymentModal = false" class="text-[10px] font-black text-slate-300 uppercase tracking-widest hover:text-slate-500 transition-colors">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection
