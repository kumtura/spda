@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Tiket Wisata</h1>
        <p class="text-slate-400 text-[10px] mt-1">Kelola penjualan tiket objek wisata</p>
    </div>

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-ticket-perforated text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">Hari Ini</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Penjualan {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
            
            <div class="flex items-center justify-between text-xs pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Tiket Terjual</p>
                    <p class="font-bold">{{ $totalTiketTerjual }} Tiket</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Transaksi</p>
                    <p class="font-bold">{{ $tiketHariIni->count() }} Order</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ url('administrator/ticketcounter/tiket/scan') }}" class="bg-white border border-blue-100 p-4 rounded-xl shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 text-[#00a6eb] rounded-lg flex items-center justify-center transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-qr-code-scan text-lg"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-slate-800">Scan Tiket</p>
                    <p class="text-[10px] text-slate-400">Validasi QR</p>
                </div>
            </div>
        </a>
        <a href="{{ url('administrator/ticketcounter/tiket/jual') }}" class="bg-white border border-blue-100 p-4 rounded-xl shadow-sm hover:shadow-md transition-all group">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 text-[#00a6eb] rounded-lg flex items-center justify-center transition-colors group-hover:bg-[#00a6eb] group-hover:text-white">
                    <i class="bi bi-cash-coin text-lg"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-slate-800">Jual Offline</p>
                    <p class="text-[10px] text-slate-400">Bayar Cash</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Objek Wisata List -->
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Objek Wisata</h3>
        
        @if($objekWisata->count() > 0)
        <div class="space-y-3">
            @foreach($objekWisata as $objek)
            <div class="bg-white border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <div class="flex items-start gap-4 p-4">
                    <div class="h-16 w-16 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                        @if($objek->foto)
                            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="h-full w-full object-cover" alt="{{ $objek->nama_objek }}">
                        @else
                            <i class="bi bi-image text-slate-300 text-2xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-black text-slate-800 mb-1">{{ $objek->nama_objek }}</h3>
                        <p class="text-[10px] text-slate-500 mb-2 line-clamp-1">{{ $objek->alamat }}</p>
                        <div class="flex items-center gap-2 mb-2">
                            @php
                                $tiketObjek = $tiketHariIni->where('id_objek_wisata', $objek->id_objek_wisata);
                                $pemasukanObjek = $tiketObjek->sum('total_harga');
                                $tiketTerjualObjek = $tiketObjek->sum(fn($t) => $t->details->sum('jumlah'));
                            @endphp
                            <span class="text-[9px] font-bold text-[#00a6eb] bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                Rp {{ number_format($pemasukanObjek, 0, ',', '.') }}
                            </span>
                            <span class="text-[9px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">
                                {{ $tiketTerjualObjek }} terjual hari ini
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-ticket-perforated text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada objek wisata yang ditugaskan</p>
        </div>
        @endif
    </div>
</div>
@endsection
