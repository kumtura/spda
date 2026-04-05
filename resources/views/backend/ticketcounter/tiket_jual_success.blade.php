@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-12 pb-8 text-center">
        <div class="h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-check-circle text-5xl text-white"></i>
        </div>
        <h1 class="text-xl font-black text-white mb-2">Tiket Berhasil Dijual</h1>
        <p class="text-xs text-white/80 font-medium">Pembayaran telah dicatat dalam sistem</p>
    </div>

    <div class="px-5 -mt-4">
        <!-- Ticket Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Kode Tiket</span>
                    <span class="text-xs font-black text-slate-800">{{ $tiket->kode_tiket }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Objek Wisata</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->objekWisata->nama_objek }}</span>
                </div>
                
                <div class="pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest block mb-2">Detail Tiket</span>
                    <div class="space-y-1.5">
                        @foreach($tiket->details as $detail)
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800">{{ $detail->kategoriTiket->nama_kategori }}</span>
                                <span class="text-[9px] text-slate-400">× {{ $detail->jumlah }}</span>
                            </div>
                            <span class="font-bold text-slate-700">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Tanggal Kunjungan</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->tanggal_kunjungan->translatedFormat('d F Y') }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Metode Pembayaran</span>
                    <span class="text-xs font-bold text-slate-800">{{ strtoupper($tiket->metode_pembayaran) }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-slate-700 uppercase tracking-tight">TOTAL</span>
                    <span class="text-xl font-black text-[#00a6eb]">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="bg-blue-50 border-t border-blue-100 p-4">
                <div class="flex items-start gap-2">
                    <i class="bi bi-info-circle text-[#00a6eb] text-sm mt-0.5"></i>
                    <p class="text-[10px] text-slate-600 leading-relaxed font-semibold">
                        <strong>Berhasil!</strong> Tiket sudah tercatat dan pengunjung dapat langsung masuk. Simpan kode tiket untuk referensi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 space-y-3">
            <a href="{{ url('administrator/ticketcounter/tiket/jual') }}" class="block w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl text-center shadow-lg">
                <i class="bi bi-plus-circle mr-2"></i>Jual Tiket Lagi
            </a>
            <a href="{{ url('administrator/ticketcounter/tiket') }}" class="block w-full py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl text-center">
                Kembali ke Dashboard Tiket
            </a>
        </div>
    </div>
</div>
@endsection
