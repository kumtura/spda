@extends('mobile_layout')

@section('isi_menu')
@php
    $usaha = $rows;
    $totalBulan = 12;
    $sudahBayar = collect($payments)->filter(fn($p) => $p !== null)->count();
    $belumBayar = $totalBulan - $sudahBayar;
    $monthNames = ['', 'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
@endphp

<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{
    showBayar: false,
    selectedMonth: null,
    selectedMonthName: '',
    nominal: {{ $usaha->punia ?? 0 }},
    metode: 'tunai',
    openBayar(month, name) {
        this.selectedMonth = month;
        this.selectedMonthName = name;
        this.showBayar = true;
    }
}">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/penagih/usaha') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>

        @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                <p class="text-xs text-blue-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <h1 class="text-xl font-black text-slate-800 tracking-tight">Detail Usaha</h1>
        <p class="text-slate-400 text-[10px] mt-0.5">Punia usaha tahun {{ $selectedYear }}</p>
    </div>

    <!-- Usaha Profile Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-5 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10 flex items-center gap-4">
            <div class="h-14 w-14 bg-white/20 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                @if($usaha->logo)
                @php
                    $logoPath = file_exists(public_path('usaha/icon/'.$usaha->logo)) 
                        ? 'usaha/icon/'.$usaha->logo 
                        : 'storage/usaha/icon/'.$usaha->logo;
                @endphp
                <img src="{{ asset($logoPath) }}" class="w-full h-full object-contain" alt="Logo" onerror="this.outerHTML='<i class=\'bi bi-building text-white/60 text-2xl\'></i>'">
                @else
                <i class="bi bi-building text-white/60 text-2xl"></i>
                @endif
            </div>
            <div>
                <h3 class="font-black text-lg leading-tight">{{ $usaha->nama_usaha }}</h3>
                <p class="text-white/70 text-[10px]">{{ $usaha->nama_kategori_usaha ?? 'Umum' }} &bull; Banjar {{ $banjar->nama_banjar ?? '-' }}</p>
                <p class="text-[10px] text-white/50 mt-0.5">Punia: Rp {{ number_format($usaha->punia ?? 0, 0, ',', '.') }}/bulan</p>
            </div>
        </div>
        <div class="relative z-10 flex gap-4 text-xs pt-4 mt-4 border-t border-white/20">
            <div>
                <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                <p class="font-bold text-emerald-300">{{ $sudahBayar }} bulan</p>
            </div>
            <div>
                <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                <p class="font-bold text-rose-300">{{ $belumBayar }} bulan</p>
            </div>
        </div>
    </div>

    <!-- Year Navigation -->
    <div class="flex items-center justify-between bg-slate-50 rounded-xl px-4 py-2.5 border border-slate-200">
        <a href="{{ url('administrator/penagih/usaha/detail/'.$usaha->id_usaha.'?tahun='.($selectedYear-1)) }}" class="h-7 w-7 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#00a6eb] transition-colors">
            <i class="bi bi-chevron-left text-[10px]"></i>
        </a>
        <span class="text-sm font-black text-slate-700">{{ $selectedYear }}</span>
        <a href="{{ url('administrator/penagih/usaha/detail/'.$usaha->id_usaha.'?tahun='.($selectedYear+1)) }}" class="h-7 w-7 bg-white border border-slate-200 rounded-lg flex items-center justify-center text-slate-400 hover:text-[#00a6eb] transition-colors">
            <i class="bi bi-chevron-right text-[10px]"></i>
        </a>
    </div>

    <!-- 12-Month Grid -->
    <div class="grid grid-cols-3 gap-2">
        @for($m = 1; $m <= 12; $m++)
        @php
            $pay = $payments[$m] ?? null;
        @endphp
        <div class="rounded-xl border {{ $pay ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-slate-200 hover:border-[#00a6eb]/30 cursor-pointer' }} p-3 text-center transition-colors"
             @if(!$pay) @click="openBayar({{ $m }}, '{{ $monthNames[$m] }}')" @endif>
            <p class="text-[9px] font-bold uppercase {{ $pay ? 'text-emerald-500' : 'text-slate-400' }}">{{ $monthNames[$m] }}</p>
            @if($pay)
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg my-1"></i>
            <p class="text-[8px] text-emerald-600">Rp {{ number_format($pay->nominal, 0, ',', '.') }}</p>
            @else
            <i class="bi bi-circle text-slate-200 text-lg my-1"></i>
            <p class="text-[8px] text-slate-400">Belum</p>
            @endif
        </div>
        @endfor
    </div>

    <!-- Bayar Modal -->
    <div x-show="showBayar" x-transition class="fixed inset-0 z-50 bg-black/40 flex items-end" @click.self="showBayar = false">
        <div class="bg-white rounded-t-3xl w-full p-6 max-h-[80vh] overflow-y-auto" @click.stop>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-black text-slate-800">Bayar Punia Usaha</h3>
                <button @click="showBayar = false" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                    <i class="bi bi-x text-sm"></i>
                </button>
            </div>

            <div class="bg-slate-50 rounded-xl p-3 mb-4">
                <p class="text-[9px] text-slate-400 uppercase mb-1">Pembayaran untuk</p>
                <p class="text-xs font-bold text-slate-800">{{ $usaha->nama_usaha }}</p>
                <p class="text-[10px] text-slate-500">Bulan <span x-text="selectedMonthName"></span> {{ $selectedYear }}</p>
            </div>

            <form action="{{ url('administrator/penagih/usaha/bayar-manual') }}" method="POST">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $usaha->id_usaha }}">
                <input type="hidden" name="tahun" :value="'{{ $selectedYear }}'">
                <input type="hidden" name="bulan" :value="selectedMonth">

                <div class="space-y-3">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Nominal (Rp)</label>
                        <input type="number" name="nominal" x-model="nominal"
                               class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10"
                               required>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Metode Pembayaran</label>
                        <div class="flex gap-2">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="metode" value="tunai" x-model="metode" class="hidden peer">
                                <div class="flex items-center justify-center gap-1.5 border border-slate-200 rounded-xl p-2.5 peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 transition-colors">
                                    <i class="bi bi-cash-stack text-sm" :class="metode=='tunai' ? 'text-[#00a6eb]' : 'text-slate-400'"></i>
                                    <span class="text-xs font-bold" :class="metode=='tunai' ? 'text-[#00a6eb]' : 'text-slate-500'">Cash</span>
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="metode" value="qris" x-model="metode" class="hidden peer">
                                <div class="flex items-center justify-center gap-1.5 border border-slate-200 rounded-xl p-2.5 peer-checked:border-[#00a6eb] peer-checked:bg-blue-50 transition-colors">
                                    <i class="bi bi-qr-code text-sm" :class="metode=='qris' ? 'text-[#00a6eb]' : 'text-slate-400'"></i>
                                    <span class="text-xs font-bold" :class="metode=='qris' ? 'text-[#00a6eb]' : 'text-slate-500'">QRIS</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase mb-1">Keterangan (opsional)</label>
                        <textarea name="keterangan" rows="2"
                                  class="w-full border border-slate-200 rounded-xl p-3 text-sm outline-none focus:ring-4 focus:ring-[#00a6eb]/10"
                                  placeholder="Catatan pembayaran..."></textarea>
                    </div>
                </div>

                <button type="submit" class="w-full mt-5 bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white font-bold text-sm py-3 rounded-xl shadow-md shadow-blue-200/50 hover:shadow-lg transition-all">
                    <i class="bi bi-check-circle mr-1.5"></i> Konfirmasi Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
