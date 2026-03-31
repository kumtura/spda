@extends('mobile_layout_public')

@section('content')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <a href="{{ url('wisata/detail/'.$objek->id_objek_wisata) }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-black mb-2">Beli Tiket</h1>
            <p class="text-white/80 text-xs font-medium">{{ $objek->nama_objek }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="px-4 -mt-6 relative z-10">
        <form action="{{ url('wisata/beli/submit') }}" method="POST" id="formBeli" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-6">
            @csrf
            <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
            
            <!-- Tanggal Kunjungan -->
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Tanggal Kunjungan</h3>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">
                        Pilih Tanggal <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="tanggal_kunjungan" min="{{ date('Y-m-d') }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                </div>
            </div>

            <!-- Pilih Tiket -->
            @if($objek->kategoriTiket->count() > 0)
            <div class="space-y-4">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Tiket</h3>
                
                <div class="space-y-3">
                    @foreach($objek->kategoriTiket as $kategori)
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
                            <button type="button" class="btn-minus h-9 w-9 bg-white border border-slate-200 rounded-lg flex items-center justify-center" data-kategori="{{ $kategori->id_kategori_tiket }}">
                                <i class="bi bi-dash text-slate-700"></i>
                            </button>
                            <input type="number" name="kategori[{{ $kategori->id_kategori_tiket }}]" 
                                class="qty-input w-16 text-center text-sm font-bold bg-white border border-slate-200 rounded-lg py-2" 
                                value="0" min="0" readonly
                                data-kategori="{{ $kategori->id_kategori_tiket }}"
                                data-harga="{{ $kategori->harga }}">
                            <button type="button" class="btn-plus h-9 w-9 bg-[#00a6eb] rounded-lg flex items-center justify-center" data-kategori="{{ $kategori->id_kategori_tiket }}">
                                <i class="bi bi-plus text-white"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
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
                
                <div id="summary-items" class="space-y-2 mb-3 text-xs">
                    <p class="text-slate-400 text-center py-2 text-[10px]">Pilih tiket terlebih dahulu</p>
                </div>
                
                <div class="pt-3 border-t border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-black text-slate-800">Total</span>
                        <span id="summary-total" class="text-xl font-black text-[#00a6eb]">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Setelah ini, Anda akan memilih metode pembayaran untuk menyelesaikan pembelian tiket.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-xl font-black text-sm shadow-lg shadow-blue-200 hover:shadow-xl hover:shadow-blue-300 transition-all active:scale-[0.98]">
                Lanjutkan Pembayaran
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInputs = document.querySelectorAll('.qty-input');
    const summaryItems = document.getElementById('summary-items');
    const summaryTotal = document.getElementById('summary-total');
    const formBeli = document.getElementById('formBeli');

    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const kategoriId = this.dataset.kategori;
            const input = document.querySelector(`.qty-input[data-kategori="${kategoriId}"]`);
            input.value = parseInt(input.value) + 1;
            updateSummary();
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const kategoriId = this.dataset.kategori;
            const input = document.querySelector(`.qty-input[data-kategori="${kategoriId}"]`);
            if (parseInt(input.value) > 0) {
                input.value = parseInt(input.value) - 1;
                updateSummary();
            }
        });
    });

    function updateSummary() {
        let total = 0;
        let items = [];
        
        qtyInputs.forEach(input => {
            const qty = parseInt(input.value);
            if (qty > 0) {
                const harga = parseFloat(input.dataset.harga);
                const subtotal = qty * harga;
                total += subtotal;
                
                const kategoriName = input.closest('.bg-slate-50').querySelector('.text-sm.font-bold').textContent;
                items.push({
                    name: kategoriName,
                    qty: qty,
                    subtotal: subtotal
                });
            }
        });

        if (items.length > 0) {
            let html = '';
            items.forEach(item => {
                html += `
                    <div class="flex items-center justify-between">
                        <span class="text-slate-600">${item.name} x${item.qty}</span>
                        <span class="font-bold text-slate-800">Rp ${item.subtotal.toLocaleString('id-ID')}</span>
                    </div>
                `;
            });
            summaryItems.innerHTML = html;
        } else {
            summaryItems.innerHTML = '<p class="text-slate-400 text-center py-2 text-[10px]">Pilih tiket terlebih dahulu</p>';
        }

        summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    formBeli.addEventListener('submit', function(e) {
        let hasTicket = false;
        qtyInputs.forEach(input => {
            if (parseInt(input.value) > 0) {
                hasTicket = true;
            }
        });

        if (!hasTicket) {
            e.preventDefault();
            alert('Pilih minimal 1 tiket');
        }
    });
});
</script>
@endsection
