@extends('mobile_layout')

@section('isi_menu')
<div class="px-6 py-4 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-800 tracking-tight">Iuran Bulanan</h1>
        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Riwayat pembayaran punia wajib</p>
    </div>

    <!-- Current Month Status -->
    @php
        $currentMonth = date('m');
        $currentYear = date('Y');
        $myUsaha = App\Models\Usaha::join('tb_detail_usaha','tb_detail_usaha.id_detail_usaha','tb_usaha.id_detail_usaha')
            ->where('tb_usaha.username', Auth::user()->email)->first();
        $paidThisMonth = null;
        if($myUsaha) {
            $paidThisMonth = App\Models\Danapunia::where('id_usaha', $myUsaha->id_usaha)
                ->where('aktif','1')
                ->whereMonth('tanggal_pembayaran', $currentMonth)
                ->whereYear('tanggal_pembayaran', $currentYear)
                ->first();
        }
    @endphp

    @if($myUsaha)
    <div class="bg-{{ $paidThisMonth ? '[#00a6eb]' : 'amber-500' }} rounded-3xl p-5 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
        <p class="text-[9px] font-bold text-white/80 uppercase tracking-widest mb-1">Status {{ date('F Y') }}</p>
        <h2 class="text-2xl font-black mb-2">{{ $paidThisMonth ? 'Lunas' : 'Belum Bayar' }}</h2>
        <div class="flex items-center gap-2 text-[10px] font-bold bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full w-fit">
            <i class="bi bi-building"></i>
            {{ $myUsaha->nama_usaha }}
        </div>
        @if($myUsaha->minimal_bayar)
        <p class="text-white/70 text-[10px] font-bold mt-2">Minimal: Rp {{ number_format($myUsaha->minimal_bayar, 0, ',', '.') }}/bulan</p>
        @endif
    </div>

    @if(!$paidThisMonth)
    <!-- Payment Form -->
    <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-widest flex items-center gap-2">
            <i class="bi bi-wallet2 text-[#00a6eb]"></i> Bayar Sekarang
        </h3>
        <form action="{{ url('administrator/post_pembayaran_baru/'.$myUsaha->id_usaha) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="t_hidden_usaha" value="{{ $myUsaha->id_usaha }}">
            <input type="hidden" name="t_bulan_pembayaran" value="{{ $currentMonth }}">

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jumlah Pembayaran (Rp)</label>
                <input type="number" name="teks_input_pembayarans" value="{{ $myUsaha->minimal_bayar ?? '' }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal Pembayaran</label>
                <input type="date" name="tanggal_bukti_pembayaran" value="{{ date('Y-m-d') }}" required
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Upload Bukti Pembayaran</label>
                <input type="file" name="f_bukti_pembayaran" required accept="image/*"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-500">
            </div>
            <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090cc] text-white font-bold py-3.5 rounded-2xl shadow-lg shadow-[#00a6eb]/20 transition-all text-sm uppercase tracking-widest">
                <i class="bi bi-send-fill mr-2"></i> Kirim Pembayaran
            </button>
        </form>
    </div>
    @endif

    <!-- Payment History -->
    <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 px-1">Riwayat Pembayaran</h3>
        @php
            $history = App\Models\Danapunia::where('id_usaha', $myUsaha->id_usaha)->where('aktif','1')->orderBy('tanggal_pembayaran','desc')->take(12)->get();
        @endphp
        <div class="space-y-3">
            @forelse($history as $h)
            <div class="flex items-center justify-between bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center">
                        <i class="bi bi-check-circle-fill text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-800">Rp {{ number_format($h->jumlah_dana, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-slate-400 font-medium">{{ \Carbon\Carbon::parse($h->tanggal_pembayaran)->format('d M Y') }}</p>
                    </div>
                </div>
                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md uppercase tracking-widest">Lunas</span>
            </div>
            @empty
            <div class="bg-slate-50 rounded-2xl p-6 text-center">
                <i class="bi bi-inbox text-3xl text-slate-300 mb-2"></i>
                <p class="text-xs text-slate-400 font-medium">Belum ada riwayat pembayaran</p>
            </div>
            @endforelse
        </div>
    </div>
    @else
    <div class="bg-amber-50 border border-amber-100 rounded-3xl p-6 text-center">
        <i class="bi bi-exclamation-triangle text-3xl text-amber-400 mb-2"></i>
        <p class="text-sm font-bold text-slate-700">Akun usaha belum terdaftar</p>
        <p class="text-xs text-slate-400 mt-1">Hubungi Kelian Adat untuk mendaftarkan usaha Anda.</p>
    </div>
    @endif
</div>
@endsection
