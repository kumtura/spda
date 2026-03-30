@extends('front.layout.template')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 mb-2">Pilih Metode Pembayaran</h1>
                <p class="text-slate-600">{{ $objek->nama_objek }}</p>
            </div>

            <!-- Summary Card -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Detail Pembelian</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Nama</span>
                        <span class="font-bold text-slate-800">{{ $tiketData['nama_pengunjung'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Jumlah Tiket</span>
                        <span class="font-bold text-slate-800">{{ $tiketData['jumlah_tiket'] }} Tiket</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-600">Tanggal Kunjungan</span>
                        <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($tiketData['tanggal_kunjungan'])->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-lg pt-3 border-t border-slate-200">
                        <span class="font-bold text-slate-800">Total</span>
                        <span class="font-black text-[#00a6eb]">Rp {{ number_format($tiketData['total_harga'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="space-y-3">
                @php
                    $paymentChannels = App\Models\PaymentChannel::where('aktif', '1')->get();
                @endphp

                @foreach($paymentChannels as $channel)
                <a href="{{ url('wisata/payment/xendit?channel='.$channel->channel_code.'&amount='.$tiketData['total_harga']) }}" 
                    class="block bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-lg hover:border-[#00a6eb] transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            @if($channel->logo)
                            <img src="{{ asset('storage/payment/'.$channel->logo) }}" alt="{{ $channel->nama_channel }}" class="h-8">
                            @else
                            <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-credit-card text-slate-400"></i>
                            </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $channel->nama_channel }}</p>
                                <p class="text-xs text-slate-500">{{ $channel->deskripsi }}</p>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right text-slate-400 group-hover:text-[#00a6eb] transition-colors"></i>
                    </div>
                </a>
                @endforeach

                <!-- Transfer Manual -->
                <a href="{{ url('wisata/payment/manual?amount='.$tiketData['total_harga']) }}" 
                    class="block bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:shadow-lg hover:border-[#00a6eb] transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 bg-slate-100 rounded-lg flex items-center justify-center">
                                <i class="bi bi-bank text-slate-600"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-bold text-slate-800">Transfer Manual</p>
                                    <span class="text-[8px] font-bold uppercase bg-amber-100 text-amber-700 px-2 py-0.5 rounded border border-amber-200">Verifikasi Manual</span>
                                </div>
                                <p class="text-xs text-slate-500">Transfer ke rekening desa adat</p>
                            </div>
                        </div>
                        <i class="bi bi-arrow-right text-slate-400 group-hover:text-[#00a6eb] transition-colors"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
