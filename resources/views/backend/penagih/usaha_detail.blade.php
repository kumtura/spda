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

    $arrStsPunia = [];
    $arrDanas = [];
    $totalPuniaTahun = 0;
    for($m = 1; $m <= 12; $m++) {
        $payment = $payments[$m] ?? null;
        if($payment) {
            $arrStsPunia[$m] = 'y';
            $arrDanas[$m] = $payment;
            $totalPuniaTahun += $payment->jumlah_dana;
        } else {
            $arrStsPunia[$m] = 'n';
        }
    }

    $sudahBayar = collect($arrStsPunia)->filter(fn($v) => $v === 'y')->count();
    $belumBayar = $currentMonth - $sudahBayar;
    if($belumBayar < 0) $belumBayar = 0;

    // Get karyawan/tenaga kerja
    $karyawanList = App\Models\Jadwal_Interview::join('tb_tenaga_kerja', 'tb_tenaga_kerja.id_tenaga_kerja', 'tb_jadwal_interview.id_karyawan')
        ->where('tb_jadwal_interview.id_usaha', $rows->id_usaha)
        ->where('tb_tenaga_kerja.aktif', '1')
        ->select('tb_tenaga_kerja.*', 'tb_jadwal_interview.status_diterima', 'tb_jadwal_interview.tanggal_interview', 'tb_jadwal_interview.jabatan', 'tb_jadwal_interview.tanggal_diterima')
        ->orderBy('tb_tenaga_kerja.nama', 'asc')
        ->get();

    foreach($karyawanList as $karyawan) {
        $karyawan->skills = App\Models\List_Skill_Tk::join('tb_skill_tenaga_kerja','tb_skill_tenaga_kerja.id_skill_tenaga_kerja','tb_list_skill_tenaga_kerja.id_skill')
            ->where('tb_list_skill_tenaga_kerja.id_karyawan', $karyawan->id_tenaga_kerja)
            ->where('tb_list_skill_tenaga_kerja.aktif','1')
            ->pluck('tb_skill_tenaga_kerja.nama_skill');
    }

    $totalKaryawan = $karyawanList->count();
    $karyawanBali = $karyawanList->filter(fn($k) => stripos($k->alamat ?? '', 'bali') !== false)->count();
    $karyawanLokal = $totalKaryawan - $karyawanBali;

    $logoPath = '';
    if($rows->logo) {
        $logoPath = file_exists(public_path('usaha/icon/'.$rows->logo)) 
            ? 'usaha/icon/'.$rows->logo 
            : 'storage/usaha/icon/'.$rows->logo;
    }
@endphp

<div class="bg-white pb-28" x-data="{ 
    activeTab: 'info',
    showPayModal: false,
    payMonth: null,
    payMonthName: '',
    payProcessing: false,
    payAmount: {{ (float) ($rows->minimal_bayar ?? 0) }},
    payDate: '{{ date('Y-m-d') }}'
}">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/penagih/usaha') }}" class="inline-flex items-center gap-2 mb-4 text-white/80 hover:text-white">
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
                                <th class="text-center px-3 py-2 text-[10px] font-black text-slate-500 uppercase">Aksi</th>
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
                                <td class="px-3 py-2.5">
                                    <div class="flex items-center justify-center gap-1">
                                        @if($isPaid)
                                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded border border-emerald-100">Lunas</span>
                                        @else
                                        <button @click="payMonth = {{ $num }}; payMonthName = '{{ $name }}'; payAmount = {{ (float) ($rows->minimal_bayar ?? 0) }}; payDate = '{{ date('Y-m-d') }}'; showPayModal = true"
                                                class="h-7 w-7 bg-[#00a6eb] text-white rounded-lg flex items-center justify-center hover:bg-[#0090d0] transition-all" title="Bayar Manual">
                                            <i class="bi bi-wallet2 text-xs"></i>
                                        </button>
                                        @php
                                            $waTarget = $rows->no_wa_pngg ?: $rows->no_wa ?: '';
                                            $waPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $waTarget));
                                            $payUrl = url('punia/bayar/'.$rows->id_usaha.'?bulan='.$num.'&tahun='.$currentYear);
                                            $waMsg = urlencode("Yth. ".$rows->nama_usaha.",\n\nIni adalah pengingat pembayaran Punia Wajib bulan ".$name." ".$currentYear.".\nMinimal: Rp ".number_format($rows->minimal_bayar ?? 0, 0, ',', '.')."\n\nSilakan lakukan pembayaran melalui link berikut:\n".$payUrl."\n\nTerimakasih,\nPenagih Banjar ".($rows->nama_banjar ?? ''));
                                        @endphp
                                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank" rel="noopener noreferrer"
                                           class="h-7 w-7 bg-emerald-500 text-white rounded-lg flex items-center justify-center hover:bg-emerald-600 transition-all" title="Reminder WA">
                                            <i class="bi bi-whatsapp text-xs"></i>
                                        </a>
                                        @endif
                                    </div>
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
                            <div class="flex items-center gap-1 flex-wrap mt-0.5">
                                @if($karyawan->jabatan)
                                <span class="text-[8px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ $karyawan->jabatan }}</span>
                                @endif
                                @if($karyawan->no_wa)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $karyawan->no_wa) }}" target="_blank"
                                   class="text-[8px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                                    <i class="bi bi-whatsapp"></i> {{ $karyawan->no_wa }}
                                </a>
                                @endif
                            </div>
                            @if($karyawan->skills && $karyawan->skills->count() > 0)
                            <div class="flex items-center gap-1 flex-wrap mt-1">
                                @foreach($karyawan->skills->take(3) as $skill)
                                <span class="text-[7px] font-medium text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">{{ $skill }}</span>
                                @endforeach
                                @if($karyawan->skills->count() > 3)
                                <span class="text-[7px] text-slate-400">+{{ $karyawan->skills->count() - 3 }}</span>
                                @endif
                            </div>
                            @endif
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

    <!-- Payment Modal -->
    <div x-show="showPayModal" x-cloak class="fixed inset-0 z-50 flex items-end justify-center">
        <div class="absolute inset-0 bg-black/40" @click="showPayModal = false"></div>
        <div class="relative bg-white rounded-t-2xl w-full max-w-[480px] max-h-[85vh] overflow-y-auto p-5 pb-8 shadow-xl" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-black text-slate-800">Pembayaran Iuran - <span x-text="payMonthName"></span> {{ $selectedYear }}</h3>
                <button @click="showPayModal = false" class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center">
                    <i class="bi bi-x-lg text-xs text-slate-500"></i>
                </button>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 space-y-3">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Nominal (Rp)</label>
                        <input type="number" x-model="payAmount" min="1000"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none">
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-slate-500 uppercase mb-1 block">Tanggal Pembayaran Tunai</label>
                        <input type="date" x-model="payDate"
                               class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-xs focus:border-blue-400 focus:ring-1 focus:ring-blue-100 outline-none">
                    </div>
                </div>

                <button type="button" @click="payProcessing = true; $refs.usahaCashForm.submit()"
                        class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-cash-coin text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Tunai</h4>
                            <p class="text-[10px] text-slate-400">Catat pembayaran tunai langsung</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>

                <button type="button" @click="payProcessing = true; $refs.usahaOnlineForm.submit()"
                        class="w-full text-left bg-white border-2 border-slate-100 rounded-2xl p-5 hover:border-[#00a6eb]/30 hover:bg-slate-50/50 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-slate-50 rounded-xl flex items-center justify-center shrink-0 border border-slate-100 transition-colors group-hover:bg-[#00a6eb] group-hover:border-[#00a6eb]">
                            <i class="bi bi-phone text-slate-400 text-xl group-hover:text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800 mb-0.5">Online Payment</h4>
                            <p class="text-[10px] text-slate-400">Lanjut ke metode pembayaran Xendit</p>
                        </div>
                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-[#00a6eb] transition-transform group-hover:translate-x-1"></i>
                    </div>
                </button>
            </div>

            <form x-ref="usahaCashForm" action="{{ url('administrator/penagih/usaha/bayar-manual') }}" method="POST" style="display:none"
                  @submit="payProcessing = true">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $rows->id_usaha }}">
                <input type="hidden" name="tahun" value="{{ $selectedYear }}">
                <input type="hidden" :name="'bulan'" :value="payMonth">
                <input type="hidden" name="jumlah_dana" :value="payAmount">
                <input type="hidden" name="tanggal_pembayaran" :value="payDate">
                <input type="hidden" name="metode_pembayaran" value="tunai">
            </form>

            <form x-ref="usahaOnlineForm" action="{{ url('administrator/penagih/usaha/bayar-online') }}" method="POST" style="display:none">
                @csrf
                <input type="hidden" name="id_usaha" value="{{ $rows->id_usaha }}">
                <input type="hidden" name="tahun" value="{{ $selectedYear }}">
                <input type="hidden" :name="'bulan'" :value="payMonth">
                <input type="hidden" name="jumlah_dana" :value="payAmount">
            </form>

            <div x-show="payProcessing" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center z-50">
                <i class="bi bi-arrow-repeat animate-spin text-4xl text-[#00a6eb] mb-3"></i>
                <p class="text-xs font-bold text-slate-600">Sedang diproses...</p>
            </div>
        </div>
    </div>
</div>
@endsection
