@extends('mobile_layout_public')

@section('content')
@php
    $orangWna = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'wna');
    $orangLocal = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'local');
    $orangAll = $objek->kategoriTiket->where('tipe_kategori', 'orang')->where('market_type', 'all');
    $kendaraanKategori = $objek->kategoriTiket->where('tipe_kategori', 'kendaraan');
    $hasMultipleMarkets = ($orangWna->count() > 0 && $orangLocal->count() > 0);
    $defaultMarket = $orangLocal->count() > 0 ? 'local' : ($orangWna->count() > 0 ? 'wna' : 'all');
    $todayStr = date('Y-m-d');
@endphp

<div class="bg-white pb-24" x-data="{
    selectedDate: '',
    quantities: {},
    summaryItems: {},
    totalPrice: 0,
    marketFilter: '{{ $defaultMarket }}',
    availabilityInfo: null,
    checkingAvailability: false,

    onDateChange: function(e) {
        var self = this;
        self.selectedDate = e.target.value;
        self.availabilityInfo = null;

        if (!self.selectedDate) return;

        @if($objek->batas_tiket_harian)
        self.checkingAvailability = true;
        var dt = new Date(self.selectedDate + 'T00:00:00');
        var url = '{{ url("wisata/check-availability") }}?id_objek_wisata={{ (int) $objek->id_objek_wisata }}&month=' + (dt.getMonth() + 1) + '&year=' + dt.getFullYear();
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.unlimited) {
                    self.availabilityInfo = { unlimited: true };
                } else if (data.dates && data.dates[self.selectedDate]) {
                    self.availabilityInfo = { unlimited: false, available: data.dates[self.selectedDate].available };
                } else {
                    self.availabilityInfo = { unlimited: true };
                }
            })
            .catch(function() { self.availabilityInfo = null; })
            .finally(function() { self.checkingAvailability = false; });
        @else
        self.availabilityInfo = { unlimited: true };
        @endif
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
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10 space-y-5">
        <form action="{{ url('wisata/beli/submit') }}" method="POST" id="formBeli" @submit.prevent="submitForm" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
            
            <!-- Tanggal Kunjungan -->
            <div class="space-y-3">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tanggal Kunjungan</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Pilih Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kunjungan" 
                        min="{{ $todayStr }}" 
                        x-model="selectedDate"
                        @change="onDateChange($event)"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-4 focus:ring-[#00a6eb]/10 focus:border-[#00a6eb]/50 outline-none transition-all appearance-none">
                    
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
            <div class="space-y-4">
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
        </form>

        <!-- Submit Button -->
        <button type="button" @click="submitForm()"
                class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-2xl font-black text-sm shadow-lg shadow-blue-200/50 hover:shadow-xl hover:shadow-blue-300/50 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
            <span>Lanjutkan Pembayaran</span>
            <i class="bi bi-arrow-right"></i>
        </button>
    </div>
</div>
@endsection
