@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-28" x-data="{ showKategoriModal: false, editMode: false, kategoriId: null }">

    <!-- Hero Section -->
    <div class="relative h-[180px] bg-slate-100 flex items-center justify-center overflow-hidden">
        @if($objek->foto)
            <img src="{{ asset('storage/wisata/'.$objek->foto) }}" class="w-full h-full object-cover" alt="{{ $objek->nama_objek }}">
        @else
            <i class="bi bi-image text-[60px] text-slate-200"></i>
        @endif
        @if($objek->status === 'aktif')
        <span class="absolute top-5 right-4 bg-emerald-500 text-white text-[8px] font-bold px-2 py-1 rounded uppercase">Aktif</span>
        @else
        <span class="absolute top-5 right-4 bg-slate-500 text-white text-[8px] font-bold px-2 py-1 rounded uppercase">Nonaktif</span>
        @endif
        <a href="{{ url('administrator/kelian/tiket') }}" class="absolute top-5 left-4 h-9 w-9 bg-white/90 backdrop-blur-sm rounded-xl flex items-center justify-center text-slate-600 border border-white/50 active:scale-90 transition-transform">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
    </div>

    <!-- Main Content -->
    <div class="px-4 -mt-5 relative z-10 space-y-5">

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-emerald-600 text-sm"></i>
                <p class="text-xs text-emerald-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Title Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-3.5">
            <h1 class="text-base font-bold text-slate-800 leading-tight">{{ $objek->nama_objek }}</h1>
            
            <div class="flex items-center gap-2 text-[10px] text-slate-500">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $objek->alamat }}</span>
            </div>

            @if($objek->jam_buka && $objek->jam_tutup)
            <div class="flex items-center gap-2 text-[10px] text-slate-500">
                <i class="bi bi-clock"></i>
                <span>{{ $objek->jam_buka }} - {{ $objek->jam_tutup }} WITA</span>
            </div>
            @endif

            @if($objek->kapasitas_harian)
            <div class="flex items-center gap-2 text-[10px] text-slate-500">
                <i class="bi bi-people"></i>
                <span>Kapasitas: {{ number_format($objek->kapasitas_harian, 0, ',', '.') }} orang/hari</span>
            </div>
            @endif
        </div>

        <!-- Stats Card -->
        @php
            $tiketHariIni = App\Models\TiketWisata::where('id_objek_wisata', $objek->id_objek_wisata)
                ->whereDate('created_at', today())
                ->where('status_pembayaran', 'completed')
                ->get();
            $totalPenjualanHariIni = $tiketHariIni->sum('total_harga');
            $totalTiketTerjual = $tiketHariIni->sum(function($tiket) {
                return $tiket->details->sum('jumlah');
            });
            
            // Tiket yang sudah digunakan (scanned)
            $tiketDigunakan = App\Models\TiketWisata::where('id_objek_wisata', $objek->id_objek_wisata)
                ->whereDate('created_at', today())
                ->where('status_pembayaran', 'completed')
                ->where('status_tiket', 'sudah_digunakan')
                ->get();
            $totalPengunjungHariIni = $tiketDigunakan->sum(function($tiket) {
                return $tiket->details->sum('jumlah');
            });
        @endphp
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
            <h4 class="text-xs font-bold text-slate-800">Statistik Hari Ini</h4>
            
            <!-- Pendapatan -->
            <div>
                <p class="text-[9px] font-bold uppercase text-slate-400 mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-black text-[#00a6eb]">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}</h3>
            </div>
            
            <!-- Grid Stats -->
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-blue-50 rounded-lg p-3 text-center">
                    <p class="text-[9px] text-slate-500 mb-1">Tiket Terjual</p>
                    <p class="text-lg font-black text-[#00a6eb]">{{ $totalTiketTerjual }}</p>
                </div>
                <div class="bg-emerald-50 rounded-lg p-3 text-center">
                    <p class="text-[9px] text-slate-500 mb-1">Pengunjung</p>
                    <p class="text-lg font-black text-emerald-600">{{ $totalPengunjungHariIni }}</p>
                </div>
                <div class="bg-slate-50 rounded-lg p-3 text-center">
                    <p class="text-[9px] text-slate-500 mb-1">Transaksi</p>
                    <p class="text-lg font-black text-slate-700">{{ $tiketHariIni->count() }}</p>
                </div>
            </div>
            
            <!-- Progress Bar -->
            @if($totalTiketTerjual > 0)
            <div>
                <div class="flex items-center justify-between text-[9px] mb-1.5">
                    <span class="text-slate-500">Tingkat Kunjungan</span>
                    <span class="font-bold text-slate-700">{{ $totalTiketTerjual > 0 ? round(($totalPengunjungHariIni / $totalTiketTerjual) * 100) : 0 }}%</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-[#00a6eb] to-emerald-500 rounded-full transition-all" 
                         style="width: {{ $totalTiketTerjual > 0 ? ($totalPengunjungHariIni / $totalTiketTerjual) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endif
        </div>

        @if($objek->deskripsi)
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-2.5">
            <h4 class="text-xs font-bold text-slate-800">Deskripsi</h4>
            <p class="text-xs text-slate-600 leading-relaxed">{{ $objek->deskripsi }}</p>
        </div>
        @endif

        <!-- Kategori Tiket -->
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-xs font-bold text-slate-800">Kategori Harga Tiket</h4>
                <button @click="showKategoriModal = true; editMode = false; $nextTick(() => { resetFormForAdd() })" class="h-7 px-3 bg-[#00a6eb] text-white rounded-lg text-[10px] font-bold">
                    <i class="bi bi-plus-lg mr-1"></i>Tambah
                </button>
            </div>
            <div class="space-y-2.5">
                @forelse($objek->kategoriTiket as $kategori)
                    <div class="bg-white rounded-xl border border-slate-100 p-3 flex items-center gap-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <p class="text-xs font-bold text-slate-800">{{ $kategori->nama_kategori }}</p>
                                <span class="text-[8px] font-bold text-[#00a6eb] bg-blue-50 px-1.5 py-0.5 rounded">{{ ucfirst($kategori->tipe_kategori) }}</span>
                                @if($kategori->market_type === 'wna')
                                <span class="text-[8px] font-bold text-violet-600 bg-violet-50 px-1.5 py-0.5 rounded">WNA</span>
                                @elseif($kategori->market_type === 'local')
                                <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">Lokal</span>
                                @endif
                            </div>
                            <p class="text-sm font-black text-slate-700">Rp {{ number_format($kategori->harga, 0, ',', '.') }}</p>
                            @if($kategori->deskripsi)
                            <p class="text-[9px] text-slate-500 mt-1">{{ $kategori->deskripsi }}</p>
                            @endif
                        </div>
                        <div class="flex gap-1">
                            <button onclick="editKategori({{ $kategori->id_kategori_tiket }})" class="h-7 w-7 bg-blue-50 text-[#00a6eb] rounded-lg flex items-center justify-center">
                                <i class="bi bi-pencil text-xs"></i>
                            </button>
                            <button onclick="deleteKategori({{ $kategori->id_kategori_tiket }})" class="h-7 w-7 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center">
                                <i class="bi bi-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="bi bi-ticket-perforated text-3xl text-slate-200 mb-2 block"></i>
                        <p class="text-xs font-bold text-slate-400">Belum ada kategori tiket</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Riwayat Scan Hari Ini -->
        @php
            $scannedTickets = App\Models\TiketWisata::with(['details.kategoriTiket'])
                ->where('id_objek_wisata', $objek->id_objek_wisata)
                ->whereDate('waktu_scan', today())
                ->where('status_tiket', 'sudah_digunakan')
                ->orderBy('waktu_scan', 'desc')
                ->get();
        @endphp
        
        @if($scannedTickets->count() > 0)
        <div>
            <h4 class="text-xs font-bold text-slate-800 mb-3">Riwayat Scan Hari Ini</h4>
            <div class="space-y-2.5">
                @foreach($scannedTickets as $ticket)
                <div class="bg-white rounded-xl border border-slate-100 p-3.5">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <p class="text-[10px] font-bold text-slate-800 mb-0.5">{{ $ticket->kode_tiket }}</p>
                            <p class="text-[9px] text-slate-500">
                                <i class="bi bi-clock mr-1"></i>{{ $ticket->waktu_scan->format('H:i') }} WITA
                            </p>
                        </div>
                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">Tervalidasi</span>
                    </div>
                    
                    <div class="bg-slate-50 rounded-lg p-2 space-y-1">
                        @foreach($ticket->details as $detail)
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-600">{{ $detail->kategoriTiket->nama_kategori }}</span>
                            <span class="text-[10px] font-bold text-slate-800">{{ $detail->jumlah }}x</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-2">
            <a href="{{ url('administrator/kelian/tiket/objek/edit/'.$objek->id_objek_wisata) }}" 
               class="block w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white text-center py-3 rounded-xl font-bold text-sm shadow-lg">
                <i class="bi bi-pencil mr-2"></i>Edit Objek
            </a>
            
            <a href="{{ url('administrator/kelian/tiket/objek/toggle/'.$objek->id_objek_wisata) }}" 
               class="block w-full bg-white border-2 border-slate-200 text-slate-700 text-center py-3 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
                <i class="bi bi-toggle-{{ $objek->status === 'aktif' ? 'on' : 'off' }} mr-2"></i>{{ $objek->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}
            </a>
            
            <button onclick="confirmDelete()" 
                    class="w-full bg-white border-2 border-rose-200 text-rose-600 text-center py-3 rounded-xl font-bold text-sm hover:bg-rose-50 transition-all">
                <i class="bi bi-trash mr-2"></i>Hapus Objek
            </button>
        </div>
    </div>

<!-- Modal Kategori -->
<template x-teleport="body">
    <div x-show="showKategoriModal" 
         x-cloak
         @click.self="showKategoriModal = false"
         @keydown.escape.window="showKategoriModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        
        <div @click.stop 
             class="bg-white rounded-2xl max-w-md w-full overflow-hidden"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 scale-95 translate-y-8"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-5 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                <button @click="showKategoriModal = false" type="button" class="absolute top-3 right-3 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-lg font-bold" id="modal-title">Tambah Kategori</h3>
                    <p class="text-white/80 text-[10px] mt-1">Atur harga tiket</p>
                </div>
            </div>

            <form id="kategori-form" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" id="kategori-id" name="id_kategori_tiket">
                <input type="hidden" name="id_objek_wisata" value="{{ $objek->id_objek_wisata }}">
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Nama Kategori</label>
                    <input type="text" id="nama-kategori" name="nama_kategori" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none"
                           placeholder="Contoh: Dewasa, Motor">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Tipe</label>
                    <select id="tipe-kategori" name="tipe_kategori" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                        <option value="orang">Per Orang</option>
                        <option value="kendaraan">Per Kendaraan</option>
                        <option value="paket">Paket</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Kategori Pasar</label>
                    <select id="market-type" name="market_type"
                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none">
                        <option value="all">Semua (Lokal & WNA)</option>
                        <option value="local">Lokal</option>
                        <option value="wna">WNA</option>
                    </select>
                    <p class="text-[9px] text-slate-400 mt-1">Pilih untuk siapa kategori harga ini berlaku</p>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Harga (Rp)</label>
                    <input type="number" id="harga-kategori" name="harga" required min="0"
                           class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none"
                           placeholder="10000">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 mb-1.5">Deskripsi (Opsional)</label>
                    <textarea id="deskripsi-kategori" name="deskripsi" rows="2"
                              class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-[#00a6eb]/20 focus:border-[#00a6eb] outline-none resize-none"
                              placeholder="Keterangan..."></textarea>
                </div>
                
                <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-bold py-3 rounded-xl transition-all text-sm">
                    <i class="bi bi-save mr-2"></i><span id="submit-text">Simpan</span>
                </button>
            </form>
        </div>
    </div>
</template>
</div>

<script>
const kategoriData = @json($objek->kategoriTiket);

function editKategori(id) {
    const kategori = kategoriData.find(k => k.id_kategori_tiket === id);
    if (!kategori) return;
    
    document.getElementById('modal-title').textContent = 'Edit Kategori';
    document.getElementById('submit-text').textContent = 'Update';
    document.getElementById('kategori-form').action = '{{ url("administrator/kelian/tiket/kategori/update") }}/' + id;
    
    let methodInput = document.getElementById('kategori-form').querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        document.getElementById('kategori-form').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    
    document.getElementById('kategori-id').value = kategori.id_kategori_tiket;
    document.getElementById('nama-kategori').value = kategori.nama_kategori;
    document.getElementById('tipe-kategori').value = kategori.tipe_kategori;
    document.getElementById('harga-kategori').value = kategori.harga;
    document.getElementById('deskripsi-kategori').value = kategori.deskripsi || '';
    document.getElementById('market-type').value = kategori.market_type || 'all';
    
    // Trigger Alpine.js to show modal in edit mode
    const el = document.querySelector('[x-data]');
    Alpine.$data(el).editMode = true;
    Alpine.$data(el).showKategoriModal = true;
}

function resetFormForAdd() {
    document.getElementById('modal-title').textContent = 'Tambah Kategori';
    document.getElementById('submit-text').textContent = 'Simpan';
    document.getElementById('kategori-form').action = '{{ url("administrator/kelian/tiket/kategori/store") }}';
    document.getElementById('kategori-form').method = 'POST';
    
    document.getElementById('kategori-id').value = '';
    document.getElementById('nama-kategori').value = '';
    document.getElementById('tipe-kategori').value = 'orang';
    document.getElementById('harga-kategori').value = '';
    document.getElementById('deskripsi-kategori').value = '';
    document.getElementById('market-type').value = 'all';
    
    // Remove method spoofing if exists
    const methodInput = document.getElementById('kategori-form').querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
}

function deleteKategori(id) {
    if (confirm('Hapus kategori tiket ini?')) {
        window.location.href = '{{ url("administrator/kelian/tiket/kategori/delete") }}/' + id;
    }
}

function confirmDelete() {
    if (confirm('Hapus objek wisata ini?')) {
        window.location.href = '{{ url("administrator/kelian/tiket/objek/delete/".$objek->id_objek_wisata) }}';
    }
}
</script>
@endsection
