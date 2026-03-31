@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/pendatang/detail/'.$punia->id_pendatang) }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <h1 class="text-lg font-black">Konfirmasi Pembayaran</h1>
            <p class="text-white/80 text-[10px] mt-1">Tandai tagihan sebagai lunas</p>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4">
        <!-- Detail Tagihan -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Detail Tagihan</h4>
            
            <div class="space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-slate-600">Pendatang:</span>
                    <span class="font-bold text-slate-800">{{ $punia->pendatang->nama }}</span>
                </div>
                
                <div class="flex justify-between text-xs">
                    <span class="text-slate-600">Jenis:</span>
                    <span class="font-bold text-slate-800">
                        @if($punia->jenis_punia === 'rutin')
                            Punia {{ ucfirst($punia->periode_rutin) }}
                        @else
                            {{ $punia->nama_acara }}
                        @endif
                    </span>
                </div>
                
                @if($punia->jenis_punia === 'rutin')
                <div class="flex justify-between text-xs">
                    <span class="text-slate-600">Periode:</span>
                    <span class="font-bold text-slate-800">{{ $punia->bulan_tahun }}</span>
                </div>
                @endif
                
                <div class="pt-2 border-t border-slate-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-700">Total:</span>
                        <span class="text-xl font-black text-emerald-600">Rp {{ number_format($punia->nominal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Pembayaran -->
        <form method="POST" action="{{ url('administrator/kelian/pendatang/punia/bayar/'.$punia->id_punia_pendatang) }}" class="space-y-4">
            @csrf
            
            <div class="bg-white rounded-xl border border-slate-200 p-5">
                <label class="block text-xs font-bold text-slate-700 mb-3">Metode Pembayaran <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative">
                        <input type="radio" name="metode_pembayaran" value="cash" checked class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-xl p-4 cursor-pointer transition-all">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-cash-coin text-2xl text-emerald-600"></i>
                                <span class="text-sm font-bold text-slate-700">Cash</span>
                            </div>
                        </div>
                    </label>
                    <label class="relative">
                        <input type="radio" name="metode_pembayaran" value="qris" class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-xl p-4 cursor-pointer transition-all">
                            <div class="flex flex-col items-center gap-2">
                                <i class="bi bi-qr-code text-2xl text-emerald-600"></i>
                                <span class="text-sm font-bold text-slate-700">QRIS</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-emerald-500 text-white py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-600 transition-all">
                <i class="bi bi-check-circle mr-2"></i>Konfirmasi Lunas
            </button>
        </form>
    </div>
</div>
@endsection
