@extends('mobile_layout')

@section('isi_menu')
@php
    $penagihBanjar = $banjar;
    $currentMonth = (int)date('m');
    $currentYear = (int)date('Y');

    $totalUsaha = count($usahaWithPayment);
    $sudahBayar = 0;
    $belumBayar = 0;
    $totalPunia = 0;
    foreach($usahaWithPayment as $item) {
        if($item['payment']) {
            $sudahBayar++;
            $totalPunia += $item['payment']->jumlah_dana;
        } else {
            $belumBayar++;
        }
    }
@endphp

<div class="bg-white px-4 pt-8 pb-24 space-y-6" x-data="{ 
    selectedMonth: {{ $selectedMonth }},
    selectedYear: {{ $selectedYear }},
    searchQuery: '',
    showFilter: false,
    showTambahModal: false,
    waNumber: '',
    sendingLink: false
}">
    <!-- Back + Header -->
    <div>
        <a href="{{ url('administrator/penagih') }}" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] transition-colors mb-3">
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

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-800 tracking-tight">Data Usaha</h1>
                <p class="text-slate-400 text-[10px] mt-1">Banjar {{ $penagihBanjar ? $penagihBanjar->nama_banjar : '-' }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showTambahModal = true" class="h-8 px-3 bg-[#00a6eb] hover:bg-[#0090d0] text-white rounded-lg flex items-center justify-center gap-1.5 transition-colors">
                    <i class="bi bi-plus-lg text-sm"></i>
                    <span class="text-[10px] font-bold">Tambah</span>
                </button>
                <button @click="showFilter = !showFilter" class="h-8 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg flex items-center justify-center gap-1.5 transition-colors">
                    <i class="bi bi-funnel text-sm"></i>
                    <span class="text-[10px] font-bold">Filter</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Panel -->
    <div x-show="showFilter" x-cloak x-transition class="bg-slate-50 border border-slate-100 rounded-xl p-4 space-y-3">
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Bulan</label>
                <select x-model="selectedMonth" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 bg-white">
                    @foreach($months as $num => $name)
                    <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Tahun</label>
                <select x-model="selectedYear" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2 bg-white">
                    @for($y = $currentYear; $y >= $currentYear - 3; $y--)
                    <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <button @click="window.location.href = '{{ url('administrator/penagih/usaha') }}?bulan=' + selectedMonth + '&tahun=' + selectedYear" 
                class="w-full bg-[#00a6eb] text-white text-xs font-bold py-2.5 rounded-lg hover:bg-[#0090d0] transition-colors">
            Terapkan Filter
        </button>
    </div>

    <!-- Stats Card -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="bi bi-building text-lg"></i>
                </div>
                <span class="text-[8px] font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">{{ $months[$selectedMonth] }} {{ $selectedYear }}</span>
            </div>
            <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Unit Usaha</p>
            <h3 class="text-3xl font-black mb-3">Rp {{ number_format($totalPunia, 0, ',', '.') }}</h3>
            
            <div class="grid grid-cols-3 gap-3 pt-3 border-t border-white/20">
                <div>
                    <p class="text-white/60 text-[9px] mb-0.5">Total Usaha</p>
                    <p class="font-bold text-sm">{{ $totalUsaha }}</p>
                </div>
                <div class="text-center">
                    <p class="text-white/60 text-[9px] mb-0.5">Sudah Bayar</p>
                    <p class="font-bold text-sm text-emerald-300">{{ $sudahBayar }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[9px] mb-0.5">Belum Bayar</p>
                    <p class="font-bold text-sm text-amber-300">{{ $belumBayar }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="relative">
        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
        <input type="text" x-model="searchQuery" placeholder="Cari usaha..." 
               class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-4 py-2.5 text-xs text-slate-700 placeholder-slate-300 focus:outline-none focus:border-[#00a6eb]/30 focus:ring-1 focus:ring-[#00a6eb]/20">
    </div>

    <!-- Usaha List -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Daftar Unit Usaha</h3>
            <span class="text-[10px] font-medium text-slate-400">{{ $sudahBayar }}/{{ $totalUsaha }} bayar</span>
        </div>

        @if(count($usahaWithPayment) > 0)
        <div class="space-y-2.5">
            @foreach($usahaWithPayment as $item)
            @php $usaha = $item['usaha']; $payment = $item['payment']; @endphp
            <a href="{{ url('administrator/penagih/usaha/detail/'.$usaha->id_usaha) }}" 
               x-show="searchQuery === '' || '{{ strtolower($usaha->nama_usaha) }}'.includes(searchQuery.toLowerCase())"
               class="block bg-white border border-slate-100 rounded-xl p-3.5 hover:border-[#00a6eb]/30 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                        @if($usaha->logo)
                            @php
                                $logoPath = file_exists(public_path('usaha/icon/'.$usaha->logo)) 
                                    ? 'usaha/icon/'.$usaha->logo 
                                    : 'storage/usaha/icon/'.$usaha->logo;
                            @endphp
                            <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                        @else
                            <i class="bi bi-building text-slate-300 text-xl"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-800 mb-0.5 truncate">{{ $usaha->nama_usaha }}</p>
                        <div class="flex items-center gap-2 text-[9px] text-slate-400">
                            @if($usaha->nama_kategori_usaha)
                            <span>{{ $usaha->nama_kategori_usaha }}</span>
                            <span>&middot;</span>
                            @endif
                            @if($payment)
                            <span class="text-emerald-500">Rp {{ number_format($payment->jumlah_dana, 0, ',', '.') }}</span>
                            @else
                            <span>Min. Rp {{ number_format($usaha->minimal_bayar ?? 0, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    @if($payment)
                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 shrink-0">Lunas</span>
                    @else
                    <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100 shrink-0">Belum</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
            <i class="bi bi-building text-3xl text-slate-300 mb-2"></i>
            <p class="text-xs text-slate-400">Belum ada unit usaha di banjar ini</p>
        </div>
        @endif
    </div>

    <!-- Recent Payments History -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-slate-800">Riwayat Pembayaran Terbaru</h3>
        </div>

        @php
            if($penagihBanjar) {
                $usahaIds = $usahaList->pluck('id_usaha');
                $recentPayments = App\Models\Danapunia::whereIn('id_usaha', $usahaIds)
                    ->where('aktif', '1')
                    ->where('status_pembayaran', 'completed')
                    ->orderBy('tanggal_pembayaran', 'desc')
                    ->limit(15)
                    ->get();
            } else {
                $recentPayments = collect([]);
            }
        @endphp

        @if($recentPayments->count() > 0)
        <div class="space-y-1.5">
            @foreach($recentPayments as $dp)
            @php
                $namaUsaha = $usahaList->firstWhere('id_usaha', $dp->id_usaha);
            @endphp
            <div class="bg-white border border-slate-100 rounded-xl px-3 py-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate">{{ $namaUsaha ? $namaUsaha->nama_usaha : 'Usaha #'.$dp->id_usaha }}</p>
                        <div class="flex items-center gap-2 mt-0.5 text-[9px] text-slate-400">
                            <span>{{ $dp->tanggal_pembayaran ? \Carbon\Carbon::parse($dp->tanggal_pembayaran)->format('d M Y') : '-' }}</span>
                            @if($dp->metode_pembayaran)
                            <span>&middot; {{ strtoupper($dp->metode_pembayaran) }}</span>
                            @elseif($dp->metode)
                            <span>&middot; {{ strtoupper($dp->metode) }}</span>
                            @endif
                            <span>&middot; Bln {{ $dp->bulan_punia ?? $dp->bulan ?? '-' }}</span>
                        </div>
                    </div>
                    <span class="text-[11px] font-bold text-emerald-600 shrink-0">
                        +Rp {{ number_format($dp->jumlah_dana, 0, ',', '.') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-4 text-center">
            <p class="text-xs text-slate-400">Belum ada riwayat pembayaran</p>
        </div>
        @endif
    </div>

    <!-- Tambah Usaha Modal -->
    <div x-show="showTambahModal" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-end justify-center bg-slate-900/60 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="showTambahModal = false"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="bg-white rounded-t-3xl shadow-2xl max-w-md w-full overflow-hidden max-h-[90vh] overflow-y-auto" x-data="{ mode: 'choose' }">
            
            <!-- Header -->
            <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] p-6 text-white relative overflow-hidden sticky top-0 z-10">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                <button @click="showTambahModal = false; mode = 'choose'" type="button" class="absolute top-4 right-4 h-8 w-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors z-10">
                    <i class="bi bi-x text-xl"></i>
                </button>
                <div class="relative">
                    <h3 class="text-xl font-black">Tambah Unit Usaha</h3>
                    <p class="text-white/80 text-xs font-medium mt-1">Pilih metode pendaftaran</p>
                </div>
            </div>

            <!-- Mode: Choose -->
            <div x-show="mode === 'choose'" class="p-6 space-y-3">
                <button @click="mode = 'manual'" type="button"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-pencil-square text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Tambah Manual</h4>
                            <p class="text-[10px] text-slate-400">Isi data usaha secara langsung</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <button @click="mode = 'link'" type="button"
                   class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-emerald-500 group-hover:border-emerald-500">
                            <i class="bi bi-whatsapp text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Kirim Link Pendaftaran</h4>
                            <p class="text-[10px] text-slate-400">Kirim form pendaftaran via WhatsApp</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-emerald-500 transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <div class="pt-2 text-center">
                    <button @click="showTambahModal = false" class="text-[10px] font-bold text-slate-300 uppercase tracking-widest hover:text-slate-500 transition-colors">Batal</button>
                </div>
            </div>

            <!-- Mode: Manual Form -->
            <div x-show="mode === 'manual'" class="p-6">
                <button @click="mode = 'choose'" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] text-[10px] font-bold mb-4">
                    <i class="bi bi-arrow-left text-xs"></i> Kembali
                </button>

                <form action="{{ url('administrator/penagih/usaha/store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="text_desc_new" value="{{ $penagihBanjar ? $penagihBanjar->id_data_banjar : '' }}">

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Nama Usaha <span class="text-rose-500">*</span></label>
                        <input type="text" name="text_title_new" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="Nama unit usaha">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Kategori <span class="text-rose-500">*</span></label>
                        <select name="cmb_kategori_usaha" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20">
                            <option value="">Pilih Kategori</option>
                            @php $kategoriList = App\Models\Kategori_Usaha::get_kategoriusaha(); @endphp
                            @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id_kategori_usaha }}">{{ $kat->nama_kategori_usaha }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">No. Telp</label>
                            <input type="text" name="text_telpkantor_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="No telp kantor">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">No. WA</label>
                            <input type="text" name="text_notelp_was" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="08xxx">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Email Usaha</label>
                        <input type="email" name="text_email_usaha_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="email@usaha.com">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Minimal Punia/bln (Rp)</label>
                        <input type="number" name="text_minimal_pembayaran" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="0" value="0">
                    </div>

                    <div class="pt-1 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-500 uppercase mb-3">Data Penanggung Jawab</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Nama PJ <span class="text-rose-500">*</span></label>
                        <input type="text" name="text_namapngg_new" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="Nama penanggung jawab">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Jabatan PJ</label>
                        <input type="text" name="text_statuspngg_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="Manager, Owner, dll">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">WA PJ</label>
                            <input type="text" name="text_notelp_pngg_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="08xxx">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Email PJ</label>
                            <input type="email" name="text_email_pngg_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="email@pj.com">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Alamat PJ</label>
                        <input type="text" name="text_alamat_pngg_new" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="Alamat penanggung jawab">
                    </div>

                    <div class="pt-1 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-500 uppercase mb-3">Akun Login Usaha</p>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Username/Email <span class="text-rose-500">*</span></label>
                        <input type="text" name="text_username_new" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="username login">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="text_password_new" required minlength="6" class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-[#00a6eb]/50 focus:ring-1 focus:ring-[#00a6eb]/20" placeholder="Min 6 karakter">
                    </div>

                    <button type="submit" class="w-full bg-[#00a6eb] text-white text-xs font-bold py-3 rounded-xl hover:bg-[#0090d0] transition-colors shadow-lg">
                        <i class="bi bi-check-circle mr-1.5"></i> Simpan Data Usaha
                    </button>
                </form>
            </div>

            <!-- Mode: Kirim Link -->
            <div x-show="mode === 'link'" class="p-6">
                <button @click="mode = 'choose'" class="inline-flex items-center gap-1.5 text-slate-400 hover:text-[#00a6eb] text-[10px] font-bold mb-4">
                    <i class="bi bi-arrow-left text-xs"></i> Kembali
                </button>

                <div class="space-y-4">
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3">
                        <p class="text-[10px] text-emerald-700 leading-relaxed">
                            <i class="bi bi-info-circle mr-1"></i>
                            Masukkan nomor WhatsApp PIC usaha. Link pendaftaran akan dikirim melalui WhatsApp.
                        </p>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1.5 block">Nomor WhatsApp <span class="text-rose-500">*</span></label>
                        <input type="text" x-model="waNumber" placeholder="08xxxxxxxxxx" 
                               class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2.5 bg-white focus:outline-none focus:border-emerald-300 focus:ring-1 focus:ring-emerald-200">
                    </div>

                    @php
                        $registerUrl = url('register-usaha') . '?banjar=' . ($penagihBanjar ? $penagihBanjar->id_data_banjar : '');
                    @endphp

                    <button @click="
                        if(!waNumber || waNumber.length < 10) { alert('Masukkan nomor WA yang valid'); return; }
                        let phone = waNumber.replace(/^0/, '62').replace(/[^0-9]/g, '');
                        let msg = encodeURIComponent('Halo, silakan daftarkan unit usaha Anda melalui link berikut:\n\n{{ $registerUrl }}\n\nTerimakasih,\nPenagih Banjar {{ $penagihBanjar ? $penagihBanjar->nama_banjar : '' }}');
                        window.open('https://wa.me/' + phone + '?text=' + msg, '_blank');
                        showTambahModal = false; mode = 'choose';
                    " class="w-full bg-emerald-500 text-white text-xs font-bold py-3 rounded-xl hover:bg-emerald-600 transition-colors shadow-lg flex items-center justify-center gap-2">
                        <i class="bi bi-whatsapp text-sm"></i> Kirim Link via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
