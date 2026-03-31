@extends('mobile_layout_public')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ url('wisata/payment/methods') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-[#00a6eb] mb-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <h1 class="text-3xl font-black text-slate-800 mb-2">Transfer Manual</h1>
                <p class="text-slate-600">Upload bukti transfer Anda</p>
            </div>

            <!-- Amount Card -->
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white mb-6 shadow-lg">
                <p class="text-xs uppercase text-white/70 mb-2">Total Pembayaran</p>
                <h2 class="text-4xl font-black">Rp {{ number_format($amount, 0, ',', '.') }}</h2>
            </div>

            <!-- Bank Accounts -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6 mb-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Transfer ke Rekening Desa Adat</h3>
                <div class="space-y-3">
                    @foreach($bankAccounts as $bank)
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-800">{{ $bank['bank_name'] }}</span>
                            <span class="text-[8px] font-bold uppercase bg-blue-100 text-blue-700 px-2 py-0.5 rounded">{{ $bank['bank_code'] }}</span>
                        </div>
                        <p class="text-lg font-black text-slate-800 mb-1">{{ $bank['account_number'] }}</p>
                        <p class="text-xs text-slate-500">a.n. {{ $bank['account_holder'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Upload Form -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4">Upload Bukti Transfer</h3>
                
                <form action="{{ url('wisata/payment/manual/submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Transfer <span class="text-rose-500">*</span></label>
                        <input type="file" name="bukti_transfer" accept="image/*,.pdf" required
                            class="w-full px-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        <p class="text-xs text-slate-500 mt-2">Format: JPG, PNG, PDF. Maksimal 2MB</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="bi bi-info-circle text-blue-600 text-lg mt-0.5"></i>
                            <div class="text-xs text-slate-600 leading-relaxed">
                                <p class="font-bold text-slate-800 mb-1">Catatan Penting:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Pastikan bukti transfer jelas dan terbaca</li>
                                    <li>Nominal transfer harus sesuai dengan total pembayaran</li>
                                    <li>Tiket akan diverifikasi dalam 1x24 jam</li>
                                    <li>Anda akan menerima email konfirmasi setelah verifikasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="bi bi-upload mr-2"></i>Upload Bukti Transfer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
