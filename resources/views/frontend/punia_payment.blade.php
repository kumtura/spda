<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>Pembayaran Punia - SPDA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background-color: #f3f4f6; padding: 0; margin: 0; font-family: 'Inter', sans-serif;}
        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="antialiased font-sans bg-gray-100">

@php
    $t = [
        'title' => ['id' => 'Pembayaran Punia', 'en' => 'Punia Payment'],
        'subtitle' => ['id' => 'Upload bukti pembayaran punia wajib', 'en' => 'Upload mandatory punia payment proof'],
        'unit_usaha' => ['id' => 'Unit Usaha', 'en' => 'Business Unit'],
        'period' => ['id' => 'Periode', 'en' => 'Period'],
        'min_amount' => ['id' => 'Minimal Pembayaran', 'en' => 'Minimum Payment'],
        'amount' => ['id' => 'Nominal Pembayaran (Rp)', 'en' => 'Payment Amount (Rp)'],
        'pay_date' => ['id' => 'Tanggal Transaksi Pembayaran', 'en' => 'Payment Transaction Date'],
        'proof' => ['id' => 'Bukti Pembayaran', 'en' => 'Payment Proof'],
        'proof_hint' => ['id' => 'Upload foto/screenshot bukti transfer', 'en' => 'Upload photo/screenshot of transfer proof'],
        'submit' => ['id' => 'Kirim Pembayaran', 'en' => 'Submit Payment'],
        'submitting' => ['id' => 'Mengirim...', 'en' => 'Submitting...'],
        'already_paid_title' => ['id' => 'Sudah Dibayar', 'en' => 'Already Paid'],
        'already_paid_msg' => ['id' => 'Pembayaran untuk periode ini sudah tercatat dalam sistem.', 'en' => 'Payment for this period is already recorded in the system.'],
        'success_title' => ['id' => 'Berhasil!', 'en' => 'Success!'],
        'success_msg' => ['id' => 'Bukti pembayaran berhasil dikirim. Kelian akan memverifikasi pembayaran Anda.', 'en' => 'Payment proof submitted successfully. The Kelian will verify your payment.'],
        'error_title' => ['id' => 'Gagal', 'en' => 'Failed'],
        'max_file' => ['id' => 'Maks 5MB, format JPG/PNG', 'en' => 'Max 5MB, JPG/PNG format'],
    ];
@endphp

<div class="mobile-container" x-data="{ lang: 'id', processing: false, previewUrl: null }">

    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0080c0] px-5 pt-8 pb-10 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full -ml-10 -mb-10"></div>

        <!-- Language Toggle -->
        <div class="flex justify-end mb-4">
            <div class="bg-white/20 rounded-full p-0.5 flex">
                <button @click="lang = 'id'" :class="lang === 'id' ? 'bg-white text-[#00a6eb] shadow' : 'text-white'"
                        class="px-3 py-1 rounded-full text-[10px] font-bold transition-all">ID</button>
                <button @click="lang = 'en'" :class="lang === 'en' ? 'bg-white text-[#00a6eb] shadow' : 'text-white'"
                        class="px-3 py-1 rounded-full text-[10px] font-bold transition-all">EN</button>
            </div>
        </div>

        <div class="relative z-10 text-center">
            <div class="h-14 w-14 mx-auto bg-white/20 rounded-2xl flex items-center justify-center mb-3">
                <i class="bi bi-wallet2 text-white text-2xl"></i>
            </div>
            <h1 class="text-white font-black text-lg" x-text="lang === 'id' ? '{{ $t['title']['id'] }}' : '{{ $t['title']['en'] }}'"></h1>
            <p class="text-white/70 text-xs mt-1" x-text="lang === 'id' ? '{{ $t['subtitle']['id'] }}' : '{{ $t['subtitle']['en'] }}'"></p>
        </div>
    </div>

    <div class="px-5 -mt-5 relative z-10 pb-10">

        @if(session('success'))
        <!-- Success Message -->
        <div class="bg-white rounded-2xl shadow-lg border border-emerald-100 p-6 text-center">
            <div class="h-16 w-16 mx-auto bg-emerald-50 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle-fill text-emerald-500 text-3xl"></i>
            </div>
            <h2 class="font-black text-lg text-slate-800 mb-2" x-text="lang === 'id' ? '{{ $t['success_title']['id'] }}' : '{{ $t['success_title']['en'] }}'"></h2>
            <p class="text-xs text-slate-500 leading-relaxed" x-text="lang === 'id' ? '{{ $t['success_msg']['id'] }}' : '{{ $t['success_msg']['en'] }}'"></p>
        </div>
        @elseif($existing)
        <!-- Already Paid -->
        <div class="bg-white rounded-2xl shadow-lg border border-amber-100 p-6 text-center">
            <div class="h-16 w-16 mx-auto bg-amber-50 rounded-full flex items-center justify-center mb-4">
                <i class="bi bi-check-circle text-amber-500 text-3xl"></i>
            </div>
            <h2 class="font-black text-lg text-slate-800 mb-2" x-text="lang === 'id' ? '{{ $t['already_paid_title']['id'] }}' : '{{ $t['already_paid_title']['en'] }}'"></h2>
            <p class="text-xs text-slate-500 leading-relaxed" x-text="lang === 'id' ? '{{ $t['already_paid_msg']['id'] }}' : '{{ $t['already_paid_msg']['en'] }}'"></p>
        </div>
        @else
        <!-- Payment Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">

            @if(session('error'))
            <div class="bg-rose-50 border-b border-rose-100 px-4 py-3">
                <p class="text-xs text-rose-600 font-bold flex items-center gap-2">
                    <i class="bi bi-exclamation-circle"></i>
                    <span x-text="lang === 'id' ? '{{ $t['error_title']['id'] }}' : '{{ $t['error_title']['en'] }}'"></span>: {{ session('error') }}
                </p>
            </div>
            @endif

            @if($errors->any())
            <div class="bg-rose-50 border-b border-rose-100 px-4 py-3">
                @foreach($errors->all() as $error)
                <p class="text-xs text-rose-600">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <!-- Info Card -->
            <div class="bg-slate-50 px-4 py-4 border-b border-slate-100">
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase" x-text="lang === 'id' ? '{{ $t['unit_usaha']['id'] }}' : '{{ $t['unit_usaha']['en'] }}'"></span>
                        <span class="text-xs font-black text-slate-700">{{ $usaha->nama_usaha }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase" x-text="lang === 'id' ? '{{ $t['period']['id'] }}' : '{{ $t['period']['en'] }}'"></span>
                        <span class="text-xs font-bold text-slate-700" x-text="lang === 'id' ? '{{ $months_id[$bulan] }} {{ $tahun }}' : '{{ $months_en[$bulan] }} {{ $tahun }}'"></span>
                    </div>
                    @if($usaha->minimal_bayar)
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase" x-text="lang === 'id' ? '{{ $t['min_amount']['id'] }}' : '{{ $t['min_amount']['en'] }}'"></span>
                        <span class="text-xs font-bold text-emerald-600">Rp {{ number_format($usaha->minimal_bayar, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('public.punia.bayar.submit') }}" method="POST" enctype="multipart/form-data"
                  @submit="processing = true" class="p-4 space-y-4">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $usaha->id_usaha }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <!-- Amount -->
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase mb-1.5 block"
                           x-text="lang === 'id' ? '{{ $t['amount']['id'] }}' : '{{ $t['amount']['en'] }}'"></label>
                    <input type="number" name="jumlah_dana" required min="1000"
                           value="{{ old('jumlah_dana', $usaha->minimal_bayar ?? '') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                </div>

                <!-- Payment Date -->
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase mb-1.5 block"
                           x-text="lang === 'id' ? '{{ $t['pay_date']['id'] }}' : '{{ $t['pay_date']['en'] }}'"></label>
                    <input type="date" name="tanggal_pembayaran" required
                           value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none transition-all">
                </div>

                <!-- Payment Proof -->
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase mb-1.5 block"
                           x-text="lang === 'id' ? '{{ $t['proof']['id'] }}' : '{{ $t['proof']['en'] }}'"></label>

                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center hover:border-blue-300 transition-all cursor-pointer relative"
                         @click="$refs.fileInput.click()">
                        <template x-if="!previewUrl">
                            <div>
                                <i class="bi bi-cloud-arrow-up text-3xl text-slate-300 mb-2"></i>
                                <p class="text-xs text-slate-400 font-bold" x-text="lang === 'id' ? '{{ $t['proof_hint']['id'] }}' : '{{ $t['proof_hint']['en'] }}'"></p>
                                <p class="text-[10px] text-slate-300 mt-1" x-text="lang === 'id' ? '{{ $t['max_file']['id'] }}' : '{{ $t['max_file']['en'] }}'"></p>
                            </div>
                        </template>
                        <template x-if="previewUrl">
                            <div>
                                <img :src="previewUrl" class="max-h-40 mx-auto rounded-lg shadow-sm mb-2" alt="Preview">
                                <p class="text-[10px] text-blue-500 font-bold">Tap to change</p>
                            </div>
                        </template>
                    </div>
                    <input type="file" name="bukti_pembayaran" accept="image/*" required x-ref="fileInput" class="hidden"
                           @change="if($event.target.files[0]) { previewUrl = URL.createObjectURL($event.target.files[0]) }">
                </div>

                <!-- Submit -->
                <button type="submit" :disabled="processing"
                        class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white font-bold text-sm py-3.5 rounded-xl hover:shadow-lg hover:shadow-blue-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <template x-if="!processing">
                        <span x-text="lang === 'id' ? '{{ $t['submit']['id'] }}' : '{{ $t['submit']['en'] }}'"></span>
                    </template>
                    <template x-if="processing">
                        <span><i class="bi bi-arrow-repeat animate-spin mr-1"></i> <span x-text="lang === 'id' ? '{{ $t['submitting']['id'] }}' : '{{ $t['submitting']['en'] }}'"></span></span>
                    </template>
                </button>
            </form>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-[10px] text-slate-400">SPDA - Sistem Punia Desa Adat</p>
        </div>
    </div>
</div>

</body>
</html>
