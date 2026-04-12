@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <div>
        <a href="{{ url('administrator/puniapura/detail/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-primary-light transition-colors">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-xs font-bold">Kembali ke Detail</span>
        </a>
        <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-2">Kelola QRIS - {{ $pura->nama_pura }}</h1>
        <p class="text-sm text-slate-400">Setup QRIS statis dari Bank BPD Bali untuk dicetak di lokasi pura</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
        <p class="text-sm text-emerald-700"><i class="bi bi-check-circle-fill mr-1"></i>{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-sm text-red-700"><i class="bi bi-x-circle-fill mr-1"></i>{{ session('error') }}</p>
    </div>
    @endif

    <!-- Current QRIS -->
    @if($qris)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-4">QRIS Aktif Saat Ini</h3>
        <div class="flex items-start gap-6">
            @if($qris->qris_image)
            <div class="flex flex-col items-center gap-2">
                <img src="{{ asset($qris->qris_image) }}" class="h-48 w-48 rounded-xl border-2 border-slate-200" alt="QRIS">
                <a href="{{ url('administrator/puniapura/qris/download/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-xl hover:bg-emerald-600 transition-colors">
                    <i class="bi bi-download"></i> Download untuk Print
                </a>
            </div>
            @endif
            <div class="space-y-2">
                <p class="text-xs"><span class="font-bold text-slate-700">Merchant:</span> <span class="text-slate-500">{{ $qris->merchant_name ?? '-' }}</span></p>
                <p class="text-xs"><span class="font-bold text-slate-700">NMID:</span> <span class="text-slate-500">{{ $qris->nmid ?? '-' }}</span></p>
                <p class="text-xs"><span class="font-bold text-slate-700">Dibuat:</span> <span class="text-slate-500">{{ $qris->created_at->format('d M Y H:i') }}</span></p>
                <div class="mt-3 p-3 bg-slate-50 rounded-xl">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">QRIS Content String</p>
                    <p class="text-[10px] text-slate-500 break-all font-mono">{{ Str::limit($qris->qris_content, 100) }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-4">{{ $qris ? 'Update' : 'Setup' }} QRIS BPD Bali</h3>
        
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
            <p class="text-xs text-blue-700">
                <i class="bi bi-info-circle mr-1"></i>
                Masukkan QRIS content string yang diperoleh dari Bank BPD Bali. 
                String ini biasanya dimulai dengan "0002" dan berisi informasi merchant QRIS statis.
                Sistem akan otomatis men-generate gambar QR code yang bisa dicetak.
            </p>
        </div>

        <form action="{{ url('administrator/puniapura/qris/'.$pura->id_pura) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">QRIS Content String *</label>
                <textarea name="qris_content" rows="3" required
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono outline-none focus:ring-4 focus:ring-primary-light/10"
                          placeholder="00020101021126...">{{ old('qris_content', $qris->qris_content ?? '') }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NMID (National Merchant ID)</label>
                    <input type="text" name="nmid" value="{{ old('nmid', $qris->nmid ?? '') }}" placeholder="ID..."
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Merchant Name</label>
                    <input type="text" name="merchant_name" value="{{ old('merchant_name', $qris->merchant_name ?? $pura->nama_pura) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary-light hover:bg-primary-dark text-white text-sm font-bold shadow-md shadow-blue-200/50 transition-all">
                    <i class="bi bi-qr-code mr-1.5"></i>{{ $qris ? 'Update' : 'Generate' }} QRIS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
