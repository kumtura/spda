@extends('front.layout.template')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ url('wisata/detail/'.$objek->id_objek_wisata) }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-[#00a6eb] mb-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <h1 class="text-3xl font-black text-slate-800 mb-2">Beli Tiket</h1>
                <p class="text-slate-600">{{ $objek->nama_objek }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                        <form action="{{ url('wisata/beli/submit') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="nama_pengunjung" required
                                        class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                        placeholder="Nama lengkap Anda">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Email <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" required
                                        class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                        placeholder="email@example.com">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon <span class="text-rose-500">*</span></label>
                                    <input type="text" name="no_telp" required
                                        class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                                        placeholder="08xxxxxxxxxx">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Tiket <span class="text-rose-500">*</span></label>
                                    <input type="number" name="jumlah_tiket" id="jumlah_tiket" min="1" value="1" required
                                        class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                                    <input type="date" name="tanggal_kunjungan" min="{{ date('Y-m-d') }}" required
                                        class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                                </div>

                                <button type="submit" class="w-full py-4 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                                    <i class="bi bi-arrow-right-circle mr-2"></i>Lanjut ke Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 sticky top-24">
                        <h3 class="text-sm font-bold text-slate-800 mb-4">Ringkasan Pembelian</h3>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-600">Harga per tiket</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-600">Jumlah tiket</span>
                                <span id="summary-jumlah" class="font-bold text-slate-800">1</span>
                            </div>
                        </div>
                        
                        <div class="pt-3 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-800">Total</span>
                                <span id="summary-total" class="text-xl font-black text-[#00a6eb]">Rp {{ number_format($objek->harga_tiket, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const hargaTiket = {{ $objek->harga_tiket }};

document.getElementById('jumlah_tiket').addEventListener('input', function() {
    const jumlah = parseInt(this.value) || 1;
    const total = hargaTiket * jumlah;
    
    document.getElementById('summary-jumlah').textContent = jumlah;
    document.getElementById('summary-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
});
</script>
@endpush
@endsection
