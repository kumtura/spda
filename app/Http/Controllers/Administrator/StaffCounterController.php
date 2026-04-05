<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AbsensiCounter;
use App\Models\TicketCounterAssignment;
use App\Models\TiketWisata;
use App\Models\ObjekWisata;
use App\Models\Banjar;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffCounterController extends Controller
{
    /**
     * List semua staff ticket counter
     */
    public function index(Request $request)
    {
        $query = User::where('id_level', 5)
            ->leftJoin('tb_data_banjar', 'users.id_banjar', '=', 'tb_data_banjar.id_data_banjar')
            ->select('users.*', 'tb_data_banjar.nama_banjar');

        if ($request->filled('id_data_banjar')) {
            $query->where('users.id_banjar', $request->id_data_banjar);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('users.name', 'like', "%{$s}%")
                  ->orWhere('users.email', 'like', "%{$s}%");
            });
        }

        $staffList = $query->orderBy('users.name')->get();

        // Enrich each staff with assignment & stats
        $today = Carbon::today();
        $bulanIni = Carbon::now();

        foreach ($staffList as $staff) {
            // Assigned objek wisata
            $staff->assignments = TicketCounterAssignment::where('id_user', $staff->id)
                ->where('aktif', '1')
                ->with('objekWisata')
                ->get();

            // Active shift
            $staff->activeShift = AbsensiCounter::where('id_user', $staff->id)
                ->whereNull('waktu_keluar')
                ->latest('waktu_masuk')
                ->first();

            // Absensi bulan ini
            $staff->absensi_bulan_ini = AbsensiCounter::where('id_user', $staff->id)
                ->whereMonth('waktu_masuk', $bulanIni->month)
                ->whereYear('waktu_masuk', $bulanIni->year)
                ->count();

            // Penjualan hari ini
            $staff->penjualan_hari_ini = TiketWisata::where('petugas_scan', $staff->id)
                ->whereDate('created_at', $today)
                ->where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->sum('total_harga');

            $staff->tiket_hari_ini = TiketWisata::where('petugas_scan', $staff->id)
                ->whereDate('created_at', $today)
                ->where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->count();

            // Total penjualan bulan ini
            $staff->penjualan_bulan_ini = TiketWisata::where('petugas_scan', $staff->id)
                ->whereMonth('created_at', $bulanIni->month)
                ->whereYear('created_at', $bulanIni->year)
                ->where('status_pembayaran', 'completed')
                ->where('aktif', '1')
                ->sum('total_harga');
        }

        $banjarList = Banjar::where('aktif', '1')->orderBy('nama_banjar')->get();

        // Ringkasan global
        $totalStaff = User::where('id_level', 5)->count();
        $staffAktifHariIni = AbsensiCounter::whereDate('waktu_masuk', $today)
            ->distinct('id_user')
            ->count('id_user');
        $totalPenjualanHariIni = TiketWisata::whereDate('created_at', $today)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->whereNotNull('petugas_scan')
            ->sum('total_harga');

        return view('admin.pages.staff_counter.index', compact(
            'staffList', 'banjarList', 'totalStaff', 'staffAktifHariIni', 'totalPenjualanHariIni'
        ));
    }

    /**
     * Detail staff counter - absensi, penjualan, history
     */
    public function detail(Request $request, $id)
    {
        $staff = User::where('id_level', 5)->findOrFail($id);

        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Assigned objek wisata
        $assignments = TicketCounterAssignment::where('id_user', $id)
            ->where('aktif', '1')
            ->with('objekWisata')
            ->get();

        // Active shift
        $activeShift = AbsensiCounter::where('id_user', $id)
            ->whereNull('waktu_keluar')
            ->latest('waktu_masuk')
            ->first();

        // Riwayat absensi bulan terpilih
        $riwayatAbsensi = AbsensiCounter::where('id_user', $id)
            ->whereMonth('waktu_masuk', $bulan)
            ->whereYear('waktu_masuk', $tahun)
            ->with('objekWisata')
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Statistik absensi
        $totalHariMasuk = AbsensiCounter::where('id_user', $id)
            ->whereMonth('waktu_masuk', $bulan)
            ->whereYear('waktu_masuk', $tahun)
            ->distinct(DB::raw('DATE(waktu_masuk)'))
            ->count(DB::raw('DISTINCT DATE(waktu_masuk)'));

        $totalJamKerja = 0;
        foreach ($riwayatAbsensi as $absen) {
            if ($absen->waktu_keluar) {
                $totalJamKerja += $absen->waktu_masuk->diffInMinutes($absen->waktu_keluar);
            }
        }
        $rataJamPerHari = $totalHariMasuk > 0 ? round($totalJamKerja / $totalHariMasuk / 60, 1) : 0;

        // Penjualan bulan terpilih
        $penjualanBulan = TiketWisata::where('petugas_scan', $id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1');

        $totalPenjualan = (clone $penjualanBulan)->sum('total_harga');
        $totalTiket = (clone $penjualanBulan)->count();

        // Penjualan per hari
        $penjualanPerHari = TiketWisata::where('petugas_scan', $id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('COUNT(*) as jumlah_tiket'),
                DB::raw('SUM(total_harga) as total_pendapatan')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal', 'desc')
            ->get();

        // Penjualan per objek wisata
        $penjualanPerObjek = TiketWisata::where('petugas_scan', $id)
            ->whereMonth('tb_tiket_wisata.created_at', $bulan)
            ->whereYear('tb_tiket_wisata.created_at', $tahun)
            ->where('tb_tiket_wisata.status_pembayaran', 'completed')
            ->where('tb_tiket_wisata.aktif', '1')
            ->join('tb_objek_wisata', 'tb_tiket_wisata.id_objek_wisata', '=', 'tb_objek_wisata.id_objek_wisata')
            ->select(
                'tb_objek_wisata.nama_objek',
                DB::raw('COUNT(*) as jumlah_tiket'),
                DB::raw('SUM(tb_tiket_wisata.total_harga) as total_pendapatan')
            )
            ->groupBy('tb_objek_wisata.nama_objek')
            ->get();

        // History transaksi terbaru
        $transaksiTerbaru = TiketWisata::where('petugas_scan', $id)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->with('objekWisata')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return view('admin.pages.staff_counter.detail', compact(
            'staff', 'assignments', 'activeShift',
            'riwayatAbsensi', 'totalHariMasuk', 'totalJamKerja', 'rataJamPerHari',
            'totalPenjualan', 'totalTiket',
            'penjualanPerHari', 'penjualanPerObjek', 'transaksiTerbaru',
            'bulan', 'tahun'
        ));
    }

    /**
     * Dashboard data ticket counter - overview staff on duty, stats
     */
    public function ticketData(Request $request)
    {
        $today = Carbon::today();

        // Staff on duty right now
        $staffOnDuty = AbsensiCounter::whereNull('waktu_keluar')
            ->with(['user', 'objekWisata'])
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Summary stats
        $totalStaff = User::where('id_level', 5)->count();
        $staffAktifHariIni = AbsensiCounter::whereDate('waktu_masuk', $today)
            ->distinct('id_user')
            ->count('id_user');

        $penjualanHariIni = TiketWisata::whereDate('created_at', $today)
            ->where('status_pembayaran', 'completed')
            ->where('aktif', '1');

        $totalPenjualanHariIni = (clone $penjualanHariIni)->sum('total_harga');
        $totalTiketHariIni = (clone $penjualanHariIni)->count();

        // Breakdown online vs offline today
        $offlineHariIni = (clone $penjualanHariIni)->where('metode_pembelian', 'offline')->count();
        $onlineHariIni = (clone $penjualanHariIni)->where('metode_pembelian', 'online')->count();

        // Per objek wisata stats today
        $perObjekHariIni = TiketWisata::whereDate('tb_tiket_wisata.created_at', $today)
            ->where('tb_tiket_wisata.status_pembayaran', 'completed')
            ->where('tb_tiket_wisata.aktif', '1')
            ->join('tb_objek_wisata', 'tb_tiket_wisata.id_objek_wisata', '=', 'tb_objek_wisata.id_objek_wisata')
            ->select(
                'tb_objek_wisata.nama_objek',
                'tb_objek_wisata.id_objek_wisata',
                DB::raw('COUNT(*) as jumlah_tiket'),
                DB::raw('SUM(tb_tiket_wisata.total_harga) as total_pendapatan'),
                DB::raw("SUM(CASE WHEN tb_tiket_wisata.metode_pembelian = 'offline' THEN 1 ELSE 0 END) as offline_count"),
                DB::raw("SUM(CASE WHEN tb_tiket_wisata.metode_pembelian = 'online' THEN 1 ELSE 0 END) as online_count")
            )
            ->groupBy('tb_objek_wisata.nama_objek', 'tb_objek_wisata.id_objek_wisata')
            ->get();

        // Recent 10 transactions
        $recentTransactions = TiketWisata::where('status_pembayaran', 'completed')
            ->where('aktif', '1')
            ->with(['objekWisata', 'petugas', 'details.kategoriTiket'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.pages.ticket_counter_data.index', compact(
            'staffOnDuty', 'totalStaff', 'staffAktifHariIni',
            'totalPenjualanHariIni', 'totalTiketHariIni',
            'offlineHariIni', 'onlineHariIni',
            'perObjekHariIni', 'recentTransactions'
        ));
    }

    /**
     * Full history of ticket purchases (online & offline)
     */
    public function ticketHistory(Request $request)
    {
        $query = TiketWisata::where('aktif', '1')
            ->with(['objekWisata', 'petugas', 'details.kategoriTiket'])
            ->orderBy('created_at', 'desc');

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Default: last 7 days if no date filter
        if (!$request->filled('tanggal_dari') && !$request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '>=', Carbon::today()->subDays(7));
        }

        // Filter metode pembelian
        if ($request->filled('metode_pembelian')) {
            $query->where('metode_pembelian', $request->metode_pembelian);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Filter objek wisata
        if ($request->filled('id_objek_wisata')) {
            $query->where('id_objek_wisata', $request->id_objek_wisata);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_tiket', 'like', "%{$s}%")
                  ->orWhere('nama_pengunjung', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $tikets = $query->paginate(25)->appends($request->query());

        $objekWisataList = ObjekWisata::where('aktif', '1')->orderBy('nama_objek')->get();

        return view('admin.pages.ticket_counter_data.history', compact(
            'tikets', 'objekWisataList'
        ));
    }
}
