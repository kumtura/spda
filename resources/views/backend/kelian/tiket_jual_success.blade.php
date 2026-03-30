@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 px-5 pt-12 pb-8 text-center">
        <div class="h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="bi bi-check-circle text-5xl text-white"></i>
        </div>
        <h1 class="text-xl font-black text-white mb-2">Tiket Berhasil Dijual</h1>
        <p class="text-xs text-white/80">Pembayaran cash telah dicatat dalam sistem</p>
    </div>

    <div class="px-5 -mt-4">
        <!-- Ticket Info Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="p-5 space-y-3">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase">Kode Tiket</span>
                    <span class="text-xs font-black text-slate-800">{{ $tiket->kode_tiket }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase">Objek Wisata</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->objekWisata->nama_objek }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase">Nama Pengunjung</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->nama_pengunjung }}</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase">Jumlah Tiket</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->jumlah_tiket }} Tiket</span>
                </div>
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <span class="text-[9px] text-slate-400 uppercase">Tanggal Kunjungan</span>
                    <span class="text-xs font-bold text-slate-800">{{ $tiket->tanggal_kunjungan->translatedFormat('d F Y') }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-[9px] text-slate-400 uppercase">Total Harga</span>
                    <span class="text-lg font-black text-[#00a6eb]">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <div class="bg-slate-50 border-t border-slate-100 p-4">
                <div class="flex items-start gap-2">
                    <i class="bi bi-info-circle text-slate-400 text-sm mt-0.5"></i>
                    <p class="text-[10px] text-slate-500 leading-relaxed">
                        Tiket ini dapat digunakan pada tanggal kunjungan yang dipilih. Pengunjung dapat menunjukkan kode tiket untuk validasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 space-y-3">
            <a href="{{ url('administrator/kelian/tiket/jual') }}" class="block w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl text-center shadow-lg">
                <i class="bi bi-plus-circle mr-2"></i>Jual Tiket Lagi
            </a>
            <a href="{{ url('administrator/kelian/tiket') }}" class="block w-full py-3 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl text-center">
                Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
