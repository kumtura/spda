@extends('index')

@section('isi_menu')
<div class="space-y-6" x-data="{ 
    showTarik: false,
    activeTab: 'log'
}">
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
        <i class="bi bi-check-circle-fill text-emerald-500"></i>
        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center gap-3">
        <i class="bi bi-x-circle-fill text-red-500"></i>
        <p class="text-sm text-red-700">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Back + Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ url('administrator/puniapura') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-primary-light transition-colors">
                <i class="bi bi-arrow-left text-sm"></i>
                <span class="text-xs font-bold">Kembali</span>
            </a>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight mt-2">{{ $pura->nama_pura }}</h1>
            <p class="text-sm text-slate-400">{{ $pura->lokasi ?? 'Lokasi belum diisi' }} &bull; Banjar {{ $pura->nama_banjar ?? '-' }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url('administrator/puniapura/edit/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 font-bold text-xs px-4 py-2 rounded-xl hover:bg-blue-100 transition-colors">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ url('administrator/puniapura/qris/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 font-bold text-xs px-4 py-2 rounded-xl hover:bg-blue-100 transition-colors">
                <i class="bi bi-qr-code"></i> Kelola QRIS
            </a>
        </div>
    </div>

    <!-- Hero Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
        <div class="flex flex-col md:flex-row gap-6 p-6 relative z-10">
            <!-- Image -->
            <div class="w-full md:w-48 h-48 rounded-xl overflow-hidden flex-shrink-0 bg-white/20">
                @if($pura->gambar_pura)
                <img src="{{ asset($pura->gambar_pura) }}" class="w-full h-full object-cover" alt="{{ $pura->nama_pura }}" onerror="this.outerHTML='<div class=\'w-full h-full flex items-center justify-center\'><i class=\'bi bi-building text-white/40 text-5xl\'></i></div>'">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="bi bi-building text-white/40 text-5xl"></i>
                </div>
                @endif
            </div>
            <!-- Info -->
            <div class="flex-1 text-white">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-[10px] text-white/60 uppercase tracking-widest">Wuku Odalan</p>
                        <p class="text-sm font-bold">{{ $pura->wuku_odalan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-white/60 uppercase tracking-widest">Odalan Terdekat</p>
                        <p class="text-sm font-bold">{{ $pura->odalan_terdekat ? \Carbon\Carbon::parse($pura->odalan_terdekat)->format('d M Y') : '-' }}</p>
                    </div>
                </div>
                @if($pura->google_maps_url)
                <a href="{{ $pura->google_maps_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-xs text-white/80 hover:text-white transition-colors">
                    <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                </a>
                @endif
                @if($pura->deskripsi)
                <p class="text-xs text-white/80 line-clamp-2 mt-2">{{ $pura->deskripsi }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Pengurus Pura -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-4">Pengurus Pura</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Ketua Pura -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[9px] text-slate-400 uppercase tracking-widest font-bold mb-2">Ketua Pura</p>
                <p class="text-sm font-bold text-slate-800">{{ $pura->nama_ketua_pura ?? '-' }}</p>
                @if($pura->no_telp_ketua)
                <p class="text-xs text-slate-500 mt-0.5"><i class="bi bi-telephone"></i> {{ $pura->no_telp_ketua }}</p>
                @endif
                <p class="text-xs text-slate-400 mt-0.5"><i class="bi bi-house-door"></i> 
                    @if($pura->id_banjar_ketua && isset($banjarList[$pura->id_banjar_ketua]))
                        Banjar {{ $banjarList[$pura->id_banjar_ketua] }}
                    @elseif($pura->banjar_ketua_manual)
                        {{ $pura->banjar_ketua_manual }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <!-- Pemangku -->
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-[9px] text-slate-400 uppercase tracking-widest font-bold mb-2">Pemangku</p>
                <p class="text-sm font-bold text-slate-800">{{ $pura->nama_pemangku ?? '-' }}</p>
                @if($pura->no_telp_pemangku)
                <p class="text-xs text-slate-500 mt-0.5"><i class="bi bi-telephone"></i> {{ $pura->no_telp_pemangku }}</p>
                @endif
                <p class="text-xs text-slate-400 mt-0.5"><i class="bi bi-house-door"></i> 
                    @if($pura->id_banjar_pemangku && isset($banjarList[$pura->id_banjar_pemangku]))
                        Banjar {{ $banjarList[$pura->id_banjar_pemangku] }}
                    @elseif($pura->banjar_pemangku_manual)
                        {{ $pura->banjar_pemangku_manual }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Penanggung Jawab (Admin Pura) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-4">Penanggung Jawab (Admin Pura)</h3>
        @if($adminPura)
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                <i class="bi bi-person-gear text-blue-500 text-xl"></i>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-800">{{ $adminPura->name }}</p>
                <p class="text-xs text-slate-400">{{ $adminPura->email }}</p>
                @if($adminPura->no_wa)
                <p class="text-xs text-slate-500"><i class="bi bi-whatsapp"></i> {{ $adminPura->no_wa }}</p>
                @endif
            </div>
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-dashed border-slate-200 p-4 text-center">
            <i class="bi bi-person-x text-2xl text-slate-200 mb-1"></i>
            <p class="text-xs text-slate-400">Belum ada admin pura yang ditugaskan</p>
            <p class="text-[10px] text-slate-300 mt-1">Tambahkan melalui menu <a href="{{ url('administrator/datauser') }}" class="text-primary-light hover:underline font-bold">Data Pengguna</a> dengan role Admin Pura</p>
        </div>
        @endif
    </div>

    <!-- Gallery -->
    @if($gallery->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest mb-3">Gallery Pura</h3>
        <div class="flex gap-2 overflow-x-auto pb-2">
            @foreach($gallery as $g)
            <img src="{{ asset($g->gambar) }}" class="h-24 w-32 rounded-xl object-cover flex-shrink-0" alt="{{ $g->caption ?? 'Gallery' }}">
            @endforeach
        </div>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="bi bi-wallet2 text-blue-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Total Punia</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($totalPunia, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="bi bi-globe text-blue-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Online</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($totalOnline, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="bi bi-cash-stack text-blue-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Manual</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($totalManual, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="bi bi-calendar-check text-blue-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">Hari Ini</p>
                    <p class="text-lg font-black text-slate-800">Rp {{ number_format($puniaHariIni, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- QRIS Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">QRIS Statis (BPD Bali)</h3>
            <div class="flex items-center gap-2">
                @if($qris)
                <a href="{{ url('administrator/puniapura/qris/download/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 font-bold text-xs px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="bi bi-download"></i> Download QR
                </a>
                @endif
                <a href="{{ url('administrator/puniapura/qris/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 font-bold text-xs px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="bi bi-gear"></i> {{ $qris ? 'Ubah' : 'Setup' }} QRIS
                </a>
            </div>
        </div>
        @if($qris)
        <div class="flex items-center gap-6">
            @if($qris->qris_image)
            <img src="{{ asset($qris->qris_image) }}" class="h-32 w-32 rounded-xl border border-slate-200" alt="QRIS">
            @endif
            <div>
                <p class="text-xs text-slate-500"><span class="font-bold text-slate-700">Merchant:</span> {{ $qris->merchant_name ?? '-' }}</p>
                <p class="text-xs text-slate-500 mt-1"><span class="font-bold text-slate-700">NMID:</span> {{ $qris->nmid ?? '-' }}</p>
                <p class="text-[10px] text-slate-400 mt-1">QR code ini bisa dicetak dan ditaruh di lokasi pura</p>
            </div>
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-dashed border-slate-200 p-6 text-center">
            <i class="bi bi-qr-code text-3xl text-slate-200 mb-2"></i>
            <p class="text-xs text-slate-400">QRIS BPD Bali belum disetup</p>
            <a href="{{ url('administrator/puniapura/qris/'.$pura->id_pura) }}" class="inline-block mt-2 text-xs font-bold text-primary-light hover:underline">Setup sekarang</a>
        </div>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-3">
        <button @click="showTarik = true" class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-red-100 transition-colors">
            <i class="bi bi-arrow-down-circle"></i> Tarik Dana Punia
        </button>
        <a href="{{ url('administrator/puniapura/generate-qris-xendit/'.$pura->id_pura) }}" class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-600 font-bold text-xs px-4 py-2.5 rounded-xl hover:bg-blue-100 transition-colors">
            <i class="bi bi-qr-code-scan"></i> Generate QRIS Xendit (Dynamic)
        </a>
    </div>

    <!-- Tarik Dana Modal -->
    <div x-show="showTarik" x-transition class="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4" @click.self="showTarik = false">
        <div class="bg-white rounded-2xl w-full max-w-md p-6 space-y-4" @click.stop>
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-800">Tarik Dana Punia</h3>
                <button @click="showTarik = false" class="h-7 w-7 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <form action="{{ url('administrator/puniapura/tarik/'.$pura->id_pura) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" required min="1000"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-primary-light/10"
                              placeholder="Tujuan penarikan..."></textarea>
                </div>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold text-sm py-2.5 rounded-xl transition-colors">
                    <i class="bi bi-arrow-down-circle mr-1.5"></i> Konfirmasi Penarikan
                </button>
            </form>
        </div>
    </div>

    <!-- Log Punia Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="text-sm font-black text-slate-700 uppercase tracking-widest">Log Punia Pura</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Tanggal</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Donatur</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Nominal</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Metode</th>
                        <th class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($punia as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-3 text-xs text-slate-500">
                            {{ $item->tanggal_pembayaran ? \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('d M Y') : $item->created_at->format('d M Y H:i') }}
                        </td>
                        <td class="px-6 py-3 text-xs text-slate-700 font-semibold">
                            @if($item->metode_pembayaran === 'tarik')
                                <span class="text-red-600">PENARIKAN</span>
                            @elseif($item->is_anonymous)
                                <span class="text-slate-400 italic">Hamba Tuhan</span>
                            @else
                                {{ $item->nama_donatur ?? '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-3 text-xs font-bold {{ $item->nominal < 0 ? 'text-red-600' : 'text-emerald-600' }}">
                            {{ $item->nominal < 0 ? '-' : '+' }} Rp {{ number_format(abs($item->nominal), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3">
                            @php
                                $metodeLabel = match($item->metode_pembayaran) {
                                    'xendit' => ['Xendit', 'bg-blue-50 text-blue-600'],
                                    'qris_bpd' => ['QRIS BPD', 'bg-blue-50 text-blue-600'],
                                    'manual' => ['Manual', 'bg-slate-50 text-slate-600'],
                                    'tarik' => ['Penarikan', 'bg-red-50 text-red-600'],
                                    default => ['Lainnya', 'bg-slate-50 text-slate-600'],
                                };
                            @endphp
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg {{ $metodeLabel[1] }}">{{ $metodeLabel[0] }}</span>
                        </td>
                        <td class="px-6 py-3">
                            @if($item->status_pembayaran === 'completed')
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-600"><i class="bi bi-check-circle"></i> Selesai</span>
                            @elseif($item->status_pembayaran === 'pending')
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-blue-50 text-blue-600"><i class="bi bi-clock"></i> Pending</span>
                            @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-lg bg-red-50 text-red-600">{{ $item->status_pembayaran }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-xs text-slate-400">Belum ada data punia</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($punia->hasPages())
        <div class="px-6 py-3 border-t border-slate-100">
            {{ $punia->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
