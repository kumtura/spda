@extends('mobile_layout_public')

@section('content')
<style>
    nav.fixed.bottom-0 { display: none !important; }
    .mobile-container { padding-bottom: 0 !important; }
</style>
@php
    $orangWna = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && $k->market_type === 'wna');
    $orangLocal = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && $k->market_type === 'local');
    $orangAll = $objek->kategoriTiket->filter(fn($k) => $k->tipe_kategori !== 'kendaraan' && !in_array($k->market_type, ['wna', 'local']));
    $kendaraanKategori = $objek->kategoriTiket->where('tipe_kategori', 'kendaraan');
    $hasMultipleMarkets = ($orangWna->count() > 0 && $orangLocal->count() > 0);
    $defaultMarket = $orangLocal->count() > 0 ? 'local' : ($orangWna->count() > 0 ? 'wna' : 'all');
    $todayStr = date('Y-m-d');
    $cheapestPrice = $objek->kategoriTiket->min('harga') ?? 0;
    $todaySold = \App\Models\TiketDetail::query()
        ->join('tb_tiket_wisata', 'tb_tiket_wisata.id_tiket', '=', 'tb_tiket_detail.id_tiket')
        ->where('tb_tiket_wisata.id_objek_wisata', $objek->id_objek_wisata)
        ->whereIn('tb_tiket_wisata.status_pembayaran', ['paid', 'completed', 'pending'])
        ->where('tb_tiket_wisata.tanggal_kunjungan', $todayStr)
        ->sum('tb_tiket_detail.jumlah');
@endphp

<div class="bg-white pb-48" x-data="{
    selectedDate: '',
    quantities: {},
    summaryItems: {},
    totalPrice: 0,
    totalQty: 0,
    marketFilter: '{{ $defaultMarket }}',
    availabilityInfo: null,
    checkingAvailability: false,
    showCalendar: false,
    calMonth: new Date().getMonth(),
    calYear: new Date().getFullYear(),
    calAvail: {},
    calLoading: false,
    calCellsArr: [],
    calTitleStr: '',
    summaryStr: '',
    _today: null,
    _months: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
    _days: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],

    init: function() {
        this._today = new Date();
        this._today.setHours(0,0,0,0);
        this.buildCalCells();
    },

    openCalendar: function() {
        this.showCalendar = true;
        this.fetchAvail();
    },

    fetchAvail: function() {
        @if($objek->batas_tiket_harian)
        var self = this;
        var key = self.calYear + '-' + (self.calMonth+1);
        if (self.calAvail['_loaded_' + key]) return;
        self.calLoading = true;
        var url = '{{ url("wisata/check-availability") }}?id_objek_wisata={{ (int) $objek->id_objek_wisata }}&month=' + (self.calMonth+1) + '&year=' + self.calYear;
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.unlimited && data.dates) {
                    var updated = Object.assign({}, self.calAvail);
                    for (var d in data.dates) { updated[d] = data.dates[d]; }
                    updated['_loaded_' + key] = true;
                    self.calAvail = updated;
                } else {
                    var updated = Object.assign({}, self.calAvail);
                    updated['_loaded_' + key] = true;
                    self.calAvail = updated;
                }
            })
            .catch(function() {})
            .finally(function() { self.calLoading = false; self.buildCalCells(); });
        @endif
    },

    buildCalCells: function() {
        this.calTitleStr = this._months[this.calMonth] + ' ' + this.calYear;
        var first = new Date(this.calYear, this.calMonth, 1);
        var last = new Date(this.calYear, this.calMonth + 1, 0);
        var cells = [];
        for (var i = 0; i < first.getDay(); i++) cells.push(null);
        for (var d = 1; d <= last.getDate(); d++) {
            var dt = new Date(this.calYear, this.calMonth, d);
            var str = this.calYear + '-' + String(this.calMonth+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
            var avail = this.calAvail[str];
            var isSold = !!(avail && avail.available <= 0);
            cells.push({ day: d, dateStr: str, isPast: dt < this._today, avail: avail || null, isSold: isSold });
        }
        this.calCellsArr = cells;
    },

    prevMonth: function() {
        if (this.calMonth === 0) { this.calMonth = 11; this.calYear--; }
        else { this.calMonth--; }
        this.buildCalCells();
        this.fetchAvail();
    },

    nextMonth: function() {
        if (this.calMonth === 11) { this.calMonth = 0; this.calYear++; }
        else { this.calMonth++; }
        this.buildCalCells();
        this.fetchAvail();
    },

    pickDate: function(dateStr, isSold) {
        if (isSold) return;
        this.selectedDate = dateStr;
        this.showCalendar = false;
        this.availabilityInfo = null;
        var avail = this.calAvail[dateStr];
        if (avail) {
            this.availabilityInfo = { unlimited: false, available: avail.available };
        } else {
            this.availabilityInfo = { unlimited: true };
        }
    },

    fmtDate: function(ds) {
        var d = new Date(ds + 'T00:00:00');
        var dn = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return dn[d.getDay()] + ', ' + d.getDate() + ' ' + this._months[d.getMonth()] + ' ' + d.getFullYear();
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
        this.totalQty = Object.values(this.summaryItems).reduce(function(sum, item) { return sum + item.qty; }, 0);
        var sitems = Object.values(this.summaryItems);
        this.summaryStr = sitems.length === 0 ? '' : sitems.map(function(i) { return i.name + ' x' + i.qty; }).join(', ');
    },

    submitForm: function() {
        if (!this.selectedDate) { alert('Pilih tanggal kunjungan terlebih dahulu'); return; }
        if (this.totalQty === 0) { alert('Pilih minimal 1 tiket'); return; }
        document.getElementById('formBeli').submit();
    }
}">
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
            @if($objek->alamat)
            <p class="text-white/70 text-[10px] mt-1 flex items-center gap-1 drop-shadow"><i class="bi bi-geo-alt"></i> {{ $objek->alamat }}</p>
            @endif
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10 space-y-5">
        <form action="{{ url('wisata/beli/submit') }}" method="POST" id="formBeli" @submit.prevent="submitForm" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
            
            <input type="hidden" name="tanggal_kunjungan" :value="selectedDate">

            <!-- Tanggal Kunjungan -->
            <div class="space-y-3">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tanggal Kunjungan</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Pilih Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" @click="openCalendar()"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-left flex items-center justify-between transition-all hover:border-[#00a6eb]/50 focus:ring-4 focus:ring-[#00a6eb]/10">
                        <span class="text-sm" :class="selectedDate ? 'font-bold text-slate-800' : 'text-slate-400'" x-text="selectedDate ? fmtDate(selectedDate) : 'Pilih tanggal kunjungan...'"></span>
                        <i class="bi bi-calendar3 text-[#00a6eb]"></i>
                    </button>
                    
                    <template x-if="checkingAvailability">
                        <div class="flex items-center gap-1.5 px-1">
                            <div class="animate-spin h-3 w-3 border-2 border-[#00a6eb] border-t-transparent rounded-full"></div>
                            <span class="text-[10px] text-slate-500 font-bold">Memeriksa ketersediaan...</span>
                        </div>
                    </template>
                    <template x-if="!checkingAvailability && selectedDate && availabilityInfo">
                        <div class="flex items-center gap-1.5 px-1">
                            <template x-if="availabilityInfo.unlimited">
                                <span class="text-[10px] text-emerald-600 font-bold"><i class="bi bi-check-circle-fill mr-1"></i>Tiket tersedia</span>
                            </template>
                            <template x-if="!availabilityInfo.unlimited && availabilityInfo.available > 0">
                                <span class="text-[10px] text-amber-600 font-bold"><i class="bi bi-exclamation-circle-fill mr-1"></i>Sisa <span x-text="availabilityInfo.available"></span> tiket</span>
                            </template>
                            <template x-if="!availabilityInfo.unlimited && availabilityInfo.available <= 0">
                                <span class="text-[10px] text-rose-600 font-bold"><i class="bi bi-x-circle-fill mr-1"></i>Tiket habis untuk tanggal ini</span>
                            </template>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Pilih Tiket -->
            @if($objek->kategoriTiket->count() > 0)
            <div id="section-tiket" class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Tiket</h3>

                @if($hasMultipleMarkets)
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
                    @if($orangLocal->count() > 0)
                    <div x-show="marketFilter === 'local' || marketFilter === 'all'" x-transition class="space-y-3">
                        @if(!$hasMultipleMarkets)
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1"><i class="bi bi-geo-alt"></i> Lokal</p>
                        @endif
                        @foreach($orangLocal as $kategori)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)<p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>@endif
                                </div>
                                <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center"><i class="bi bi-dash text-slate-700"></i></button>
                                <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0" class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" readonly>
                                <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center"><i class="bi bi-plus text-white"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($orangWna->count() > 0)
                    <div x-show="marketFilter === 'wna' || marketFilter === 'all'" x-transition class="space-y-3">
                        @if(!$hasMultipleMarkets)
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1"><i class="bi bi-globe"></i> WNA</p>
                        @endif
                        @foreach($orangWna as $kategori)
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                    @if($kategori->deskripsi)<p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>@endif
                                </div>
                                <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center"><i class="bi bi-dash text-slate-700"></i></button>
                                <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0" class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" readonly>
                                <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center"><i class="bi bi-plus text-white"></i></button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @foreach($orangAll as $kategori)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                @if($kategori->deskripsi)<p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>@endif
                            </div>
                            <p class="text-base font-black text-[#00a6eb]">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center"><i class="bi bi-dash text-slate-700"></i></button>
                            <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0" class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" readonly>
                            <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center"><i class="bi bi-plus text-white"></i></button>
                        </div>
                    </div>
                    @endforeach

                    @if($kendaraanKategori->count() > 0)
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest flex items-center gap-1 pt-2"><i class="bi bi-car-front"></i> Kendaraan</p>
                    @foreach($kendaraanKategori as $kategori)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                @if($kategori->deskripsi)<p class="text-[10px] text-slate-500">{{ $kategori->deskripsi }}</p>@endif
                            </div>
                            <p class="text-base font-black text-slate-800">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="decrementQty({{ $kategori->id_kategori_tiket }})" class="h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center"><i class="bi bi-dash text-slate-700"></i></button>
                            <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" :value="quantities[{{ $kategori->id_kategori_tiket }}] || 0" class="w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" readonly>
                            <button type="button" @click="incrementQty({{ $kategori->id_kategori_tiket }}, {{ $kategori->harga }}, '{{ addslashes($kategori->nama_kategori) }}')" class="h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center"><i class="bi bi-plus text-white"></i></button>
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

        </form>

        <!-- Info Sections -->
        <div class="space-y-4">
            @if($objek->alamat)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <div class="flex items-start gap-3">
                    <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center shrink-0">
                        <i class="bi bi-geo-alt-fill text-[#00a6eb] text-sm"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-1">Alamat Lokasi</h4>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $objek->alamat }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($objek->deskripsi)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="bi bi-info-circle-fill text-[#00a6eb]"></i> Deskripsi
                </h4>
                <div class="text-xs text-slate-600 leading-relaxed prose prose-sm max-w-none">{!! nl2br(e($objek->deskripsi)) !!}</div>
            </div>
            @endif

            @if($objek->detail_termasuk)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="bi bi-ticket-perforated-fill text-[#00a6eb]"></i> Deskripsi Tiket
                </h4>
                <div class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($objek->detail_termasuk)) !!}</div>
            </div>
            @endif

            @if($objek->cara_penggunaan)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="bi bi-journal-check text-[#00a6eb]"></i> Cara Penggunaan
                </h4>
                <div class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($objek->cara_penggunaan)) !!}</div>
            </div>
            @endif

            @if($objek->pembatalan)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="bi bi-x-circle-fill text-rose-400"></i> Kebijakan Pembatalan
                </h4>
                <div class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($objek->pembatalan)) !!}</div>
            </div>
            @endif

            @if($objek->syarat_ketentuan)
            <div class="bg-white rounded-2xl border border-slate-100 p-5">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="bi bi-shield-check text-[#00a6eb]"></i> Syarat & Ketentuan
                </h4>
                <div class="text-xs text-slate-600 leading-relaxed">{!! nl2br(e($objek->syarat_ketentuan)) !!}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Floating Bottom Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-slate-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
        <div class="max-w-lg mx-auto px-4 py-3">
            <!-- State 1: No tickets selected -->
            <div x-show="totalQty === 0" class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-400 font-medium">Mulai dari</p>
                    <p class="text-lg font-black text-slate-800">IDR {{ number_format($cheapestPrice, 0, ',', '.') }}</p>
                    <p class="text-[9px] text-slate-400">Terjual {{ (int) $todaySold }} hari ini</p>
                </div>
                <a href="#section-tiket" class="bg-[#00a6eb] text-white px-6 py-3 rounded-xl font-black text-sm shadow-lg shadow-blue-200/50 active:scale-95 transition-all flex items-center gap-1.5">
                    <span>Beli Tiket</span>
                    <i class="bi bi-arrow-down text-xs"></i>
                </a>
            </div>
            <!-- State 2: Tickets selected -->
            <div x-show="totalQty > 0" x-cloak class="flex items-center justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] text-slate-400 font-medium">Total (<span x-text="totalQty"></span> pax):</p>
                    <p class="text-lg font-black text-slate-800" x-text="'IDR ' + totalPrice.toLocaleString('id-ID')"></p>
                    <p class="text-[9px] text-slate-400 truncate" x-text="summaryStr"></p>
                </div>
                <button type="button" @click="submitForm()" class="bg-[#00a6eb] text-white px-6 py-3 rounded-xl font-black text-sm shadow-lg shadow-blue-200/50 active:scale-95 transition-all shrink-0">
                    Pesan
                </button>
            </div>
        </div>
    </div>

    <!-- Calendar Bottom Sheet -->
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
             x-show="showCalendar"
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
                <!-- Calendar title -->
                <div class="flex items-center justify-between mb-5">
                    <button type="button" @click="prevMonth()" class="h-9 w-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-all active:scale-95">
                        <i class="bi bi-chevron-left text-sm"></i>
                    </button>
                    <h3 class="text-sm font-black text-slate-800" x-text="calTitleStr"></h3>
                    <button type="button" @click="nextMonth()" class="h-9 w-9 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-all active:scale-95">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </button>
                </div>

                <!-- Day headers -->
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <template x-for="dn in _days" :key="dn">
                        <div class="text-center text-[10px] font-bold text-slate-400 uppercase py-1" x-text="dn"></div>
                    </template>
                </div>

                <!-- Date grid -->
                <div class="grid grid-cols-7 gap-1">
                    <template x-for="(cell, idx) in calCellsArr" :key="idx">
                        <div>
                            <template x-if="cell === null">
                                <div class="aspect-square"></div>
                            </template>
                            <template x-if="cell !== null">
                                <button type="button"
                                    :disabled="cell.isPast || cell.isSold"
                                    @click="pickDate(cell.dateStr, cell.isPast || cell.isSold)"
                                    class="w-full rounded-xl flex flex-col items-center justify-center text-xs font-semibold transition-all py-1" style="min-height: 44px;"
                                    :class="{
                                        'bg-[#00a6eb] text-white shadow-md shadow-blue-200 font-black scale-105': cell.dateStr === selectedDate,
                                        'text-slate-800 hover:bg-[#00a6eb]/10 active:scale-95': !cell.isPast && !cell.isSold && cell.dateStr !== selectedDate,
                                        'text-slate-300 cursor-not-allowed': cell.isPast,
                                        'text-rose-300 cursor-not-allowed bg-rose-50/50': cell.isSold && !cell.isPast
                                    }">
                                    <span x-text="cell.day"></span>
                                    <template x-if="!cell.isPast && cell.avail && cell.avail.available > 0 && cell.dateStr !== selectedDate">
                                        <span class="text-emerald-500 font-bold leading-none mt-0.5" style="font-size:7px;" x-text="cell.avail.available"></span>
                                    </template>
                                    <template x-if="!cell.isPast && cell.isSold && cell.dateStr !== selectedDate">
                                        <span class="text-rose-400 font-bold leading-none mt-0.5" style="font-size:7px;">Sold</span>
                                    </template>
                                    <template x-if="!cell.isPast && !cell.avail && !cell.isSold && cell.dateStr !== selectedDate">
                                        <span class="text-emerald-400 font-bold leading-none mt-0.5" style="font-size:7px;">&#10003;</span>
                                    </template>
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                <!-- Today shortcut -->
                <div class="mt-5 flex items-center justify-center">
                    <button type="button" @click="pickDate('{{ $todayStr }}')" class="text-xs font-bold text-[#00a6eb] hover:underline flex items-center gap-1">
                        <i class="bi bi-calendar-check"></i> Pilih Hari Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
