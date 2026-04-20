@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-8 pb-24 space-y-5" x-data="{ showRejectModal: false, rejectId: null }">
    <div>
        <a href="{{ url('administrator/pura/home') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-2">
            <i class="bi bi-arrow-left text-sm"></i>
            <span class="text-[10px] font-bold">Kembali</span>
        </a>
        <h2 class="text-xl font-black text-slate-800 leading-tight">Verifikasi Pembayaran</h2>
        <p class="text-[10px] text-slate-400 mt-1">{{ $pura->nama_pura }} &mdash; Pembayaran manual menunggu verifikasi</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm font-medium flex items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    <div class="space-y-3">
        @forelse($pending as $item)
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-bold text-slate-800">{{ $item->is_anonymous ? 'Hamba Tuhan' : ($item->nama_donatur ?: 'Anonim') }}</p>
                    <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</p>
                </div>
                <p class="text-sm font-black text-[#00a6eb]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
            </div>

            @if($item->keterangan)
            <p class="text-[11px] text-slate-500 bg-slate-50 rounded-lg p-2">{{ $item->keterangan }}</p>
            @endif

            @if($item->bukti_transfer)
            <div>
                <p class="text-[10px] font-bold text-slate-400 mb-1">Bukti Transfer:</p>
                <img src="{{ asset('storage/bukti_punia_pura/' . $item->bukti_transfer) }}" class="rounded-lg border border-slate-200 max-h-48 object-contain" alt="Bukti">
            </div>
            @endif

            <div class="flex gap-2">
                <form action="{{ url('administrator/pura/approve') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="id_punia_pura" value="{{ $item->id_punia_pura }}">
                    <button type="submit" onclick="return confirm('Setujui pembayaran ini?')"
                            class="w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold transition-colors">
                        <i class="bi bi-check-lg mr-1"></i>Setujui
                    </button>
                </form>
                <button @click="showRejectModal = true; rejectId = {{ $item->id_punia_pura }}"
                        class="flex-1 py-2.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-500 text-xs font-bold border border-red-200 transition-colors">
                    <i class="bi bi-x-lg mr-1"></i>Tolak
                </button>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="bi bi-check-circle text-3xl text-emerald-300"></i>
            <p class="text-xs text-slate-400 mt-2">Tidak ada pembayaran menunggu verifikasi</p>
        </div>
        @endforelse
    </div>

    {{-- Reject Modal --}}
    <template x-teleport="body">
        <div x-show="showRejectModal" x-cloak
             class="fixed inset-0 bg-black/50 flex items-end sm:items-center justify-center z-[100]"
             @click.self="showRejectModal = false" @keydown.escape.window="showRejectModal = false">
            <div class="bg-white w-full max-w-[480px] rounded-t-2xl sm:rounded-2xl p-6 space-y-4">
                <h3 class="text-sm font-black text-slate-800">Tolak Pembayaran</h3>
                <form action="{{ url('administrator/pura/reject') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_punia_pura" x-bind:value="rejectId">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alasan Penolakan *</label>
                            <textarea name="catatan_verifikasi" required rows="3" minlength="5" maxlength="500" placeholder="Tuliskan alasan penolakan..."
                                      class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-red-100 focus:border-red-400"></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="showRejectModal = false" class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-500 text-xs font-bold">Batal</button>
                            <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition-colors">Tolak</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
