@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/pendatang/detail/'.$punia->id_pendatang) }}" class="text-sm text-primary-light hover:underline font-medium mb-1 inline-block">
            <i class="bi bi-arrow-left mr-1"></i> Kembali ke Detail
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">Konfirmasi Pembayaran</h1>
        <p class="text-slate-500 font-medium text-sm">Tandai tagihan sebagai lunas.</p>
    </div>

    <div class="max-w-lg space-y-6">
        {{-- Detail Tagihan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Detail Tagihan</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Pendatang</span>
                    <span class="font-bold text-slate-800">{{ $punia->pendatang->nama }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Jenis</span>
                    <span class="font-bold text-slate-800">
                        @if($punia->jenis_punia === 'rutin')
                            Punia {{ ucfirst($punia->periode_rutin) }}
                        @else
                            {{ $punia->nama_acara }}
                        @endif
                    </span>
                </div>
                @if($punia->jenis_punia === 'rutin')
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Periode</span>
                    <span class="font-bold text-slate-800">{{ $punia->bulan_tahun }}</span>
                </div>
                @endif
                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-700">Total</span>
                    <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($punia->nominal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <form method="POST" action="{{ url('administrator/pendatang/punia/bayar/'.$punia->id_punia_pendatang) }}" class="space-y-5">
            @csrf
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <label class="block text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="cash" checked class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-xl p-5 transition-all text-center">
                            <i class="bi bi-cash-coin text-3xl text-emerald-600 mb-2 block"></i>
                            <span class="text-sm font-bold text-slate-700">Cash</span>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" name="metode_pembayaran" value="qris" class="peer sr-only">
                        <div class="border-2 border-slate-200 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 rounded-xl p-5 transition-all text-center">
                            <i class="bi bi-qr-code text-3xl text-emerald-600 mb-2 block"></i>
                            <span class="text-sm font-bold text-slate-700">QRIS</span>
                        </div>
                    </label>
                </div>
            </div>
            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-emerald-100 transition-all transform hover:-translate-y-0.5">
                <i class="bi bi-check-circle-fill mr-2"></i> Konfirmasi Lunas
            </button>
        </form>
    </div>
</div>
@endsection
