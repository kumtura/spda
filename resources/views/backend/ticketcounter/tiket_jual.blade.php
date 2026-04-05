@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header with gradient and back button -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/ticketcounter/tiket') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Jual Tiket Offline</h1>
                <p class="text-[10px] text-white/80">Penjualan tiket dengan pembayaran cash/QRIS</p>
            </div>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">
        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 flex items-start gap-2">
            <i class="bi bi-exclamation-circle-fill text-rose-500 mt-0.5"></i>
            <p class="text-xs text-rose-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
            <form action="{{ url('administrator/ticketcounter/tiket/jual/submit') }}" method="POST" id="jual-form">
                @csrf
                
                <div class="space-y-4">
                    <!-- Objek Wisata -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Objek Wisata <span class="text-rose-500">*</span></label>
                        <select name="id_objek_wisata" id="id_objek_wisata" required onchange="loadKategori()"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                            <option value="">Pilih Objek Wisata</option>
                            @foreach($objekWisata as $objek)
                            <option value="{{ $objek->id_objek_wisata }}">
                                {{ $objek->nama_objek }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kategori Tiket Container -->
                    <div id="kategori-container" class="hidden space-y-3">
                        <div class="border-t border-slate-200 pt-3">
                            <label class="block text-xs font-bold text-slate-700 mb-3">Pilih Tiket <span class="text-rose-500">*</span></label>
                            <div id="kategori-list" class="space-y-2"></div>
                        </div>
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                        <input type="date" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Metode Pembayaran <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="relative">
                                <input type="radio" name="metode_pembayaran" value="cash" checked class="peer sr-only">
                                <div class="border-2 border-slate-200 peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 rounded-xl p-3 cursor-pointer transition-all">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-cash-coin text-lg text-[#00a6eb]"></i>
                                        <span class="text-xs font-bold text-slate-700">Cash</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative">
                                <input type="radio" name="metode_pembayaran" value="qris" class="peer sr-only">
                                <div class="border-2 border-slate-200 peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 rounded-xl p-3 cursor-pointer transition-all">
                                    <div class="flex items-center gap-2">
                                        <i class="bi bi-qr-code text-lg text-[#00a6eb]"></i>
                                        <span class="text-xs font-bold text-slate-700">QRIS</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Total Harga -->
                    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-xl p-4 text-white">
                        <div class="space-y-2">
                            <div id="breakdown-list" class="space-y-1 text-xs"></div>
                            <div class="border-t border-white/20 pt-2 flex items-center justify-between">
                                <span class="text-sm font-bold">TOTAL</span>
                                <span id="total-harga" class="text-2xl font-black">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-btn" disabled class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="bi bi-cash-coin mr-2"></i>Proses Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
@php
    $objekWisataJson = $objekWisata->map(function($objek) {
        return [
            'id' => $objek->id_objek_wisata,
            'nama' => $objek->nama_objek,
            'kategori' => $objek->kategoriTiket->map(function($kat) {
                return [
                    'id' => $kat->id_kategori_tiket,
                    'nama' => $kat->nama_kategori,
                    'tipe' => $kat->tipe_kategori,
                    'harga' => $kat->harga,
                    'deskripsi' => $kat->deskripsi
                ];
            })->toArray()
        ];
    })->toArray();
@endphp

<script>
const objekWisataData = @json($objekWisataJson);
let selectedKategori = {};

function loadKategori() {
    const objekId = document.getElementById('id_objek_wisata').value;
    const container = document.getElementById('kategori-container');
    const list = document.getElementById('kategori-list');
    
    if (!objekId) {
        container.classList.add('hidden');
        return;
    }
    
    const objek = objekWisataData.find(o => o.id == objekId);
    if (!objek || objek.kategori.length === 0) {
        container.classList.add('hidden');
        alert('Objek wisata ini belum memiliki kategori tiket.');
        document.getElementById('id_objek_wisata').value = '';
        return;
    }
    
    selectedKategori = {};
    list.innerHTML = '';
    
    objek.kategori.forEach(kat => {
        const div = document.createElement('div');
        div.className = 'bg-slate-50 border border-slate-200 rounded-lg p-3';
        div.innerHTML = `
            <div class="flex items-center justify-between gap-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="text-xs font-bold text-slate-800">${kat.nama}</h4>
                        <span class="text-[8px] font-bold text-[#00a6eb] bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">${kat.tipe}</span>
                    </div>
                    <p class="text-[10px] font-bold text-[#00a6eb]">Rp ${parseInt(kat.harga).toLocaleString('id-ID')}</p>
                    ${kat.deskripsi ? `<p class="text-[9px] text-slate-500 mt-1">${kat.deskripsi}</p>` : ''}
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="updateQty(${kat.id}, -1)" class="h-8 w-8 bg-white border border-slate-200 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors">
                        <i class="bi bi-dash text-slate-600"></i>
                    </button>
                    <span id="qty-${kat.id}" class="text-sm font-bold text-slate-800 w-8 text-center">0</span>
                    <button type="button" onclick="updateQty(${kat.id}, 1)" class="h-8 w-8 bg-[#00a6eb] text-white rounded-lg flex items-center justify-center hover:bg-[#0090d0] transition-colors">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            </div>
        `;
        list.appendChild(div);
    });
    
    container.classList.remove('hidden');
    updateTotal();
}

function updateQty(kategoriId, change) {
    const objekId = document.getElementById('id_objek_wisata').value;
    const objek = objekWisataData.find(o => o.id == objekId);
    const kategori = objek.kategori.find(k => k.id == kategoriId);
    
    if (!selectedKategori[kategoriId]) {
        selectedKategori[kategoriId] = { ...kategori, qty: 0 };
    }
    
    selectedKategori[kategoriId].qty = Math.max(0, selectedKategori[kategoriId].qty + change);
    document.getElementById(`qty-${kategoriId}`).textContent = selectedKategori[kategoriId].qty;
    updateTotal();
}

function updateTotal() {
    let total = 0;
    const breakdownList = document.getElementById('breakdown-list');
    breakdownList.innerHTML = '';
    
    Object.values(selectedKategori).forEach(kat => {
        if (kat.qty > 0) {
            const subtotal = kat.harga * kat.qty;
            total += subtotal;
            const div = document.createElement('div');
            div.className = 'flex justify-between text-white/90';
            div.innerHTML = `<span>${kat.nama} × ${kat.qty}</span><span>Rp ${subtotal.toLocaleString('id-ID')}</span>`;
            breakdownList.appendChild(div);
        }
    });
    
    document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('submit-btn').disabled = total === 0;
}

document.getElementById('jual-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const total = Object.values(selectedKategori).reduce((sum, kat) => sum + (kat.qty * kat.harga), 0);
    if (total === 0) { alert('Pilih minimal 1 tiket'); return; }
    
    Object.values(selectedKategori).forEach(kat => {
        if (kat.qty > 0) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `kategori[${kat.id}]`;
            input.value = kat.qty;
            this.appendChild(input);
        }
    });
    this.submit();
});
</script>
@endpush
@endsection
