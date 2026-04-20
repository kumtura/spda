@extends('index')

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
    $fromPunia = request('from') === 'punia';
    $fromParam = $fromPunia ? '&from=punia' : '';
    $backUrl = $fromPunia 
        ? url('administrator/pendatang/detail/'.$pendatang->id_pendatang.'?from=punia') 
        : url('administrator/pendatang/detail/'.$pendatang->id_pendatang);
@endphp

<div class="space-y-6" x-data="{ 
    showPaymentModal: false, 
    selectedMonth: null, 
    selectedMonthName: '',
    selectedYear: {{ $selectedYear }},
    processing: false
}">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ $backUrl }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Detail
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Kartu Punia</h1>
            <p class="text-slate-500 font-medium text-sm">{{ $pendatang->nama }} &middot; Pembayaran bulanan</p>
        </div>
        <div class="flex items-center gap-2">
            <a :href="'{{ url('administrator/pendatang/kartu-punia/print/'.$pendatang->id_pendatang) }}?year=' + selectedYear" 
               class="h-9 px-4 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center gap-1.5 transition-colors text-sm font-bold">
                <i class="bi bi-printer"></i> Cetak
            </a>
            <button @click="window.location.href = '{{ url('administrator/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear - 1) + '{{ $fromParam }}'" 
                    class="h-9 w-9 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors">
                <i class="bi bi-chevron-left"></i>
            </button>
            <div class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-1.5">
                <span class="text-sm font-bold text-slate-700" x-text="selectedYear"></span>
            </div>
            <button @click="if(selectedYear < {{ date('Y') }}) window.location.href = '{{ url('administrator/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear + 1) + '{{ $fromParam }}'" 
                    :disabled="selectedYear >= {{ date('Y') }}"
                    :class="selectedYear >= {{ date('Y') }} ? 'h-9 w-9 bg-slate-50 text-slate-300 rounded-lg flex items-center justify-center cursor-not-allowed' : 'h-9 w-9 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors'">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-primary-light/10 rounded-xl flex items-center justify-center">
                    <i class="bi bi-wallet2 text-primary-light text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase">Total Kontribusi <span x-text="selectedYear"></span></p>
                    <p class="text-xl font-black text-slate-800">Rp {{ number_format($totalKontribusi, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @if($selectedYear == $currentYear)
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 {{ $paidThisMonth ? 'bg-emerald-50' : 'bg-rose-50' }} rounded-xl flex items-center justify-center">
                    <i class="bi {{ $paidThisMonth ? 'bi-check-circle text-emerald-500' : 'bi-exclamation-circle text-rose-500' }} text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase">Bulan Ini</p>
                    <p class="text-lg font-black {{ $paidThisMonth ? 'text-emerald-600' : 'text-rose-600' }}">{{ $paidThisMonth ? 'Lunas' : 'Belum Bayar' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center">
                    <i class="bi bi-calendar3 text-slate-500 text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase">Tarif Bulanan</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($pendatang->effective_punia_nominal, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="text-left px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Bulan</th>
                    <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Nominal</th>
                    <th class="text-center px-4 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Tgl Bayar</th>
                    <th class="text-center px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="text-center px-6 py-3 text-[10px] font-black text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $num => $name)
                @php
                    $payment = $payments[$num] ?? null;
                    $isPaid = !!$payment;
                    $isPast = ($selectedYear < $currentYear) || ($selectedYear == $currentYear && $num < $currentMonth);
                @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors {{ !$isPaid && $isPast ? 'bg-rose-50/30' : '' }}">
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-800">{{ $name }}</span>
                            @if(!$isPaid && $isPast)
                            <span class="text-[9px] font-bold text-rose-500 bg-rose-100 px-1.5 py-0.5 rounded uppercase">Terlewat</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-sm font-bold text-slate-700">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</span>
                        @else
                        <span class="text-sm text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-sm text-slate-500">{{ $payment->tanggal_bayar->format('d/m/Y') }}</span>
                        @else
                        <span class="text-sm text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        @if($isPaid)
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <i class="bi bi-check-circle-fill text-[10px]"></i> Lunas
                        </span>
                        @elseif($isPast)
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full">
                            <i class="bi bi-exclamation-circle-fill text-[10px]"></i> Belum
                        </span>
                        @else
                        <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        @if(!$isPaid)
                        <button @click="selectedMonth = {{ $num }}; selectedMonthName = '{{ $name }}'; showPaymentModal = true" 
                                class="inline-flex items-center gap-1 bg-primary-light hover:bg-primary-dark text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                            <i class="bi bi-wallet2"></i> Bayar
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Payment Modal --}}
    <div x-show="showPaymentModal" x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        <div @click.away="showPaymentModal = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-primary-light p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showPaymentModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <h3 class="text-xl font-black relative">Bayar Iuran</h3>
                <p class="text-white/80 text-sm font-medium mt-1 relative" x-text="'Bulan ' + selectedMonthName + ' ' + selectedYear"></p>
            </div>
            <div class="p-6 space-y-3">
                <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Nominal</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($pendatang->effective_punia_nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
                <button type="button" @click="processing = true; $refs.paymentForm.metode_pembayaran.value = 'cash'; $refs.paymentForm.submit()"
                        class="w-full text-left bg-white border-2 border-slate-100 rounded-xl p-4 hover:border-primary-light/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-primary-light group-hover:border-primary-light">
                            <i class="bi bi-cash-coin text-slate-400 text-lg group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800">Tunai</h4>
                            <p class="text-xs text-slate-400">Catat pembayaran manual</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light"></i>
                    </div>
                </button>
                <button type="button" @click="processing = true; $refs.paymentForm.metode_pembayaran.value = 'qris'; $refs.paymentForm.submit()"
                        class="w-full text-left bg-white border-2 border-slate-100 rounded-xl p-4 hover:border-primary-light/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-primary-light group-hover:border-primary-light">
                            <i class="bi bi-qr-code text-slate-400 text-lg group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800">Scan QRIS</h4>
                            <p class="text-xs text-slate-400">Metode QRIS</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-primary-light"></i>
                    </div>
                </button>
                <div x-show="processing" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50">
                    <i class="bi bi-arrow-repeat animate-spin text-4xl text-primary-light mb-3"></i>
                    <p class="text-xs font-bold text-slate-600">Sedang diproses...</p>
                </div>
            </div>
            <form x-ref="paymentForm" action="{{ url('administrator/pendatang/kartu-punia/bayar') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
                <input type="hidden" name="bulan" x-model="selectedMonth">
                <input type="hidden" name="tahun" x-model="selectedYear">
                <input type="hidden" name="metode_pembayaran">
            </form>
            <div class="px-6 pb-5 pt-1 text-center">
                <button @click="showPaymentModal = false" class="text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batal</button>
            </div>
        </div>
    </div>
</div>
@endsection
