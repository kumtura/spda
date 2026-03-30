@extends('front.layout.template')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Success Header -->
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-8 text-center text-white mb-6 shadow-lg">
                <div class="h-20 w-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-check-circle text-5xl"></i>
                </div>
                <h1 class="text-2xl font-black mb-2">Bukti Transfer Berhasil Dikirim</h1>
                <p class="text-sm text-white/90">Pembayaran Anda sedang dalam proses verifikasi</p>
            </div>

            <!-- Info Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-slate-100 p-4 text-center shadow-sm">
                    <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-database text-slate-600"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 mb-1">Data Tersimpan</p>
                    <p class="text-[10px] text-slate-500">Informasi tiket Anda tersimpan permanen di sistem</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-100 p-4 text-center shadow-sm">
                    <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-clock-history text-slate-600"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 mb-1">Proses Verifikasi</p>
                    <p class="text-[10px] text-slate-500">Tim kami akan memverifikasi dalam 1x24 jam</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-100 p-4 text-center shadow-sm">
                    <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <i class="bi bi-envelope text-slate-600"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 mb-1">Notifikasi Email</p>
                    <p class="text-[10px] text-slate-500">Anda akan menerima email konfirmasi</p>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Detail Tiket</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Kode Tiket</span>
                        <span class="font-bold text-slate-800">{{ $tiket->kode_tiket }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Objek Wisata</span>
                        <span class="font-bold text-slate-800">{{ $tiket->objekWisata->nama_objek }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Nama Pengunjung</span>
                        <span class="font-bold text-slate-800">{{ $tiket->nama_pengunjung }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Jumlah Tiket</span>
                        <span class="font-bold text-slate-800">{{ $tiket->jumlah_tiket }} Tiket</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Tanggal Kunjungan</span>
                        <span class="font-bold text-slate-800">{{ $tiket->tanggal_kunjungan->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm pt-3 border-t border-slate-200">
                        <span class="text-slate-600">Status</span>
                        <span class="text-xs font-bold uppercase bg-amber-100 text-amber-700 px-3 py-1 rounded-full border border-amber-200">
                            Menunggu Verifikasi
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-lg pt-3 border-t border-slate-200">
                        <span class="font-bold text-slate-800">Total</span>
                        <span class="font-black text-[#00a6eb]">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ url('wisata') }}" class="block w-full py-4 bg-[#00a6eb] text-white text-center text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <i class="bi bi-house mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
