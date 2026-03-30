@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-8 pb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/kelian/tiket') }}" class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-arrow-left text-white"></i>
            </a>
            <div>
                <h1 class="text-lg font-black text-white">Jual Tiket Offline</h1>
                <p class="text-[10px] text-white/70">Penjualan tiket dengan pembayaran cash</p>
            </div>
        </div>
    </div>

    <div class="px-5 -mt-3">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
            <form action="{{ url('administrator/kelian/tiket/jual/submit') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Objek Wisata -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Objek Wisata</label>
                        <select name="id_objek_wisata" id="id_objek_wisata" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                            <option value="">Pilih Objek Wisata</option>
                            @foreach($objekWisata as $objek)
                            <option value="{{ $objek->id_objek_wisata }}" data-harga="{{ $objek->harga_tiket }}">
                                {{ $objek->nama_objek }} - Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nama Pengunjung -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Nama Pengunjung</label>
                        <input type="text" name="nama_pengunjung" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Nama lengkap pengunjung">
                    </div>

                    <!-- No Telp -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">No. Telepon</label>
                        <input type="text" name="no_telp" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <!-- Email (Optional) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Email <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="email" name="email"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="email@example.com">
                    </div>

                    <!-- Jumlah Tiket -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Jumlah Tiket</label>
                        <input type="number" name="jumlah_tiket" id="jumlah_tiket" min="1" value="1" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    </div>

                    <!-- Tanggal Kunjungan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Tanggal Kunjungan</label>
                        <input type="date" name="tanggal_kunjungan" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                    </div>

                    <!-- Total Harga -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700">Total Harga</span>
                            <span id="total-harga" class="text-lg font-black text-[#00a6eb]">Rp 0</span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="bi bi-cash-coin mr-2"></i>Proses Pembayaran Cash
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleManualInput() {
    document.getElementById('manual-input').classList.toggle('hidden');
}

function updateTotalHarga() {
    const select = document.getElementById('id_objek_wisata');
    const jumlah = parseInt(document.getElementById('jumlah_tiket').value) || 0;
    const selectedOption = select.options[select.selectedIndex];
    const harga = parseInt(selectedOption.getAttribute('data-harga')) || 0;
    const total = harga * jumlah;
    
    document.getElementById('total-harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

document.getElementById('id_objek_wisata').addEventListener('change', updateTotalHarga);
document.getElementById('jumlah_tiket').addEventListener('input', updateTotalHarga);
</script>
@endpush
@endsection
