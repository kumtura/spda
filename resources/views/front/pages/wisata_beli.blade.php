@extends('mobile_layout_public')

@section('content')
<div class="bg-white pb-24" x-data="ticketApp">
    <!-- Header with wisata background image -->
    <div class="px-4 pt-8 pb-12 text-white relative overflow-hidden" style="min-height: 160px;">
        @if($objek->foto)
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/wisata/'.$objek->foto) }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-black/70"></div>
        @else
        <div class="absolute inset-0 bg-gradient-to-br from-[#00a6eb] to-[#0090d0]"></div>
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        @endif
        
        <a href="{{ url('wisata/detail/'.$objek->id_objek_wisata) }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2 drop-shadow-md">Beli Tiket</h1>
            <p class="text-white/90 text-xs font-medium drop-shadow">{{ $objek->nama_objek }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10">
        <form action="{{ url('wisata/beli/submit') }}" method="POST" id="formBeli" @submit.prevent="submitForm" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
            <input type="hidden" name="tanggal_kunjungan" :value="selectedDate">
            
            <!-- Tanggal Kunjungan -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tanggal Kunjungan</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Pilih Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" @click="openCalendar()" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-left flex items-center justify-between transition-all hover:border-[#00a6eb]/50 focus:ring-4 focus:ring-[#00a6eb]/10">
                        <span class="text-sm" :class="selectedDate ? 'font-bold text-slate-800' : 'text-slate-400'" x-text="selectedDate ? formatDisplayDate(selectedDate) : 'Pilih tanggal kunjungan...'"></span>
                        <i class="bi bi-calendar3 text-[#00a6eb]"></i>
                    </button>
                    <template x-if="selectedDate && availabilityInfo">
                        <div class="flex items-center gap-1.5 px-1">
                            <template x-if="availabilityInfo.unlimited">
                                <span class="text-[10px] text-slate-600 font-bold"><i class="bi bi-check-circle mr-1"></i>Tiket tersedia</span>
                            </template>
                            <template x-if="!availabilityInfo.unlimited && availabilityInfo.available > 0">
                                <span class="text-[10px] text-slate-600 font-bold"><i class="bi bi-exclamation-circle mr-1"></i>Sisa <span x-text="availabilityInfo.available"></span> tiket</span>
                            </template>
                            <template x-if="!availabilityInfo.unlimited && availabilityInfo.available <= 0">
                                <span class="text-[10px] text-rose-600 font-bold"><i class="bi bi-x-circle mr-1"></i>Tiket habis untuk tanggal ini</span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Pilih Tiket -->
            @if($objek->kategoriTiket->count() > 0)
            @php
                $orangWna = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'wna');
                $orangLocal = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'local');
                $orangAll = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'all');
                $kendaraanKategori = $objek->kategoriTiket->where('tipe_kategori', 'kendaraan');
                $hasMultipleMarkets = ($orangWna->count() > 0 && $orangLocal->count() > 0);
            @endphp

            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Tiket</h3>

                @if($hasMultipleMarkets)
                <!-- Market Type Toggle -->
                <div class="flex bg-slate-100 rounded-xl p-1 gap-1">
                    <button type="button" @click="marketFilter = 'local'" 
                        :class="marketFilter === 'local' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500'"
                        class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                        <i class="bi bi-geo-alt"></i> Lokal
                    </button>
                    <button type="button" @click="marketFilter = 'wna'" 
                        :class="marketFilter === 'wna' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500'"
                        class="flex-1 py-2.5 rounded-lg text-xs font-bold transition-all flex items-center justify-center gap-1.5">
                        <i class="bi bi-globe"></i> WNA
                    </button>
                </div>
                @endif
                
                <div class="space-y-3">
                    {{-- Local tickets --}}
                    @if($orangLocal->count() > 0)
                    <div x-show="marketFilter === 'local' || marketFilter === 'all'" x-transition class="space-y-3">
                        @if(!$hasMultipleMarkets && $orangLocal->count() > 0)
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1"><i class="bi bi-geo-alt"></i> Lokal</p>
                        @endif
                        @foreach($orangLocal as $kategori)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-dash text-slate-700"></i>
                                </button>
                                <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" 
                                    :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0"
                                    class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" 
                                    readonly>
                                <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center">
                                    <i class="bi bi-plus text-white"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- WNA tickets --}}
                    @if($orangWna->count() > 0)
                    <div x-show="marketFilter === 'wna' || marketFilter === 'all'" x-transition class="space-y-3">
                        @if(!$hasMultipleMarkets && $orangWna->count() > 0)
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1"><i class="bi bi-globe"></i> WNA</p>
                        @endif
                        @foreach($orangWna as $kategori)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)
                                    <p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                    @endif
                                </div>
                                <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-dash text-slate-700"></i>
                                </button>
                                <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" 
                                    :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0"
                                    class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" 
                                    readonly>
                                <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center">
                                    <i class="bi bi-plus text-white"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- All market tickets --}}
                    @foreach($orangAll as $kategori)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                @if($kategori->deskripsi)
                                <p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                @endif
                            </div>
                            <p class="text-base font-black text-[#00a6eb]">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center">
                                <i class="bi bi-dash text-slate-700"></i>
                            </button>
                            <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" 
                                :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0"
                                class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" 
                                readonly>
                            <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center">
                                <i class="bi bi-plus text-white"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach

                    {{-- Kendaraan tickets --}}
                    @if($kendaraanKategori->count() > 0)
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1 pt-2"><i class="bi bi-car-front"></i> Kendaraan</p>
                    @foreach($kendaraanKategori as $kategori)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                @if($kategori->deskripsi)
                                <p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>
                                @endif
                            </div>
                            <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center">
                                <i class="bi bi-dash text-slate-700"></i>
                            </button>
                            <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" 
                                :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0"
                                class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" 
                                readonly>
                            <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center">
                                <i class="bi bi-plus text-white"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-exclamation-triangle text-amber-600 text-lg"></i>
                    <p class="text-xs text-amber-800">Kategori tiket belum tersedia</p>
                </div>
            </div>
            @endif

            <!-- Summary -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-3">Ringkasan</h3>
                
                <div class="space-y-2 mb-3 text-xs">
                    <template x-if="Object.keys(summaryItems).length === 0">
                        <p class="text-slate-400 text-center py-2 text-[10px]">Pilih tiket terlebih dahulu</p>
                    </template>
                    <template x-for="(item, id) in summaryItems" :key="id">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600" x-text="item.name + ' x' + item.qty"></span>
                            <span class="font-bold text-slate-800" x-text="'Rp ' + item.subtotal.toLocaleString('id-ID')"></span>
                        </div>
                    </template>
                </div>
                
                <div class="pt-3 border-t border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-black text-slate-800">Total</span>
                        <span class="text-xl font-black text-[#00a6eb]" x-text="'Rp ' + totalPrice.toLocaleString('id-ID')"></span>
                    </div>
                </div>
            </div>

            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-xl font-black text-sm shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 transition-all active:scale-[0.98]">
                Lanjutkan Pembayaran
            </button>
        </form>
    </div>

    <!-- Calendar Modal -->
    <div x-show="showCalendar" x-cloak
         @click.self="showCalendar = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-end justify-center bg-black/40 backdrop-blur-sm">
        <div @click.stop
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-y-0"
             x-transition:leave-end="translate-y-full"
             class="bg-white rounded-t-3xl w-full max-w-lg shadow-2xl pb-8">
            
            <!-- Handle bar -->
            <div class="flex justify-center pt-3 pb-2">
                <div class="w-10 h-1 bg-slate-300 rounded-full"></div>
            </div>

            <div class="px-6">
                <!-- Calendar Header -->
                <div class="flex items-center justify-between mb-6">
                    <button type="button" @click="prevMonth()" class="h-9 w-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-all">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <h3 class="text-sm font-black text-slate-800" x-text="calendarTitle"></h3>
                    <button type="button" @click="nextMonth()" class="h-9 w-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-all">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <!-- Day names -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <template x-for="day in ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']" :key="day">
                        <div class="text-center text-[10px] font-bold text-slate-400 uppercase py-1" x-text="day"></div>
                    </template>
                </div>

                <!-- Calendar grid -->
                <div class="grid grid-cols-7 gap-1">
                    <template x-for="(cell, index) in calendarDays" :key="index">
                        <button type="button"
                            :disabled="!cell.date || cell.isPast || cell.soldOut"
                            @click="cell.date && !cell.isPast && !cell.soldOut && selectDate(cell.dateStr)"
                            class="aspect-square rounded-xl flex flex-col items-center justify-center text-xs transition-all relative"
                            :class="{
                                'hover:bg-blue-50 cursor-pointer': cell.date && !cell.isPast && !cell.soldOut,
                                'bg-[#00a6eb] text-white shadow-md shadow-blue-200': cell.dateStr === selectedDate,
                                'text-slate-800 font-bold': cell.date && !cell.isPast && !cell.soldOut && cell.dateStr !== selectedDate,
                                'text-slate-300 cursor-not-allowed': cell.isPast || cell.soldOut,
                                'bg-rose-50': cell.soldOut && !cell.isPast,
                                '': !cell.date
                            }">
                            <span x-text="cell.day"></span>
                            <template x-if="cell.date && !cell.isPast && cell.hasLimit && !cell.soldOut">
                                <span class="text-[7px] font-bold leading-none mt-0.5"
                                      :class="cell.dateStr === selectedDate ? 'text-white/80' : (cell.available <= 5 ? 'text-slate-500' : 'text-slate-500')"
                                      x-text="cell.available + ' left'"></span>
                            </template>
                            <template x-if="cell.soldOut && !cell.isPast">
                                <span class="text-[7px] font-bold text-rose-400 leading-none mt-0.5">Habis</span>
                            </template>
                        </button>
                    </template>
                </div>

                <!-- Loading indicator -->
                <div x-show="loadingAvailability" class="flex items-center justify-center py-3">
                    <div class="animate-spin h-5 w-5 border-2 border-[#00a6eb] border-t-transparent rounded-full"></div>
                    <span class="text-[10px] text-slate-500 ml-2">Memuat ketersediaan...</span>
                </div>

                <!-- Legend -->
                <div class="flex items-center justify-center gap-4 mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-1.5">
                        <div class="h-3 w-3 rounded bg-[#00a6eb]"></div>
                        <span class="text-[9px] text-slate-500">Dipilih</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="h-3 w-3 rounded bg-white border border-slate-200"></div>
                        <span class="text-[9px] text-slate-500">Tersedia</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="h-3 w-3 rounded bg-rose-50 border border-rose-200"></div>
                        <span class="text-[9px] text-slate-500">Habis</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', function() {
    var hasDailyLimit = @json((bool) $objek->batas_tiket_harian);
    var marketFilterInit = '{{ ($orangLocal ?? collect())->count() > 0 ? "local" : (($orangWna ?? collect())->count() > 0 ? "wna" : "all") }}';
    var checkAvailabilityUrl = '{{ url("wisata/check-availability") }}';
    var objekWisataId = {{ (int) $objek->id_objek_wisata }};

    var today = new Date();
    today.setHours(0, 0, 0, 0);

    Alpine.data('ticketApp', function() {
        return {
            showCalendar: false,
            selectedDate: '',
            calendarMonth: today.getMonth(),
            calendarYear: today.getFullYear(),
            availabilityData: {},
            loadedAvailabilityMonths: {},
            loadingAvailability: false,
            activeAvailabilityRequest: 0,
            availabilityAbortController: null,
            hasDailyLimit: hasDailyLimit,
            isUnlimited: !hasDailyLimit,
            quantities: {},
            summaryItems: {},
            totalPrice: 0,
            marketFilter: marketFilterInit,
            availabilityInfo: null,

            openCalendar: function() {
                this.showCalendar = true;
                this.ensureAvailabilityForCurrentMonth();
            },

            getAvailabilityMonthKey: function(month, year) {
                return year + '-' + String(month).padStart(2, '0');
            },

            ensureAvailabilityForCurrentMonth: function() {
                if (!this.hasDailyLimit) {
                    this.isUnlimited = true;
                    return;
                }
                var month = this.calendarMonth + 1;
                var year = this.calendarYear;
                var monthKey = this.getAvailabilityMonthKey(month, year);
                if (this.loadedAvailabilityMonths[monthKey]) return;
                this.fetchAvailability(month, year);
            },

            get calendarTitle() {
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return months[this.calendarMonth] + ' ' + this.calendarYear;
            },

            get calendarDays() {
                var firstDay = new Date(this.calendarYear, this.calendarMonth, 1);
                var lastDay = new Date(this.calendarYear, this.calendarMonth + 1, 0);
                var startDay = firstDay.getDay();
                var daysInMonth = lastDay.getDate();
                var cells = [];

                for (var i = 0; i < startDay; i++) {
                    cells.push({ date: null, day: '', dateStr: '', isPast: true, soldOut: false, hasLimit: false, available: 0 });
                }

                for (var d = 1; d <= daysInMonth; d++) {
                    var date = new Date(this.calendarYear, this.calendarMonth, d);
                    var dateStr = this.calendarYear + '-' + String(this.calendarMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                    var isPast = date < today;
                    var hasLimit = false;
                    var available = 0;
                    var soldOut = false;

                    if (!this.isUnlimited && this.availabilityData[dateStr]) {
                        hasLimit = true;
                        available = this.availabilityData[dateStr].available;
                        soldOut = available <= 0;
                    }

                    cells.push({ date: date, day: d, dateStr: dateStr, isPast: isPast, soldOut: soldOut, hasLimit: hasLimit, available: available });
                }

                return cells;
            },

            prevMonth: function() {
                if (this.calendarMonth === 0) { this.calendarMonth = 11; this.calendarYear--; }
                else { this.calendarMonth--; }
                this.ensureAvailabilityForCurrentMonth();
            },

            nextMonth: function() {
                if (this.calendarMonth === 11) { this.calendarMonth = 0; this.calendarYear++; }
                else { this.calendarMonth++; }
                this.ensureAvailabilityForCurrentMonth();
            },

            fetchAvailability: function(month, year) {
                if (!this.hasDailyLimit) { this.isUnlimited = true; return; }

                var monthKey = this.getAvailabilityMonthKey(month, year);
                if (this.loadedAvailabilityMonths[monthKey]) return;

                var self = this;
                var requestId = ++self.activeAvailabilityRequest;

                if (self.availabilityAbortController) self.availabilityAbortController.abort();

                var controller = new AbortController();
                self.availabilityAbortController = controller;
                self.loadingAvailability = true;

                var failsafe = setTimeout(function() {
                    if (requestId === self.activeAvailabilityRequest) self.loadingAvailability = false;
                }, 8000);

                var timeoutId = setTimeout(function() { controller.abort(); }, 6000);
                var url = checkAvailabilityUrl + '?id_objek_wisata=' + objekWisataId + '&month=' + month + '&year=' + year;

                fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
                    .then(function(response) {
                        clearTimeout(timeoutId);
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        return response.text();
                    })
                    .then(function(text) {
                        var data = JSON.parse(text);
                        self.isUnlimited = data.unlimited === true || data.unlimited === undefined;
                        if (self.isUnlimited) {
                            self.loadedAvailabilityMonths[monthKey] = true;
                        } else if (data.dates) {
                            self.availabilityData = Object.assign({}, self.availabilityData, data.dates);
                            self.loadedAvailabilityMonths[monthKey] = true;
                        }
                    })
                    .catch(function(e) {
                        if (e.name !== 'AbortError') console.error('Availability fetch failed:', e);
                    })
                    .finally(function() {
                        clearTimeout(failsafe);
                        if (requestId === self.activeAvailabilityRequest) {
                            self.loadingAvailability = false;
                            self.availabilityAbortController = null;
                        }
                    });
            },

            selectDate: function(dateStr) {
                this.selectedDate = dateStr;
                this.showCalendar = false;
                if (this.isUnlimited) {
                    this.availabilityInfo = { unlimited: true };
                } else if (this.availabilityData[dateStr]) {
                    this.availabilityInfo = { unlimited: false, available: this.availabilityData[dateStr].available };
                } else {
                    this.availabilityInfo = null;
                }
            },

            formatDisplayDate: function(dateStr) {
                var date = new Date(dateStr + 'T00:00:00');
                var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return days[date.getDay()] + ', ' + date.getDate() + ' ' + months[date.getMonth()] + ' ' + date.getFullYear();
            },

            incrementQty: function(id, harga, nama) {
                if (!this.quantities[id]) this.quantities[id] = 0;
                this.quantities[id]++;
                this.updateSummary(id, harga, nama);
            },

            decrementQty: function(id) {
                if (!this.quantities[id] || this.quantities[id] <= 0) return;
                this.quantities[id]--;
                if (this.summaryItems[id]) {
                    var item = this.summaryItems[id];
                    this.updateSummary(id, item.subtotal / (this.quantities[id] + 1), item.name);
                }
            },

            updateSummary: function(id, harga, nama) {
                var qty = this.quantities[id] || 0;
                if (qty > 0) {
                    this.summaryItems[id] = { name: nama, qty: qty, subtotal: qty * harga };
                } else {
                    delete this.summaryItems[id];
                }
                this.totalPrice = Object.values(this.summaryItems).reduce(function(sum, item) { return sum + item.subtotal; }, 0);
            },

            submitForm: function() {
                if (!this.selectedDate) { alert('Pilih tanggal kunjungan terlebih dahulu'); return; }
                var hasTicket = false;
                for (var id in this.quantities) { if (this.quantities[id] > 0) { hasTicket = true; break; } }
                if (!hasTicket) { alert('Pilih minimal 1 tiket'); return; }
                document.getElementById('formBeli').submit();
            }
        };
    });
});
</script>
@endsection
