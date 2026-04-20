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

<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ 
    showPaymentModal: false, 
    showDeleteModal: false,
    selectedMonth: null, 
    selectedMonthName: '',
    selectedYear: {{ $selectedYear }},
    deleteId: null,
    deleteMonthName: '',
    deleteCatatan: '',
    processing: false
}">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/penagih/pendatang/detail/'.$pendatang->id_pendatang) }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-3">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Kartu Punia</h1>
                <p class="text-slate-400 text-[10px] mt-1">{{ $pendatang->nama }} &middot; Pembayaran bulanan</p>
            </div>
            
            <div class="flex items-center gap-2">
                <!-- Print Button -->
                <a :href="'{{ url('administrator/penagih/pendatang/kartu-punia/print/'.$pendatang->id_pendatang) }}?year=' + selectedYear" 
                   class="h-8 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center gap-1.5 transition-colors">
                    <i class="bi bi-printer text-sm"></i>
                    <span class="text-[10px] font-bold">Cetak</span>
                </a>

                <!-- Year Selector -->
                <button @click="window.location.href = '{{ url('administrator/penagih/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear - 1)" 
                        class="h-8 w-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors">
                    <i class="bi bi-chevron-left text-sm"></i>
                </button>
                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5">
                    <span class="text-xs font-bold text-slate-700" x-text="selectedYear"></span>
                </div>
                <button @click="if(selectedYear < {{ date('Y') }}) window.location.href = '{{ url('administrator/penagih/pendatang/kartu-punia/'.$pendatang->id_pendatang) }}?year=' + (selectedYear + 1)" 
                        :disabled="selectedYear >= {{ date('Y') }}"
                        :class="selectedYear >= {{ date('Y') }} ? 'h-8 w-8 bg-slate-50 text-slate-300 rounded-lg flex items-center justify-center cursor-not-allowed' : 'h-8 w-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center transition-colors'">
                    <i class="bi bi-chevron-right text-sm"></i>
                </button>
            </div>
        </div>
    </div>

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
                    <p class="text-[11px] font-bold text-white/80 opacity-60">{{ $currentDateFormatted }}</p>
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
                @foreach($months as $num => $name)
                @php
                    $payment = $payments[$num] ?? null;
                    $isPaid = !!$payment;
                    $isPast = ($selectedYear < $currentYear) || ($selectedYear == $currentYear && $num < $currentMonth);
                @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors {{ !$isPaid && $isPast ? 'bg-rose-50/30' : '' }}">
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-800">{{ $name }}</span>
                            @if(!$isPaid && $isPast)
                            <span class="text-[7px] font-bold text-rose-500 bg-rose-100 px-1.5 py-0.5 rounded uppercase">Terlewat</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-2 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-[10px] font-bold text-slate-700">{{ number_format($payment->nominal, 0, ',', '.') }}</span>
                        @else
                        <span class="text-[10px] text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-2 py-3.5 text-center">
                        @if($isPaid)
                        <span class="text-[10px] text-slate-500">{{ $payment->tanggal_bayar ? $payment->tanggal_bayar->format('d/m') : '-' }}</span>
                        @else
                        <span class="text-[10px] text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            @if($isPaid)
                            <button class="h-7 px-2.5 bg-slate-50 text-slate-400 rounded-lg flex items-center justify-center gap-1 border border-slate-200 hover:bg-[#00a6eb] hover:text-white hover:border-[#00a6eb] transition-all text-[10px] font-bold">
                                <i class="bi bi-eye text-xs"></i>
                            </button>
                            <button @click="deleteId = {{ $payment->id_punia_pendatang }}; deleteMonthName = '{{ $name }}'; deleteCatatan = ''; showDeleteModal = true"
                                    class="h-7 px-2.5 bg-rose-50 text-rose-400 rounded-lg flex items-center justify-center gap-1 border border-rose-200 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all text-[10px] font-bold">
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                            @else
                            <button @click="selectedMonth = {{ $num }}; selectedMonthName = '{{ $name }}'; showPaymentModal = true" 
                                    class="h-7 px-2.5 bg-[#00a6eb] text-white rounded-lg flex items-center justify-center gap-1 hover:bg-[#0090d0] transition-all shadow-sm text-[10px] font-bold">
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

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" 
         x-cloak
         x-transition.opacity
         class="fixed inset-0 z-[90] flex items-end justify-center"
            style="display: none; z-index: 9999;">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[1px]" @click="showPaymentModal = false"></div>

        <div x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="relative bg-white rounded-t-[28px] shadow-2xl w-full max-w-[480px] max-h-[calc(100vh-5rem)] overflow-y-auto p-5 pb-[calc(2rem+env(safe-area-inset-bottom))]"
               style="z-index: 10000; max-height: calc(100vh - 5rem); padding-bottom: calc(2rem + env(safe-area-inset-bottom));"
             @click.stop>
            <div class="w-14 h-1.5 bg-slate-200 rounded-full mx-auto mb-4"></div>

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-black text-slate-800">Pembayaran Iuran</h3>
                    <p class="text-slate-400 text-[10px] mt-1" x-text="selectedMonthName + ' ' + selectedYear"></p>
                </div>
                <button @click="showPaymentModal = false" type="button" class="h-8 w-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <i class="bi bi-x-lg text-xs text-slate-500"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Nominal</span>
                        <span class="text-sm font-bold text-slate-800">Rp {{ number_format($pendatang->effective_punia_nominal, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Tunai -->
                <button type="button" @click="processing = true; $refs.paymentForm.metode_pembayaran.value = 'cash'; $refs.paymentForm.submit()"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-cash-coin text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Tunai</h4>
                            <p class="text-[10px] text-slate-400">Catat pembayaran manual</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <!-- Online Payment -->
                <button type="button" @click="processing = true; $refs.onlinePaymentForm.submit()"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-phone text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Online Payment</h4>
                            <p class="text-[10px] text-slate-400">Lanjut ke metode pembayaran Xendit</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <!-- Loading -->
                <div x-show="processing" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50">
                    <i class="bi bi-arrow-repeat animate-spin text-4xl text-[#00a6eb] mb-3"></i>
                    <p class="text-xs font-bold text-slate-600">Sedang diproses...</p>
                </div>
            </div>

            <!-- Hidden Form -->
            <form x-ref="paymentForm" action="{{ url('administrator/penagih/pendatang/kartu-punia/bayar') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
                <input type="hidden" name="bulan" x-model="selectedMonth">
                <input type="hidden" name="tahun" x-model="selectedYear">
                <input type="hidden" name="metode_pembayaran">
            </form>

            <form x-ref="onlinePaymentForm" action="{{ url('administrator/penagih/pendatang/kartu-punia/online') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_pendatang" value="{{ $pendatang->id_pendatang }}">
                <input type="hidden" name="bulan" x-model="selectedMonth">
                <input type="hidden" name="tahun" x-model="selectedYear">
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm shadow-2xl"
         style="display: none;">
        
        <div @click.away="showDeleteModal = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden">
            
            <!-- Modal Header -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showDeleteModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <div class="h-10 w-10 bg-white/20 rounded-lg flex items-center justify-center mb-3">
                        <i class="bi bi-exclamation-triangle text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black">Hapus Pembayaran</h3>
                    <p class="text-white/80 text-xs font-medium mt-1" x-text="'Bulan ' + deleteMonthName + ' ' + selectedYear"></p>
                </div>
            </div>

            <!-- Content -->
            <form x-ref="deleteForm" action="{{ url('administrator/penagih/pendatang/kartu-punia/hapus') }}" method="POST">
                @csrf
                <input type="hidden" name="id_punia_pendatang" x-model="deleteId">
                
                <div class="p-6 space-y-4">
                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-3">
                        <p class="text-[10px] text-rose-600 leading-relaxed">
                            <i class="bi bi-info-circle mr-1"></i>
                            Data pembayaran akan dihapus secara permanen. Anda <strong>wajib</strong> mengisi alasan penghapusan.
                        </p>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1.5 block">Alasan Penghapusan <span class="text-rose-500">*</span></label>
                        <textarea name="catatan_hapus" x-model="deleteCatatan" rows="3" required minlength="5"
                                  placeholder="Contoh: Salah input bulan, duplikat pembayaran, dll..."
                                  class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2.5 bg-white placeholder-slate-300 focus:outline-none focus:border-rose-300 focus:ring-1 focus:ring-rose-200 resize-none"></textarea>
                        <p class="text-[9px] text-slate-400 mt-1">Minimal 5 karakter</p>
                    </div>
                </div>

                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" @click="showDeleteModal = false" 
                            class="flex-1 bg-slate-100 text-slate-600 text-xs font-bold py-2.5 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            :disabled="deleteCatatan.length < 5"
                            :class="deleteCatatan.length < 5 ? 'flex-1 bg-slate-200 text-slate-400 text-xs font-bold py-2.5 rounded-xl cursor-not-allowed' : 'flex-1 bg-rose-500 text-white text-xs font-bold py-2.5 rounded-xl hover:bg-rose-600 transition-colors'"
                            class="flex items-center justify-center gap-1.5">
                        <i class="bi bi-trash text-sm"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
