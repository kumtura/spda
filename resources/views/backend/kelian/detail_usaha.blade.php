@extends('mobile_layout')

@section('isi_menu')
@php
    $currentMonth = (int)date('m');
    $currentYear = (int)date('Y');
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    // Payment status per month
    $arrStsPunia = [];
    $arrDanas = [];
    $totalPuniaTahun = 0;
    for($m = 1; $m <= 12; $m++) {
        $awal = str_pad($m, 2, '0', STR_PAD_LEFT);
        $awalBln = $currentYear."-".$awal."-01";
        $akhirBln = $currentYear."-".$awal."-31";
        $dana = App\Models\Danapunia::where('id_usaha', $rows->id_usaha)
            ->where('tanggal_pembayaran', '>=', $awalBln)
            ->where('tanggal_pembayaran', '<=', $akhirBln)
            ->where('aktif', '1')
            ->first();
        if($dana) {
            $arrStsPunia[$m] = 'y';
            $arrDanas[$m] = $dana;
            $totalPuniaTahun += $dana->jumlah_dana;
        } else {
            $arrStsPunia[$m] = 'n';
        }
    }

    $sudahBayar = collect($arrStsPunia)->filter(fn($v) => $v === 'y')->count();
    $belumBayar = $currentMonth - $sudahBayar;
    if($belumBayar < 0) $belumBayar = 0;

    // Get karyawan/tenaga kerja for this usaha
    $karyawanList = App\Models\Jadwal_Interview::join('tb_tenaga_kerja', 'tb_tenaga_kerja.id_tenaga_kerja', 'tb_jadwal_interview.id_karyawan')
        ->where('tb_jadwal_interview.id_usaha', $rows->id_usaha)
        ->where('tb_tenaga_kerja.aktif', '1')
        ->select('tb_tenaga_kerja.*', 'tb_jadwal_interview.status_diterima', 'tb_jadwal_interview.tanggal_interview')
        ->orderBy('tb_tenaga_kerja.nama', 'asc')
        ->get();

    $totalKaryawan = $karyawanList->count();
    // Estimate: check alamat for "bali" keyword as rough approximation
    $karyawanBali = $karyawanList->filter(fn($k) => stripos($k->alamat ?? '', 'bali') !== false)->count();
    $karyawanLokal = $totalKaryawan - $karyawanBali;

    $logoPath = '';
    if($rows->logo) {
        $logoPath = file_exists(public_path('usaha/icon/'.$rows->logo)) 
            ? 'usaha/icon/'.$rows->logo 
            : 'storage/usaha/icon/'.$rows->logo;
    }
@endphp

<div class="bg-white pb-28" x-data="{ activeTab: 'info' }">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/kelian/data_usaha') }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
                <i class="bi bi-arrow-left text-lg"></i>
                <span class="text-xs">Kembali</span>
            </a>
            
            <div class="flex items-start gap-4">
                <div class="h-16 w-16 bg-white/20 rounded-xl flex items-center justify-center shrink-0 overflow-hidden">
                    @if($rows->logo)
                    <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
                    @else
                    <i class="bi bi-building text-white text-2xl"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-lg font-black leading-tight">{{ $rows->nama_usaha }}</h1>
                    </div>
                    <span class="text-[8px] font-bold bg-white/20 px-2 py-0.5 rounded border border-white/20">{{ $rows->nama_kategori_usaha ?? 'Unit Usaha' }}</span>
                    <p class="text-white/70 text-[10px] mt-1">ID: #{{ str_pad($rows->id_usaha, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-5 relative z-10 space-y-5">
        @if(session('success'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3">
            <div class="flex items-center gap-2">
                <i class="bi bi-check-circle text-blue-600 text-sm"></i>
                <p class="text-xs text-blue-700">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Info Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Informasi Usaha</h4>
            
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-geo-alt text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Banjar:</span>
                    <span class="text-slate-700">{{ $rows->nama_banjar ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-house text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Alamat:</span>
                    <span class="text-slate-700">{{ $rows->alamat_banjar ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-telephone text-slate-400 text-xs"></i>
                    <span class="text-slate-500">No. Telp:</span>
                    <span class="text-slate-700">{{ $rows->no_telp ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-whatsapp text-slate-400 text-xs"></i>
                    <span class="text-slate-500">WhatsApp:</span>
                    <span class="text-slate-700">{{ $rows->no_wa ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-envelope text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Email:</span>
                    <span class="text-slate-700">{{ $rows->email_usaha ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-calendar text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Tgl Daftar:</span>
                    <span class="text-slate-700">{{ $rows->tanggal_daftar ? \Carbon\Carbon::parse($rows->tanggal_daftar)->format('d M Y') : '-' }}</span>
                </div>
            </div>

            <div class="flex gap-2 pt-3 border-t border-slate-100">
                <a href="{{ url('administrator/kelian/pendatang/kartu-punia/'.$rows->id_usaha) }}" class="flex-1 py-2 bg-blue-50 text-[#00a6eb] rounded-lg text-[10px] font-medium text-center border border-blue-100" style="display:none;">
                    <i class="bi bi-wallet2 mr-1"></i>Kartu Iuran
                </a>
            </div>
        </div>

        <!-- Penanggung Jawab / CP Card -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Contact Person (PJ)</h4>
            
            <div class="space-y-1.5">
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-person text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Nama:</span>
                    <span class="text-slate-700 font-medium">{{ $rows->nama ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-briefcase text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Jabatan:</span>
                    <span class="text-slate-700">{{ $rows->status_penanggung_jawab ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-whatsapp text-slate-400 text-xs"></i>
                    <span class="text-slate-500">WA PJ:</span>
                    <span class="text-slate-700">{{ $rows->no_wa_pngg ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-envelope text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Email PJ:</span>
                    <span class="text-slate-700">{{ $rows->email ?? '-' }}</span>
                </div>
                <div class="flex items-center gap-2 text-[11px]">
                    <i class="bi bi-geo text-slate-400 text-xs"></i>
                    <span class="text-slate-500">Alamat PJ:</span>
                    <span class="text-slate-700">{{ $rows->alamat ?? $rows->alamat_usaha ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Stats Grid: Karyawan -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
            <h4 class="text-xs font-bold text-slate-800">Data Tenaga Kerja</h4>
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-slate-700">{{ $totalKaryawan }}</p>
                    <p class="text-[9px] text-slate-400">Total</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-[#00a6eb]">{{ $karyawanBali }}</p>
                    <p class="text-[9px] text-slate-400">Bali</p>
                </div>
                <div class="bg-slate-50 rounded-lg px-3 py-2.5 text-center">
                    <p class="text-lg font-bold text-emerald-600">{{ $karyawanLokal }}</p>
                    <p class="text-[9px] text-slate-400">Non-Bali</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
            <div class="flex border-b border-slate-100 overflow-x-auto">
                <button @click="activeTab = 'info'" :class="activeTab === 'info' ? 'text-[#00a6eb] border-b-2 border-[#00a6eb] bg-white' : 'text-slate-400'" 
                        class="px-4 py-3 text-[10px] font-black uppercase tracking-wider whitespace-nowrap transition-all">Punia</button>
                <button @click="activeTab = 'karyawan'" :class="activeTab === 'karyawan' ? 'text-[#00a6eb] border-b-2 border-[#00a6eb] bg-white' : 'text-slate-400'" 
                        class="px-4 py-3 text-[10px] font-black uppercase tracking-wider whitespace-nowrap transition-all">Tenaga Kerja</button>
            </div>

            <!-- Tab: Punia -->
            <div x-show="activeTab === 'info'" class="p-4 space-y-4">
                <!-- Punia Stats -->
                <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] rounded-xl p-4 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-12 -mt-12"></div>
                    <div class="relative z-10">
                        <p class="text-[9px] uppercase text-white/60 mb-1">Total Punia Tahun {{ $currentYear }}</p>
                        <h3 class="text-2xl font-black mb-2">Rp {{ number_format($totalPuniaTahun, 0, ',', '.') }}</h3>
                        <div class="flex items-center justify-between text-xs pt-2 border-t border-white/20">
                            <div>
                                <p class="text-white/60 text-[9px]">Sudah Bayar</p>
                                <p class="font-bold">{{ $sudahBayar }} bulan</p>
                            </div>
                            <div>
                                <p class="text-white/60 text-[9px]">Minimal</p>
                                <p class="font-bold">Rp {{ number_format($rows->minimal_bayar ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Payment Table -->
                <div class="border border-slate-100 rounded-xl overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-left px-3 py-2 text-[10px] font-black text-slate-500 uppercase">Bulan</th>
                                <th class="text-center px-2 py-2 text-[10px] font-black text-slate-500 uppercase">Nominal</th>
                                <th class="text-center px-2 py-2 text-[10px] font-black text-slate-500 uppercase">Tgl</th>
                                <th class="text-center px-3 py-2 text-[10px] font-black text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($months as $num => $name)
                            @php
                                $isPaid = ($arrStsPunia[$num] ?? 'n') === 'y';
                                $dana = $arrDanas[$num] ?? null;
                                $isPast = $num < $currentMonth;
                            @endphp
                            <tr class="border-b border-slate-50 {{ !$isPaid && $isPast ? 'bg-rose-50/30' : '' }}">
                                <td class="px-3 py-2.5">
                                    <span class="text-xs font-bold text-slate-700">{{ $name }}</span>
                                    @if(!$isPaid && $isPast)
                                    <span class="text-[7px] font-bold text-rose-500 bg-rose-100 px-1 py-0.5 rounded ml-1">TERLEWAT</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2.5 text-center">
                                    @if($isPaid && $dana)
                                    <span class="text-[10px] font-bold text-slate-700">{{ number_format($dana->jumlah_dana, 0, ',', '.') }}</span>
                                    @else
                                    <span class="text-[10px] text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-2.5 text-center">
                                    @if($isPaid && $dana)
                                    <span class="text-[10px] text-slate-500">{{ \Carbon\Carbon::parse($dana->tanggal_pembayaran)->format('d/m') }}</span>
                                    @else
                                    <span class="text-[10px] text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    @if($isPaid)
                                    <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">Lunas</span>
                                    @else
                                    <span class="text-[8px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded border border-slate-100">Belum</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab: Tenaga Kerja -->
            <div x-show="activeTab === 'karyawan'" class="p-4 space-y-3">
                @if($karyawanList->count() > 0)
                @foreach($karyawanList as $karyawan)
                <div class="bg-white border border-slate-100 rounded-xl p-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
                            @if($karyawan->foto_profile)
                            <img src="{{ asset('karyawan/'.$karyawan->foto_profile) }}" class="h-full w-full object-cover" alt="">
                            @else
                            <i class="bi bi-person text-slate-300 text-lg"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 truncate">{{ $karyawan->nama }}</p>
                            <div class="flex items-center gap-2 text-[9px] text-slate-400 mt-0.5">
                                @if($karyawan->alamat)
                                <span class="truncate">{{ $karyawan->alamat }}</span>
                                @endif
                                @if($karyawan->no_wa)
                                <span>&middot; {{ $karyawan->no_wa }}</span>
                                @endif
                            </div>
                        </div>
                        @if($karyawan->status_diterima == 1)
                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100 shrink-0">Aktif</span>
                        @else
                        <span class="text-[8px] font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded border border-amber-100 shrink-0">Proses</span>
                        @endif
                    </div>
                </div>
                @endforeach
                @else
                <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
                    <i class="bi bi-people text-3xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-400">Belum ada data tenaga kerja</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
