@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-6 pb-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10 flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/ticketcounter') }}" class="h-9 w-9 bg-white/20 rounded-lg flex items-center justify-center hover:bg-white/30 transition-colors">
                <i class="bi bi-arrow-left text-white text-lg"></i>
            </a>
            <div>
                <h1 class="text-lg font-black">Absensi</h1>
                <p class="text-[10px] text-white/80">Clock In / Clock Out shift harian</p>
            </div>
        </div>
    </div>

    <div class="px-4 -mt-4 relative z-20 space-y-5">
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-lg mt-0.5"></i>
            <p class="text-xs text-emerald-700 font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3">
            <i class="bi bi-exclamation-circle-fill text-rose-500 text-lg mt-0.5"></i>
            <p class="text-xs text-rose-700 font-medium">{{ session('error') }}</p>
        </div>
        @endif

        <!-- Current Shift Status -->
        @if($activeShift)
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="bg-emerald-500 px-5 py-3 flex items-center gap-2">
                <div class="h-2.5 w-2.5 bg-white rounded-full animate-pulse"></div>
                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Shift Aktif</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1">Lokasi</p>
                        <p class="text-sm font-bold text-slate-800">{{ $activeShift->objekWisata->nama_objek }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1">Clock In</p>
                        <p class="text-sm font-bold text-[#00a6eb]">{{ $activeShift->waktu_masuk->format('H:i') }}</p>
                    </div>
                </div>
                <div class="bg-slate-50 rounded-xl p-3 text-center">
                    <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mb-1">Durasi Shift</p>
                    <p class="text-xl font-black text-slate-800">{{ $activeShift->waktu_masuk->diffForHumans(now(), true) }}</p>
                </div>

                <!-- Clock Out Form -->
                <form action="{{ url('administrator/ticketcounter/absensi/clockout') }}" method="POST" id="clockout-form">
                    @csrf
                    <input type="hidden" name="lokasi" id="lokasi-keluar">
                    <div class="mb-3">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Catatan Shift (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Catatan akhir shift..."
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-rose-500 text-white text-sm font-black rounded-xl shadow-lg active:scale-95 transition-all">
                        <i class="bi bi-box-arrow-right mr-2"></i>Clock Out
                    </button>
                </form>
            </div>
        </div>
        @else
        <!-- Clock In Form -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="bg-[#00a6eb] px-5 py-3">
                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Mulai Shift</span>
            </div>
            <div class="p-5">
                <form action="{{ url('administrator/ticketcounter/absensi/clockin') }}" method="POST" id="clockin-form">
                    @csrf
                    <input type="hidden" name="lokasi" id="lokasi-masuk">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Lokasi Tugas <span class="text-rose-500">*</span></label>
                        <select name="id_objek_wisata" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                            <option value="">Pilih Objek Wisata</option>
                            @foreach($objekWisata as $objek)
                            <option value="{{ $objek->id_objek_wisata }}">{{ $objek->nama_objek }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-emerald-500 text-white text-sm font-black rounded-xl shadow-lg active:scale-95 transition-all">
                        <i class="bi bi-box-arrow-in-right mr-2"></i>Clock In
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Riwayat Absensi Bulan Ini -->
        <div>
            <h3 class="text-sm font-bold text-slate-800 mb-3">
                Riwayat Absensi — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </h3>

            @if($riwayat->count() > 0)
            <div class="space-y-2">
                @foreach($riwayat as $absen)
                <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] font-bold text-slate-500">{{ $absen->waktu_masuk->translatedFormat('l, d M Y') }}</span>
                        @if($absen->waktu_keluar)
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">Selesai</span>
                        @else
                        <span class="text-[9px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">Aktif</span>
                        @endif
                    </div>
                    <p class="text-xs font-bold text-slate-800 mb-1">{{ $absen->objekWisata->nama_objek ?? '-' }}</p>
                    <div class="flex items-center gap-4 text-[10px] text-slate-500">
                        <span><i class="bi bi-box-arrow-in-right text-emerald-500"></i> {{ $absen->waktu_masuk->format('H:i') }}</span>
                        <span>
                            <i class="bi bi-box-arrow-right text-rose-500"></i>
                            {{ $absen->waktu_keluar ? $absen->waktu_keluar->format('H:i') : '-' }}
                        </span>
                        @if($absen->durasi_menit)
                        <span><i class="bi bi-clock"></i> {{ floor($absen->durasi_menit / 60) }}j {{ $absen->durasi_menit % 60 }}m</span>
                        @endif
                    </div>
                    @if($absen->catatan)
                    <p class="text-[10px] text-slate-400 mt-2 italic">{{ $absen->catatan }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-slate-50 rounded-xl border border-slate-100 border-dashed p-6 text-center">
                <i class="bi bi-clipboard text-3xl text-slate-300 mb-2"></i>
                <p class="text-xs text-slate-400">Belum ada riwayat absensi bulan ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Try to get GPS location for absensi
document.addEventListener('DOMContentLoaded', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            const coords = pos.coords.latitude + ',' + pos.coords.longitude;
            const lokasiMasuk = document.getElementById('lokasi-masuk');
            const lokasiKeluar = document.getElementById('lokasi-keluar');
            if (lokasiMasuk) lokasiMasuk.value = coords;
            if (lokasiKeluar) lokasiKeluar.value = coords;
        });
    }
});
</script>
@endpush
@endsection
