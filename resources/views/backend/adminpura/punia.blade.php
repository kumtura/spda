@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-5">
    <div>
        <a href="{{ url('administrator/pura/home') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Data Punia</h2>
        <p class="text-[10px] text-slate-400 mt-1">{{ $pura->nama_pura }}</p>
    </div>

    {{-- Total Card --}}
    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-2xl p-5 text-white shadow-lg">
        <p class="text-[9px] uppercase text-white/60 font-bold">Total Punia Terkumpul</p>
        <h3 class="text-2xl font-black">Rp {{ number_format($totalPunia ?? 0, 0, ',', '.') }}</h3>
    </div>

    {{-- List --}}
    <div class="space-y-2.5">
        @forelse($punia as $item)
        <div class="bg-white rounded-xl border border-slate-100 p-3.5">
            <div class="flex items-start justify-between gap-3 mb-2">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 line-clamp-1">
                        {{ $item->is_anonymous ? 'Hamba Tuhan' : ($item->nama_donatur ?: 'Anonim') }}
                    </p>
                    <p class="text-[10px] text-slate-400">
                        {{ $item->tanggal_pembayaran ? \Carbon\Carbon::parse($item->tanggal_pembayaran)->translatedFormat('d M Y, H:i') : \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-sm font-black {{ $item->nominal < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                        Rp {{ number_format(abs($item->nominal), 0, ',', '.') }}
                    </p>
                    @php
                        $statusColor = match($item->status_pembayaran) {
                            'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            default => 'bg-slate-50 text-slate-500 border-slate-100'
                        };
                    @endphp
                    <span class="inline-block mt-1 text-[8px] font-bold uppercase px-2 py-0.5 rounded-full border {{ $statusColor }}">
                        {{ $item->status_pembayaran }}
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-3 text-[10px] text-slate-400">
                <span><i class="bi bi-credit-card"></i> {{ ucfirst($item->metode_pembayaran) }}</span>
                @if($item->keterangan)
                <span class="line-clamp-1"><i class="bi bi-chat-left-text"></i> {{ $item->keterangan }}</span>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="bi bi-inbox text-3xl text-slate-200"></i>
            <p class="text-xs text-slate-400 mt-2">Belum ada data punia</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($punia->hasPages())
    <div class="flex justify-center">
        {{ $punia->links('pagination::simple-tailwind') }}
    </div>
    @endif
</div>
@endsection
