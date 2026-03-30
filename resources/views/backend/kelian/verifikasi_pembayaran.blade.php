@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white px-4 pt-6 pb-24 space-y-6" x-data="{ 
    activeTab: 'punia',
    showApproveModal: false, 
    showRejectModal: false, 
    selectedPayment: null, 
    selectedType: null 
}">

    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight mb-1">Verifikasi Pembayaran</h1>
        <p class="text-slate-400 text-xs">Review pembayaran transfer manual</p>
    </div>

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <i class="bi bi-check-circle text-emerald-600"></i>
            <p class="text-xs font-medium text-emerald-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
            <p class="text-[10px] font-medium text-slate-500 uppercase mb-1">Punia</p>
            <p class="text-2xl font-bold text-[#00a6eb]">{{ $pending_punia->count() }}</p>
        </div>
        <div class="bg-rose-50 border border-rose-100 rounded-xl p-4">
            <p class="text-[10px] font-medium text-slate-500 uppercase mb-1">Donasi</p>
            <p class="text-2xl font-bold text-rose-600">{{ $pending_donasi->count() }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-slate-50 rounded-xl p-1 flex gap-1">
        <button @click="activeTab = 'punia'" 
                :class="activeTab === 'punia' ? 'bg-white text-[#00a6eb] shadow-sm' : 'text-slate-500'"
                class="flex-1 py-2.5 rounded-lg font-medium text-xs transition-all">
            Punia ({{ $pending_punia->count() }})
        </button>
        <button @click="activeTab = 'donasi'" 
                :class="activeTab === 'donasi' ? 'bg-white text-rose-600 shadow-sm' : 'text-slate-500'"
                class="flex-1 py-2.5 rounded-lg font-medium text-xs transition-all">
            Donasi ({{ $pending_donasi->count() }})
        </button>
    </div>

    <!-- Punia List -->
    <div x-show="activeTab === 'punia'" x-transition class="space-y-3">
        @forelse($pending_punia as $punia)
        <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[9px] font-medium mb-2">
                        <span class="h-1.5 w-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Pending
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 mb-0.5">{{ $punia->nama_donatur ?? 'Anonim' }}</h3>
                    <p class="text-[10px] text-slate-500">{{ $punia->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-[#00a6eb]">Rp {{ number_format($punia->jumlah_dana, 0, ',', '.') }}</p>
                    <p class="text-[9px] text-slate-400">{{ $punia->created_at->format('d M, H:i') }}</p>
                </div>
            </div>

            <a href="{{ asset($punia->bukti_transfer) }}" target="_blank" 
               class="flex items-center gap-2 bg-slate-50 rounded-lg p-2.5 border border-slate-200">
                <i class="bi bi-file-earmark-image text-[#00a6eb]"></i>
                <span class="text-[10px] text-slate-600 flex-1 truncate">Lihat Bukti Transfer</span>
                <i class="bi bi-box-arrow-up-right text-slate-400 text-xs"></i>
            </a>

            <div class="flex items-center gap-2">
                <button @click="selectedPayment = {{ $punia->id_dana_punia }}; selectedType = 'punia'; showApproveModal = true" 
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-lg text-[10px] transition-all">
                    <i class="bi bi-check-circle mr-1"></i> Setujui
                </button>
                <button @click="selectedPayment = {{ $punia->id_dana_punia }}; selectedType = 'punia'; showRejectModal = true" 
                        class="flex-1 bg-slate-600 hover:bg-slate-700 text-white font-medium py-2.5 rounded-lg text-[10px] transition-all">
                    <i class="bi bi-x-circle mr-1"></i> Tolak
                </button>
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-8 text-center">
            <i class="bi bi-inbox text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Tidak ada pembayaran punia pending</p>
        </div>
        @endforelse
    </div>

    <!-- Donasi List -->
    <div x-show="activeTab === 'donasi'" x-transition class="space-y-3">
        @forelse($pending_donasi as $donasi)
        <div class="bg-white border border-slate-100 rounded-xl p-4 space-y-3">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-600 text-[9px] font-medium mb-2">
                        <span class="h-1.5 w-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Pending
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 mb-0.5">{{ $donasi->nama ?? 'Anonim' }}</h3>
                    @if($donasi->programDonasi)
                    <p class="text-[10px] text-slate-500">{{ $donasi->programDonasi->nama_program }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-rose-600">Rp {{ number_format($donasi->nominal, 0, ',', '.') }}</p>
                    <p class="text-[9px] text-slate-400">{{ $donasi->created_at->format('d M, H:i') }}</p>
                </div>
            </div>

            <a href="{{ asset($donasi->bukti_transfer) }}" target="_blank" 
               class="flex items-center gap-2 bg-slate-50 rounded-lg p-2.5 border border-slate-200">
                <i class="bi bi-file-earmark-image text-rose-600"></i>
                <span class="text-[10px] text-slate-600 flex-1 truncate">Lihat Bukti Transfer</span>
                <i class="bi bi-box-arrow-up-right text-slate-400 text-xs"></i>
            </a>

            <div class="flex items-center gap-2">
                <button @click="selectedPayment = {{ $donasi->id_sumbangan_sukarela }}; selectedType = 'donasi'; showApproveModal = true" 
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 rounded-lg text-[10px] transition-all">
                    <i class="bi bi-check-circle mr-1"></i> Setujui
                </button>
                <button @click="selectedPayment = {{ $donasi->id_sumbangan_sukarela }}; selectedType = 'donasi'; showRejectModal = true" 
                        class="flex-1 bg-slate-600 hover:bg-slate-700 text-white font-medium py-2.5 rounded-lg text-[10px] transition-all">
                    <i class="bi bi-x-circle mr-1"></i> Tolak
                </button>
            </div>
        </div>
        @empty
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-8 text-center">
            <i class="bi bi-inbox text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Tidak ada pembayaran donasi pending</p>
        </div>
        @endforelse
    </div>

    <!-- Approve Modal -->
    <div x-show="showApproveModal" 
         x-cloak
         @click.self="showApproveModal = false"
         @keydown.escape.window="showApproveModal = false"
         x-transition
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showApproveModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Setujui Pembayaran</h3>
                    <p class="text-white/80 text-xs mt-1">Konfirmasi pembayaran diterima</p>
                </div>
            </div>

            <form action="{{ url('administrator/verifikasi_pembayaran/approve') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id" x-model="selectedPayment">
                <input type="hidden" name="type" x-model="selectedType">

                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <i class="bi bi-check-circle text-emerald-600 text-lg shrink-0"></i>
                        <div>
                            <p class="text-xs font-bold text-slate-700 mb-1">Konfirmasi</p>
                            <p class="text-[10px] text-slate-600 leading-relaxed">
                                Pembayaran akan disetujui dan status berubah menjadi "Completed".
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-check-circle mr-2"></i> Setujui Pembayaran
                </button>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div x-show="showRejectModal" 
         x-cloak
         @click.self="showRejectModal = false"
         @keydown.escape.window="showRejectModal = false"
         x-transition
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.stop class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden">
            <div class="bg-gradient-to-br from-slate-600 to-slate-700 p-6 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showRejectModal = false" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-bold">Tolak Pembayaran</h3>
                    <p class="text-white/80 text-xs mt-1">Berikan alasan penolakan</p>
                </div>
            </div>

            <form action="{{ url('administrator/verifikasi_pembayaran/reject') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="id" x-model="selectedPayment">
                <input type="hidden" name="type" x-model="selectedType">

                <div>
                    <label class="block text-[10px] font-medium text-slate-600 mb-1.5">Alasan Penolakan (Opsional)</label>
                    <textarea name="alasan" rows="3" placeholder="Contoh: Bukti transfer tidak jelas..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all resize-none"></textarea>
                </div>

                <button type="submit" class="w-full bg-slate-600 hover:bg-slate-700 text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
                    <i class="bi bi-x-circle mr-2"></i> Tolak Pembayaran
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
